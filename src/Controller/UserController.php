<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
     * @Route("/user")
     */
    class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/profile/{id}",name="user_profile",methods={"GET"})
     */
    public function profile(User $user)
    {
        return $this->render('user/profile.html.twig',[
            'user'=>$user,
        ]);
    }

        /**
         * @Route("/edit/{id}", name="user_edit",methods={"GET","PUT"})
         */
    public function edit(User $user, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(UserType::class, $user,[
           'method'=>'put'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('user.modify.success'));

            return $this->redirectToRoute('user_profile',[
                'id'=>$user->getId(),
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'form_edit'=>$form->createView(),
    ]);
    }
}
