<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PricacyPolicyController extends AbstractController
{
    #[Route('/politique-de-confidentialite', name: 'app_pricacy_policy')]
    public function index(): Response
    {
        $user = new User();
        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();
        }
        $user_name = $user->getUserName();
        return $this->render('pricacy_policy/index.html.twig', [
            'user_name' => $user_name
        ]);
    }
}
