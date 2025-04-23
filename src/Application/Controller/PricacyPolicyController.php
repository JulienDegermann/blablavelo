<?php

namespace App\Application\Controller;

// dependencies
use App\Domain\User\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// entities
use App\Domain\Ride\Contrat\FindMyRidesInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PricacyPolicyController extends AbstractController
{
    public function __construct(
        private readonly FindMyRidesInterface        $findMyRides,
    ) {}

    #[Route('/politique-de-confidentialite', name: 'app_pricacy_policy')]
    public function index(
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user instanceof User) {
            $myRides = ($this->findMyRides)($user);
        }


        return $this->render('pricacy_policy/index.html.twig', [
            'user' => $user,
            'my_next_rides' => $myRides['myNextRides'],
            'my_created_rides' => $myRides['myCreatedRides'],
            'my_prev_rides' => $myRides['allMyRides'],
        ]);
    }
}
