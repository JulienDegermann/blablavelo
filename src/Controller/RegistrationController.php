<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;


class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        UserAuthenticatorInterface $userAuthenticator, 
        UserAuthAuthenticator $authenticator, 
        TokenGeneratorInterface $tokenGenerator,
        MailerInterface $mail,
        UserRepository $userRepo
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
            $plain_user_name = $form->get('user_name')->getData();

            if($userRepo->findBy(['email' => $plain_email]) || $userRepo->findBy(['user_name' => $plain_user_name])) {
                $this->addFlash('danger', 'Impossible de créer le compte avec les informations fournies. Veuillez recommencer.');
                return $this->redirectToRoute('app_register');
            };

            // encode the plain password
            $token = $tokenGenerator->generateToken();
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            )
                ->setRoles(['ROLE_USER'])
                ->setToken($token);            

            $userRepo->save($user);

            // mail sending
            if ($user->getToken()) {
                    $url = $this->generateUrl('app_email_verify', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                    $email = (new Email())
                        ->from('no-reply.blablabike@julien-degermann.fr')
                        // ->to($user->getEmail()) -> replace when all is ok
                        ->to('degermann.julien@gmail.com')
                        ->subject('Confirmation de votre e-mail')
                        ->html('<p>Vous pouvez confirmer votre e-mail en cliquant sur le lien suivant :</p> <a href="'.$url.'">cliquez ici</a>');
                    $mail->send($email);
                }

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'user' => $user
        ]);
    }

    #[Route('/supprimer-mon-compte', name: 'app_delete')]
    public function unregister(
        UserRepository $repo     
        ): Response
    {
        if(!$this->getUser()) {
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



    #[Route('/nouveau-code', name: 'app_new_token')]
    public function newCode(
        UserRepository $repo,
        TokenGeneratorInterface $tokenGenerator,
        MailerInterface $mail,
    ) {

        if(!$this->getUser()) {
            $this->addFlash('danger', 'Vous devez être connecté pour utiliser l\'application.');
            return $this->redirectToRoute('app_login');
        }
        
        /** @var User $user */
        $user = $this->getUser();
        $token = $tokenGenerator->generateToken();
        $user->setToken($token);

        $repo->save($user);
        if ($user->getToken()) {
            $url = $this->generateUrl('app_email_verify', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
            $email = (new Email())
                ->from('no-reply.blablabike@julien-degermann.fr')
                // ->to($user->getEmail()) -> replace when all is ok
                ->to('degermann.julien@gmail.com')
                ->subject('Confirmation de votre e-mail')
                ->html('<p>Vous pouvez confirmer votre e-mail en cliquant sur le lien suivant :</p> <a href="'.$url.'">cliquez ici</a>');
            $mail->send($email);
        }

        $this->addFlash('success', 'Un nouveau code de vérification vous a été envoyé par e-mail.');
        return $this->redirectToRoute('app_home');
    }

    #[Route('/verification-email/{token}', name: 'app_email_verify')]
    public function emailVerify(
        string $token,
        UserRepository $repo,
    ) {
        $user = $repo->findOneBy(['token' => $token]);
        
        if (!$user) {
            $this->addFlash('danger', 'Oups ! Ce lien n\'est plus valide.');
            return $this->redirectToRoute('app_home');
        }

        $user->setIsVerified(true)
            ->setToken(null);
        $repo->save($user);

        $this->addFlash('success', 'Votre compte a été vérifé.');
        return $this->redirectToRoute('app_home');
    }

}
