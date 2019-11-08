<?php

namespace App\Controller;

use App\Entity\Faction;
use App\Form\FactionType;
use App\Repository\FactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/faction")
 */
class FactionController extends AbstractController
{
    /**
     * @Route("/", name="faction_index", methods={"GET"})
     */
    public function index(FactionRepository $factionRepository): Response
    {
        return $this->render('faction/index.html.twig', [
            'factions' => $factionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="faction_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $faction = new Faction();
        $form = $this->createForm(FactionType::class, $faction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($faction);
            $entityManager->flush();

            return $this->redirectToRoute('faction_index');
        }

        return $this->render('faction/new.html.twig', [
            'faction' => $faction,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="faction_show", methods={"GET"})
     */
    public function show(Faction $faction): Response
    {
        return $this->render('faction/show.html.twig', [
            'faction' => $faction,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="faction_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Faction $faction): Response
    {
        $form = $this->createForm(FactionType::class, $faction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('faction_index');
        }

        return $this->render('faction/edit.html.twig', [
            'faction' => $faction,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="faction_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Faction $faction): Response
    {
        if ($this->isCsrfTokenValid('delete'.$faction->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($faction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('faction_index');
    }
}
