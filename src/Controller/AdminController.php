<?php

namespace App\Controller;

use App\Entity\Admins;
use App\Form\AdminsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * 
 * @IsGranted("ROLE_USER")
 */

class AdminController extends AbstractController
{
    /**
     * @Route("/create/admin", name="create-admin")
     * 
     * @IsGranted("ROLE_ADMIN")
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

            return $this->redirectToRoute('accueil');
        }

        return $this->render('admin/create-admin.html.twig', [
            'admin' => $admin,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/edit/{id}", name="edit-admin", methods={"GET","POST"})
     */
    public function edit(Request $request, UserPasswordEncoderInterface $encoder, Admins $admin): Response {
        $oldPassword = $admin->getPassword();

        $form = $this->createForm(AdminsType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($admin->getPassword())) {
                $encodedPassword = $encoder->encodePassword($admin, $admin->getPassword());
                $admin->setPassword($encodedPassword);
            } else {
                $admin->setPassword($oldPassword);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($admin);
            $entityManager->flush();

            return $this->redirectToRoute('gerer-admin');
        }

        return $this->render('admin/update-admin.html.twig', [
            'admin' => $admin,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/gerer/admin", name="gerer-admin")
     * 
     * @IsGranted("ROLE_ADMIN") 
     * 
     */
    public function gererAdmin(): Response {
        $repository = $this->getDoctrine()->getRepository(Admins::class);
        $admins = $repository->findAll();

        return $this->render('admin/gerer-admins.html.twig', [
            'admins' => $admins
        ]);
    }

     /**
     * @Route("/{id}", name="delete-admin", methods={"DELETE"})
     */
    public function delete(Request $request, Admins $admin): Response {
        if ($this->isCsrfTokenValid('delete' . $admin->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($admin);
            $entityManager->flush();
        }

        return $this->redirectToRoute('utilisateur_index');
    }

}
      