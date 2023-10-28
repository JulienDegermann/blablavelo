<?php

namespace App\Controller\Admin;


use App\Entity\Brand;
use App\Entity\Department;
use App\Entity\City;
use App\Entity\Mind;
use App\Entity\Practice;
use App\Entity\User;
use App\Entity\Model;
use App\Entity\Ride;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
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
            ->setTitle('Ty Ride');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Marques', 'fas fa-list', Brand::class);
        yield MenuItem::linkToCrud('Départements', 'fas fa-list', Department::class);
        yield MenuItem::linkToCrud('Villes', 'fas fa-list', City::class);
        yield MenuItem::linkToCrud('Objectifs', 'fas fa-list', Mind::class);
        yield MenuItem::linkToCrud('Pratiques', 'fas fa-list', Practice::class);
        yield MenuItem::linkToCrud('Modèles', 'fas fa-list', Model::class);
        yield MenuItem::linkToCrud('Rides', 'fas fa-list', Ride::class);
        yield MenuItem::linkToCrud('Membres', 'fas fa-list', User::class);
    }
}
