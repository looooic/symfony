<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
     * @Route ("/admin",name="admin_")
     * * @IsGranted("ROLE_ADMIN")
     */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="index",methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route ("/users", name="users",methods={"GET"})
     */

    public function users(UserRepository $userRepository)
    {
        return $this->render('admin/users.html.twig',[
            'users'=> $userRepository->findAll(),
        ]);
    }

    /**
     * @Route ("/articles",name="liste_articles",methods={"GET"})
     */

    public function articles(ArticleRepository $articleRepository)
    {
        return $this->render('admin/liste_articles.html.twig',[
            'articles'=> $articleRepository->findAll(),
        ]);
    }


    /**
     * @Route ("/role/{id}",name="change_role",methods={"GET", "PUT"})
     */
    public function changeRole(User $user, Request $request,
     EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        $form=$this->createFormBuilder($user,[
           'method'=>'put',
        ])
            ->add('roles', ChoiceType::class,[
                'multiple' => true,
                'choices'=>[
                    'Utilisateur'=>'ROLE_USER',
                    'Manager'=>'ROLE_MANAGER',
                    'Administateur'=> 'ROLE_ADMIN',

                ]
            ])
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $entityManager->flush();
            $this->addFlash('success',$translator->trans('admin.change_role.success'));

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/change_role.html.twig', [
            'user'=> $user,
            'form'=>$form->createView(),
        ]);
    }
}
