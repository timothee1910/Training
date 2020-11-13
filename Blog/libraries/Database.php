<?php

/**
 * Class Database
 * Principe des methodes statique
 * Deux écriture possible :
 * $db = new Database();
 * $pdo = $db->getPdo();
 * Ici , on appel de façon statique, c'est-a-dire directement, une methode qui appartient à la classe elle-même
 * Sans passé par une variable $pdo par exemple
 *
 * $pdo = Database::getPdo();
 */
class Database {
    /**
     * @var null
     */
    private static $instance = null;
    /**
     * Connexion à la base de donnée
     * @return PDO
     */
    public static function getPdo(): PDO {
        if (self::$instance === null) {
            self::$instance = new PDO('mysql:host=localhost;dbname=blogpoo;charset=utf8', 'root', 'root', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }
        return self::$instance ;
    }
}
