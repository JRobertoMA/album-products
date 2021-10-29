<?php
require_once __DIR__.'/vendor/autoload.php';

use \Firebase\JWT\JWT;
date_default_timezone_set("America/Mexico_City");

class WebToken {

    private $privateKey = <<<EOD
    -----BEGIN RSA PRIVATE KEY-----
    MIICWwIBAAKBgQCi6ph6pTrImUSG0KnFNuNLPbj6NMF+Fuxxrxa/p858LIXLJNYj
    XVuPCh9XstEFl19BvuJ4JPkJmCSMLkovkgRN03aBDqWOZghUqQ0BuoURngGK3AIn
    TviZSfLcA/wZxJ9Qz1k0qqRYYYzcTcFft3TZhOVv+i+jRwzmZF9BbTWnkwIDAQAB
    AoGAM1kdlgpd7LviDVvXJoGWQjDCDJp0Ifm3rF0zHlUVj624vb5uf9b89KiMGGgT
    2V9MdrxjRNriY1PAsS4l07iCAkTXRUHXEZ51pYVG/h5oonP/sK0jBaOzW4K3imLG
    HX9ae3Dsgk/dP736NKG6TH3MvGiE7x/gPf6yI11TEc2Q4oECQQDNapDKR/SbGN3S
    ZZlLaTAVt18FnzecmvsvDodTu4YfKyFjXiVbhaf4vJd1RrWQfFk1IORHOjZiH7WC
    rmZ/tC6HAkEAywjWxbM4wewLOgsb0yolybNojikyoy8HIGgJAonxX1tDtHVeRpk9
    p7+UaaUfPqbgBfwsD5IbAt4YTLDdsi6VlQJARPy3J9hGpBgT12dbtmHbTk/JT+AL
    E0NRfJpKhKqD/s/DZNXngfc/VGAyFabrr1yzsQ4c3HcGcKnpkbv0nIrs3QJAc798
    QYUlDFj3JYYDvOTAWjbvmmweNC2xUGY/DLV7Z7Nt68klj/X40lND1t6N63fTK1ZS
    ACZ5Q68+ByqlmNk56QJARlxzz6GlJKNwt06piBblBhmfGoQzoTMqmDOeiEywc5Pr
    pQzMiODU8a+JXAcym2BhAWSPzRGxys/Ob8e23T4rug==
    -----END RSA PRIVATE KEY-----
    EOD;
    private $publicKey = <<<EOD
    -----BEGIN PUBLIC KEY-----
    MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCi6ph6pTrImUSG0KnFNuNLPbj6
    NMF+Fuxxrxa/p858LIXLJNYjXVuPCh9XstEFl19BvuJ4JPkJmCSMLkovkgRN03aB
    DqWOZghUqQ0BuoURngGK3AInTviZSfLcA/wZxJ9Qz1k0qqRYYYzcTcFft3TZhOVv
    +i+jRwzmZF9BbTWnkwIDAQAB
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