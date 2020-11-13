<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleLike;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleLikeRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index() // pour remplacer $repo on peut mettre en param: ArticleRepository $repo et en use : App\Repository\ArticleRepository;
    {
        // Pour faire une SELECTION et non une manipulation -> Repository
        //Pour faire des manipulation ->Manager
        $repo = $this->getDoctrine()->getRepository(Article::class);
        /**
         * Exemple $article=$repo->find(12);
         * $articles = $repo->findBy(['title'=>'titre de ']);
         *              -> SELECT t0.id AS id_1,
         *                        t0.title AS title_2,
         *                        t0.content AS content_3,
         *                        t0.image AS image_4,
         *                        t0.created_at AS created_at_5
         *                 FROM article t0
         *                 WHERE t0.title = 'titre de '
         */
        $articles = $repo->findAll();
        return $this->render('blog/index.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("/", name="home")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function home() {
        return $this->render('blog/home.html.twig', ['title' => "Bienvenue sur ce test", 'age' => 21]);
    }

    /**
     * @Route("/blog/new", name="blog_create")
     * $article est null quand on en crée un (étape avant la soumission)
     * @Route("/blog/{id}/edit", name="blog_edit")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function form(Article $article = null, Request $request, ObjectManager $manager) {

        if (!$article) {
            $article = new Article();
        }

        //$form = $this->createFormBuilder($article)
        //              ->add('title', TextType::class, [
        //                  'attr' => [
        //                      'placeholder' => "Titre de l'article"
        //                  ]
        //              ])
        //              ->add('content', TextareaType::class, [
        //                  'attr' => [
        //                      'placeholder' => "Contenu de l'article"
        //                  ]
        //              ])
        //              ->add('image',TextType::class, [
        //                  'attr' => [
        //                      'placeholder' => "Image de l'article"
        //                  ]
        //              ])
        //             ->getForm();
        ///!\ a bien lié la variable qui doit contenir les infos sql (l'entité) ici $article avec l'entité Article
        $form = $this->createForm(ArticleType::class, $article);
        /**
         * Analyse de la requète d'envoie du formulaire
         * Utilisation de la function handleRequest
         * Pour la modification
         */
        $form->handleRequest($request);
        /**
         * Soumission du formulaire
         * Si l'urilisateur à appuyer sur Enregistrer
         * On ajoute une date de création
         */
        if ($form->isSubmitted() && $form->isValid()) {
            //pour la soumission de l'édition = si il a pas d'id déja renseigné
            //dump($article);
            if (!$article->getId()) {
                $article->setCreatedAt(new \DateTime());
            }

            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }

        return $this->render('blog/create.html.twig', ['formArticle' => $form->createView(), 'editMode' => $article->getId() !== null // param pour changer le bouton. Si il a déja un Id ou non par un booleen
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show($id, Request $request, ObjectManager $manager) {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $article = $repo->find($id);

        if ($form->isSubmitted() && $form->isValid()) {
            //On associe le commentaire a l'article
            $comment->setCreatedAt(new \DateTime())->setArticle($article);
            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }
        return $this->render('blog/show.html.twig', ['article' => $article, 'commentForm' => $form->createView()]);
    }
    /**
     * Peut être écrit avec ConvertParam (il comprend que l'on veut une identification par l'id)
     * @Route("/blog/{id}", name="blog_show")
     *
     *
     * public function show(Article $article) {
     *   return $this->render('blog/show.html.twig',[
     *   'article' => $article
     *   ]);
     *   }
     *
     */

    /**
     * Permet de like ou d'unliker un article
     * Sous format JSON
     * @Route("blog/{id}/like", name="article_like")
     *
     * @param Article               $article
     * @param ObjectManager         $manager
     * @param ArticleLikeRepository $likeRepository
     *
     * @return Response
     */
    public function like(Article $article, ObjectManager $manager, ArticleLikeRepository $likeRepository): Response {
        //On recupère l'utilisateur
        $user = $this->getUser();
        //Si pas connecté
        if (!$user) {
            return $this->json(['code' => 403, 'message' => 'Unauthorized'], 403);
        }
        //Cet article est-il liké = Si oui pouvoir supprimer ce like
        // Ou cet article n'est pas liké = Si oui pouvoir liker cet article
        if ($article->isLikedByUser($user)) {
            //Si j'aime cet article alors je veux pouvoir supprimer mon'j'aime'
            //Pour retrouver le like il faut appeler le Repository (en fonction de l'article et de l'utilisateur)
            $like = $likeRepository->findOneBy(['article' => $article, 'user' => $user]);
            $manager->remove($like);
            $manager->flush();
            return $this->json(['code' => 200, 'message' => 'success pour la suppression du like',
                                'likes' => $likeRepository->count(['article'=>$article])
                                ],200);
        }
        //Création d'un nouveau like
        $like = new ArticleLike();
        $like->setArticle($article)
            ->setUser($user)
            ->setCreatedAt(new \DateTime());
        $manager->persist($like);
        $manager->flush();
        return $this->json(['code' => 200,
                            'message' => 'success le like est bien ajouté',
                            'likes' => $likeRepository->count(['article' => $article])
                           ],200);
    }
}
