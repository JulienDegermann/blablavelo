<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordResetType;
use App\Form\PasswordForgotType;
use App\Repository\UserRepository;
use App\Service\MailSendService;
use EasyCorp\Bundle\EasyAdminBundle\Security\AuthorizationChecker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Mime\Email;



class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();
            if ($this->isGranted('ROLE_ADMIN', $user)) {
                return $this->redirectToRoute('admin');
            }
            elseif ($this->isGranted('ROLE_USER', $user)) {
                return $this->redirectToRoute('app_home');
            }
        }

        $user = null;

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        if($error != '' || $error !== null) {
            $this->addFlash('danger', 'Identifiants incorrects');
        }
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'user' => $user
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/mot-de-passe-oublie', name: 'app_pwd_forgot')]
    public function pwdForgot(
        UserRepository $repo,
        Request $request,
        TokenGeneratorInterface $tokenGenerator,
        MailerInterface $mail,
        MailSendService $mailSendService
    ): Response {
        
        $user = null;

        if($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();

            $token = $tokenGenerator->generateToken();
            $user->setToken($token);
            $repo->save($user);
            
            return $this->redirectToRoute('app_pwd_reset', ['token' => $token]);
        }

        $form = $this->createForm(PasswordForgotType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $repo->findOneBy(['email' => $form->getData()->getEmail()]);
            
            if ($user) {
                $mail = $mailSendService->forgotPasswordEmail($user);
                $this->addFlash('success', $mail);
            }

            return $this->render('security/pwd_forgot.html.twig', [
                'form' => $form->createView()
            ]);
        }

        return $this->render('security/pwd_forgot.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route(path: '/reinitialiser-mot-de-passe/{token}', name: 'app_pwd_reset')]
    public function pwdReset(
        Request $request,
        string $token,
        UserRepository $repo,
        UserPasswordHasherInterface $pwdhash
    ): Response {
    
        $user = $repo->findOneBy(['token' => $token]);

        if (!$user) {
            $this->addFlash('error', 'Oups ! Ce lien n\'est plus valide.');
            return $this->redirectToRoute('app_login');
        }
        
        $form = $this->createForm(PasswordResetType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user->setPassword(
                    $pwdhash->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                )
                    ->setToken(null);
                $repo->save($user);
                $this->addFlash('success', 'Mot de passe mis à jour');
            } else {
                $this->addFlash('error', 'Une erreur est survenue.');
                return $this->render('security/pwd_reset.html.twig', [
                    'form' => $form->createView(),
                    'user' => $user
                ]);
            }
        }

        return $this->render('security/pwd_reset.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}
