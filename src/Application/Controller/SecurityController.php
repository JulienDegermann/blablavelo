<?php

namespace App\Application\Controller;

use App\Application\Form\PasswordResetType;
use App\Application\Form\ResetPasswordType;
use App\Domain\User\Contrat\ResetPasswordInterface;
use App\Infrastructure\Repository\RideRepository;
use App\Infrastructure\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $user = $this->getUser();
        if ($user) {
            if ($this->isGranted('ROLE_ADMIN', $user)) {
                return $this->redirectToRoute('admin');
            } elseif ($this->isGranted('ROLE_USER', $user)) {
                return $this->redirectToRoute('app_home');
            }
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        if ($error != '' || $error !== null) {
            $this->addFlash('danger', 'Identifiants incorrects');
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'user' => $user,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    // #[Route(path: '/reinitialiser-mot-de-passe/{token}', name: 'app_pwd_reset')]
    // public function pwdReset(
    //     Request                     $request,
    //     string                      $token,
    //     UserRepository              $repo,
    //     UserPasswordHasherInterface $pwdhash,
    //     RideRepository              $rideRepository
    // ): Response {
        
    //     $user = ($this->resetPassword)($token);

    //     // $user = $repo->findOneBy(['token' => $token]);

    //     $form = $this->createForm(ResetPasswordType::class, $user);
    //     $form->handleRequest($request);

    //     // check with uses => find PAST rides of user to count them (participated and created) => may get all times ?
    //     $myPrevRides = $rideRepository->myPrevRides($user);
    //     $myCreatedRides = $rideRepository->myCreatedRides($user);

    //     if ($form->isSubmitted()) {
    //         if ($form->isValid()) {
    //             $user->setPassword(
    //                 $pwdhash->hashPassword(
    //                     $user,
    //                     $form->get('password')->getData()
    //                 )
    //             )
    //                 ->setToken(null);
    //             $repo->save($user);
    //             $this->addFlash('success', 'Mot de passe mis à jour');
    //         } else {
    //             $this->addFlash('error', 'Une erreur est survenue.');

    //             return $this->render('security/pwd_reset.html.twig', [
    //                 'form' => $form->createView(),
    //                 'user' => $user,
    //                 'my_rides' => $myCreatedRides,
    //                 'my_prev_rides' => $myPrevRides,
    //             ]);
    //         }
    //     }

    //     return $this->render('security/pwd_reset.html.twig', [
    //         'form' => $form->createView(),
    //         'user' => $user,
    //         'my_rides' => $myCreatedRides,
    //         'my_prev_rides' => $myPrevRides,
    //     ]);
    // }
}
