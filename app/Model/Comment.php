<?php


namespace App\Model;

class Comment {

    private $id;
    private $article;
    private $level;
    private $report;
    private $username;
    private $content;
    private $date;
    private $isDelete;

    /**
     * Constructeur
     *
     * @param null $id
     * @param Article $article
     * @param Comment $parent
     * @param null $level
     * @param bool $report
     * @param bool $isDelete
     * @param null $username
     * @param null $content
     * @param null $date
     */
    public function __construct($id = null, Article $article = null, Comment $parent = null, $level = null, $report = false, $isDelete = false, $username = null, $content = null, $date = null){
        $this->id           = $id;
        $this->article      = $article;
        $this->parent       = $parent;
        $this->level        = (int) $level;
        $this->report       = (bool) $report;
        $this->username     = $username;
        $this->content      = $content;
        $this->date         = $date;
        $this->isDelete     = (bool) $isDelete;
    }

    /**
     * Récupération de l'ID
     *
     * @return int
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Récupération de l'article parent associé à ce commentaire
     *
     * @return Article
     */
    public function getArticle(){
        return $this->article;
    }

    /**
     * Mise à jour de l'article parent associé à ce commentaire
     *
     * @param Article $article
     */
    public function setArticle(Article $article) {
        $this->article = $article;
    }

    /**
     * Récupération du commentaire parent
     *
     * @return Comment
     */
    public function getParent(){
        return $this->parent;
    }

    /**
     * Mise à jour du commentaire parent
     *
     * @param Comment $parent
     */
    public function setParent(Comment $parent){
        $this->parent = $parent;
    }

    /**
     * Récupération du level du commentaire
     *
     * @return int
     */
    public function getLevel(){
        return $this->level;
    }

    /**
     * Mise à jour du level /** onb peut améliorer
     * @param $level
     */
    public function setLevel($level){
        $this->level = $level;
    }

    /**
     * Récupération du nombre de report
     *
     * @return bool
     */
    public function getReport(){
        return $this->report;
    }

    /**
     * Mise à jour du compteur de report
     *
     * @param $report
     */
    public function setReport($report){
        $this->report = (bool) $report;
    }

    /**
     * Récupération de la suppression
     *
     * @return bool
     */
    public function getIsDelete(){
        return $this->isDelete;
    }

    /**
     * Mise à jour de la suppression
     *
     * @param $isDelete
     */
    public function setIsDelete($isDelete){
        $this->isDelete = (bool) $isDelete;
    }

    /**
     * Récupération du nom d'utilisateur
     *
     * @return string
     */
    public function getUsername(){
        return $this->username;
    }

    /**
     * Mise à jour du nom d'utilisateur
     *
     * @param $username
     */
    public function setUsername($username){
        $this->username = $username;
    }

    /**
     * Récupération du contenu
     *
     * @return null|string
     */
    public function getContent(){
        return $this->content;
    }

    /**
     * Mise à jour du contenu
     *
     * @param $content
     */
    public function setContent($content){
        $this->content = $content;
    }

    /**
     * Mise à jour de la date
     *
     * @return string
     */
    public function getDate(){
        return $this->date;
    }

    /**
     * Mise à jour de la date
     *
     * @param $date
     */
    public function setDate($date){
        $this->date = $date;
    }

    /**
     * Formate une date pour l'affichage
     * exemple : 01/01/1970 | 01/01/1970 10:00
     *
     * @return bool|string
     * Retourne une date formaté pour les commentaires
     */
    public function getFormatedDate(){
        return  sprintf( "Posté le %s à %s h %s", date('d/m/Y', strtotime($this->getDate())), date('H', strtotime($this->getDate())), date('i', strtotime($this->getDate())) ) ;
    }

    /**
     * Vérification de la validité d'un commentaire
     *
     * @return bool
     */
    public function checkIsValidForCreate() {
        $errors = false;

        // Dans le cas d'un sous commentaire
        // On va vérifier que le parent est bien associé au même article
        // (toujours faire attention au données des visiteurs :) )
        if(!is_null($this->parent->getId())){
            if($this->parent->getArticle()->getId() !== $this->article->getId()){
                $errors["message"] = "Le commentaire parent n'appartient pas à cet article";
            }
        }

        // Vérification du champ commentaire
        if (strlen(trim($this->getContent())) < 2 ) {
            $errors["content"] = "Ce commentaire n'est pas valide !";
        }
        /*echo $this->username;*/
        // vérification de la validité du nom d'utilisateur
        if(!preg_match('#^[a-zA-Z0-9_-]{3,16}$#u', $this->getUsername())){
            $errors["name"] = "Nom d'utilisateur invalide !";
        }

        return $errors;
    }
}