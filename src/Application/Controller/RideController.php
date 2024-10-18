<?php

namespace App\Application\Controller;

use App\Application\Form\NewRideType;
use App\Application\Form\RideCommentType;
use App\Application\Form\RideFilterType;
use App\Domain\Ride\Contrat\CreateNewRideInterface;
use App\Domain\Ride\Contrat\FindMyRidesInterface;
use App\Domain\Ride\Contrat\FindRidesInterface;
use App\Domain\Ride\Contrat\RideDetailsInterface;
use App\Domain\Ride\Contrat\RideRepositoryInterface;
use App\Domain\Ride\Ride;
use App\Domain\Ride\RideComment;
use App\Domain\Ride\UseCase\AddRideComment\AddRideComment;
use App\Domain\Ride\UseCase\AddRideComment\AddRideCommentInput;
use App\Domain\Ride\UseCase\CreateRide\NewRideInput;
use App\Domain\Ride\UseCase\FindMyRides\FindMyRides;
use App\Domain\Ride\UseCase\FindRides\FindRidesInput;
use App\Domain\User\User;
use App\Infrastructure\Repository\RideCommentRepository;
use App\Infrastructure\Repository\RideRepository;
use App\Infrastructure\Service\MailSendService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RideController extends AbstractController
{
    public function __construct(
        private readonly FindRidesInterface      $rides,
        private readonly CreateNewRideInterface  $createRide,
        private readonly FindMyRidesInterface    $findMyRides,
        private readonly RideDetailsInterface    $rideDetails,
        private readonly AddRideComment  $addRideComment,
    )
    {
    }

    #[Route('/dashboard', name: 'app_rides')]
    public function index(
        Request            $request,
        PaginatorInterface $paginator,
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user || !($user instanceof User)) {
            return $this->redirectToRoute('app_home');
        }

        $myRides = ($this->findMyRides)($user);

        $input = new FindRidesInput($user);

        $form = $this->createForm(RideFilterType::class, $input, ['user' => $user]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $input = $form->getData();
        }

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
        RideRepository        $rideRepository,
        RideCommentRepository $rideCommentRepository,
        Request               $request,
        int                   $id
    ): Response
    {
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

            return $this->redirectToRoute('app_ride', ['id' => $ride->getId()]);
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
        RideRepository  $rideRepository,
        Request         $request,
        MailSendService $mailSendService,
    ): Response
    {
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
        Request        $request,
    ): Response
    {
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
        $rides = $rideRepository->findBy(['id' === $id]);
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
        Request        $request
    ): Response
    {
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
        $rides = $rideRepository->findBy(['id' === $id]);
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
        Request        $request,
        RideRepository $rideRepository
    ): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('danger', 'Vous devez être connecté utiliser l\'application.');

            return $this->redirectToRoute('app_login');
        }

        /** @var User $user */
        $user = $this->getUser();

        $myRides = ($this->findMyRides)($user);

        // check with uses => find PAST rides of user to count them (participated and created) => may get all times ?
        // $myPrevRides = $rideRepository->myPrevRides($user);
        // $myCreatedRides = $rideRepository->myCreatedRides($user);

        if ($user->getDepartment() == null) {
            $this->addFlash('warning', 'Veuillez renseigner votre département pour créer une sortie.');

            return $this->redirectToRoute('app_profile');
        }

        if ($user->getIsVerified() == false) {
            $this->addFlash('warning', 'Veuillez vérifier votre e-mail pour profiter de l\'application. 
            Pas de mail ? <a class="px-2 text-primary fw-bold" title="demander un nouveau lien de validation" href=" ' . $this->generateUrl("app_new_token") . '">Générer un lien</a>');

            return $this->redirectToRoute('app_home');
        }

//        $ride = new Ride();
//        $ride->setAuthor($this->getUser());
//        $ride->addParticipant($this->getUser());

        $form = $this->createForm(NewRideType::class, new NewRideInput($user), ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newRide = $form->getData();
            $newRide->setCreator($user);
            $return = ($this->createRide)($newRide);

//            $repo->save($ride);

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
