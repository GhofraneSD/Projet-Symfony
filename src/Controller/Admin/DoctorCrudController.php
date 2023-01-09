<?php

namespace App\Controller\Admin;

use App\Entity\Doctor;
use App\Repository\DoctorRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\BatchActionDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DoctorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Doctor::class;
    }



    public function configureFields(string $pageName): iterable
    {
        return [
            'email',
            'firstName',
            'lastName',
            'verified',
            ArrayField::new('roles'),
        ];
    }


    public function approveDoctor(Request $request): Response
    {

        $doctrine = $this->getDoctrine();
        $id = $request->query->get('entityId');
        $user = $doctrine->getRepository(Doctor::class)->find($id);

        $user->setRoles(['ROLE_USER', 'ROLE_DOCTOR']);


        $doctrine->getManager()->persist($user);
        $this->addFlash('success', 'Doctor approved');
        $doctrine->getManager()->flush();
        return $this->redirect($request->query->get('referrer'));
    }

}
