<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RideRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PricacyPolicyController extends AbstractController
{
    #[Route('/politique-de-confidentialite', name: 'app_pricacy_policy')]
    public function index(
        RideRepository $rideRepository
    ): Response
    {

        /** @var User $user */
        $user = $this->getUser();

        $myCreatedRides = $rideRepository->findBy(['author' => $user], ['date' => 'ASC']);
        $myParticipatedRides = $rideRepository->rideOfUser($user);

        return $this->render('pricacy_policy/index.html.twig', [
            'user' => $user,
            'my_rides' => $myCreatedRides,
            'all_my_rides' => $myParticipatedRides
        ]);
    }
}
