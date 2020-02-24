<?php


namespace App\Controller;


use App\Form\UserEditType;
use App\Repository\FactionRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/", name="account_index", methods={"GET", "POST"})
     * @param JWTTokenManagerInterface $JWTManager
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function index(JWTTokenManagerInterface $JWTManager, EntityManagerInterface $manager): Response
    {
        $blocks = [];
        $token = $JWTManager->create($this->getUser());
        $user = $this->getUser();
        $form = $this->createForm(UserEditType::class, $user);
        if ($form->isSubmitted()){
            if ($form->isValid()){
                $manager->persist($user);
                $manager->flush();
                return $this->redirect('account_index');
            }
            $this->addFlash('error', 'The form was not valid');
        }
        $apiBody = $this->renderView('account/partials/api_tab.html.twig', [
            "token" => $token
        ]);
        $accountInfosBody = $this->renderView('account/partials/account_tab.html.twig', [
            "form" => $form->createView()
        ]);
        $blocks[] = ["id" => "accountData", "header" => "Elements", "body" => $accountInfosBody];
        $blocks[] = ["id" => "apiData","header" => "Api Data", "body" => $apiBody];
        return $this->render('account/index.html.twig', [
            "blocks" => $blocks
        ]);
    }
}