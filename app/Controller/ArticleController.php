<?php


namespace App\Controller;

use App\Core\Pagination;
use App\Model\Article;

class ArticleController extends Controller{

    /**
     * Gestion de l'affichage de la page principale
     */
    public function index(){
        // 1. Récupération de la page courante
        // 2. Calcul de l'offset pour l'affichage des résulats
        // 3. Creation de la pagination
        $page               = !empty($_GET[PAG_KEY]) ? ((int) $_GET[PAG_KEY]) : 1;
        $offset             = ($page - 1) * PAG_ITEM_PER_PAGE;
        $this->pagination   = new Pagination($page, $this->articleManager->getAllAmount('PUBLISHED'));

        echo $this->twig->render('index.php', array(
            'articles'      => $this->articleManager->getBetween($offset,PAG_ITEM_PER_PAGE, 'PUBLISHED'),
            'pagination'    => $this->pagination
        ));
    }

    /**
     * Gestion de l'affichage d'un article
     *
     * @param $id
     * @param $slug
     */
    public function view($id, $slug){
        $article    = $this->articleManager->getByIdWithComments($id);

        // On retroune une page 404 si l'article n'existe pas
        // ou si l'utilisateur n'a pas les droits pour voir cet article
        if (is_null($article) OR ( ($article->getIsDelete() OR !$article->getIsPublished()) AND !$this->havePermission() )) {
            header('Location: '.BASE_URL.'/404');
        }

        echo $this->twig->render('article.php', array(
            'article'       => $article,
        ));
    }

    /**
     * Gestion de l'ajout d'un article
     */
    public function add(){
        // On vérifie que l'utilisateur à les permissions pour la creation d'un article
        if(!$this->havePermission()){
            exit();
        }

        // Récupération des variables du formulaire
        $title      = (!empty($_POST['title'])) ? trim(strip_tags($_POST['title'])) : null;
        $content    = (!empty($_POST['content'])) ? $_POST['content'] : null;
        $publish    = (!isset($_POST['publish'])) ? false : true;

        // Construction de l'article
        $article = new Article();
        $article->setTitle($title);
        $article->setContent($content);
        $article->setIsDelete(false);
        $article->setIsPublished($publish);

        // On vérifie la validité de l'article avant de l'enregistrer dans la BDD
        $validation = $article->checkIsValidBeforeSave();

        if(!$validation){
            $this->articleManager->save($article);
            $this->session->setFlash('message', 'Votre article a bien été enregistré');
            header('Location: '.BASE_URL.'/admin');
        }else{
            $this->session->setFlash('errors', $validation);
            header('Location: '.BASE_URL.'/admin/article/add');
        }
    }

    /**
     * Gestion de l'édition d'un article
     *
     * @param $id
     */
    public function edit($id){

        // On vérifie que l'utilisateur à les permissions pour la creation d'un article
        if(!$this->havePermission()){
            exit();
        }

        // Récupération des variables du formulaire
        $title      = (!empty($_POST['title'])) ? trim(strip_tags($_POST['title'])) : null;
        $content    = (!empty($_POST['content'])) ? $_POST['content'] : null;
        $publish    = (!isset($_POST['publish'])) ? false : true;

        $article    = $this->articleManager->getByID($id);

        if(!is_null($article)){
            $article->setTitle($title);
            $article->setContent($content);
            $article->setIsPublished($publish);

            // On vérifie la validité de l'article avant de l'enregistrer dans la BDD
            $validation = $article->checkIsValidBeforeSave();

            if(!$validation){
                $this->articleManager->update($article);

                $this->session->setFlash('message', 'Votre article a bien été modifié');
                header('Location: '.BASE_URL.'/admin');
            }else{
                $this->session->setFlash('errors', $validation);
                header('Location: '.BASE_URL.'/admin/article/edit/'.$article->getId().'');
            }
        }
    }

    /**
     * Mise à jour d'un article
     *
     * @param $id
     * @param $action
     */
    public function update($id, $action){

        // On vérifie que l'utilisateur à les permissions pour la creation d'un article
        if(!$this->havePermission()){
            exit();
        }

        $article    = $this->articleManager->getByID($id);

        if(!is_null($article)){
            switch($action){
                case 'published' :
                    $article->setIsPublished(true);
                    break;
                case 'unpublished' :
                    $article->setIsPublished(false);
                    break;
                case 'trash' :
                    $article->setIsDelete(true);
                    break;
                case 'untrash' :
                    $article->setIsDelete(false);
                    break;
                default :
                    exit;
                    break;
            }
            // Apres avoir changer le status on enregistre la modification en BDD
            $this->articleManager->update($article);
        }

        header('Location:  '.$_SERVER['HTTP_REFERER'].'');
    }

    /**
     * Supression d'un article en BDD
     *
     * @param $id
     */
    public function delete($id){

        // On vérifie que l'utilisateur à les permissions pour la creation d'un article
        if(!$this->havePermission()){
            exit();
        }

        $article    = $this->articleManager->getByIDWithComments($id);

        if(!is_null($article)){
            // On supprime l'article de la BDD
            $this->articleManager->delete($article);

            // On supprime les commentaires de cet article
            $comments   = $article->getComments();
            if(!is_null($comments)){
                foreach($comments as $comment){
                    $this->commentManager->delete($comment);
                }
            }

        }
        header('Location:  '.$_SERVER['HTTP_REFERER'].'');
    }

    /**
     * Supression de tous les articles présent dans la corbeille
     */
    public function purgeTrash(){
        // On vérifie que l'utilisateur à les permissions pour la creation d'un article
        if(!$this->havePermission()){
            exit();
        }

        $articles = $this->articleManager->getAll('TRASH');

        foreach($articles as $article){
            // On supprime l'article de la BDD
            $this->articleManager->delete($article);

            // On supprime les commentaires de cet article
            $comments   = $this->commentManager->getCommentsByArticle($article);

            if(!is_null($comments)){
                foreach($comments as $comment){
                    $this->commentManager->delete($comment);
                }
            }
        }
        header('Location:  '.$_SERVER['HTTP_REFERER'].'');
    }

}