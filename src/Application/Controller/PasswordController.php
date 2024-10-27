<?php

namespace App\Application\Controller;

use App\Application\Form\ResetPasswordType;
use App\Application\Form\SendRecoveryUrlType;
use App\Domain\Ride\Contrat\FindMyRidesInterface;
use App\Domain\User\Contrat\GetUserFromTokenInterface;
use App\Domain\User\Contrat\ResetPasswordInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Domain\User\Contrat\SendRecoveryUrlInterface;
use App\Domain\User\UseCase\ResetPassword\ResetPasswordInput;
use App\Domain\User\UseCase\SendRecoveryUrl\SendRecoveryUrlInput;
use App\Domain\User\User;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

final class PasswordController extends AbstractController
{
    public function __construct(
        private readonly SendRecoveryUrlInterface $sendRecoveryUrl,
        private readonly GetUserFromTokenInterface $getUserFromToken,
        private readonly FindMyRidesInterface       $findMyRides,
        private readonly ResetPasswordInterface     $resetPassword
    ) {}

    #[Route(path: '/mot-de-passe-oublie', name: 'app_pwd_forgot')]
    public function sendPasswordURL(
        Request $request
    ): Response {

        $user = $this->getUser();
        $form = $this->createForm(SendRecoveryUrlType::class, new SendRecoveryUrlInput());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $input = $form->getData();

            ($this->sendRecoveryUrl)($input);

            $this->addFlash('success', "Un email vous a été envoyé pour réinitialiser ton mot de passe.");

            return $this->render('security/pwd_forgot.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        $myRides = ($this->findMyRides)($user);

        return $this->render('security/pwd_forgot.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'my_next_rides' => $myRides['myNextRides'],
            'my_created_rides' => $myRides['myCreatedRides'],
            'my_prev_rides' => $myRides['allMyRides']
        ]);
    }


    #[Route(path: '/reinitialiser-mot-de-passe/{token}', name: 'app_pwd_reset')]
    public function resetPassword(
        Request $request,
        string  $token,
    ): Response {

        if ($this->getUser() && $this->getUser() instanceof User) {
            $user = $this->getUser();
        } else {
            $user = ($this->getUserFromToken)($token);
        }

        try {
            $form = $this->createForm(ResetPasswordType::class, new ResetPasswordInput($user));
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $input = $form->getData();
                ($this->resetPassword)($input);

                $this->addFlash('success', 'Mot de passe mis à jour');
            }
        } catch (Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        }


        $myRides = ($this->findMyRides)($user);

        return $this->render('security/pwd_reset.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'my_next_rides' => $myRides['myNextRides'],
            'my_created_rides' => $myRides['myCreatedRides'],
            'my_prev_rides' => $myRides['allMyRides']
        ]);
    }
}
