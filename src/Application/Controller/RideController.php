<?php

namespace App\Application\Controller;

// depndencies
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// entities
use App\Domain\User\User;

// forms
use App\Application\Form\NewRideType;
use App\Application\Form\RideCommentType;
use App\Application\Form\RideFilterType;

// interfaces
use App\Domain\Ride\Contrat\AddParticipantInterface;
use App\Domain\Ride\Contrat\AddRideCommentInterface;
use App\Domain\Ride\Contrat\CreateNewRideInterface;
use App\Domain\Ride\Contrat\FindMyRidesInterface;
use App\Domain\Ride\Contrat\FindRidesInterface;
use App\Domain\Ride\Contrat\RemoveParticipantInterface;
use App\Domain\Ride\Contrat\RemoveRideInterface;
use App\Domain\Ride\Contrat\RideDetailsInterface;
use Knp\Component\Pager\PaginatorInterface;

// inputs
use App\Domain\Ride\UseCase\AddParticipant\AddParticipantInput;
use App\Domain\Ride\UseCase\AddRideComment\AddRideCommentInput;
use App\Domain\Ride\UseCase\CreateRide\NewRideInput;
use App\Domain\Ride\UseCase\FindRides\FindRidesInput;
use App\Domain\Ride\UseCase\RemoveParticipant\RemoveParticipantInput;
use App\Domain\Ride\UseCase\RemoveRide\RemoveRideInput;


class RideController extends AbstractController
{
    public function __construct(
        private readonly FindRidesInterface         $rides,
        private readonly CreateNewRideInterface     $createRide,
        private readonly FindMyRidesInterface       $findMyRides,
        private readonly RideDetailsInterface       $rideDetails,
        private readonly AddRideCommentInterface    $addRideComment,
        private readonly RemoveRideInterface        $removeRide,
        private readonly AddParticipantInterface    $addParticipant,
        private readonly RemoveParticipantInterface $removeParticipant,
    ) {}

    #[Route('/dashboard', name: 'app_rides')]
    public function index(
        Request            $request,
        PaginatorInterface $paginator,
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user || !($user instanceof User)) {
            return $this->redirectToRoute('app_home');
        }

        $input = new FindRidesInput($user);
        $form = $this->createForm(RideFilterType::class, $input, ['user' => $user]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $input = $form->getData();
        }

        $myRides = ($this->findMyRides)($user);
        $nextRides = ($this->rides)($input, $user);

        $pagination = $paginator->paginate(
            $nextRides,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('ride/index.html.twig', [
            'user' => $user,
            'my_next_rides' => $myRides['myNextRides'],
            'my_created_rides' => $myRides['myCreatedRides'],
            'my_prev_rides' => $myRides['allMyRides'],
            'all_rides' => $pagination,
            'filter_form' => $form->createView(),
        ]);
    }

    #[Route('/sortie/{id}', name: 'app_ride', methods: ['GET', 'POST'])]
    public function showRide(
        Request $request,
        int     $id
    ): Response {
        if (!$this->getUser() || !($this->getUser() instanceof User)) {
            $this->addFlash('warning', 'Vous devez être connecté pour utiliser l\'application.');

            return $this->redirectToRoute('app_login');
        }

        /** @var User $user */
        $user = $this->getUser();

        if ($user->getIsVerified() === false) {
            $this->addFlash('warning', 'Veuillez vérifier votre e-mail pour profiter de l\'application. 
            Pas de mail ? <a class="px-2 text-primary fw-bold" title="demander un nouveau lien de validation" href=" ' . $this->generateUrl("app_new_token") . '">Générer un lien</a>');

            return $this->redirectToRoute('app_rides');
        }

        $ride = ($this->rideDetails)($id);
        $myRides = ($this->findMyRides)($user);

        $rideComment = new AddRideCommentInput($ride, $user);
        $form = $this->createForm(RideCommentType::class, $rideComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rideComment = $form->getData();
            ($this->addRideComment)($rideComment);

            $this->addFlash('success', 'Votre commentaire a bien été ajouté.');

            // clear form and input
            unset($rideComment, $form);
            $rideComment = new AddRideCommentInput($ride, $user);
            $form = $this->createForm(RideCommentType::class, $rideComment);
        }

        return $this->render('ride/show_ride.html.twig', [
            'user' => $user,
            'my_next_rides' => $myRides['myNextRides'],
            'my_created_rides' => $myRides['myCreatedRides'],
            'my_prev_rides' => $myRides['allMyRides'],
            'ride' => $ride,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/sortie/supprimer-la-sortie/{id}', name: 'app_ride_delete', methods: ['GET', 'POST'])]
    public function deleteRide(
        int $id
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('warning', 'Vous devez être connecté pour utiliser l\'application.');

            return $this->redirectToRoute('app_login');
        }

        $input = new RemoveRideInput($id, $user);
        ($this->removeRide)($input);

        $this->addFlash('success', "La sortie a bien été supprimée.");

        return $this->redirectToRoute('app_home');
    }

    #[Route('/sortie/{id}/participer', name: 'app_ride_connect', methods: ['GET', 'POST'])]
    public function addToRide(
        int $id
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

        $input = new AddParticipantInput($id, $user);
        ($this->addParticipant)($input);

        $this->addFlash('success', 'Vous êtes inscrit à la sortie.');

        return $this->redirectToRoute('app_rides');
    }

    #[Route('/sortie/{id}/ne-plus-participer', name: 'app_ride_remove', methods: ['GET', 'POST'])]
    public function removeToRide(
        int $id
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

        $input = new RemoveParticipantInput($id, $user);
        ($this->removeParticipant)($input);

        $this->addFlash('success', 'Vous êtes désinscrit de la sortie.');

        return $this->redirectToRoute('app_rides');
    }

    #[Route('/nouvelle-sortie', name: 'app_new_ride', methods: ['GET', 'POST'])]
    public function newRide(
        Request        $request,
    ): Response {
        if (!$this->getUser()) {
            $this->addFlash('danger', 'Vous devez être connecté utiliser l\'application.');

            return $this->redirectToRoute('app_login');
        }

        /** @var User $user */
        $user = $this->getUser();

        $myRides = ($this->findMyRides)($user);
        if ($user->getDepartment() == null) {
            $this->addFlash('warning', 'Veuillez renseigner votre département pour créer une sortie.');

            return $this->redirectToRoute('app_profile');
        }

        if ($user->getIsVerified() == false) {
            $this->addFlash('warning', 'Veuillez vérifier votre e-mail pour profiter de l\'application. 
            Pas de mail ? <a class="px-2 text-primary fw-bold" title="demander un nouveau lien de validation" href=" ' . $this->generateUrl("app_new_token") . '">Générer un lien</a>');

            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(NewRideType::class, new NewRideInput($user), ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newRide = $form->getData();
            ($this->createRide)($newRide);

            $this->addFlash('success', 'Votre sortie a bien été créée.');

            return $this->redirectToRoute('app_rides');
        }

        return $this->render('ride/new_ride.html.twig', [
            'user' => $user,
            'my_next_rides' => $myRides['myNextRides'],
            'my_created_rides' => $myRides['myCreatedRides'],
            'my_prev_rides' => $myRides['allMyRides'],
            'form' => $form->createView(),
        ]);
    }
}
