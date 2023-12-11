<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/profil', name: 'app_profile')]
    public function index(
        UserRepository $repo,
        Request $request
    ): Response {
        
        $user = null;
        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();
        }

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $repo->save($user);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),

        ]);
    }
}
