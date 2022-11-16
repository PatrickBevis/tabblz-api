<?php

namespace Models;

use Services\DatabaseService;

class ModelList
{
    public string $table;
    public string $pk;
    public array $items;//liste des instances de la classe Model

    public function __construct(string $table, array $list)
    {
        $this->table = $table;
        $this->pk = "Id_" . $this->table;
        $this->items = [];
        foreach ($list as $json) {
            array_push($this->items, new Model($table, $json));
        }
    }

    public static function getSchema($table) : array
    {
        $schemaName = 'Schemas\\'.ucfirst($table);
        return $schemaName::COLUMNS;
    }

    /**
     * Même principe que pour Model mais sur une liste ($this->items)
     */
    public function data() : array
    {
        $result = array_map(function($item){
            return $item->data();
        }, $this->items);     
        return $result;  
    }

    /**
     * Renvoie la liste des id contenus dans $this->items
     */
    public function idList($key = null) : array //TODO  rename columnValues
    {
        if(!isset($key)) {
            $key = $this->pk;
        }
        return array_column( $this->items, $key);
    }

    /**
     * Renvoie l'instance contenue dans $this->items correspondant à $id
     */
    public function findById($id) : ?Model
    { 
        foreach($this->items as $item){
            if($item->{$this->pk} == $id){
                return $item;
            }
        }
        return null;
    }    




}