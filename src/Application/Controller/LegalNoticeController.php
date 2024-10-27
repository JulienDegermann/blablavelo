<?php

namespace App\Application\Controller;

use App\Domain\User\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalNoticeController extends AbstractController
{
    #[Route('/mentions-legales', name: 'app_legal_notice')]
    public function index(
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('legal_notice/index.html.twig', [
            'user' => $user,
        ]);
    }
}
