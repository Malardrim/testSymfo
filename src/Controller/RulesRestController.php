<?php


namespace App\Controller;

use App\Entity\Item;
use App\Entity\Rule;
use App\Services\ApiForm;
use App\Services\CustomApiException;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class RulesRestController
 * @package App\Controller
 *
 * @Rest\Route("/rest")
 */
class RulesRestController extends AbstractController
{
    private $encoders;
    private $normalizers;

    public function __construct()
    {
        $this->encoders = [new JsonEncoder()];
        $this->normalizers = [new ObjectNormalizer()];
    }

    /**
     * @return Response
     *
     * @Rest\Route("/rules")
     */
    public function getRulesAction()
    {
        $data = $this->getDoctrine()->getRepository("App:Rule")->findAll();
        $serializer = new Serializer($this->normalizers, $this->encoders);
        $data = array_values($data);
        $jsonObject = $serializer->serialize($data, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @param Request $request
     * @param ApiForm $apiService
     * @param ObjectManager $manager
     * @return Response
     *
     * @Rest\Route("/rules/new", name="rest_new_rule")
     */
    public function newRule(Request $request, ApiForm $apiService, ObjectManager $manager)
    {
        $data = $request->getContent();
        $response = [];
        try {
            $rule = $apiService->validateAndCreate($data, Rule::class);
            if (!$rule instanceof Rule) {
                dump($rule);
                return new Response(json_encode($rule), 500, ['Content-Type' => 'application/json']);
            }
            $manager->persist($rule);
            $manager->flush();
            $response['id'] = $rule->getId();
            $response['name'] = $rule->getName();
            $response['message'] = "Successfully updated the ruleset";
        } catch (\Exception $e) {
            return new Response(json_encode($e->getMessage()), 500, ['Content-Type' => 'application/json']);
        }
        return new Response(json_encode($response), 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @param Request $request
     * @param ApiForm $apiService
     * @param ObjectManager $manager
     * @return Response
     *
     * @Rest\Route("/items/new", name="rest_new_item")
     */
    public function newItem(Request $request, ApiForm $apiService, ObjectManager $manager)
    {
        $data = $request->getContent();
        $response = [];
        dump($data);
        try {
            $item = $apiService->validateAndCreate($data, Item::class);
            if (!$item instanceof Item) {
                return new Response(json_encode($item), 500, ['Content-Type' => 'application/json']);
            }
            $manager->persist($item);
            $manager->flush();
            $response['id'] = $item->getId();
            $response['name'] = $item->getName();
            $response['message'] = "Successfully updated the ruleset";
        } catch (\Exception $e) {
            return new Response(json_encode($e->getMessage()), 500, ['Content-Type' => 'application/json']);
        }
        return new Response(json_encode($response), 200, ['Content-Type' => 'application/json']);
    }
}
