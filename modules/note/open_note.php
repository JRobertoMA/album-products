<?php
require_once "../../php/database.php";
require_once "../../php/web_token.php";
date_default_timezone_set("America/Mexico_City");
$database = new Database();
$webToken = new WebToken();
$connection = $database->conexion();
$dateTime = date("Y-m-d H:i:s");
$id_note = $_POST['id_note'];
$jwt = $_POST['jwt'];
$jwt = $webToken->decode($jwt);
$query = "UPDATE `note` SET `read` = '1' WHERE `note`.`id_note` = $id_note";
if (mysqli_query($connection, $query)) {
    $response["status"] = "ok";
} else {
    $response["status"] = "error";
    $response["status"] = "Verifica la información";
}
print json_encode($response);