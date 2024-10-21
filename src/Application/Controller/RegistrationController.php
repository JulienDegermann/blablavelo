<?php

namespace App\Application\Controller;

use App\Application\Form\RegistrationFormType;
use App\Application\Security\UserAuthAuthenticator;
use App\Domain\User\Contrat\VerifyEmailTokenInterface;
use App\Domain\User\User;
use App\Infrastructure\Repository\UserRepository;
use App\Infrastructure\Service\JWTTokenGeneratorService\MailSendService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly VerifyEmailTokenInterface $VerifyEmailToken
    )
    {
    }

    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request                     $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface  $userAuthenticator,
        UserAuthAuthenticator       $authenticator,
        MailerInterface             $mail,
        UserRepository              $userRepo,
        MailSendService             $mailSendService
    ): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user, ['attr' => ['class' => 'form-signin, row']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plain_email = $form->get('email')->getData();
            $plain_user_name = $form->get('nameNumber')->getData();

            if ($userRepo->findBy(['email' => $plain_email]) || $userRepo->findBy(['nameNumber' => $plain_user_name])) {
                $this->addFlash('danger', 'Impossible de créer le compte avec les informations fournies. Veuillez recommencer.');

                return $this->redirectToRoute('app_register');
            };

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            )
                ->setRoles(['ROLE_USER']);
            // ->setToken($token);

            $userRepo->save($user);

            // mail sending
            $mail = $mailSendService->emailConfirmation($user);

            $this->addFlash('success', $mail);

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/supprimer-mon-compte', name: 'app_delete')]
    public function unregister(
        UserRepository $repo
    ): Response
    {
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
    )
    {
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
    )
    {
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
