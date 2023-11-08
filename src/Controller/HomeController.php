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
        ): Response
    {
        $rides = $rideRepository->findBy([], ['id' => 'DESC'], 5);
        // dd($rides);

        $user = new User();
        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();
        }
        $user_name = $user->getUserName();

        return $this->render('home/index.html.twig', [
            'user_name' => $user_name,
            'all_rides' => $rides,
        ]);
    }
}
