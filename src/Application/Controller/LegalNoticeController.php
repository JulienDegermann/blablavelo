<?php

namespace App\Application\Controller;

use App\Domain\User\User;
use App\Infrastructure\Repository\RideRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalNoticeController extends AbstractController
{
    #[Route('/mentions-legales', name: 'app_legal_notice')]
    public function index(
        RideRepository $rideRepository
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        // check with uses => find PAST rides of user to count them (participated and created) => may get all times ?
        $myPrevRides = $rideRepository->myPrevRides($user);
        $myCreatedRides = $rideRepository->myCreatedRides($user);

        return $this->render('legal_notice/index.html.twig', [
            'user' => $user,
            'my_rides' => $myCreatedRides,
            'my_prev_rides' => $myPrevRides
        ]);
    }
}
