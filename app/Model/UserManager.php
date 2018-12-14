<?php


namespace App\Model;

use \PDO;

class UserManager extends Model {

    private $table      = 'users';

    /**
     * Constructeur
     *
     * @param $table
     */
    public function __construc($table){
        $this->table = $table;
    }

    /**
     * Récupération d'un utilisateur via son ID
     *
     * @param $id
     * @return User|null
     */
    public function getByID($id){
        if(empty($id)){
            return null;
        }
        $query = $this->db->prepare("SELECT * FROM $this->table WHERE id = :id");
        $query->bindParam(':id', $id, \PDO::PARAM_INT);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_OBJ);
        if(!is_null($user)) {

            return new User(
                $user->id,
                $user->user_permission,
                $user->username,
                $user->password,
                $user->email,
                $user->created_at
            );
        } else {
            return null;
        }
    }

    /**
     * Récupére le nombre total d'utilisateurs
     *
     * @return mixed
     */
    public function getAllAmount(){
        $query = $this->db->query("SELECT COUNT(id) AS amount_of_users FROM $this->table");
        $response   = $query->fetch(PDO::FETCH_OBJ);
        return $response->amount_of_users;
    }

    /**
     * Vérifie les identifiants d'un utilisateurs
     *
     * @param $identifier
     * @param $password
     * @return User|null
     */
    public function checkCredentials($identifier, $password)
    {
        $query = $this->db->prepare("SELECT * FROM $this->table WHERE (username = :identifier OR email = :identifier)");
        $query->bindValue(':identifier', $identifier, \PDO::PARAM_STR);
        $query->execute();
        $user = $query->fetch(\PDO::FETCH_OBJ);

        if(!is_null($user) AND password_verify($password, @$user->password)){
            return $this->getByID($user->id);
        }else{
            return null;
        }
    }
}