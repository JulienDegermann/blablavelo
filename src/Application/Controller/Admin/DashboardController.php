<?php

namespace App\Application\Controller\Admin;

use App\Application\Message\Message;
use App\Domain\PracticeDetail\Mind;
use App\Domain\PracticeDetail\Practice;
use App\Domain\Ride\Ride;
use App\Domain\User\User;
use App\EasyAdmin\Traits\ActionsTrait;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]

    public function index(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_home');
        }
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //

        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Back Office Bla Bla Bike');
    }

    public function configureCrud(): Crud
    {
        return parent::configureCrud()
            ->showEntityActionsInlined()
            ->setDateFormat('dd-MM-yyyy, HH:mm')
            ->setTimezone('Europe/Paris')
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa-solid fa-house');
        yield MenuItem::linkToCrud('Messages', 'fa-solid fa-envelope', Message::class);
        yield MenuItem::linkToCrud('Rides', 'fa-solid fa-route', Ride::class);
        yield MenuItem::linkToCrud('Membres', 'fa-solid fa-users', User::class);
        yield MenuItem::linkToCrud('Pratiques', 'fa-solid fa-person-biking', Practice::class);
        yield MenuItem::linkToCrud('Objectifs', 'fa-solid fa-bullseye', Mind::class);
        // yield MenuItem::linkToCrud('Départements', 'fa-solid fa-map', Department::class);
        // yield MenuItem::linkToCrud('Marques', 'fa-solid fa-copyright', Brand::class);
        // yield MenuItem::linkToCrud('Modèles', 'fa-solid fa-bicycle', Model::class);
        // yield MenuItem::linkToCrud('Villes', 'fa-solid fa-city', City::class); 
        // yield MenuItem::linkToCrud('Messages', 'fa-solid fa-envelope', ProfileImage::class);
    }
}
