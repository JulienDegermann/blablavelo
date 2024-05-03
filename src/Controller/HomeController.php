<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RideRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        RideRepository $rideRepository
    ): Response {


        if ($this->getUser()) {
            
            return $this->redirectToRoute('app_rides');
        }


        return $this->render('home/index.html.twig');
    }
}
