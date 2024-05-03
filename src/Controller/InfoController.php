<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RideRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InfoController extends AbstractController
{


    #[Route('/comment-ca-marche', name: 'app_info')]
    public function index(
        RideRepository $rideRepository
    ): Response
    {
        
        $user = null;
        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();
        }

        $myCreatedRides = $rideRepository->findBy(['author' => $user], ['date' => 'ASC']);
        $myParticipatedRides = $rideRepository->rideOfUser($user);

        return $this->render('info/index.html.twig', [
            'user' => $user,
            'my_rides' => $myCreatedRides,
            'all_my_rides' => $myParticipatedRides
        ]);
    }
}
