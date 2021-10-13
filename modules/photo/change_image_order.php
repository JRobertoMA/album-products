<?php
require_once "../../php/database.php";
require_once "../../php/web_token.php";
date_default_timezone_set("America/Mexico_City");
$database = new Database();
$webToken = new WebToken();
$connection = $database->conexion();
$dateTime = date("Y-m-d H:i:s");
$photos = explode(',', $_POST['photos']);
$jwt = $_POST["jwt"];
$jwt = $webToken->decode($jwt);
$contador = 1;
for ($i=0; $i < count($photos); $i++) { 
    $query = 'UPDATE `photo` SET `order_priority` = "'.$contador.'" WHERE `photo`.`asset_id` = "'.$photos[$i].'"';
    $contador++;
    if (mysqli_query($connection, $query)) {
        $response["status"] = "ok";
        $response["answer"] = "¡Grupo actualizado con exito!";
        $response["subAnswer"] = "Puedes empezar la carga de fotos";
    } else {
        $response["status"] = "error";
        $response["answer"] = "Ocurrio un error";
        $response["subAnswer"] = "Verifica tu información y vuelve a intentarlo";
    }
}
print json_encode($response);