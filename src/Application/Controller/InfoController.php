<?php

namespace App\Application\Controller;

use App\Domain\User\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InfoController extends AbstractController
{
    #[Route('/comment-ca-marche', name: 'app_info')]
    public function index(
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('info/index.html.twig', [
            'user' => $user,
        ]);
    }
}
