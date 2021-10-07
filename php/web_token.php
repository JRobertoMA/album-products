<?php
require_once __DIR__.'/vendor/autoload.php';

use \Firebase\JWT\JWT;
date_default_timezone_set("America/Mexico_City");

class WebToken {

    private $privateKey = <<<EOD
    -----BEGIN RSA PRIVATE KEY-----

    -----END RSA PRIVATE KEY-----
    EOD;
    private $publicKey = <<<EOD
    -----BEGIN PUBLIC KEY-----

    -----END PUBLIC KEY-----
    EOD;

    public function encode($payload, $device) {
        $issuedAt = time();
        $data = [
            'iat' => $issuedAt,
            'jti' => $device,
            'data' => [
                $payload
            ]
        ];
        return JWT::encode($data, $this->privateKey, 'RS256');
    }

    public function decode($jwt) {
        try {
            $dataNative = JWT::decode($jwt, $this->publicKey, array('RS256'));
            $data = $dataNative->data[0];
            $data->status = "ok";
        } catch (\Throwable $th) {
            $data->message = $th->getMessage();
            $data->status = "error";
        }
        return $data;
    }
}