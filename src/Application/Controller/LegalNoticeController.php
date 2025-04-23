<?php

namespace App\Application\Controller;

// dependencies
use App\Domain\User\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// entities
use App\Domain\Ride\Contrat\FindMyRidesInterface;
use App\Domain\User\Contrat\UpdateUserSettingsInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LegalNoticeController extends AbstractController
{

    public function __construct(
        private readonly FindMyRidesInterface        $findMyRides,
    ) {}

    #[Route('/mentions-legales', name: 'app_legal_notice')]
    public function index(): Response
    {

        /** @var User $user */
        $user = $this->getUser();

        $myRides = ($this->findMyRides)($user);


        return $this->render('legal_notice/index.html.twig', [
            'user' => $user,
            'my_next_rides' => $myRides['myNextRides'],
            'my_created_rides' => $myRides['myCreatedRides'],
            'my_prev_rides' => $myRides['allMyRides'],
        ]);
    }
}
