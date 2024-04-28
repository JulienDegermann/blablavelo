<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RideRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LegalNoticeController extends AbstractController
{
    #[Route('/mentions-legales', name: 'app_legal_notice')]
    public function index(
        RideRepository $rideRepository
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $myCreatedRides = $rideRepository->findBy(['author' => $user], ['date' => 'ASC']);
        $myParticipatedRides = $rideRepository->rideOfUser($user);

        return $this->render('legal_notice/index.html.twig', [
            'user' => $user,
            'my_rides' => $myCreatedRides,
            'all_my_rides' => $myParticipatedRides
        ]);
    }
}
