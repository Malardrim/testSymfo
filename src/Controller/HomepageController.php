<?php

namespace App\Controller;

use App\Entity\CategoryEntry;
use App\Entity\Item;
use App\Entity\Rule;
use App\Form\ItemType;
use App\Form\RuleType;
use App\Services\ImportBSManager;
use Doctrine\Common\Persistence\ObjectManager;
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
     * @param ImportBSManager $importBSManager
     * @param ObjectManager $manager
     * @return Response
     */
    public function index(ImportBSManager $importBSManager, ObjectManager $manager)
    {
        $res = [];
        try {
            $importBSManager->importCategoryEntries();
            $res = $importBSManager->deserializeLink();
        } catch (ClientExceptionInterface $e) {
            dump($e->getTraceAsString());
        } catch (RedirectionExceptionInterface $e) {
            dump($e->getTraceAsString());
        } catch (ServerExceptionInterface $e) {
            dump($e->getTraceAsString());
        } catch (TransportExceptionInterface $e) {
            dump($e->getTraceAsString());
        }
        $form = $this->createForm(ItemType::class, new Item());
        return $this->render('homepage/index.html.twig', [
            'form' => $form->createView(),
            'nodes' => $res,
        ]);
    }
}
