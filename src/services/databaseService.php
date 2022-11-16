<?php

namespace Services;

use PDO;
use PDOException;
use Models\ModelList;
use Models\Model;

class DatabaseService
{
    public string $table;
    public string $pk;
    public function __construct(string $table = null)
    {
        $this->table = $table;
        $this->pk = "Id_" . $this->table;
    }
    private static ?PDO $connection = null;
    private function connect(): PDO
    {
        if (self::$connection == null) {
            $config = $_ENV['config']->db;
            $host = $config->host;
            $port = $config->port;
            $dbName = $config->dbName;
            $dsn = "mysql:host=$host;port=$port;dbname=$dbName";
            $user = $config->user;
            $pass = $config->pass;
            try {
                $dbConnection = new PDO(
                    $dsn,
                    $user,
                    $pass,
                    array(
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    )
                );
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données :
$e->getMessage()");
            }
            self::$connection = $dbConnection;
        }
        return self::$connection;
    }
    public function query(string $sql, array $params = []): object
    {
        $statment = $this->connect()->prepare($sql);
        $result = $statment->execute($params);
        return (object)['result' => $result, 'statment' => $statment];
    }
    /**
     * Retourne la liste des tables en base de données sous forme de tableau
     */
    public static function getTables(): array
    {
        $dbs = new DatabaseService('');
        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = ?";
        $resp = $dbs->query($sql, [$_ENV['config']->db->dbName]);
        $tables = $resp->statment->fetchAll(PDO::FETCH_COLUMN);
        return $tables;
    }
    /**
     * Retourne les lignes correspondant à la condition where
     */
    public function selectWhere(string $where = "1", array $bind = []) : array
    {
        $sql ="SELECT * FROM $this->table WHERE $where";
        $resp = $this->query($sql, $bind);
        $rows = $resp->statment->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    /**
     * Retourne la liste des colonnes d'une table (son schéma)
     */
    public function getSchema(): array
    {
        $schemas = [];
        $sql = "SHOW FULL COLUMNS FROM $this->table";
        $resp = $this->query($sql);
        $schemas = $resp->statment->fetchAll(PDO::FETCH_ASSOC);
        return $schemas;
    }
    public function insertOrUpdate(array $body) : ?array
    {
        $modelList = new ModelList($this->table, $body['items']);
        $inClause = trim(str_repeat("?,", count($modelList->items)), ",");
        $existingRowsList = $this->selectWhere("$this->pk IN ($inClause)", $modelList->idList());
        $existingModelList = new ModelList($this->table, $existingRowsList);
        $valuesToBind = [];
        foreach($modelList->items as &$model){
            $existingModel = $existingModelList->findById($model->{$this->pk});
            foreach ( $body['items'] as $item ) {
                if (isset($item[$this->pk]) && $model->{$this->pk} == $item[$this->pk] ) {
                    $model = new Model($this->table, array_merge((array)$existingModel, $item));
                }
            }
            $valuesToBind = array_merge($valuesToBind, array_values($model->data()));
        }
        $columns = array_keys(Model::getSchema($this->table));
        $values = "(" . trim(str_repeat("?,", count($columns)), ',') . "),";
        $valuesClause = trim(str_repeat($values, count($body["items"])), ',');
        $columnsClause = implode(",", $columns);
        $fieldsToUpdate = array_diff($columns, array($this->pk, "is_deleted"));
        $updatesClause = "";
        foreach ($fieldsToUpdate as $field) {
            $updatesClause .= "$field = VALUES($field), ";
        }
        $updatesClause = rtrim($updatesClause, ", ");
        $sql = "INSERT INTO $this->table ($columnsClause) VALUES $valuesClause ON DUPLICATE KEY UPDATE $updatesClause";
        $resp = $this->query($sql, $valuesToBind);
        if ($resp->result) {
            $rows = $this->selectWhere("$this->pk IN ($inClause)", $modelList->idList());
            return $rows;
        }
        return null;
    }
    /**
* permet la suppression (is_deleted = 1) d'une ou plusieurs lignes
* renvoie les lignes deleted sous forme de tableau
* si la mise à jour s'est bien passé (sinon null)
*/
// public function softDelete (array $body) : ? array
//     {
//     $sql = "UPDATE $this->table SET is_deleted = 1 WHERE $this->pk "
//     //   $resp = $this->query ( $sql , $valuesToBind );
//       if ( $resp -> result ) {
//       $rows = // ???
//       return $rows ;   
//       }
//       return null ;
//     }

/**
* permet la suppression (effective) d'une ou plusieurs lignes
* renvoie un tableau vide si la suppression est effective, sinon null
*/
// public function hardDelete () : ?array
// {
// // ???
// $resp = $this -> query ( $sql , $valuesToBind );
// }
    
}
