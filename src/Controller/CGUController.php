<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cgu")
 */

class CGUController extends AbstractController
{
    /**
     * @Route("/cconditions-generales-utilisations", name="cgu_conditions")
     */
    public function index(): Response
    {
        return $this->render('cgu/index.html.twig', [
            'controller_name' => 'CGUController',
        ]);
    }
}
