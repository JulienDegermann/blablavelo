<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\User;
use App\Form\NewRideType;
use App\Repository\RideRepository;
use App\Repository\UserRepository;
use App\Service\MailSendService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Command\MailerTestCommand;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class RideController extends AbstractController
{
    #[Route('/sorties', name: 'app_rides')]
    public function index(
        RideRepository $rideRepository,
        Request $request,
        PaginatorInterface $paginator,
    ): Response {
        
        $user = null;
        $userdepartment = null;
        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();
        
            if ($user->getDepartment() != null) {
            	$userdepartment = $user->getDepartment();
            } else {
            }
        }

        $allRides = $rideRepository->findAll();
        foreach($allRides as $ride) {
            if($ride->getDate() < (new \DateTime('now - 1 day'))) {
                $rideRepository->remove($ride);
            }
        }

        $pagination = $paginator->paginate(
            $rideRepository->ridePaginated($userdepartment),
            $request->query->getInt('page', 1),
            6
        );
        
        return $this->render('ride/index.html.twig', [
            'user' => $user,
            'all_rides' => $pagination,
        ]);
    }

    #[Route('/sortie/{id}', name: 'app_ride', methods: ['GET', 'POST'])]
    public function showRide(
        RideRepository $rideRepository,
        Request $request
    ): Response {

        $allRides = $rideRepository->findAll();
        foreach($allRides as $ride) {
            if($ride->getDate() < (new \DateTime('now - 1 day'))) {
                $rideRepository->remove($ride);
            }
        }

        $id = $request->attributes->get('id');
        $rides = $rideRepository->findBy(['id' => $id]);
        $ride = $rides[0];

        if (!$this->getUser()) {
            $this->addFlash('warning', 'Vous devez être connecté pour utiliser l\'application.');
            return $this->redirectToRoute('app_login');
        }

        /** @var User $user */
        $user = $this->getUser();
        if($user->getIsVerified() == false) {
            $this->addFlash('warning', 'Veuillez vérifier votre e-mail pour profiter de l\'application. 
            Pas de mail ? <a class="px-2 text-primary fw-bold" title="demander un nouveau lien de validation" href=" '. $this->generateUrl("app_new_token") . '">Générer un lien</a>');
            return $this->redirectToRoute('app_rides');
        }
        return $this->render('ride/show_ride.html.twig', [
            'user' => $user,
            'ride' => $ride,
        ]);
    }


    #[Route('/sortie/supprimer-la-sortie/{id}', name: 'app_ride_delete', methods: ['GET', 'POST'])]
    public function deleteRide(
        RideRepository $rideRepository,
        UserRepository $userRepository,
        Request $request,
        MailSendService $mailSendService,
    ): Response {
        
        /** @var User $user */
        $user = $this->getUser();

        if(!$user) {
            $this->addFlash('warning', 'Vous devez être connecté pour utiliser l\'application.');
            return $this->redirectToRoute('app_login');
        }
        $id = $request->attributes->get('id');

        /** @var Ride $ride */
        $ride = $rideRepository->findOneBy(['id' => $id]);

        if($ride->getUserCreator() != $user) {
            $this->addFlash('warning', 'Vous ne pouvez pas supprimer cette sortie.');
            return $this->redirectToRoute('app_rides');
        }

        if ($user->getIsVerified() == false) {
            $this->addFlash('warning', 'Veuillez vérifier votre e-mail pour profiter de l\'application. 
            Pas de mail ? <a class="px-2 text-primary fw-bold" title="demander un nouveau lien de validation" href=" '. $this->generateUrl("app_new_token") . '">Générer un lien</a>');
            return $this->redirectToRoute('app_home');
        }

        $participants = $ride->getUserParticipant();    
        $mail = $mailSendService->deleteRideEmail($participants, $user);

        $rideRepository->remove($ride);
        
        $this->addFlash('success', $mail);
        return $this->redirectToRoute('app_home');
    }


    #[Route('/sortie/{id}/participer', name: 'app_ride_connect', methods: ['GET', 'POST'])]
    public function addToRide(
        RideRepository $rideRepository,
        Request $request
    ): Response {

        $user = null;
        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();
        }

        if ($user->getIsVerified() == false) {
            $this->addFlash('warning', 'Veuillez vérifier votre e-mail pour profiter de l\'application. 
            Pas de mail ? <a class="px-2 text-primary fw-bold" title="demander un nouveau lien de validation" href=" '. $this->generateUrl("app_new_token") . '">Générer un lien</a>');
            return $this->redirectToRoute('app_home');
        }

        $id = $request->attributes->get('id');
        $rides = $rideRepository->findBy(['id' => $id]);
        $ride = $rides[0];
        $user = $this->getUser();
        $ride->addUserParticipant($user);
        $rideRepository->save($ride);

        $this->addFlash('success', 'Vous êtes inscrit à la sortie.');
        return $this->redirectToRoute('app_rides');
    }

    #[Route('/sortie/{id}/ne-plus-particioer', name: 'app_ride_remove', methods: ['GET', 'POST'])]
    public function removeToRide(
        RideRepository $rideRepository,
        Request $request
    ): Response {

        if(!$this->getUser()) {
            $this->addFlash('warning', 'Vous devez être connecté pour voir les annonces');
            return $this->redirectToRoute('app_login');
        }

        /** @var User $user */
        $user = $this->getUser();

        if ($user->getIsVerified() == false) {
            $this->addFlash('warning', 'Veuillez vérifier votre e-mail pour profiter de l\'application. 
            Pas de mail ? <a class="px-2 text-primary fw-bold" title="demander un nouveau lien de validation" href=" '. $this->generateUrl("app_new_token") . '">Générer un lien</a>');
            return $this->redirectToRoute('app_home');
        }

        $id = $request->attributes->get('id');
        $rides = $rideRepository->findBy(['id' => $id]);
        $ride = $rides[0];
        $user = $this->getUser();
        $ride->removeUserParticipant($user);
        $rideRepository->save($ride);

        $this->addFlash('success', 'Vous êtes désinscrit de la sortie.');
        return $this->redirectToRoute('app_rides');
    }


    #[Route('/nouvelle-sortie', name: 'app_new_ride', methods: ['GET', 'POST'])]
    public function newRide(
        RideRepository $repo,
        Request $request
    ): Response {
        
        if (!$this->getUser()) {
            $this->addFlash('danger', 'Vous devez être connecté utiliser l\'application.');
            return $this->redirectToRoute('app_login');
        }

        /** @var User $user */
        $user = $this->getUser();

        if ($user->getDepartment() == null) {
            $this->addFlash('warning', 'Veuillez renseigner votre département pour créer une sortie.');
            return $this->redirectToRoute('app_profile');
        }

        if ($user->getIsVerified() == false) {
            $this->addFlash('warning', 'Veuillez vérifier votre e-mail pour profiter de l\'application. 
            Pas de mail ? <a class="px-2 text-primary fw-bold" title="demander un nouveau lien de validation" href=" '. $this->generateUrl("app_new_token") . '">Générer un lien</a>');
            return $this->redirectToRoute('app_home');
        }
        
        $ride = new Ride();
        $ride->setUserCreator($this->getUser());
        $ride->addUserParticipant($this->getUser());
        $form = $this->createForm(NewRideType::class, $ride, ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ride = $form->getData();
            $repo->save($ride);

            $this->addFlash('success', 'Votre sortie a bien été créée.');
            return $this->redirectToRoute('app_rides');
        }

        return $this->render('ride/new_ride.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
