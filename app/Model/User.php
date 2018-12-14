<?php


namespace App\Model;


class User {

    private $id;
    private $permission;
    private $username;
    private $password;
    private $email;
    private $date;

    /**
     * Constructeur
     *
     * @param null $id
     * @param null $permission
     * @param null $username
     * @param null $password
     * @param null $email
     * @param null $date
     */
    public function __construct($id = null, $permission = null, $username = null, $password = null, $email = null, $date = null){
        $this->id           = (int) $id;
        $this->permission   = (int) $permission;
        $this->username     = $username;
        $this->password     = $password;
        $this->email        = $email;
        $this->date         = $date;
    }

    /**
     * Récupération de l'ID
     *
     * @return mixed
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Récupération de la permission
     *
     * @return mixed
     */
    public function getPermission(){
        return $this->permission;
    }

    /**
     * Mise à jour de la permission
     *
     * @param $permission
     */
    public function setPermission($permission){
        $this->permission = (int) $permission;
    }

    /**
     * Récupération du nom d'utilisateur
     *
     * @return mixed
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
     * Récupération du mot de passe
     *
     * @return mixed
     */
    public function getPassword(){
        return $this->password;
    }

    /**
     * Mise à jour du mot de passe
     *
     * @param $password
     */
    public function setPassword($password){
        $this->password = $password;
    }

    /**
     * Récupération de l'email
     *
     * @return mixed
     */
    public function getEmail(){
        return $this->email;
    }

    /**
     * Mise à jour de l'email
     *
     * @param $email
     */
    public function setEmail($email){
        $this->email = $email;
    }

    /**
     * Récupération de la date d'enregistrement
     *
     * @return mixed
     */
    public function getDate(){
        return $this->date;
    }

    /**
     * Mise à jour de la date d'enregistrement
     *
     * @param $date
     */
    public function setDate($date){
        $this->date = $date;
    }

    /**
     * Détermine si le membre a les droits administrateur
     *
     * @return bool
     */
    public function isAdmin(){

        return ($this->getPermission() == 1) ? true : false;
    }


}