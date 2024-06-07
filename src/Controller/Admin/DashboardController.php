<?php

namespace App\Controller\Admin;

use App\Entity\Usuario;
use App\Entity\Post;
use App\Entity\Producto;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $userEmail = $this->getUser()->getEmail();

        if ('admin@admin.com' !== $userEmail) {
            return $this->render('error/admin_access_denied.html.twig');
        }

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UsuarioCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SportHub');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home', 'homepage');
        yield MenuItem::linkToCrud('Usuario', 'fas fa-user', Usuario::class);
        yield MenuItem::linkToCrud('Post', 'fas fa-mail', Post::class);
        yield MenuItem::linkToCrud('Producto', 'fas fa-store', Producto::class);
    }
}