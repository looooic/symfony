<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/default", name="default", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/contact_us",name="contact_us",methods={"GET","POST"})
     */

    public function contact(Request $request)
    {
        $form=$this->createFormBuilder([])
            ->add('pseudo', TextType::class)
            ->add('email', EmailType::class)
            ->add('subject', TextType::class)
            ->add('message',TextareaType::class)
            ->getForm();

        return $this->render('default/contact_us.html.twig',[
            'form_contact_us'=> $form->createView(),
        ]);
    }
}
