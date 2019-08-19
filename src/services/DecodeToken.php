<?php
use \Firebase\JWT\JWT;


   function decodeToken()
    {
        try {
            $headers = apache_request_headers();
            $token = $headers['Authorization'];
            $decoded = JWT::decode($token, "supersecretkeymylogindemo", array('HS256'));
            return $decoded->id;
        } catch (Exception $e) { // Also tried JwtException
            echo "";
        }
    }


