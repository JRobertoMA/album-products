<?php
require_once "../../php/database.php";
require_once "../../php/web_token.php";
date_default_timezone_set("America/Mexico_City");
$database = new Database();
$webToken = new WebToken();
$connection = $database->conexion();
$dateTime = date("Y-m-d H:i:s");
$name = mysqli_real_escape_string($connection, $_POST["name"]);
$date = $_POST["date"];
$jwt = $_POST["jwt"];
$jwt = $webToken->decode($jwt);
if ($jwt->id_user == 1) {
    $query = "INSERT INTO `collection` (`id_collection`, `name`, `date`, `date_add`) VALUES (NULL, '$name', '$date', '$dateTime') ";
    if (mysqli_query($connection, $query)) {
        $id = mysqli_insert_id($connection);
        $response["status"] = "ok";
        $response["answer"] = "¡Colección agregada con exito!";
        $response["subAnswer"] = "";
    } else {
        $response["status"] = "error";
        $response["answer"] = "Ocurrio un error";
        $response["subAnswer"] = "Verifica tu información y vuelve a intentarlo";
    }
} else {
    $response["status"] = "error";
    $response["answer"] = "No eres admin";
    $response["subAnswer"] = "";
}

print json_encode($response);
