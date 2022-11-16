<?php

use Controllers\DatabaseController;
use Helpers\HttpRequest;
use Helpers\HttpResponse;
use Services\DatabaseService;
use Tools\Initializer;
use Helpers\Token;

$_ENV["current"] = "dev";
$config = file_get_contents("src/configs/" . $_ENV["current"] . ".config.json");
$_ENV['config'] = json_decode($config);

if ($_ENV["current"] == "dev") {
    $origin = "http://localhost:3000";
} else if ($_ENV["current"] == "prod") {
    $origin = "http://nomdedomaine.com";
}

header("Access-Control-Allow-Origin: $origin");

require_once 'autoload.php';
Autoload::register();



$request = HttpRequest::instance();
$tables = DatabaseService::getTables();

if (
    $_ENV['current'] == 'dev' && !empty($request->route) && $request->route[0] ==
    'init'
    ) {
        if (Initializer::start($request, $tables)) {
            HttpResponse::send(["message" => "Api Initialized"]);
        }
        HttpResponse::send(["message" => "Api Not Initialized, try again ..."]);
    }
    

if (empty($request->route) || !in_array($request->route[0], $tables)) {
    HttpResponse::exit();
}
$controller = new DatabaseController($request);
$result = $controller->execute();
HttpResponse::send(["data" => $result]);
