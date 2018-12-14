<?php


namespace App\Model;

use \PDO;


class ArticleManager extends Model{

    private $table      = 'articles';

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
            case 'PUBLISHED' :
                return array(
                    'is_published' => true,
                    'is_delete'     => false
                );
                break;
            case 'UNPUBLISHED' :
                return array(
                    'is_published' => false,
                    'is_delete'     => false
                );
                break;
            case 'TRASH' :
                return array(
                    'is_published' => null,
                    'is_delete'     => true
                );
                break;
            case 'UNTRASH' :
                return array(
                    'is_published' => null,
                    'is_delete'     => false
                );
                break;
            default :
                return null;
                break;
        }

    }

    /**
     * Récupération de tout les articles
     *
     * @return array
     */
    public function getAll($status = null){

        $params = $this->getStatus($status);

        if(!is_null($params)){

            if(!is_null($params['is_published'])){
                $query      =  $this->db->prepare("SELECT * FROM $this->table WHERE ( is_published = :is_published AND is_delete = :is_delete) ORDER BY created_at DESC");
                $query->bindParam(':is_published', $params['is_published'], \PDO::PARAM_INT);
                $query->bindParam(':is_delete', $params['is_delete'], \PDO::PARAM_INT);
                $query->execute();
            }else{
                $query      =  $this->db->prepare("SELECT * FROM $this->table WHERE is_delete = :is_delete ORDER BY created_at DESC");
                $query->bindParam(':is_delete', $params['is_delete'], \PDO::PARAM_INT);
                $query->execute();
            }
        }else{
            $query      = $this->db->query("SELECT * FROM $this->table  ORDER BY created_at DESC");
        }

        $response   = $query->fetchAll(PDO::FETCH_OBJ);
        $articles   = [];

        foreach($response as $article){

            $articles[] = new Article(
                $article->id,
                $article->title,
                $article->content,
                $article->is_delete,
                $article->is_published,
                $article->created_at
            );
        }

        return $articles;
    }

    /**
     * Récupére une partie des articles comprit entre $offset et $offset + $limit
     *
     * @param $offset
     * @param $limit
     * @param string $status
     * @return array
     */
    public function getBetween($offset, $limit, $status = null){

        $params = $this->getStatus($status);

        if(!is_null($params)){

            if(!is_null($params['is_published'])){
                $query = $this->db->prepare("SELECT * FROM $this->table WHERE ( is_published = :is_published AND is_delete = :is_delete) ORDER BY created_at DESC LIMIT :offset, :limit");
                $query->bindParam(':is_published', $params['is_published'], \PDO::PARAM_INT);
                $query->bindParam(':is_delete', $params['is_delete'], \PDO::PARAM_INT);
                $query->bindParam(':offset', $offset, \PDO::PARAM_INT);
                $query->bindParam(':limit', $limit, \PDO::PARAM_INT);
            }else{
                $query = $this->db->prepare("SELECT * FROM $this->table WHERE is_delete = :is_delete ORDER BY created_at DESC LIMIT :offset, :limit");
                $query->bindParam(':is_delete', $params['is_delete'], \PDO::PARAM_INT);
                $query->bindParam(':offset', $offset, \PDO::PARAM_INT);
                $query->bindParam(':limit', $limit, \PDO::PARAM_INT);
            }
        }else{
            $query = $this->db->prepare("SELECT * FROM $this->table ORDER BY created_at DESC LIMIT :offset, :limit");
            $query->bindParam(':offset', $offset, \PDO::PARAM_INT);
            $query->bindParam(':limit', $limit, \PDO::PARAM_INT);
        }

        $query->execute();
        $response   = $query->fetchAll(PDO::FETCH_OBJ);
        $articles   = [];

        foreach($response as $article){

            $articles[] = new Article(
                $article->id,
                $article->title,
                $article->content,
                $article->is_delete,
                $article->is_published,
                $article->created_at
            );
        }

        return $articles;
    }

    /**
     * Récupération du nombre total d'articles
     *
     * @return mixed
     */
    public function getAllAmount($status = null){

        $params = $this->getStatus($status);

        if(!is_null($params)){

            if(!is_null($params['is_published'])){
                $query = $this->db->prepare("SELECT COUNT(id) AS amount_of_articles FROM $this->table
                                              WHERE ( is_published = :is_published AND is_delete = :is_delete)");
                $query->bindParam(':is_published', $params['is_published'], \PDO::PARAM_INT);
                $query->bindParam(':is_delete', $params['is_delete'], \PDO::PARAM_INT);
                $query->execute();
            }else{
                $query = $this->db->prepare("SELECT COUNT(id) AS amount_of_articles FROM $this->table
                                              WHERE is_delete = :is_delete");
                $query->bindParam(':is_delete', $params['is_delete'], \PDO::PARAM_INT);
                $query->execute();
            }
        }else{
            $query = $this->db->query("SELECT COUNT(id) AS amount_of_articles FROM $this->table");
        }
        $response   = $query->fetch(PDO::FETCH_OBJ);
        return $response->amount_of_articles;
    }

    /**
     * Récupération d'un article avec son ID,
     * retourne NULL si l'article n'a pas pu être trouvé
     *
     * @param $id
     * @return Article|null
     */
    public function getByID($id){
        if(empty($id)){
            return null;
        }
        $query = $this->db->prepare("SELECT * FROM $this->table WHERE id = :id");
        $query->bindParam(':id', $id, \PDO::PARAM_INT);
        $query->execute();
        $article = $query->fetch(PDO::FETCH_OBJ);

        if(!is_null($article)) {
            return new Article(
                    $article->id,
                    $article->title,
                    $article->content,
                    $article->is_delete,
                    $article->is_published,
                    $article->created_at
            );
        } else {
            return null;
        }
    }

    /**
     * Récupération d'un article complet et des ses commentaires
     * retourne NULL si l'article n'a pas pu être trouvé
     *
     * @param $id
     * @return Article|null
     */
    public function getByIDWithComments($id){
        if(empty($id)){
            return null;
        }
        $query = $this->db->prepare("SELECT * FROM $this->table WHERE id = :id");
        $query->bindParam(':id', $id, \PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);

        // Pas d'article trouvé
        if (!is_null($result)) {

            $article = new Article(
                $result->id,
                $result->title,
                $result->content,
                $result->is_delete,
                $result->is_published,
                $result->created_at
            );

            $commentManager = new CommentManager();
            $comments       = $commentManager->getCommentsByArticle($article);

            // On enregistre les commentaires dans l'article si il y en a
            if(!is_null($comments)){
                $article->setComments($comments);
            }

            return $article;

        } else{
            return null;
        }

    }


    /**
     * Sauvegarde d'un article en BDD
     *
     * @param Article $article
     * @return mixed
     */
    public function save(Article $article) {
        $query = $this->db->prepare("INSERT INTO $this->table
                                    (title, content, is_delete, is_published, created_at)
                                     VALUES (:title, :content, :is_delete, :is_published, NOW())");
        $query->execute(array(
            ':title'            => $article->getTitle(),
            ':content'          => $article->getContent(),
            ':is_delete'        => (bool) $article->getIsDelete(),
            ':is_published'     => (bool) $article->getIsPublished(),
        ));
        return $this->db->lastInsertId();
    }


    /**
     * Met à jour d'un article
     *
     * @param Article $article
     */
    public function update(Article $article) {
        $query = $this->db->prepare("UPDATE $this->table
                                    SET title = :title, content = :content, is_delete = :is_delete, is_published = :is_published
                                    WHERE id = :article_id");

        $query->execute(array(
            ':article_id'       => $article->getId(),
            ':title'            => $article->getTitle(),
            ':content'          => $article->getContent(),
            ':is_delete'        => (bool) $article->getIsDelete(),
            ':is_published'     => (bool) $article->getIsPublished()
        ));
    }

    /**
     * Suppression d'un article
     *
     * @param Article $article
     */
    public function delete(Article $article) {
        $query = $this->db->prepare("DELETE FROM $this->table WHERE id = :article_id");
        $query->execute(array(
            ':article_id'       => $article->getId(),
        ));
    }

}