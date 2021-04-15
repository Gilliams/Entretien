<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticlesController extends AbstractController
{

    private $manager;

    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }

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
    public function create(Request $request): Response
    {
        $article = new Article();
        $slugify = new Slugify();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $article = $form->getData();

            $slug = $slugify->slugify($article->getTitle());

            $article->setAuthor($this->getUser());
            $article->setSlug($slug);

            $this->manager->persist($article);
            $this->manager->flush();
        }

        return $this->render('articles/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
