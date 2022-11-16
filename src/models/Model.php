<?php

namespace Models;

use Services\DatabaseService;

class Model
{

    public string $table;
    public string $pk;
    public array $schema;

    /**
     * 1. initialise les 3 variables $this->table, 
     * $this->pk (nom de l'id de la table) 
     * et $this->schema (à l'aide de getSchema)
     * 2. le param $json correspond aux données en entrée
     * (ex pour la table role : ["id_role"=>"...", "title"=>"", ...])
     * si $json ne contient pas d'id, crée un nouvel id (nextGuid)
     * 3. ajoute à l'instance toutes les colonnes contenues dans $json
     * si elles sont présentes dans le schema
     * 4. compléte le contenu de $json pour obtenir une instance ayant 
     * les mêmes propriétés que le schema 
     * (avec des valeurs par défaut si elles sont définit dans le schema)
     * (ex pour la table role, $json vaut ["title"=>"seller", "weight"=>2, "nimportequoi"=>"..."])
     * seul title et weight existent dans la table (schema), 
     * ils sont donc ajoutés comme variable à l'instance
     * nimportequoi n'est pas gardé, 
     * $this->title = "seller" et $this->weight = 2
     * il manque les colonnes id_role et is_deleted
     * id_role étant manquant, on le crée avec nextGuid, 
     * $this->id_role = "................" (16 caractères)
     * is_deleted à une valeur par defaut qui vaut 0 dans le schema, 
     * $this->is_deleted = 0
     * une fois construite, notre instance, en plus des variables table, pk et schema,
     * possede les variables id_role, title, weight et is_deleted
     */
    public function __construct(string $table, array $json)
    {
        $this->table = $table;
        $this->pk = "Id_" . $this->table;
        $this->schema = self::getSchema($table);
        if(!isset($json[$this->pk]))
        {
            $json[$this->pk] = $this->nextGuidV2();
        }
        foreach ($this->schema as $k => $v) {
            if(isset($json[$k])){
                $this->$k = $json[$k];
            }
            elseif($this->schema[$k]['nullable'] == 1 && 
                    $this->schema[$k]['default'] == '')
            {
                $this->$k = null;
            }
            else
            {
                $this->$k = $this->schema[$k]['default'];
            }
        }
    }

    /**
     * Renvoie le schema (colonnes de la table) défini dans la classe Schemas
     * correspondant à $table sous forme de tableau associatif 
     * (classe Schemas généré au sprint 4)
     */
    public static function getSchema(string $table) : array
    {
        $schemaName = 'Schemas\\'.ucfirst($table);
        return $schemaName::COLUMNS;
    }

    /**
     * Crée un identifiant unique de $length caractères (par defaut)
     * L'idée est de se servir de la fonction microtime() pour récupérer le timestamp
     * Puis de le convertir en base 32 [a-z][0-9] ce qui vous donnera 9 caractères 
     * Completer ensuite en générant autant de caractère aléatoire (base 32) 
     * que nécessaire pour obtenir la $length souhaitée (16 par defaut)
     */
    private function nextGuid(int $length = 16) : string
    {
        $guid = base_convert((microtime(true)*10000), 10, 36);
        while (strlen($guid) < $length) {
            $guid = base_convert(rand(0, 35), 10, 36) . $guid;
        }
        return $guid;
    }
    public function nextGuidV2(int $length = 24) : string
    {
        $time = explode(' ', microtime());
        $time[0] = substr($time[0], 2);
        $nanotime = $time[1] . $time[0];
        $guid = base_convert($nanotime, 10, 36);
        while (strlen($guid) < $length) {
            $guid = base_convert(rand(0, 35), 10, 36) . $guid;
        }
        return $guid;
    }

    /**
     * Revoie la liste des données sous forme de tableau associatif
     * (Récupérez les colonnes grace au schema)
     * exemple : une instance de Model correspondant à la table role 
     * a pour variables :
     * table, pk, schema, id_role, title, weight et is_deleted
     * seules les variables id_role, title, weight et is_deleted 
     * existent en base de données
     * la méthode data les renvoie sous forme de tableau associatif
     * table, pk et schema ne sont pas renvoyée
     */
    public function data() : array 
    {
        $data = (array) clone $this;
        unset($data['table']);
        unset($data['pk']);
        unset($data['schema']);
        return $data;
    }

    



    


}