<?php
require_once "../../php/database.php";
require_once "../../php/web_token.php";
date_default_timezone_set("America/Mexico_City");
$database = new Database();
$webToken = new WebToken();
$connection = $database->conexion();
$dateTime = date("Y-m-d H:i:s");
$asset_id = $_POST["asset_id"];
$original_filename = $_POST["original_filename"];
$url = $_POST["url"];
$id_group = $_POST["id_group"];
$jwt = $_POST["jwt"];
$jwt = $webToken->decode($jwt);
$query = "INSERT INTO `photo` (`id_photo`, `id_group`, `asset_id`, `original_filename`, `url`, `date_add`) VALUES (NULL, '$id_group', '$asset_id', '$original_filename', '$url', '$dateTime')";
if (mysqli_query($connection, $query)) {
    $response["status"] = "ok";
    $response["answer"] = "Foto agregada con exito!";
    $response["subAnswer"] = "Puedes empezar la carga de fotos";
} else {
    $response["status"] = "error";
    $response["answer"] = "Ocurrio un error";
    $response["subAnswer"] = "Verifica tu información y vuelve a intentarlo";
}
print json_encode($response);