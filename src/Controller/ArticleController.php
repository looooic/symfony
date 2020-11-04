<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @Route ("/list/last",name="article_list_last",methods={"GET"})
     */

    public function listLast(ArticleRepository $articleRepository)
    {
        return $this->render('article/index.html.twig',[
            'articles'=>$articleRepository->findLast(),
    ]);
    }

    /**
     * @Route("/list/published",name="article_list_published", methods={"GET"})
     */

    public function listPublished(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig',[
            'articles'=>$articleRepository->findPublished(true),
        ]);
    }

    /**
     * @Route ("/list/unpublished",name="article_list_unpublished",methods={"GET"})
     */

    public function listUnpublished(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig',[
            'articles'=>$articleRepository->findPublished(false),
        ]);
    }

    /**
     * @Route("/new", name="article_new", methods={"GET","POST"})
     */
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator
    ): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success',$translator->trans('article.created.success'));
            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request,
                         Article $article,
                         EntityManagerInterface $entityManager,
                         TranslatorInterface $translator): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUpdatedAt(new \DateTime());
            $entityManager->flush();
            $this->addFlash('success',$translator->trans('article.modify.success'));
            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article,
                           EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(),
            $request->request->get('_token'))) {

            $entityManager->remove($article);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('article.delete.success'));

        }
        return $this->redirectToRoute('article_index');
    }
}
