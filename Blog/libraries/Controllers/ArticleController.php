<?php
namespace Controllers;
/**
 * Ne pas oublier d'utiliser 'use' : Permet d'appeler l'instance avec les infos dedans (on autorise l'accès aux infos du fichier)
 * Ainsi que 'require_once': Permet d'avoir accès aux infos de l'instance (on inclus le fichier)
 */
use Models\Article;
use Models\Comment;

class ArticleController extends Controller
{
   protected $modelName = Article::class;

    public function index () {
        /**
         * On crée un nouvel objet Article = On crée une instance de la classe Article
         */
        //$model = new Article();
        /**

        /**
         * CE FICHIER A POUR BUT D'AFFICHER LA PAGE D'ACCUEIL !
         *
         * On va donc se connecter à la base de données, récupérer les articles du plus récent au plus ancien (SELECT * FROM articles ORDER BY created_at DESC)
         * puis on va boucler dessus pour afficher chacun d'entre eux
         */

        /**
         * 1. Connexion à la base de données avec PDO
       */

        //$pdo = getPdo();

        /**
         * 2. Récupération des articles
         */ // On utilisera ici la méthode query (pas besoin de préparation car aucune variable n'entre en jeu)
        //$resultats = $pdo->query('SELECT * FROM articles ORDER BY created_at DESC');
        // On fouille le résultat pour en extraire les données réelles
        //$articles = $resultats->fetchAll();
        $articles = $this->model->findAll("created_at DESC");

        /**
         * 3. Affichage
         */

        $pageTitle = 'Accueil';
        \Renderer::render('articles/index', compact('pageTitle', 'articles'));
    }

    public function show() {


        $commentModel = new Comment();
        /**
         * CE FICHIER DOIT AFFICHER UN ARTICLE ET SES COMMENTAIRES !
         *
         * On doit d'abord récupérer le paramètre "id" qui sera présent en GET et vérifier son existence
         * Si on n'a pas de param "id", alors on affiche un message d'erreur !
         *
         * Sinon, on va se connecter à la base de données, récupérer les commentaires du plus ancien au plus récent (SELECT * FROM comments WHERE article_id = ?)
         *
         * On va ensuite afficher l'article puis ses commentaires
         */

        /**
         * 1. Récupération du param "id" et vérification de celui-ci
         */
        // On part du principe qu'on ne possède pas de param "id"
        $article_id = null;

        // Mais si il y'en a un et que c'est un nombre entier, alors c'est cool
        if (!empty($_GET['id']) && ctype_digit($_GET['id'])) {
            $article_id = $_GET['id'];
        }

        // On peut désormais décider : erreur ou pas ?!
        if (!$article_id) {
            die("Vous devez préciser un paramètre `id` dans l'URL !");
        }

        /**
         * 2. Connexion à la base de données avec PDO
         * Attention, on précise ici deux options :
         * - Le mode d'erreur : le mode exception permet à PDO de nous prévenir violament quand on fait une connerie ;-)
         * - Le mode d'exploitation : FETCH_ASSOC veut dire qu'on exploitera les données sous la forme de tableaux associatifs
         *
         * PS : Vous remarquez que ce sont les mêmes lignes que pour l'index.php ?!
         */ //$pdo = getPdo();


        /**
         * 3. Récupération de l'article en question
         * On va ici utiliser une requête préparée car elle inclue une variable qui provient de l'utilisateur : Ne faites
         * jamais confiance à ce connard d'utilisateur ! :D
         */
        $article = $this->model->find($article_id);
        //$query = $pdo->prepare("SELECT * FROM articles WHERE id = :article_id");

        // On exécute la requête en précisant le paramètre :article_id
        //$query->execute(['article_id' => $article_id]);

        // On fouille le résultat pour en extraire les données réelles de l'article
        //$article = $query->fetch();

        /**
         * 4. Récupération des commentaires de l'article en question
         * Pareil, toujours une requête préparée pour sécuriser la donnée filée par l'utilisateur (cet enfoiré en puissance !)
         */ //$query = $pdo->prepare("SELECT * FROM comments WHERE article_id = :article_id");
        //$query->execute(['article_id' => $article_id]);
        //$commentaires = $query->fetchAll();
        $commentaires = $commentModel->findAllWithArticle($article_id);
        /**
         * 5. On affiche
         */
        \Renderer::render('articles/show', compact('pageTitle', 'article', 'commentaires', 'article_id'));
        /**
         * compact est une fonction qui crée un tableau associatif avec le nom de variable egal au nom des champs dans compact
         * compact('pageTitle','article','commentaire','article_id')
         * ==
         * ['pageTitle'=>$pageTitle,
         * 'article'=>$article,
         * 'commentaire'=>$commentaires,
         * 'article_id'=>$article_id
         * ]
         */ //$pageTitle = $article['title'];
        //ob_start();
        //require('templates/articles/show.html.php');
        //$pageContent = ob_get_clean();

        require('templates/layout.html.php');

    }

    public function delete() {

        /**
         * DANS CE FICHIER, ON CHERCHE A SUPPRIMER L'ARTICLE DONT L'ID EST PASSE EN GET
         *
         * Il va donc falloir bien s'assurer qu'un paramètre "id" est bien passé en GET, puis que cet article existe bel et bien
         * Ensuite, on va pouvoir effectivement supprimer l'article et rediriger vers la page d'accueil
         */

        /**
         * 1. On vérifie que le GET possède bien un paramètre "id" (delete.php?id=202) et que c'est bien un nombre
         */
        if (empty($_GET['id']) || !ctype_digit($_GET['id'])) {
            die("Ho ?! Tu n'as pas précisé l'id de l'article !");
        }

        $id = $_GET['id'];

        /**
         * 2. Connexion à la base de données avec PDO
         * Attention, on précise ici deux options :
         * - Le mode d'erreur : le mode exception permet à PDO de nous prévenir violament quand on fait une connerie ;-)
         * - Le mode d'exploitation : FETCH_ASSOC veut dire qu'on exploitera les données sous la forme de tableaux associatifs
         *
         * PS : Vous remarquez que ce sont les mêmes lignes que pour l'index.php ?!
         */
//$pdo = getPdo();

        /**
         * 3. Vérification que l'article existe bel et bien
         */
//$query = $pdo->prepare('SELECT * FROM articles WHERE id = :id');
//$query->execute(['id' => $id]);
        $query= $this->model->find($id);
        if (!$query) {
            die("L'article $id n'existe pas, vous ne pouvez donc pas le supprimer !");
        }

        /**
         * 4. Réelle suppression de l'article
         */
        $this->model->delete($id);

        /**
         * 5. Redirection vers la page d'accueil
         */
//header("Location: index.php");
//exit();
        \Http::redirect("index.php");
    }

}