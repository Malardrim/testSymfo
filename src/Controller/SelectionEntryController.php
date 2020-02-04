<?php


namespace App\Controller;


use App\Entity\CategoryEntry;
use App\Entity\Entry;
use App\Entity\SelectionEntry;
use App\Repository\EntryRepository;
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
     * @param Entry $selectionEntry
     * @param EntityManagerInterface $manager
     * @param $types array
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function hydrateEntry(Entry $selectionEntry, EntityManagerInterface $manager, $types)
    {
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
                    if ($val){
                        $selectionEntry->addProperty($key, $val);
                        $selectionEntry->addProperty("$key Class", get_class($val));
                    }
                }
            } catch (\Exception $exception){
                //dump($exception->getMessage());
            }
        }
        if (count($selectionEntry->getChildren()) > 0){
            foreach ($selectionEntry->getChildren() as $child) {
                $this->hydrateEntry($child, $manager, $types);
            }
        }
    }

    /**
     * @param EntryRepository $entryRepository
     * @param EntityManagerInterface $manager
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @Route("/", name="selectionentry_index")
     */
    public function indexAction(EntryRepository $entryRepository, EntityManagerInterface $manager)
    {
        /*$func = function ($val) {
            return $val['dataType'];
        };*/
        /*$types = array_map($func, $manager->createQueryBuilder()
            ->select('en.dataType')
            ->from(Entry::class, 'en')
            ->where('en.dataType IS NOT NULL')
            ->groupBy('en.dataType')
            ->getQuery()
            ->getResult());*/
        $entries = $entryRepository->findBy(['catalogueId' => '30b2-6f64-b85e-b4dc', 'dataType' => 'profile'], [], 20);
        $types[] = 'target';
        foreach ($entries as $entry) {
            $this->hydrateEntry($entry, $manager, null);
        }
        return $this->render("selectionentry/index.html.twig", [
            "entries" => $entries
        ]);
    }
}