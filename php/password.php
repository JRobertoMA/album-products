<?php

class Password {

    private $options = array('cost' => 10);

    public function optimalCost($password) {
        $timeTarget = 0.05;
        $cost = 8;
        do {
            $cost++;
            $from = microtime(true);
            password_hash($password, PASSWORD_DEFAULT, ["cost" => $cost]);
            $to = microtime(true);
        } while (($to - $from) < $timeTarget);
        return $cost;
    }

    public function generateHash($password) {
        return password_hash($password, PASSWORD_DEFAULT, $this->options);
    }

    public function verify($password, $hash) {
        if (password_verify($password, $hash)) {
            if (password_needs_rehash($hash, PASSWORD_DEFAULT, $this->options)) {
                $response["hash"] = password_hash($password, PASSWORD_DEFAULT, $this->options);
                $response["change"] = true;
            } else {
                $response["hash"] = $hash;
                $response["change"] = false;
            }
            $response["status"] = true;
        } else {
            $response["status"] = false;
        }
        return $response;
    }

    public function generateHashID($id) {
        $hash = hash("ripemd128", $id);
        return $hash;
    }
}