<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\User;
use App\Form\NewRideType;
use App\Repository\RideRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RideController extends AbstractController
{
    #[Route('/rides', name: 'app_rides')]
    public function index(
        RideRepository $rideRepository
    ): Response {
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

    #[Route('/ride/{id}', name: 'app_ride', methods: ['GET', 'POST'])]
    public function showRide(
        RideRepository $rideRepository,
        Request $request
    ): Response {
        $id = $request->attributes->get('id');
        $rides = $rideRepository->findBy(['id' => $id]);
        $ride = $rides[0];


        $user = new User();
        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();
        }
        $user_name = $user->getUserName();
        // dd($ride);
        return $this->render('ride/show_ride.html.twig', [
            'user_name' => $user_name,
            'user' => $user,
            'ride' => $ride
        ]);
    }


    #[Route('/ride/{id}/add', name: 'app_ride_connect', methods: ['GET', 'POST'])]
    public function addToRide(
        RideRepository $rideRepository,
        Request $request
    ): Response {
        $id = $request->attributes->get('id');
        $rides = $rideRepository->findBy(['id' => $id]);
        $ride = $rides[0];
        $user = $this->getUser();
        $ride->addUserParticipant($user);
        $rideRepository->save($ride);

        // return $this->redirectToRoute('app_rides');

        $user = new User();
        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();
        }
        $user_name = $user->getUserName();

        // return $this->redirectToRoute('app_ride_remove');

        return $this->render('ride/show_ride.html.twig', [
            'user_name' => $user_name,
            'user' => $user,
            'ride' => $ride
        ]);
    }



    #[Route('/ride/{id}/remove', name: 'app_ride_remove', methods: ['GET', 'POST'])]
    public function removeToRide(
        RideRepository $rideRepository,
        Request $request
    ): Response {
        $id = $request->attributes->get('id');
        $rides = $rideRepository->findBy(['id' => $id]);
        $ride = $rides[0];
        $user = $this->getUser();
        $ride->removeUserParticipant($user);
        $rideRepository->save($ride);

        $user = new User();
        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();
        }
        $user_name = $user->getUserName();

        // return $this->redirectToRoute('app_ride_remove');

        return $this->render('ride/show_ride.html.twig', [
            'user_name' => $user_name,
            'user' => $user,
            'ride' => $ride
        ]);
    }


    #[Route('/ajouter-un-ride', name: 'app_new_ride', methods: ['GET', 'POST'])]
    public function newRide(
        RideRepository $repo,
        Request $request
    ): Response {
        $user = new User();
        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();
        }
        $user_name = $user->getUserName();

        $ride = new Ride();
        $ride->setUserCreator($this->getUser());
        $ride->addUserParticipant($this->getUser());
        $form = $this->createForm(NewRideType::class, $ride);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ride = $form->getData();
            $repo->save($ride);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('ride/new_ride.html.twig', [
            'form' => $form->createView(),
            'user_name' => $user_name,
            'user' => $user
        ]);
    }
}
