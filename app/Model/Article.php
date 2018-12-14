<?php


namespace App\Model;

use App\Core\Slug;


class Article {

    private $id;
    private $title;
    private $content;
    private $date;
    private $isDelete;
    private $isPublished;
    private $comments;

    /**
     * Constructeur
     *
     * @param null $id
     * @param null $title
     * @param null $content
     * @param null $date
     * @param bool $isDelete
     * @param bool $isPublished
     * @param array $comments
     */
    public function __construct($id = null, $title = null, $content = null, $isDelete = false, $isPublished = false, $date = null,  array $comments = null) {
        $this->id           = (int) $id;
        $this->title        = $title;
        $this->content      = $content;
        $this->isDelete     = (bool) $isDelete;
        $this->isPublished  = (bool) $isPublished;
        $this->date         = $date;
        $this->comments     = $comments;
    }


    /**
     * Récupération de l'ID
     *
     * @return null|string
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Récupération de l'ID
     *
     * @return null|string
     */
    public function setId($id){
        $this->id = (int) $id;
    }

    /**
     * Récupération du titre
     *
     * @return null|string
     */
    public function getTitle(){
        return $this->title;
    }

    /**
     * Mise à jour du titre
     *
     * @param $title
     */
    public function setTitle($title){
        $this->title = $title;
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
     * Récupération de la date
     *
     * @return null|string
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
     * Récupération de la suppression
     *
     * @return mixed
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
     * Récupération de la publication
     *
     * @return mixed
     */
    public function getIsPublished(){
        return $this->isPublished;
    }

    /**
     * Mise à jour de la publication
     *
     * @param isPublished
     */
    public function setIsPublished($isPublished){
        $this->isPublished = (bool) $isPublished;
    }

    /**
     * Récupération de la liste de commentaires associé
     *
     * @return array
     */
    public function getComments() {
        return $this->comments;
    }

    /**
     * Mise à jour de la liste des commentaires associé
     *
     * @param array $comments
     */
    public function setComments(array $comments) {
        $this->comments = $comments;
    }

    /**
     * Récupération du nombre de commentaires associés à cet article
     *
     * @return string
     */
    public function getAmountComments(){
        $commentManager = new CommentManager();
        return $commentManager->getAmountForArticleID($this->id);
    }

    /**
     * Récupération de l'url de l'article
     *
     * @return string
     */
    public function getUrl(){
        return BASE_URL . '/article/' . $this->id . '/' . Slug::getSlug($this->title);
    }

    /**
     * Génére un extrait du contenu de l'article
     *
     * @return string
     */
    public function getExtract($max = 400){
        // On extrait une portion de texte
        $extract =  substr(strip_tags($this->content),0, $max);

        // On évite de couper un mot
        $space   =  strrpos($extract, ' ');
        return substr($extract, 0 , $space) . '  ...';
    }

    /**
     * Formate une date pour l'affichage
     * exemple : 01/01/1970 | 01/01/1970 10:00
     *
     * @param bool $hours
     * @return bool|string
     * Retourne une date formaté pour la lecture des billets
     */
    public function getFormatedDate($hours = true){
        return (!$hours) ? date('d/m/Y', strtotime($this->getDate())) : date('d/m/Y H:i', strtotime($this->getDate())) ;
    }


    /**
     * Vérification de la validité d'un article
     *
     * @return bool
     */
    public function checkIsValidBeforeSave() {
        $errors = false;

        // vérification de la validité du titre
        if(!preg_match('#^[\p{L}0-9- ,\!.\?]{3,80}$#u', $this->getTitle())){
            $errors["title"] = "Le titre n'est pas valide !";
        }

        // Vérification du contenu de l'article
        $cleanContent = strip_tags(trim($this->getContent()));
        $cleanContent = str_replace('&nbsp;', '', $cleanContent);
        $cleanContent = str_replace(' ', '', $cleanContent);

        if (strlen(trim($cleanContent)) < 2 ) {
            $errors["content"] = "le contenu de l'article n'est pas valide !";
        }


        return $errors;
    }





}