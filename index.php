<?php

use Controllers\AuthController;
use Controllers\DatabaseController;
use Helpers\HttpRequest;
use Helpers\HttpResponse;
use Services\DatabaseService;
use Tools\Initializer;
use Helpers\Token;
use PHPMailer\PHPMailer;
use Services\MailerService;


$_ENV["current"] = "dev";
$config = file_get_contents("src/configs/" . $_ENV["current"] . ".config.json", true);
$_ENV['config'] = json_decode($config);

if ($_ENV["current"] == "dev") {
    $origin = "http://localhost:3000";
} else if ($_ENV["current"] == "prod") {
    $origin = "http://nomdedomaine.com";
}

header("Access-Control-Allow-Origin: $origin");

require_once 'autoload.php';
Autoload::register();

require_once 'vendor/autoload.php';


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

if (
    $_ENV['current'] == 'dev' && !empty($request->route) && $request->route[0] ==
    'auth'
){
    $authController = new AuthController($request);
    $bp =true;

}

if (empty($request->route) || !in_array($request->route[0], $tables)) {
    HttpResponse::exit();
}
$controller = new DatabaseController($request);
$result = $controller->execute();
HttpResponse::send(["data" => $result]);


$tokenFromEncodedString = Token::create($encoded);
$decoded = $tokenFromEncodedString->decoded;
$test = $tokenFromEncodedString->isValid();


$ms = new MailerService();
$mailParams = [
    "fromAddress" => ["newsletter@tabblz.com", "newsletter tabblz.com"],
    "destAddresses" => ["bevis.patrick@gmail.com"],
    "replyAddress" => ["info@tabblz.com", "information tabblz.com"],
    "subject" => "Newsletter tabblz.com",
    "body" => "This is the HTML message sent by <b>tabblz.com</b>",
    "altBody" => "This is the plain text message for non-HTML mail clients"
];
$send = $ms->send($mailParams);
echo ($send['message']);
