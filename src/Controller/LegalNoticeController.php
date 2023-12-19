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
        if(!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_home ');
        }
        $user = null;
        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();
        }

        return $this->render('legal_notice/index.html.twig', [
            'user' => $user
        ]);
    }
}
