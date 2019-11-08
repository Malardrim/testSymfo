<?php

namespace App\Controller;

use App\Entity\Phase;
use App\Form\PhaseType;
use App\Repository\PhaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/phase")
 */
class PhaseController extends AbstractController
{
    /**
     * @Route("/", name="phase_index", methods={"GET"})
     */
    public function index(PhaseRepository $phaseRepository): Response
    {
        return $this->render('phase/index.html.twig', [
            'phases' => $phaseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="phase_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $phase = new Phase();
        $form = $this->createForm(PhaseType::class, $phase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($phase);
            $entityManager->flush();

            return $this->redirectToRoute('phase_index');
        }

        return $this->render('phase/new.html.twig', [
            'phase' => $phase,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="phase_show", methods={"GET"})
     */
    public function show(Phase $phase): Response
    {
        return $this->render('phase/show.html.twig', [
            'phase' => $phase,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="phase_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Phase $phase): Response
    {
        $form = $this->createForm(PhaseType::class, $phase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('phase_index');
        }

        return $this->render('phase/edit.html.twig', [
            'phase' => $phase,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="phase_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Phase $phase): Response
    {
        if ($this->isCsrfTokenValid('delete'.$phase->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($phase);
            $entityManager->flush();
        }

        return $this->redirectToRoute('phase_index');
    }
}
