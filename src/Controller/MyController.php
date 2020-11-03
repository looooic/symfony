<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyController extends AbstractController
{
    /**
     *@Route ("/first_page", methods={"GET"}, name="first_page")
     */
    public function index()
    {
        return new Response('my first page');
    }

    /**
     * @Route ("/second_page", methods={"GET"},name="second_page")
     */
    public function second()
    {
        return $this->render('my/second.html.twig');
    }
}