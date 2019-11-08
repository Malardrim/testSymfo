<?php

namespace App\Controller;

use App\Entity\Rule;
use App\Form\RuleType;
use App\Repository\RuleRepository;
use App\Services\Pagination;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rule")
 */
class RuleController extends AbstractController
{
    /**
     * @Route("/main/{page<\d+>?1}", name="rule_index", methods={"GET"})
     * @param RuleRepository $ruleRepository
     * @param int $page
     * @param Pagination $pagination
     * @return Response
     */
    public function index(RuleRepository $ruleRepository, $page, Pagination $pagination): Response
    {
        $pagination->setEntityClass(Rule::class)
            ->setCurrentPage($page);
        return $this->render('rule/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/new", name="rule_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $rule = new Rule();
        $form = $this->createForm(RuleType::class, $rule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rule);
            $entityManager->flush();

            return $this->redirectToRoute('rule_index');
        }

        return $this->render('rule/new.html.twig', [
            'rule' => $rule,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="rule_show", methods={"GET"})
     */
    public function show(Rule $rule): Response
    {
        return $this->render('rule/show.html.twig', [
            'rule' => $rule,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="rule_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Rule $rule): Response
    {
        $form = $this->createForm(RuleType::class, $rule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", "success.rule_edit");
            return $this->redirectToRoute('rule_index');
        }

        return $this->render('rule/edit.html.twig', [
            'rule' => $rule,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="rule_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Rule $rule): Response
    {
        if ($this->isCsrfTokenValid('delete' . $rule->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rule);
            $entityManager->flush();
            $this->addFlash('success', 'success.rule_delete');
        }

        return $this->redirectToRoute('rule_index');
    }
}
