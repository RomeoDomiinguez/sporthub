<?php

namespace App\Controller\Admin;

use App\Entity\Usuario;
use App\Entity\Post;
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
        // Obtiene el correo electrónico del usuario actual
        $userEmail = $this->getUser()->getEmail();

        // Verifica si el usuario es el administrador
        if ('admin@admin.com' !== $userEmail) {
            // Redirigir a la página de acceso denegado personalizada
            return $this->render('error/admin_access_denied.html.twig');
        }

        // Si es el administrador, redirige a la página de administración de usuarios
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
    }
}