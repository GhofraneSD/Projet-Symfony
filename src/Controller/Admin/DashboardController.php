<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\Doctor;
use App\Entity\Patient;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\BatchActionDto;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render("admin/dashboard.html.twig");
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle("Admin Dashboard");
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard("Dashboard", "fa fa-home");
        yield MenuItem::linkToCrud("Posts", "fas fa-newspaper", Post::class);
        yield MenuItem::linkToCrud("Comments", "fas fa-comment", Comment::class);
        yield MenuItem::linkToCrud("Users", "fas fa-user", User::class);
        yield MenuItem::linkToCrud("Doctors", "fas fa-user", Doctor::class);
    }

    public function configureActions(): Actions
    {


        $actions = parent::configureActions();
        $viewInvoice = Action::new('Approve', 'Approve')
            ->displayIf(static function ($entity) {
                return $entity instanceof Doctor && !in_array('ROLE_DOCTOR', $entity->getRoles());
            })
            ->linkToCrudAction('approveDoctor');

        return $actions
            ->add(Crud::PAGE_INDEX, $viewInvoice);
    }
}
