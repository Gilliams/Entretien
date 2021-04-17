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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Gestion des articles et des commentaires
 */
class ArticlesController extends AbstractController
{

    private $manager;

    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }

    /**
     * Affichage des articles dans la HomePage récuperer depuis le repository avec findAll
     * 
     * @return Resepons
     * 
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
     * Affichage spécifique d'un article via son slug, avec ajout d'un formulaire d'ajout de commentaire
     * 
     * 
     * @param Article $article instance de Article
     * @param String $slug Titre de l'article
     * @param Request $request Requête du formulaire de commentaire
     * @return Resepons
     * 
     * @Route("/article/{slug}", name="article")
     */
    public function show(Article $article, $slug, Request $request): Response
    {

        $findArticle = $this->manager->getRepository(Article::class)->findBySlug($slug);

        $comment = new Comment();

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
            'item' => $findArticle,
            'form' => $form->createView()
        ]);
    }

    /**
     * Création d'un article dans la page creation-article
     * 
     * @param Request $request Récupere le formulaire de la création d'article
     * @return Response
     * @Security("is_granted('ROLE_USER')", message="Vous devez être connecter pour pouvoir créer un article")
     * 
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

            $this->addFlash('success', 'Votre article vient d\'être créer');

            $this->manager->persist($article);
            $this->manager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('articles/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Suppresssion d'un commentaire spécifique dans un article
     *
     * @param Comment $comment Permet de séléctionner le commentaire à supprimer
     * @param Request $request Récupére les infos pour séléctionner le commentaire
     * @param EntityManagerInterface $manager Permet d'effectuer et sauvegarder les changements
     * @return Response
     * 
     * @Route("/article/commentaire/suppression/{id<\d+>}", name="comment_delete")
     */
    public function deleteCommentaire(Comment $comment, Request $request, EntityManagerInterface $manager): Response
    {
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash('warning', 'Votre commentaire a bien été supprimer');
    }

    /**
     * Suppression d'un article spécifique
     *
     * @param Article $article Permet de séléctionner l'article à supprimer
     * @param Request $request Récupére les infos pour séléctionner l'article
     * @param EntityManagerInterface $manager 
     * @return Response
     * 
     * @Route("/article/suppression/{slug}", name="article_delete", methods={"DELETE"})
     */
    public function delete(Article $article, Request $request, EntityManagerInterface $manager): Response
    {
        if( $this->isCsrfTokenValid('article_delete_' . $article->getId(), $request->request->get('token') )){
            $manager->remove($article);
            $manager->flush();
        }

        $this->addFlash('warning', 'Votre article a bien été supprimer');
        return $this->redirectToRoute('home');
    }

}
