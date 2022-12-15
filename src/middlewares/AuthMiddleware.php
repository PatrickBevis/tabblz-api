<?php

namespace Middlewares;

use Helpers\Token;
use Helpers\HttpRequest;

class AuthMiddleware
{

    public function __construct(HttpRequest $request)
    {

        $this->id = isset($request->route[1]) ? $request->route[1] : null;

        $restrictedRoutes = (array)$_ENV['config']->restricted;
        $valueRS = $request->routeSlashed;
        if (isset($restrictedRoutes[$valueRS])) {
            $this->condition = $restrictedRoutes[$valueRS];
        }
        if($this->id !== null){
            $valueRS = str_replace(":id", $this->id, $valueRS);
        }

        // A suivre ...

        foreach ($restrictedRoutes as $key => $value) {

            if($this->id !== null){
                $restricted = str_replace(":id", $this->id, $key);
            }

            if ($restricted == $request->routeSlashed) {
                $this->condition = $value;
                break;
            }
        }
    }

    public function verify()
    {
        if (isset($this->condition)) {
            $headers = apache_request_headers();
            if (isset($headers["Authorization"])) {
                $token = $headers["Authorization"];
            }
            if (isset($token) && !empty($token)) {
                $tokenFromStringToken = Token::create($token);
                $dataTab = $tokenFromStringToken->decoded;
            }
        }
    }
}
