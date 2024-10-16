<?php

namespace App\Application\Controller;

use App\Application\Form\MessageType;
use App\Application\Message\Message;
use App\Domain\User\User;
use App\Infrastructure\Repository\MessageRepository;
use App\Infrastructure\Repository\RideRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(
        Request $request,
        MessageRepository $repo,
        RideRepository $rideRepository
    ): Response {
        
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('warning', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_home');
        }

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message, ['attr' => ['class' => 'form-signin, row']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $message->setAuthor($user);
            $repo->save($message);
            
            $this->addFlash('success', 'Votre message a bien été envoyé');
            return $this->redirectToRoute('app_home');
        }

        $myPrevRides = $rideRepository->myPrevRides($user);
        $myCreatedRides = $rideRepository->myCreatedRides($user);

        return $this->render('contact/index.html.twig', [
            'messageForm' => $form->createView(),
            'user' => $user,
            'my_rides' => $myCreatedRides,
            'my_prev_rides' => $myPrevRides
        ]);
    }
}
