<?php

namespace App\Application\Controller;

// dependencies
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// entities
use App\Domain\User\User;

// forms
use App\Application\Form\ProfileType;

// interfaces
use App\Domain\Ride\Contrat\FindMyRidesInterface;
use App\Domain\User\Contrat\UpdateUserSettingsInterface;

// inputs
use App\Domain\User\UseCase\UpdateUserSettings\UpdateUserSettingsInput;
use Exception;

class UserController extends AbstractController
{
    public function __construct(
        private readonly FindMyRidesInterface        $findMyRides,
        private readonly UpdateUserSettingsInterface $updateUserSettings,
    ) {}

    #[Route('/profil', name: 'app_profile')]
    public function index(
        Request $request,
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        if (!$this->getUser()) {
            $this->addFlash('warning', 'Vous devez être connecté pour utiliser l\'application');

            return $this->redirectToRoute('app_login');
        }

        $myRides = ($this->findMyRides)($user);

        try {
            $input = new UpdateUserSettingsInput();
            $form = $this->createForm(ProfileType::class, $input, ['user' => $user]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // email confirmation if new email

                $input = $form->getData();

                $return = ($this->updateUserSettings)($input, $user);

                $this->addFlash('success', $return);

                return $this->redirectToRoute('app_home');
            }
        } catch (Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'my_next_rides' => $myRides['myNextRides'],
            'my_created_rides' => $myRides['myCreatedRides'],
            'my_prev_rides' => $myRides['allMyRides'],
            'form' => $form->createView(),
        ]);
    }
}
