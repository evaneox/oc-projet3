<?php


namespace App\Core;

use PDO;

class Database {

    protected $db = null;

    /**
     * Connexion à la BDD
     */
    public function openDatabaseConnection(){
        $options = array(
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        try {
            $this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, $options);
            return $this->db;
        } catch (PDOException $e) {
            exit('Problème de connexion à la base de donnée !');
        }
    }

    /**
     * Déconnexion à la BDD
     */
    public function closeDatabaseConnection(){
        $this->db->close();
    }
}