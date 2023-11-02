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

        $user = new User();
        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();
        }
        $user_name = $user->getUserName();
        $user_mail = $user->getEmail();
        
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message, ['attr' => ['class' => 'form-signin, row']]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
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
