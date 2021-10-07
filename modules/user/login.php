<?php
require_once "../../php/database.php";
require_once "../../php/web_token.php";
require_once "../../php/password.php";
date_default_timezone_set("America/Mexico_City");
$database = new Database();
$pass = new Password();
$webToken = new WebToken();
$connection = $database->conexion();
$dateTime = date("Y-m-d H:i:s");
$email = $_POST["email"];
$password = $_POST["password"];
$uuid = $_POST["uuid"];
$query = "SELECT * FROM `user` WHERE `email` = '$email'";
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $hash = $row["pass"];
    $verifyPassword = $pass->verify($password, $hash);
    if ($verifyPassword["status"]) {
        $id_user = intval($row["id_user"]);
        if ($verifyPassword["change"]) {
            $hash = $verifyPassword["hash"];
            $query = 'UPDATE `user` SET `pass` = "' . $hash . '" WHERE `user`.`id_user` = ' . $id_user;
            mysqli_query($connection, $query);
        }
        $response["status"] = "ok";
        $jwt = array("id_user" => $id_user);
        $response["jwt"] = $webToken->encode($jwt, $uuid);
    } else {
        $response["status"] = "error";
        $response["answer"] = "Contraseña incorrecta";
        $response["subAnswer"] = "Verificala y vuelve a intentarlo";
    }
} else {
    $response["status"] = "error";
    $response["answer"] = "Correo no econtrado";
    $response["subAnswer"] = "Verificalo y vuelve a intentarlo";
}
print json_encode($response);