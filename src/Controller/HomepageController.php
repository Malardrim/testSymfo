<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        $faction_rules = $this->getDoctrine()->getRepository('App:Rule')->getRulesByFaction("Ynnari");
        $phases = $this->getDoctrine()->getRepository('App:Phase')->findBy([], ['priority' => 'ASC']);
        return $this->render('homepage/index.html.twig', [
            'rules' => $faction_rules,
            'phases' => $phases
        ]);
    }
}
