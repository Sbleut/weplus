<?php

namespace App\Controller;

use App\Entity\Admins;
use App\Form\AdminsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/create/admin", name="create_admin")
     * 
     */
    public function createAdmin(Request $r, UserPasswordEncoderInterface $encoder): Response
    {
        $admin = new Admins();

        $form = $this->createForm(AdminsType::class, $admin);
        $form->handleRequest($r);

        if ($form->isSubmitted() && $form->isValid()) {
            $encodedPassword = $encoder->encodePassword($admin, $admin->getPassword());
            $admin->setPassword($encodedPassword);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($admin);
            $entityManager->flush();

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('accueil.html.twig', [
            'admin' => $admin,
            'form' => $form->createView(),
        ]);
    }
}
      