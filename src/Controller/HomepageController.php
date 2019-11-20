<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Rule;
use App\Form\ItemType;
use App\Form\RuleType;
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
        $form = $this->createForm(ItemType::class, new Item());
        return $this->render('homepage/index.html.twig', [
            'rules' => $faction_rules,
            'phases' => $phases,
            'form' => $form->createView()
        ]);
    }
}
