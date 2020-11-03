<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route ("/contact")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/", name="contact", methods={"GET"})
     */
    public function index(ContactRepository $contactRepository): Response
    {
        $contacts = $contactRepository->findAll();
        return $this->render('contact/index.html.twig',
        [
            'contacts'=>$contacts,
        ]);
    }

    /**
     * @Route ("/add", name="add_contact", methods={"GET","POST"})
     */
    public function add(Request $request, EntityManagerInterface $entityManager)
    {
        $contact= new Contact();

        $form=$this->createFormBuilder($contact,
        ['label_format'=>'contact.%name%.label'])
            ->add('firstname', TextType::class)
            ->add('lastname',TextType::class)
            ->add('birthday',DateType::class,[
                'widget'=>'single_text',

            ])
            ->add('email',EmailType::class)
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($contact);
            $entityManager->flush();

            return $this->redirectToRoute('contact');
        };

        return $this->render('contact/add.html.twig',[
            'form_contact'=>$form->createView(),
        ]);
    }

    /**
     * @Route ("/edit/{id}",name="edit_contact", methods={"GET","PUT"})
     */
    public function edit(Contact $contact,
    Request $request,
    EntityManagerInterface $entityManager,
    TranslatorInterface $translator)
    {
        $form=$this->createForm(ContactType::class,$contact,[
           'method' => 'put'
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $entityManager->persist($contact);
            $entityManager->flush();

            $this->addFlash('success', $translator->trans('contact.modify.success'));

            return $this->redirectToRoute('contact');
        }
        return $this->render('contact/edit.html.twig',
        ['form_edit'=>$form->createView(),]
        );
    }
}
