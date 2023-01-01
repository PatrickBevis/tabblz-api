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
        
            $valueRS = str_replace($this->id,":id", $valueRS);
        
        $bp=true;
        // A suivre ...

        foreach ($restrictedRoutes as $key => $value) {

            
                $restricted = str_replace(":id", $this->id, $key);
                $bp=true;
            
            
            if ($restricted == $request->routeSlashed) {
                $this->condition = $value;
                $bp=true;
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
                $tokenFromEncodedString = Token::create($token);
                $test = $tokenFromEncodedString->isValid();

                if ($test == true) {
                    return true;
                }
            }

            header('HTTP/1.0 401 Unauthorized');
            die;
        }

        return true;
    }
}
