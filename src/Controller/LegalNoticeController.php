<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LegalNoticeController extends AbstractController
{
    #[Route('/mentions-legales', name: 'app_legal_notice')]
    public function index(): Response
    {
        $user = new User();
        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();
        }
        $user_name = $user->getUserName();
        return $this->render('legal_notice/index.html.twig', [
            'user_name' => $user_name
        ]);
    }
}
