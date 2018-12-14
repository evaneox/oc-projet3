<?php


namespace App\Model;

use \PDO;

class CommentManager extends Model {

    private $table      = 'comments';

    /**
     * Constructeur
     *
     * @param $table
     */
    public function __construc($table){
        $this->table = $table;
    }

    /**
     * Determine les critéres de recherche en fonction du status
     *
     * @param $status
     * @return array|null
     */
    private function getStatus($status){
        switch($status){
            case 'TRASH' :
                return array(
                    'report'        => null,
                    'is_delete'     => true
                );
                break;
            case 'UNTRASH' :
                return array(
                    'report'        => null,
                    'is_delete'     => false
                );
                break;
            case 'REPORTED' :
                return array(
                    'report'        => true,
                    'is_delete'     => false
                );
                break;
            default :
                return null;
                break;
        }

    }

    /**
     * Récupération du nombre de commentaires associés à un article
     *
     * @param $id
     * @return mixed
     */
    public function getAmountForArticleID($id){
        $query = $this->db->prepare("SELECT COUNT(id) AS amount_of_comments FROM $this->table WHERE article_id = :id");
        $query->bindParam(':id', $id, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ)->amount_of_comments;
    }

    /**
     * Récupére le nombre total de commentaire en fonction d'un critére
     *
     * @param null $status
     * @return mixed
     */
    public function getAllAmount($status = null){

        $params = $this->getStatus($status);

        if(!is_null($params)){

            if(!is_null($params['report'])){
                $query = $this->db->prepare("SELECT COUNT(id) AS amount_of_comments FROM $this->table
                                              WHERE ( report = :report AND is_delete = :is_delete)");
                $query->bindParam(':report', $params['report'], \PDO::PARAM_INT);
                $query->bindParam(':is_delete', $params['is_delete'], \PDO::PARAM_INT);
                $query->execute();
            }else{
                $query = $this->db->prepare("SELECT COUNT(id) AS amount_of_comments FROM $this->table
                                              WHERE is_delete = :is_delete");
                $query->bindParam(':is_delete', $params['is_delete'], \PDO::PARAM_INT);
                $query->execute();
            }
        }else{
            $query = $this->db->query("SELECT COUNT(id) AS amount_of_comments FROM $this->table");
        }
        $response   = $query->fetch(PDO::FETCH_OBJ);
        return $response->amount_of_comments;
    }

    /**
     * Récupération d'un commentaire avec son ID
     * retourne NULL si l'article n'a pas pu être trouvé
     *
     * @param $id
     * @return Comment|null
     */
    public function getByID($id){
        if(empty($id)){
            return null;
        }
        $query = $this->db->prepare("SELECT * FROM $this->table WHERE id = :id");
        $query->bindParam(':id', $id, \PDO::PARAM_INT);
        $query->execute();
        $comment = $query->fetch(PDO::FETCH_OBJ);

        if($comment) {

            $articleManager = new ArticleManager();
            $article        = $articleManager->getByID($comment->article_id);

            return new Comment(
                $comment->id,
                $article,
                $this->getByID($comment->parent_id),
                $comment->level,
                $comment->report,
                $comment->is_delete,
                $comment->username,
                $comment->content,
                $comment->created_at
            );
        } else {
            return null;
        }
    }

    /**
     * Récupére tous les commentaires
     *
     * @param null $status
     * @return array
     */
    public function getAll($status = null){

        $params = $this->getStatus($status);

        if(!is_null($params)){

            if(!is_null($params['report'])){
                $query = $this->db->prepare("SELECT * FROM $this->table WHERE ( report = :report AND is_delete = :is_delete) ORDER BY report DESC, created_at DESC");
                $query->bindParam(':report', $params['report'], \PDO::PARAM_INT);
                $query->bindParam(':is_delete', $params['is_delete'], \PDO::PARAM_INT);
                $query->execute();
            }else{
                $query = $this->db->prepare("SELECT * FROM $this->table WHERE is_delete = :is_delete  ORDER BY report DESC, created_at DESC");
                $query->bindParam(':is_delete', $params['is_delete'], \PDO::PARAM_INT);
                $query->execute();
            }
        }else{
            $query = $this->db->query("SELECT * FROM $this->table ORDER BY report DESC, created_at DESC");
        }

        $response       = $query->fetchAll(PDO::FETCH_OBJ);
        $articleManager = new ArticleManager();
        $comments       = [];

        foreach($response as $comment){

            $article        = $articleManager->getByID($comment->article_id);

            $comments[] = new Comment(
                $comment->id,
                $article,
                $this->getByID($comment->parent_id),
                $comment->level,
                $comment->report,
                $comment->is_delete,
                $comment->username,
                $comment->content,
                $comment->created_at
            );
        }

        return $comments;
    }

    /**
     * Récupére une partie des commentaires comprit entre $offset et $offset + $limit
     *
     * @param $offset
     * @param $limit
     * @param string $status
     * @return array
     */
    public function getBetween($offset, $limit, $status = null){

        $params = $this->getStatus($status);

        if(!is_null($params)){

            if(!is_null($params['report'])){
                $query = $this->db->prepare("SELECT * FROM $this->table WHERE ( report = :report AND is_delete = :is_delete) ORDER BY report DESC, created_at DESC LIMIT :offset, :limit");
                $query->bindParam(':report', $params['report'], \PDO::PARAM_INT);
                $query->bindParam(':is_delete', $params['is_delete'], \PDO::PARAM_INT);
                $query->bindParam(':offset', $offset, \PDO::PARAM_INT);
                $query->bindParam(':limit', $limit, \PDO::PARAM_INT);
            }else{
                $query = $this->db->prepare("SELECT * FROM $this->table WHERE is_delete = :is_delete  ORDER BY report DESC, created_at DESC LIMIT :offset, :limit");
                $query->bindParam(':is_delete', $params['is_delete'], \PDO::PARAM_INT);
                $query->bindParam(':offset', $offset, \PDO::PARAM_INT);
                $query->bindParam(':limit', $limit, \PDO::PARAM_INT);
            }
        }else{
            $query = $this->db->prepare("SELECT * FROM $this->table ORDER BY report DESC, created_at DESC LIMIT :offset, :limit");
            $query->bindParam(':offset', $offset, \PDO::PARAM_INT);
            $query->bindParam(':limit', $limit, \PDO::PARAM_INT);
        }

        $query->execute();
        $response       = $query->fetchAll(PDO::FETCH_OBJ);
        $articleManager = new ArticleManager();
        $comments       = [];

        foreach($response as $comment){

            $article        = $articleManager->getByID($comment->article_id);

            $comments[] = new Comment(
                $comment->id,
                $article,
                $this->getByID($comment->parent_id),
                $comment->level,
                $comment->report,
                $comment->is_delete,
                $comment->username,
                $comment->content,
                $comment->created_at
            );
        }

        return $comments;
    }

    /**
     * Récupération de l'ensemble des enfants pour un commentaire parent
     *
     * @param Comment $parent
     * @param Article $article
     * @return array|null
     */
    public function getChildren(Comment $parent, Article $article = null){

        $parent_id = $parent->getId();

        $query = $this->db->prepare("SELECT * FROM $this->table
                                     WHERE ( parent_id = :parent_id AND level <= 3 )
                                     ORDER BY created_at ASC");
        $query->bindParam(':parent_id', $parent_id, \PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_OBJ);

        // Pas de sous commentaire pour le parent
        if (sizeof($result) > 0){

            $childComments = [];

            foreach ($result as $comment){

                $new_child =  new Comment(
                    $comment->id,
                    $article,
                    $parent,
                    $comment->level,
                    $comment->report,
                    $comment->is_delete,
                    $comment->username,
                    $comment->content,
                    $comment->created_at
                );

                $childComments[]    = $new_child;
                $childs             = $this->getChildren($new_child);

                // On boucle plusieurs fois pour vérifier si l'enfant qu'on a récupérer à lui aussi des enfants
                // Afin de tous les associés au parent
                if(!is_null($childs)){
                    $childComments = array_merge($childComments, $childs);
                }
            }

            return $childComments;

        } else {
            return null;
        }

    }

    /**
     * Récupération des commentaires associé à un article
     *
     * @param Article $article
     * @return array|null
     */
    public function getCommentsByArticle(Article $article){

        $article_id = $article->getId();

        $query  = $this->db->prepare("SELECT * FROM $this->table
                                    WHERE ( parent_id = 0 AND article_id = :article_id )
                                    ORDER BY created_at ASC");
        $query->bindParam(':article_id', $article_id, \PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_OBJ);

        // Pas de commentaires pour cet article
        if (sizeof($result) > 0){

            $comments = [];

            foreach ($result as $comment){

                $new_parent = new Comment(
                    $comment->id,
                    $article,
                    null,
                    $comment->level,
                    $comment->report,
                    $comment->is_delete,
                    $comment->username,
                    $comment->content,
                    $comment->created_at
                );
                $comments[] = $new_parent;
                $childs     = $this->getChildren($new_parent, $article);

                // On enregistre ses enfants si il y en a
                if(!is_null($childs)){
                    $comments = array_merge($comments, $childs);
                }
            }

            return $comments;

        }else{
            return null;
        }

    }

    /**
     * Sauvegarde d'un commentaire en BDD
     *
     * @param Comment $comment
     * @return mixed
     */
    public function save(Comment $comment) {
        $query = $this->db->prepare("INSERT INTO $this->table
                                    (article_id, parent_id, level, report, is_delete, username, content, created_at)
                                     VALUES (:article_id, :parent_id, :level, :report, :is_delete, :username, :content, NOW())");
        $query->execute(array(
            ':article_id'   => $comment->getArticle()->getId(),
            ':parent_id'    => (int) $comment->getParent()->getId(),
            ':level'        => $comment->getLevel(),
            ':report'       => (bool) $comment->getReport(),
            ':is_delete'    => (bool) $comment->getIsDelete(),
            ':username'     => $comment->getUsername(),
            ':content'      => $comment->getContent()
        ));
        return $this->db->lastInsertId();
    }

    /**
     * Met à jour un commentaire
     *
     * @param Comment $comment
     */
    public function update(Comment $comment) {
        $query = $this->db->prepare("UPDATE $this->table
                                    SET article_id = :article_id, parent_id = :parent_id, level = :level, report = :report, is_delete = :is_delete, username = :username, content = :content
                                    WHERE id = :comment_id");

        $query->execute(array(
            ':comment_id'   => $comment->getId(),
            ':article_id'   => $comment->getArticle()->getId(),
            ':parent_id'    => (!is_null($comment->getParent())) ? (int) $comment->getParent()->getId() : 0,
            ':level'        => $comment->getLevel(),
            ':report'       => (bool) $comment->getReport(),
            ':is_delete'    => (bool) $comment->getIsDelete(),
            ':username'     => $comment->getUsername(),
            ':content'      => $comment->getContent()
        ));
    }

    /**
     * Suppression d'un commentaire
     *
     * @param Comment $comment
     */
    public function delete(Comment $comment) {
        $query = $this->db->prepare("DELETE FROM $this->table WHERE id = :comment_id");
        $query->execute(array(
            ':comment_id'       => $comment->getId(),
        ));
    }
}