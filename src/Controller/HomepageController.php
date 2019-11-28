<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Rule;
use App\Form\ItemType;
use App\Form\RuleType;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Annotation\Route;


class HomepageController extends AbstractController
{

    /**
     * @param $data
     * @param array $result
     * @return mixed
     */
    public function recursiveCrawling($data, $result = [])
    {
        $count = 1;
        $result[0] = $data;
        if (isset($data->childNodes)) {
            foreach ($data->childNodes as $item) {
                $result[$count] = [];
                $result[$count] = $this->recursiveCrawling($item, $result[$count]);
                $count++;
            }
        }
        return $result;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function index(SerializerInterface $serializer)
    {

        $client = HttpClient::create();
        $response = $client->request('GET', "https://raw.githubusercontent.com/BSData/wh40k/master/Aeldari%20-%20Craftworlds.cat");
        //$data = $serializer->deserialize($response->getContent(), "xml", "array");
        $crawler = new Crawler($response->getContent());
        $res = [];
        foreach ($crawler as $domElement) {
            $res[] = $this->recursiveCrawling($domElement);
        }
        $form = $this->createForm(ItemType::class, new Item());
        return $this->render('homepage/index.html.twig', [
            'form' => $form->createView(),
            'nodes' => $res[0],
        ]);
    }
}
