<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, EntityManagerInterface $entityManager): Response
    {

        $current_user = new User();
        $current_user = $this->getUser();
        $user_name = $current_user ? $current_user->getUserName() : '' ;
        $user_mail = $current_user ? $current_user->getEmail() : '' ; 
        
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message, ['attr' => ['class' => 'form-signin, row']]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            // $user = new User();
            // $user->setEmail($form->get('user')->getData());
            // $user->setFirstName('Test');
            // $user->setLastName('Test');
            // $message->setUser($user);
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('app_contact');
        }
        return $this->render('contact/index.html.twig', [
            'messageForm' => $form->createView(),
            'user_name' => $user_name,
            'user_email' => $user_mail
        ]);
    }
}
