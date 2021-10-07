<?php
require_once "../../php/database.php";
require_once "../../php/web_token.php";
date_default_timezone_set("America/Mexico_City");
$database = new Database();
$webToken = new WebToken();
$connection = $database->conexion();
$id_product = $_POST["id_product"];
$jwt = $_POST["jwt"];
$jwt = $webToken->decode($jwt);
$query = "DELETE FROM `product` WHERE `product`.`id_product` = $id_product";
if (mysqli_query($connection, $query)) {
    $id = mysqli_insert_id($connection);
    $response["status"] = "ok";
    $response["answer"] = "¡Producto removido con exito!";
    $response["subAnswer"] = "";
} else {
    $response["status"] = "error";
    $response["answer"] = "Ocurrio un error";
    $response["subAnswer"] = "Verifica tu información y vuelve a intentarlo";
}
print json_encode($response);