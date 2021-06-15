<?php

namespace App\Controller\Admin;

use App\Entity\Entreprise;
use App\Entity\Menu;
use App\Entity\Offre;
use App\Entity\Page;
use App\Entity\Profile;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('HandiCV Dashboard');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Users');
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Profiles', 'fas fa-user', Profile::class);
        yield MenuItem::section('Entreprises');
        yield MenuItem::linkToCrud('Entreprises', 'fas fa-list', Entreprise::class);
        yield MenuItem::linkToCrud('Offres commerciales', 'fa fa-list', Offre::class);
        yield MenuItem::section('Content');
        yield MenuItem::linkToCrud('Pages', 'fa fa-list', Page::class);
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setName($user->getUsername());
    }
}
