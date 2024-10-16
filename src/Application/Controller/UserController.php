<?php

namespace App\Application\Controller;

use App\Application\Form\ProfileType;
use App\Domain\User\User;
use App\Infrastructure\Repository\RideRepository;
use App\Infrastructure\Repository\UserRepository;
use App\Infrastructure\Service\MailSendService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/profil', name: 'app_profile')]
    public function index(
        UserRepository $repo,
        Request $request,
        MailerInterface $mail,
        MailSendService $mailSendService,
        RideRepository $rideRepository
    ): Response {


        /** @var User $user */
        $user = $this->getUser();

        if (!$this->getUser()) {
            $this->addFlash('warning', 'Vous devez être connecté pour utiliser l\'application');
            return $this->redirectToRoute('app_login');
        }

        // check with uses => find PAST rides of user to count them (participated and created) => may get all times ?
        $myPrevRides = $rideRepository->myPrevRides($user);
        $myCreatedRides = $rideRepository->myCreatedRides($user);

        $form = $this->createForm(ProfileType::class, $user, ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // email confirmation if new email
            if ($form->get('email')->getData() !== $user->getEmail()) {
                $user->setEmail($form->get('email')->getData());
                $mail = $mailSendService->emailConfirmation($user);
                $this->addFlash('success', $mail);
            }

            $message = 'Vos informations ont bien été mises à jour.';
            $user = $form->getData();
            $repo->save($user);

            $this->addFlash('success', $message);
            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'my_rides' => $myCreatedRides,
            'my_prev_rides' => $myPrevRides,
        ]);
    }
}
