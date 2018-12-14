<?php


namespace App\Controller;

use App\Model\Comment;

class CommentController extends Controller{

    /**
     * Ajoute un nouveau commentaire, il gére l'ajout de commentaire parent et enfant
     *
     * @param $id
     */
    public function add($id){

        // Récupération des variables du formulaire
        $name       = (!empty($_POST['name'])) ? trim($_POST['name']) : null;
        $content    = (!empty($_POST['content'])) ? strip_tags($_POST['content']) : null;
        $parent_id  = (!empty($_POST['parent'])) ? (int) $_POST['parent'] : null;
        $is_reply   = (!is_null($parent_id)) ? true : false;
        $isError    = false;
        $response   = [];

        // Récupération de cet article
        $article    = $this->articleManager->getById($id);

        // Uniquement si l'article est valide
        if(!is_null($article) AND $article->getIsPublished() AND !$article->getIsDelete()){

            // Dans le cas d'une réponse à un commentaire
            if($is_reply){

                // On récupére le commentaire commentaire en cours qui deviendra le parent du nouveau commentaire
                $parent = $this->commentManager->getByID($parent_id);

                // Uniquement si le commentaire est valide
                if(!is_null($parent) AND !$parent->getIsDelete()){
                    $comment = new Comment();
                    $comment->setArticle($article);
                    $comment->setParent($parent);
                    $comment->setLevel($parent->getLevel() + 1 );
                    $comment->setReport(false);
                    $comment->setIsDelete(false);
                    $comment->setUsername($name);
                    $comment->setContent($content);
                }else{
                    $isError = true;
                }
            }else{
                // On hydrate nottre nouveau commentaire
                $comment = new Comment();
                $comment->setArticle($article);
                $comment->setParent(new Comment());
                $comment->setLevel(0);
                $comment->setReport(false);
                $comment->setIsDelete(false);
                $comment->setUsername($name);
                $comment->setContent($content);
            }

        }else{
            $isError = true;
        }

        // On vérifie que toutes les conditions sont validées
        // nous ne devons pas accepter le post de commentaire ou de sous commentaires
        // au parents qui sont dans la corbeille ou à un article qui n'est pas publié ou placé dans la corbeille
        if(!$isError){

            // On vérifie la validité du commentaire avant de l'enregistrer dans la BDD
            $validation = $comment->checkIsValidForCreate();

            if(!$validation){
                $this->commentManager->save($comment);
                $response['isValid']  = true;
            }else{
                $response['errors']   = $validation;
                $response['isFalse']  = true;
            }
        }

        echo json_encode($response);
    }

    /**
     * Signalement d'un commentaire
     *
     * @param $id
     */
    public function report($id){

        $comment = $this->commentManager->getByID($id);
        $response   = [];

        // Uniquement si le commentaire et l'article sont valides
        if(!is_null($comment) AND !$comment->getIsDelete() AND !$comment->getArticle()->getIsDelete() AND $comment->getArticle()->getIsPublished()){

            // On signal ce commentaire
            $comment->setReport(true);
            $this->commentManager->update($comment);
            $response['isFalse']  = false;

        }else{
            $response['isFalse']  = true;
        }

        echo json_encode($response);
    }

    /**
     * Mise à jour d'un commentaire
     *
     * @param $id
     * @param $action
     */
    public function update($id, $action){

        // On vérifie que l'utilisateur à les permissions pour la creation d'un article
        if(!$this->havePermission()){
            exit();
        }

        $comment    = $this->commentManager->getByID($id);

        if(!is_null($comment)){
            switch($action){
                case 'trash' :
                    $comment->setIsDelete(true);
                    break;
                case 'untrash' :
                    $comment->setIsDelete(false);
                    break;
                case 'unreported' :
                    $comment->setReport(false);
                    break;
                default :
                    exit;
                    break;
            }
            // Apres avoir changer le status on enregistre la modification en BDD
            $this->commentManager->update($comment);
        }

        header('Location:  '.$_SERVER['HTTP_REFERER'].'');
    }

    /**
     * Supression d'un commentaire en BDD
     *
     * @param $id
     */
    public function delete($id){

        // On vérifie que l'utilisateur à les permissions pour la creation d'un article
        if(!$this->havePermission()){
            exit();
        }

        $comment    = $this->commentManager->getByID($id);

        if(!is_null($comment)){
            // On supprime le commentaire de la BDD
            $this->commentManager->delete($comment);

            // On récupére aussi la liste de ses enfants pour les supprimés
            $childs = $this->commentManager->getChildren($comment, $comment->getArticle());

            if(!is_null($childs)){
                foreach($childs as $child){
                    $this->commentManager->delete($child);
                }
            }
        }
        header('Location:  '.$_SERVER['HTTP_REFERER'].'');

    }

    /**
     * Supression de tous les commentaires présent dans la corbeille
     */
    public function purgeTrash(){
        // On vérifie que l'utilisateur à les permissions pour la creation d'un article
        if(!$this->havePermission()){
            exit();
        }

        $comments = $this->commentManager->getAll('TRASH');

        foreach($comments as $comment){
            if(!is_null($comment)){
                // On supprime le commentaire de la BDD
                $this->commentManager->delete($comment);

                // On récupére aussi la liste de ses enfants pour les supprimés
                $childs = $this->commentManager->getChildren($comment, $comment->getArticle());

                if(!is_null($childs)){
                    foreach($childs as $child){
                        $this->commentManager->delete($child);
                    }
                }
            }
        }
        header('Location:  '.$_SERVER['HTTP_REFERER'].'');
    }
}