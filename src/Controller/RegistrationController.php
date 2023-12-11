<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\RideRepository;
use App\Repository\UserRepository;
use App\Security\UserAuthAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;


class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user, ['attr' => ['class' => 'form-signin, row']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            )
                ->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

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
        UserRepository $repo,
        RideRepository $rideRepository        
        ): Response
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        /** @var User $user */
        
        // disconnect $user and destroy session
        $session = new Session();
        $session->invalidate();
        $this->redirectToRoute('app_logout');

        $repo->remove($user);

        if ($repo->findBy(['email' => $user->getEmail()])) {
            $this->addFlash('error', 'Une erreur est survenue, veuillez réessayer ultérieurement');
            return $this->redirectToRoute('app_profile');
        }
        
        $rides = $rideRepository->findBy([], ['id' => 'DESC'], 5);

        return $this->redirectToRoute('app_home');
    }
}
