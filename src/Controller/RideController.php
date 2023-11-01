<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RideController extends AbstractController
{
    #[Route('/rides', name: 'app_rides')]
    public function index(): Response
    {
        return $this->render('ride/index.html.twig', [
            'controller_name' => 'RideController',
        ]);
    }
}
