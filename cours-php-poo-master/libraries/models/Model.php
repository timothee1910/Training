<?php
namespace Models;

//use Database;

/**
 * Class Model
 * On met abstract pour éviter que la classe Model ne soit appeler
 * Dans un fichier comme ceci $model = new Model();
 */
abstract class Model
{
    /**
     * Protected veut dire que c'est pas privé ni public
     * Seulement accessible pour ma classe + les enfants
     * @var PDO
     */
    protected $pdo;
    /**
     * @var string
     */
    protected $table;

    /**
     * Model constructor.
     */
    public function __construct() {
        $this->pdo= \Database::getPdo();
    }

    /**
     * Quand on met un ? devant le type d'un paramètre
     * Cela veut dire que la variable n'est pas obligatoire
     * Quand on met = "" sur le paramètre cela veut dire qu'on définit une valeur par défaut
     * @return array
     */
    public function findAll(?string $order = "" ): array {
        $sql="SELECT * FROM {$this->table}";
        if ($order) {
           $sql .= " ORDER BY ".$order;
        }
        $resultats = $this->pdo->query($sql);
        // On fouille le résultat pour en extraire les données réelles
        $items = $resultats->fetchAll();
        return $items;
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function find (int $id) {

        $resultats = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id=:id ORDER BY created_at DESC");
        // On fouille le résultat pour en extraire les données réelles
        $resultats->execute(['id'=>$id]);
        $items = $resultats->fetch();
        return $items;
    }

    /**
     * @param int $id
     */
    function delete(int $id):void {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $query->execute(['id' => $id]);
    }


}
