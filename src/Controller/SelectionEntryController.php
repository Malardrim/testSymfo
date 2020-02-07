<?php


namespace App\Controller;


use App\Entity\CategoryEntry;
use App\Entity\Entry;
use App\Entity\EntryLink;
use App\Entity\SelectionEntry;
use App\Repository\EntryRepository;
use App\Repository\SelectionEntryRepository;
use App\Services\Pagination;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SelectionEntryController
 * @package App\Controller
 * @Route("/selectionentry")
 */
class SelectionEntryController extends AbstractController
{

    /**
     * @param Entry $selectionEntry
     * @param EntityManagerInterface $manager
     * @param $types array
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function hydrateEntry(Entry &$selectionEntry, EntityManagerInterface $manager, $types)
    {
        if ($selectionEntry instanceof EntryLink) {
            $entry = $manager->getRepository(Entry::class)->findOneBy(['id' => $selectionEntry->getTargetId()]);
            $this->hydrateEntry($entry, $manager, $types);
            $selectionEntry->setTargetObj($entry);
        }
        foreach ($selectionEntry->getProperties() as $key => $property) {
            try {
                if (preg_match('/^.*Id$/', $key)) {
                    $val = $manager->createQueryBuilder()
                        ->select('en')
                        ->from(Entry::class, 'en')
                        ->where('en.id LIKE :id_val')
                        ->setParameter('id_val', $property)
                        ->getQuery()
                        ->getSingleResult();
                    if ($val) {
                        $selectionEntry->addProperty($key, $val);
                    }
                }
            } catch (\Exception $exception) {
                //dump($exception->getMessage());
            }
        }
        if (count($selectionEntry->getChildren()) > 0) {
            foreach ($selectionEntry->getChildren() as $child) {
                $this->hydrateEntry($child, $manager, $types);
            }
        }
    }


    /**
     * @param EntityManagerInterface $manager
     * @param Pagination $pagination
     * @param $page string
     * @return Response
     * @Route("/main/{page<\d+>?1}", name="selectionentry_index")
     */
    public function indexAction(EntityManagerInterface $manager, Pagination $pagination, $page)
    {
        $pagination->setEntityClass(EntryLink::class)
            ->setCurrentPage($page);
        $entries = $pagination->getData();
        foreach ($entries as $entry) {
            $entry->setTargetObj($manager->getRepository(Entry::class)->findOneBy(['id' => $entry->getTargetId()]));
        }
        return $this->render("selectionentry/index.html.twig", [
            "pagination" => $pagination,
        ]);
    }

    /**
     * @param EntryLink $entry
     * @param EntityManagerInterface $manager
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @Route("/{id}/show", name="selectionentry_show")
     */
    public function showAction(EntryLink $entry, EntityManagerInterface $manager)
    {
        //f960-c113-b5bf-d752 (fire dragons)
        //7d2a-346f-0883-3b49 (eldrad)
        //5fba-0b5d-2e8c-9218 (rangers)
        $func = function (Entry $entry, &$data) {
            $regex = '/^.*characteristic$/';
            if ($entry->getDataType() && preg_match($regex, $entry->getDataType())) {
                $key = $entry->getParent()->getParent()->getName();
                if (!isset($data[$key])){
                    $data[$key] = [];
                }
                $elem = $entry->getValue();
                if (!empty($elem)){
                    $data[$key][$entry->getName()] = $elem;
                }
                return $data;
            }
            return false;
        };
        $this->hydrateEntry($entry, $manager, null);
        $data = $this->recursiveEntryIterator($entry, $func);
        return $this->render("selectionentry/show.html.twig", [
            "data" => $data
        ]);
    }

    /**
     * @param Entry $entry
     * @param $ressourcer callable Function to fill the array with
     * @param null|array $data
     * @return array|null
     */
    public function recursiveEntryIterator(Entry $entry, $ressourcer, &$data = null)
    {
        if ($data == null) {
            $data = [];
        }
        $ressourcer($entry, $data);
        foreach ($entry->getChildren() as $child) {
            $this->recursiveEntryIterator($child, $ressourcer, $data);
        }
        if ($entry instanceof EntryLink) {
            $this->recursiveEntryIterator($entry->getTargetObj(), $ressourcer, $data);
        }
        return $data;
    }
}