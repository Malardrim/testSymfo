<?php


namespace App\Controller;


use App\Entity\UserIcon;
use App\Form\UserEditType;
use App\Form\UserIconType;
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
     * @param Request $request
     * @param JWTTokenManagerInterface $JWTManager
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function index(Request $request, JWTTokenManagerInterface $JWTManager, EntityManagerInterface $manager): Response
    {
        $blocks = [];
        $token = $JWTManager->create($this->getUser());
        $user = $this->getUser();
        $icon = $this->getUser()->getIcon();
        if (empty($icon)){
            $icon = new UserIcon();
        }
        $form = $this->createForm(UserIconType::class, $icon);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            if ($form->isValid()){
                $user->setIcon($icon);
                $manager->persist($icon);
                $manager->persist($user);
                $manager->flush();
                return $this->redirectToRoute('account_index');
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
            "blocks" => $blocks,
            "user" => $user
        ]);
    }
}