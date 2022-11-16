<?php namespace Helpers;

use Exception;

class Token
{
    private static $prefix = "$2y$08$"; //bcrypt (salt = 8)
    private static $defaultValidity = 60 * 60 * 1; //1h
    private static $separator = "|";

    private function __construct()
    {
        $args = func_get_args();
        if (empty($args)) {
            throw new Exception("one argument required");

        } elseif (is_array($args[0])) {
            $this->encode($args[0]);

        } elseif (is_string($args[0])) {
            $this->decode($args[0]);
            
        } else {
            throw new Exception("argument must be a string or an array");
        }
    }
    public array $decoded; //stocke le tableau de données
    public string $encoded; //stocke le token
    public static function create($entry): Token
    {
        return new Token($entry);
    }

    /**
* 1. Crée un token à partir d'un tableau de données
* 2. $decoded contient les informations à stocker dans la token
* Si les entrées createdAt, usableAt, validity et expireAt
* ne sont pas fournies dans $decoded, il faut les ajouter
* 3. un token est composé d'un payload et d'une signature
* (séparé par un caractère remarquable qui permettra un découpage)
* Le payload est un encodage en base64 du tableau de données (stringifié)
* La signature est égale au payload hashé en bcrypt (salt = 8)
* Le token, une fois construit, doit être encodé pour être transmis dans un url
*/
private function encode(array $decoded = []) : void
{
// ???
$this->decoded = $decoded
// ???
$payload = // ???
$signature = password_hash($payload, PASSWORD_BCRYPT, ['cost' => 8]);
$encoded = // ???
$this->encoded = // ???
}
/**
* Décode un token pour obtenir le tableau de données initial
* (faire le cheminement de la méthode encode dans l'autre sens)
*/
private function decode(string $encoded) : void
{
$this->encoded = $encoded
// ???
$this->decoded = $decoded ?? null;
}
/**
* Vérifie la validité du token encodé ($this->decoded not null)
* si $withDate vaut true vérifie également les dates expireAt et usableAt
*/
public function isValid(bool $withDate = true) : bool
{
if(!isset($this->decoded)){
return false;
}
if($withDate){
//???
}
return true;
}

}
