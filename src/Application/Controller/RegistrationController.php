<?php

namespace App\Application\Controller;

use App\Application\Form\RegistrationFormType;
use App\Application\Security\UserAuthAuthenticator;
use App\Domain\User\Contrat\CreateUserInterface;
use App\Domain\User\Contrat\VerifyEmailTokenInterface;
use App\Domain\User\UseCase\CreateUser\CreateUserInput;
use App\Domain\User\User;
use App\Infrastructure\Repository\UserRepository;
use App\Infrastructure\Service\JWTTokenGeneratorService\MailSendService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly VerifyEmailTokenInterface $VerifyEmailToken,
        private readonly CreateUserInterface $createUser
    ) {}

    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request                     $request,
        UserAuthenticatorInterface  $userAuthenticator,
        UserAuthAuthenticator       $authenticator,
    ): Response {
        if ($this->getUser() && $this->getUser() instanceof User) {
            return $this->redirectToRoute('app_home');
        }

        try {
            $form = $this->createForm(RegistrationFormType::class, new CreateUserInput(), ['attr' => ['class' => 'form-signin, row']]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $input = $form->getData();

                $user = ($this->createUser)($input);

                $this->addFlash('success', 'Ton compte a été créé avec succès. Vérifie ta boîte mail pour l\'activer.');

                // auto login
                return $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                );
            }
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }

    #[Route('/supprimer-mon-compte', name: 'app_delete')]
    public function unregister(
        UserRepository $repo
    ): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        /** @var User $user */
        $user = $this->getUser();

        // disconnect $user and destroy session
        $session = new Session();
        $session->invalidate();
        $this->redirectToRoute('app_logout');

        $repo->remove($user);

        if ($repo->findBy(['email' => $user->getEmail()])) {
            $this->addFlash('danger', 'Une erreur est survenue, veuillez réessayer ultérieurement');

            return $this->redirectToRoute('app_profile');
        }

        $this->addFlash('success', 'Votre compte a été supprimé avec succès.');

        return $this->redirectToRoute('app_home');
    }

    // for addFlash if user has not recieved email
    #[Route('/nouveau-code', name: 'app_new_token')]
    public function newCode(
        MailSendService $mailSendService
    ) {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour utiliser l\'application.');

            return $this->redirectToRoute('app_login');
        }

        $mail = $mailSendService->emailConfirmation($user);

        $this->addFlash('success', $mail);

        return $this->redirectToRoute('app_home');
    }

    #[Route('/verification-email/{token}', name: 'app_email_verify')]
    public function emailVerify(
        string         $token,
        UserRepository $repo,
    ) {
        // verify if token is valide and re-activate account
        $isVerified = ($this->VerifyEmailToken)($token);

        if ($isVerified) {
            $this->addFlash('success', 'Votre compte a été vérifé.');
        } else {
            $this->addFlash('danger', 'Lien invalide.');
        }

        return $this->redirectToRoute('app_home');
    }
}
