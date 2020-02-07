<?php

namespace App\Controller;

use App\Entity\Catalogue;
use App\Entity\CategoryEntry;
use App\Entity\Entry;
use App\Entity\Item;
use App\Entity\Rule;
use App\Form\ItemType;
use App\Form\RuleType;
use App\Services\ImportBSManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;


class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function index(EntityManagerInterface $manager)
    {
        $catalogues = $manager->getRepository(Catalogue::class)->findAll();
        foreach ($catalogues as $catalogue) {
            $catalogue->setEntriesNb(count($manager->getRepository(Entry::class)->findBy(['catalogueId' => $catalogue->getId()])));
        }
        return $this->render('homepage/index.html.twig', [
            'catalogues' => $catalogues,
        ]);
    }

    /**
     * @Route("/data_raw", name="dump_data")
     * @param ImportBSManager $importBSManager
     * @return Response
     */
    public function dataAction(ImportBSManager $importBSManager)
    {
        $res = $importBSManager->deserializeLink();;
        //$importBSManager->importCatalogue();
        return $this->render('homepage/data.html.twig', [
            'nodes' => $res,
        ]);
    }
}
