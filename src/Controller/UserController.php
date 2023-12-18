<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\RideRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;


class UserController extends AbstractController
{
    #[Route('/profil', name: 'app_profile')]
    public function index(
        UserRepository $repo,
        RideRepository $rideRepo,
        Request $request,
        ): Response {
        
            
        /** @var User $user */
        $user = $this->getUser();
            
        if (!$this->getUser()) {
            $this->addFlash('warning', 'Vous devez être connecté pour utiliser l\'application');
            return $this->redirectToRoute('app_login');
        }
        
        $rides = $rideRepo->rideOfUser($user);

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);
            
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $repo->save($user);

            $this->addFlash('success', 'Votre profil a bien été mis à jour');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'user_rides' => $rides,

        ]);
    }
}
