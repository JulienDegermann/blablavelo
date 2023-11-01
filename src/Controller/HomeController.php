<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $user = $this->getUser();
        $user_name = $user ? $user->getUserName() : '' ;
        
        return $this->render('home/index.html.twig', [
            'user_name' => $user_name
        ]);
    }
}
