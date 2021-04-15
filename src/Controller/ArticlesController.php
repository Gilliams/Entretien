<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/article/{slug}", name="article")
     */
    public function show($slug): Response
    {
        return $this->render('articles/show.html.twig', [
            'controller_name' => 'ArticlesController',
        ]);
    }

    /**
     * @Route("/articles/creation-article", name="article_create")
     */
    public function create(): Response
    {
        return $this->render('articles/create.html.twig', [
            'controller_name' => 'ArticlesController',
        ]);
    }
}
