<?php

namespace Controllers;


use Services\DatabaseService;
use Helpers\HttpRequest;
use Helpers\Token;
use Services\MailerService;

class AuthController
{
    public function __construct(HttpRequest $request)
    {
        $this->controller = $request->route[0];
        $this->function = isset($request->route[1]) ? $request->route[1] : null;

        $request_body = file_get_contents('php://input');
        $this->body = json_decode($request_body, true) ?: [];

        $this->action = $request->method;
    }

    public function execute()
    {
        // La fonction execute, éxecute la fonction du même nom que le dernier élément
        // de la route dans le fetch, ex : auth/check ... auth/login
        $function = $this->function;
        $result = self::$function();
        return $result;
    }

    public function login()
    {
        $dbs = new DatabaseService('appuser');
        $email = filter_var($this->body['login'], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["result" => false];
        }

        $user = $dbs->selectWhere("login = ? AND is_deleted = ?", [$email, 0]);
        $prefix = $_ENV['config']->hash->prefix;

        if (count($user) == 1 && password_verify($this->body['password'], $prefix . $user[0]['password'])) {

            $dbs = new DatabaseService("role");

            $role = $dbs->selectWhere("Id_role = ? AND is_deleted = ?", [$user[0]['Id_role'], 0]);

            $tokenFromDataArray = Token::create(['login' => $user[0]['login'], 'password' => $user[0]['password']]);
            $encoded = $tokenFromDataArray->encoded;

            return ["result" => true, "role" => $role[0]['weight'], "id" => $user[0]['Id_appuser'], "token" => $encoded];
        }

        return ["result" => false];
    }

    public function check()
    {
        $headers = apache_request_headers();
        if (isset($headers["Authorization"])) {
            $token = $headers["Authorization"];
        }

        if (isset($token) && !empty($token)) {


            $tokenFromStringToken = Token::create($token);
            $dataTab = $tokenFromStringToken->decoded;

            $dbs = new DatabaseService('appuser');
            $user = $dbs->selectWhere("login = ? AND is_deleted = ?", [$dataTab['login'], 0]);
            $bp = true;
            $dbs = new DatabaseService("role");
            $role = $dbs->selectWhere("Id_role = ? AND is_deleted = ?", [$user[0]['Id_role'], 0]);
            return ["result" => true, "role" =>  $role[0]['weight'], "id" => $user[0]['Id_appuser']];
        }
        return ["result" => false];
    }

    public function register()
    {
        // Une fois que les appusers / accounts seront créé, faire les deux verifications pour
        // savoir ils existent déjà des items en bdd :

        $dbs = new DatabaseService("account");
        $accounts = $dbs->selectWhere("pseudo_account = ?", [$this->body['pseudo']]);
        if (count($accounts) > 0) {
            return ['result' => false, 'message' => 'pseudo ' . $this->body['pseudo'] . ' already used'];
        }
        $dbs = new DatabaseService("appuser");
        $users = $dbs->selectWhere("login = ?", [$this->body['email']]);
        if (count($users) > 0) {
            return ['result' => false, 'message' => 'email ' . $this->body['email'] . ' already used'];
        }

        /////////////////////////////////////////////////////////////////////////////////////////
        $tokenFromDataArray = Token::create(['pseudo' => $this->body['pseudo'], 'email' => $this->body['email']]);
        $token = $tokenFromDataArray->encoded;


        $href = "http://localhost:3000/account/validate/$token";

        $ms = new MailerService();
        $mailParams = [
            "fromAddress" => ["register@tabblz.com", "nouveau compte tabblz.com"],
            "destAddresses" => [$this->body['email']],
            "replyAddress" => ["noreply@tabblz.com", "No Reply"],
            "subject" => "Créer votre compte tabblz.com",
            "body" => 'Click to validate the account creation <br>
                        <a href="' . $href . '">Valider votre inscription</a> ',
            "altBody" => "Go to $href to validate the account creation"
        ];
        $sent = $ms->send($mailParams);
        return ['result' => $sent['result'], 'message' => $sent['result'] ?
            "Vérifier votre boîte mail et confirmer la création de votre compte sur tabblz.com" :
            "Une erreur est survenue, veuiller recommencer l'inscription"];

        $bp = true;
    }
    public function validate()
    {
        $token = $this->body['token'] ?? "";

        if (isset($token) && !empty($token)) {
            $tokenFromStringToken = Token::create($token);
            $dataTab = $tokenFromStringToken->decoded;
            $test = $tokenFromStringToken->isValid();

            if ($test == true) {
                return ['result' => true, 'pseudo' => $dataTab['pseudo'], 'email' => $dataTab['email']];
            }
            return ['result' => false];
        }
        return ['result' => false];
        $bp = true;
    }
    public function create()
    {
        $dbs = new DatabaseService("account");
        $account = $dbs->insertOrUpdate([
            "items" => [
                [
                    "pseudo_account" => $this->body["data"]["pseudo"]
                ]
            ]
        ]);
        if ($account) {

            $password = password_hash($this->body["pass"], PASSWORD_ARGON2ID, [
                'memory_cost' => 1024,
                'time_cost' => 2,
                'threads' => 2
            ]);
            $prefix = $_ENV['config']->hash->prefix;
            $password = str_replace($prefix, "", $password);

            $dbs = new DatabaseService("appuser");
            $app_user = $dbs->insertOrUpdate(["items" => [
                [
                    "login" => $this->body["data"]["email"],
                    "pseudo" => $this->body["data"]["pseudo"],
                    "password" => $password,
                    "Id_account" => $account[0]["Id_account"],
                    "Id_role" => 2
                ]
            ]]);
            if ($app_user) {
                return ["result" => true];
            }
        }
        return ["result" => false];
    }
}
