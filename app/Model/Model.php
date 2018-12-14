<?php


namespace App\Model;

use App\Core\Database;

class Model {

    protected $db;

    /**
     * Constructeur
     * On injecte la connexion à la BDD pour tout les modéles
     */
    public function __construct(){

        if ($this->db === null) {
            $connection = new Database();
            $this->db = $connection->openDatabaseConnection();
        }
    }

}
