<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\User;
use App\Form\UserType;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/registration", name="registration")
     */
    public function index(Request $request)
    {
        $userP = new User();
        
        $form = $this->createForm(UserType::class, $userP);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Retrieve the value from the extra field non-mapped !
            $is_doctor = $form->get("is_doctor")->getData();

            $user = null;
            if ($is_doctor === true) {
                $user = new Doctor();
                $user->setEmail($userP->getEmail());
                $user->setFirstName($userP->getFirstName());
                $user->setLastName($userP->getLastName());
                $user->setVerified(false);
            } else {
                $user = $userP;
            }

            // Encode the new users password
            $user->setPassword($this->passwordEncoder->encodePassword($user, $userP->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_USER']);
            $user->setJoinedAt(new DateTimeImmutable());
        
            // Save
            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
