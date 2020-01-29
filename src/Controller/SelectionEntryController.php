<?php


namespace App\Controller;


use App\Entity\Entry;
use App\Entity\SelectionEntry;
use App\Repository\SelectionEntryRepository;
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
     * @param SelectionEntry $selectionEntry
     * @param EntityManagerInterface $manager
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function hydrateSelectionEntry(SelectionEntry $selectionEntry, EntityManagerInterface $manager)
    {
        $func = function ($val) {
            return $val['dataType'];
        };
        $types = array_map($func, $manager->createQueryBuilder()
            ->select('en.dataType')
            ->from(Entry::class, 'en')
            ->where('en.dataType IS NOT NULL')
            ->groupBy('en.dataType')
            ->getQuery()
            ->getResult());
        foreach ($selectionEntry->getProperties() as $key => $property) {
            $trimName = str_replace('Id', '', $key);
            if (in_array($trimName, $types)) {
                $val = $manager->createQueryBuilder()
                    ->select('en')
                    ->from(Entry::class, 'en')
                    ->where('en.id LIKE :id_val')
                    ->setParameter('id_val', $property)
                    ->getQuery()
                    ->getSingleResult();
                $selectionEntry->addProperty($key, $val);
            }
        }
    }

    /**
     * @param SelectionEntryRepository $entryRepository
     * @param EntityManagerInterface $manager
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @Route("/", name="selectionentry_index")
     */
    public function indexAction(SelectionEntryRepository $entryRepository, EntityManagerInterface $manager)
    {
        $entries = $entryRepository->findByTypes(['unit', 'model']);
        foreach ($entries as $entry) {
            $this->hydrateSelectionEntry($entry, $manager);
        }
        return $this->render("selectionentry/index.html.twig", [
            "entries" => $entries
        ]);
    }
}