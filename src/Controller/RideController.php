<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RideRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RideController extends AbstractController
{
    #[Route('/rides', name: 'app_rides')]
    public function index(
        RideRepository $rideRepository
    ): Response
    {
        $rides = $rideRepository->findAll();

        $user = new User();
        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();
        }
        $user_name = $user->getUserName();

        return $this->render('ride/index.html.twig', [
            'user_name' => $user_name,
            'all_rides' => $rides,
        ]);
    }
}
