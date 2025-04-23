<?php

namespace App\Application\Controller;

// dependencies
use App\Domain\User\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// entities
use App\Domain\Ride\Contrat\FindMyRidesInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InfoController extends AbstractController
{
    public function __construct(
        private readonly FindMyRidesInterface        $findMyRides
    ) {}

    #[Route('/comment-ca-marche', name: 'app_info')]
    public function index(
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('info/index.html.twig', [
            'user' => $user,
            'my_next_rides' => $myRides['myNextRides'],
            'my_created_rides' => $myRides['myCreatedRides'],
            'my_prev_rides' => $myRides['allMyRides'],
        ]);
    }
}
