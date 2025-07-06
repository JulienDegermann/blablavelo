<?php

namespace App\Application\Controller;

// dependencies
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use App\Application\Security\UserAuthAuthenticator;

// entities
use App\Domain\User\User;

// forms
use App\Application\Form\RegistrationFormType;

// inputs
use App\Domain\User\UseCase\CreateUser\CreateUserInput;

// interfaces
use App\Domain\User\Contrat\CreateUserInterface;
use App\Domain\User\Contrat\DeleteAccountInterface;
use App\Domain\User\Contrat\SendNewValidationTokenInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use App\Domain\User\Contrat\VerifyEmailTokenInterface;
use App\Infrastructure\Service\Messages\EmailMessages\EmailVerificationEmailMessage\EmailVerificationPublisherInterface;
use Exception;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly VerifyEmailTokenInterface $VerifyEmailToken,
        private readonly CreateUserInterface $createUser,
        private readonly DeleteAccountInterface $deleteAccount
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
    public function unregister(): Response
    {
        if (!$this->getUser() || !($this->getUser() instanceof User)) {
            return $this->redirectToRoute('app_login');
        }

        /** @var User $user */
        $user = $this->getUser();

        // disconnect $user and destroy session
        $this->redirectToRoute('app_logout');
        $session = new Session();
        $session->invalidate();

        try {
            $response = ($this->deleteAccount)($user);

            $this->addFlash('success', $response);
        } catch (Exception $e) {
            $this->addFlash('danger', $e->getMessage());

            return $this->redirectToRoute('app_profile');
        }

        return $this->redirectToRoute('app_home');
    }


    #[Route('/nouveau-code', name: 'app_new_token')]
    public function newCode(
        EmailVerificationPublisherInterface $verify_email
    ) {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user || !($user instanceof User)) {
            $this->addFlash('danger', 'Vous devez être connecté pour utiliser l\'application.');

            return $this->redirectToRoute('app_login');
        }
        $verify_email($user->getId());

        $this->addFlash('success', 'Un nouveau code a été envoyé à ton adresse mail.');

        return $this->redirectToRoute('app_home');
    }

    #[Route('/verification-email/{token}', name: 'app_email_verify')]
    public function emailVerify(
        string         $token,
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
