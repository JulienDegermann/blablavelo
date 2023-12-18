<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\User;
use App\Form\ProfileType;
use Symfony\Component\Mime\Email;
use App\Repository\RideRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;


class UserController extends AbstractController
{
    #[Route('/profil', name: 'app_profile')]
    public function index(
        UserRepository $repo,
        RideRepository $rideRepo,
        Request $request,
        MailerInterface $mail,
        TokenGeneratorInterface $tokenGenerator
        ): Response {
        
            
        /** @var User $user */
        $user = $this->getUser();
            
        if (!$this->getUser()) {
            $this->addFlash('warning', 'Vous devez être connecté pour utiliser l\'application');
            return $this->redirectToRoute('app_login');
        }
        
        $rides = $rideRepo->rideOfUser($user);

        $form = $this->createForm(ProfileType::class, $user, ['user' => $user]);
        $form->handleRequest($request);
            
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('email')->getData() !== $user->getEmail()) {
                $user->setIsVerified(false);
                $token = $tokenGenerator->generateToken();
                $user->setToken($token);
                
                $url = $this->generateUrl('app_email_verify', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                $email = (new Email())
                        ->from('no-reply.blablabike@julien-degermann.fr')
                        // ->to($user->getEmail()) -> replace when all is ok
                        ->to('degermann.julien@gmail.com')
                        ->subject('Confirmation de votre e-mail')
                        ->html('<p>Vous pouvez confirmer votre e-mail en cliquant sur le lien suivant :</p> <a href="'.$url.'">cliquez ici</a>');
                    $mail->send($email);
            }

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
