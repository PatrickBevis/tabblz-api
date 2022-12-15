<?php

namespace Controllers;


use Services\DatabaseService;
use Helpers\HttpRequest;
use Helpers\Token;

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
}
