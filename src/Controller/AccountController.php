<?php


namespace App\Controller;


use App\Repository\FactionRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/", name="account_index", methods={"GET"})
     * @param JWTTokenManagerInterface $JWTManager
     * @return Response
     */
    public function index(JWTTokenManagerInterface $JWTManager): Response
    {
        $blocks = [];
        $token = $JWTManager->create($this->getUser());
        $apiBody = $this->renderView('account/partials/api_tab.html.twig', [
            "token" => $token
        ]);
        $blocks[] = ["id" => "accountData", "header" => "Elements", "body" => "Some content is here"];
        $blocks[] = ["id" => "apiData","header" => "Api Data", "body" => $apiBody];
        return $this->render('account/index.html.twig', [
            "blocks" => $blocks
        ]);
    }
}