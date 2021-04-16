<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
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
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $articles = $this->manager->getRepository(Article::class)->findAll();

        return $this->render('articles/index.html.twig', [
            'articles' => $articles
        ]);
    }
    /**
     * @Route("/article/{slug}", name="article")
     */
    public function show($slug, Request $request): Response
    {

        $article = $this->manager->getRepository(Article::class)->findBySlug($slug);

        $comment = new Comment();
        $articleComment = new Article();
        
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment = $form->getData();
            $comment->setAuthorComment($this->getUser());
            $comment->setCommentArticle($article);

            $this->manager->persist($comment);
            $this->manager->flush();
        }

        return $this->render('articles/show.html.twig', [
            'item' => $article,
            'form' => $form->createView()
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
