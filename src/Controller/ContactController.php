<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\RideRepository;
use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(
        Request $request,
        MessageRepository $repo,
        RideRepository $rideRepository
    ): Response {
        
        $user = null;
        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();
        }

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message, ['attr' => ['class' => 'form-signin, row']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $repo->save($message);
            
            $this->addFlash('success', 'Votre message a bien été envoyé');
            return $this->redirectToRoute('app_contact');
        }

        $myCreatedRides = $rideRepository->findBy(['author' => $user], ['date' => 'ASC']);
        $myParticipatedRides = $rideRepository->rideOfUser($user);

        return $this->render('contact/index.html.twig', [
            'messageForm' => $form->createView(),
            'user' => $user,
            'my_rides' => $myCreatedRides,
            'all_my_rides' => $myParticipatedRides
        ]);
    }
}
