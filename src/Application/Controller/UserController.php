<?php

namespace App\Application\Controller;

use App\Application\Form\ProfileType;
use App\Domain\Ride\Contrat\FindMyRidesInterface;
use App\Domain\User\Contrat\UpdateUserSettingsInterface;
use App\Domain\User\UseCase\UpdateUserSettings\UpdateUserSettingsInput;
use App\Domain\User\User;
use App\Infrastructure\Repository\UserRepository;
use App\Infrastructure\Service\JWTTokenGeneratorService\MailSendService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(
        private readonly FindMyRidesInterface        $findMyRides,
        private readonly UpdateUserSettingsInterface $updateUserSettings,
    )
    {
    }

    #[Route('/profil', name: 'app_profile')]
    public function index(
        Request $request,
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$this->getUser()) {
            $this->addFlash('warning', 'Vous devez être connecté pour utiliser l\'application');

            return $this->redirectToRoute('app_login');
        }

        $myRides = ($this->findMyRides)($user);

        $input = new UpdateUserSettingsInput();
        $form = $this->createForm(ProfileType::class, $input, ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // email confirmation if new email
            $input = $form->getData();

            ($this->updateUserSettings)($input, $user);

            $this->addFlash('success', 'Ton profil a été mis à jour.');

            return $this->redirectToRoute('app_home');
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
