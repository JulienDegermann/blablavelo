<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\User;
use App\Form\NewRideType;
use App\Entity\RideComment;
use App\Form\RideFilterType;
use App\Form\RideCommentType;
use App\Service\MailSendService;
use App\Repository\RideRepository;
use App\Repository\RideCommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RideController extends AbstractController
{
    #[Route('/dashboard', name: 'app_rides')]
    public function index(
        RideRepository $rideRepository,
        Request $request,
        PaginatorInterface $paginator
    ): Response {

        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_home');
        }

        $userdepartment = $user->getDepartment() ? $user->getDepartment() : null;
        $userMind = $user->getMind() ? $user->getMind() : null;
        $userPractice = $user->getPractice() ? $user->getPractice() : null;

        // check with uses => find PAST rides of user to count them (participated and created) => may get all times ?
        $myPrevRides = $rideRepository->myPrevRides($user);
        $myCreatedRides = $rideRepository->myCreatedRides($user);

        // find NEXT rides of user to display and count them
        $myNextRides = $rideRepository->myNextRides($user);

        // get all rides of user's department
        $allRides = $rideRepository->rideFilter(
            $userPractice,
            $userMind,
            $userdepartment,
            new \DateTime(),
            15,
            60,
            4,
            8,
            10,
            30,
            200,
            1000
        );

        $form = $this->createForm(RideFilterType::class, null, ['user' => $user]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // get all datas from form
            $practice = $request->request->all()['ride_filter']['practice'];
            $mind = $request->request->all()['ride_filter']['mind'];
            $department = $request->request->all()['ride_filter']['department'];
            $date = $request->request->all()['ride_filter']['date'];
            $distance_min = $request->request->all()['ride_filter']['distance_min'];
            $distance_max = $request->request->all()['ride_filter']['distance_max'];
            $participants_min = $request->request->all()['ride_filter']['participants_min'];
            $participants_max = $request->request->all()['ride_filter']['participants_max'];
            $averageSpeed_min = $request->request->all()['ride_filter']['average_speed_min'];
            $averageSpeed_max = $request->request->all()['ride_filter']['average_speed_max'];
            $ascent_min = $request->request->all()['ride_filter']['ascent_min'];
            $ascent_max = $request->request->all()['ride_filter']['ascent_max'];

            $allRides = $rideRepository->rideFilter(
                $practice,
                $mind,
                $department,
                $date,
                $distance_min,
                $distance_max,
                $participants_min,
                $participants_max,
                $averageSpeed_min,
                $averageSpeed_max,
                $ascent_min,
                $ascent_max
            );
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
            'my_next_rides' => $myNextRides,
            'my_prev_rides' => $myPrevRides,
            'filter_form' => $form->createView(),
        ]);
    }

    #[Route('/sortie/{id}', name: 'app_ride', methods: ['GET', 'POST'])]
    public function showRide(
        RideRepository $rideRepository,
        RideCommentRepository $rideCommentRepository,
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

        // check with uses => find PAST rides of user to count them (participated and created) => may get all times ?
        $myPrevRides = $rideRepository->myPrevRides($user);
        $myCreatedRides = $rideRepository->myCreatedRides($user);

        // find NEXT rides of user to display and count them
        $myNextRides = $rideRepository->myNextRides($user);

        $rideComment = new RideComment();
        $form = $this->createForm(RideCommentType::class, $rideComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rideComment = $form->getData();
            $rideComment->setAuthor($user);
            $rideComment->setRide($ride);

            $rideCommentRepository->save($rideComment);

            $ride->addRideComment($rideComment);
            $rideRepository->save($ride);

            $this->addFlash('success', 'Votre commentaire a bien été ajouté.');
            return $this->redirectToRoute('app_ride', ['id' => $ride->getId()]);
        }

        return $this->render('ride/show_ride.html.twig', [
            'user' => $user,
            'ride' => $ride,
            'my_rides' => $myCreatedRides,
            'my_next_rides' => $myNextRides,
            'my_prev_rides' => $myPrevRides,
            'form' => $form->createView(),
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
    ): Response {


        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('warning', 'Vous devez être connecté pour voir les annonces');
            return $this->redirectToRoute('app_home');
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

        // check with uses => find PAST rides of user to count them (participated and created) => may get all times ?
        // check with uses => find PAST rides of user to count them (participated and created) => may get all times ?
        $myPrevRides = $rideRepository->myPrevRides($user);
        $myCreatedRides = $rideRepository->myCreatedRides($user);

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
            'my_prev_rides' => $myPrevRides,
        ]);
    }
}
