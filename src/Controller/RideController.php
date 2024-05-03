<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\User;
use App\Form\NewRideType;
use App\Form\RideFilterType;
use App\Service\MailSendService;
use App\Repository\RideRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RideController extends AbstractController
{
    #[Route('/sorties', name: 'app_rides')]
    public function index(
        RideRepository $rideRepository,
        Request $request,
        PaginatorInterface $paginator,
    ): Response {

        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_home');
        }
        
        $userdepartment = $user->getDepartment() ? $user->getDepartment() : null;

        $myCreatedRides = $rideRepository->findBy(['author' => $user], ['date' => 'ASC']);
        $myParticipatedRides = $rideRepository->rideOfUser($user);
        
        // get all rides of user's department
        $allRides = $rideRepository->rideFilter(null, $user->getDepartment(), null, null, null, null, null);

        $form = $this->createForm(RideFilterType::class, null, ['user' => $user]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // get all datas from form
            $mind = $request->request->all()['ride_filter']['mind'];
            $department = $request->request->all()['ride_filter']['department'];
            $date = $request->request->all()['ride_filter']['date'];
            $distance = $request->request->all()['ride_filter']['distance'];
            $participants = $request->request->all()['ride_filter']['participants'];
            $averageSpeed = $request->request->all()['ride_filter']['average_speed'];
            $ascent = $request->request->all()['ride_filter']['ascent'];

            $allRides = $rideRepository->rideFilter($mind, $department, $date, $distance, $participants, $averageSpeed, $ascent);
        }

        $pagination = $paginator->paginate(
            $allRides,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('ride/index.html.twig', [
            'user' => $user,
            'all_rides' => $pagination,
            'my_rides' => $myCreatedRides,
            'all_my_rides' => $myParticipatedRides,
            'filter_form' => $form->createView(),
        ]);
    }

    #[Route('/sortie/{id}', name: 'app_ride', methods: ['GET', 'POST'])]
    public function showRide(
        RideRepository $rideRepository,
        Request $request,
    ): Response {

        $id = $request->attributes->get('id');
        $rides = $rideRepository->findBy(['id' => $id]);
        $ride = $rides[0];

        if (!$this->getUser()) {
            $this->addFlash('warning', 'Vous devez être connecté pour utiliser l\'application.');
            return $this->redirectToRoute('app_login');
        }

        /** @var User $user */
        $user = $this->getUser();
        if ($user->getIsVerified() == false) {
            $this->addFlash('warning', 'Veuillez vérifier votre e-mail pour profiter de l\'application. 
            Pas de mail ? <a class="px-2 text-primary fw-bold" title="demander un nouveau lien de validation" href=" ' . $this->generateUrl("app_new_token") . '">Générer un lien</a>');
            return $this->redirectToRoute('app_rides');
        }

        $myCreatedRides = $rideRepository->findBy(['author' => $user], ['date' => 'ASC']);
        $myParticipatedRides = $rideRepository->rideOfUser($user);

        return $this->render('ride/show_ride.html.twig', [
            'user' => $user,
            'ride' => $ride,
            'my_rides' => $myCreatedRides,
            'all_my_rides' => $myParticipatedRides,
        ]);
    }


    #[Route('/sortie/supprimer-la-sortie/{id}', name: 'app_ride_delete', methods: ['GET', 'POST'])]
    public function deleteRide(
        RideRepository $rideRepository,
        Request $request,
        MailSendService $mailSendService,
    ): Response {

        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('warning', 'Vous devez être connecté pour utiliser l\'application.');
            return $this->redirectToRoute('app_login');
        }
        $id = $request->attributes->get('id');

        /** @var Ride $ride */
        $ride = $rideRepository->findOneBy(['id' => $id]);

        if ($ride->getAuthor() != $user) {
            $this->addFlash('warning', 'Vous ne pouvez pas supprimer cette sortie.');
            return $this->redirectToRoute('app_rides');
        }

        if ($user->getIsVerified() == false) {
            $this->addFlash('warning', 'Veuillez vérifier votre e-mail pour profiter de l\'application. 
            Pas de mail ? <a class="px-2 text-primary fw-bold" title="demander un nouveau lien de validation" href=" ' . $this->generateUrl("app_new_token") . '">Générer un lien</a>');
            return $this->redirectToRoute('app_home');
        }

        $participants = $ride->getParticipants();
        $mail = $mailSendService->deleteRideEmail($participants, $user, $ride);

        $rideRepository->remove($ride);

        $this->addFlash('success', $mail);
        return $this->redirectToRoute('app_home');
    }

    #[Route('/sortie/{id}/participer', name: 'app_ride_connect', methods: ['GET', 'POST'])]
    public function addToRide(
        RideRepository $rideRepository,
        Request $request,
        MailSendService $mailSendService,
    ): Response {

        $user = null;
        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();
        }

        if ($user->getIsVerified() == false) {
            $this->addFlash('warning', 'Veuillez vérifier votre e-mail pour profiter de l\'application. 
            Pas de mail ? <a class="px-2 text-primary fw-bold" title="demander un nouveau lien de validation" href=" ' . $this->generateUrl("app_new_token") . '">Générer un lien</a>');
            return $this->redirectToRoute('app_home');
        }

        $id = $request->attributes->get('id');
        $rides = $rideRepository->findBy(['id' => $id]);
        $ride = $rides[0];
        $user = $this->getUser();
        $ride->addParticipant($user);
        $rideRepository->save($ride);

        $this->addFlash('success', 'Vous êtes inscrit à la sortie.');
        return $this->redirectToRoute('app_rides');
    }

    #[Route('/sortie/{id}/ne-plus-particioer', name: 'app_ride_remove', methods: ['GET', 'POST'])]
    public function removeToRide(
        RideRepository $rideRepository,
        Request $request
    ): Response {

        if (!$this->getUser()) {
            $this->addFlash('warning', 'Vous devez être connecté pour voir les annonces');
            return $this->redirectToRoute('app_login');
        }

        /** @var User $user */
        $user = $this->getUser();

        if ($user->getIsVerified() == false) {
            $this->addFlash('warning', 'Veuillez vérifier votre e-mail pour profiter de l\'application. 
            Pas de mail ? <a class="px-2 text-primary fw-bold" title="demander un nouveau lien de validation" href=" ' . $this->generateUrl("app_new_token") . '">Générer un lien</a>');
            return $this->redirectToRoute('app_home');
        }

        $id = $request->attributes->get('id');
        $rides = $rideRepository->findBy(['id' => $id]);
        $ride = $rides[0];
        $user = $this->getUser();
        $ride->removeParticipant($user);
        $rideRepository->save($ride);

        $this->addFlash('success', 'Vous êtes désinscrit de la sortie.');
        return $this->redirectToRoute('app_rides');
    }


    #[Route('/nouvelle-sortie', name: 'app_new_ride', methods: ['GET', 'POST'])]
    public function newRide(
        RideRepository $repo,
        Request $request,
        RideRepository $rideRepository
    ): Response {

      

        if (!$this->getUser()) {
            $this->addFlash('danger', 'Vous devez être connecté utiliser l\'application.');
            return $this->redirectToRoute('app_login');
        }

        /** @var User $user */
        $user = $this->getUser();

        $myCreatedRides = $rideRepository->findBy(['author' => $user], ['date' => 'ASC']);
        $myParticipatedRides = $rideRepository->rideOfUser($user);

        if ($user->getDepartment() == null) {
            $this->addFlash('warning', 'Veuillez renseigner votre département pour créer une sortie.');
            return $this->redirectToRoute('app_profile');
        }

        if ($user->getIsVerified() == false) {
            $this->addFlash('warning', 'Veuillez vérifier votre e-mail pour profiter de l\'application. 
            Pas de mail ? <a class="px-2 text-primary fw-bold" title="demander un nouveau lien de validation" href=" ' . $this->generateUrl("app_new_token") . '">Générer un lien</a>');
            return $this->redirectToRoute('app_home');
        }

        $ride = new Ride();
        $ride->setAuthor($this->getUser());
        $ride->addParticipant($this->getUser());
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
            'my_rides' => $myCreatedRides,
            'all_my_rides' => $myParticipatedRides,
        ]);
    }
}
