<?php
require_once "../../php/database.php";
require_once "../../php/web_token.php";
date_default_timezone_set("America/Mexico_City");
$database = new Database();
$webToken = new WebToken();
$connection = $database->conexion();
$dateTime = date("Y-m-d H:i:s");
$jwt = $_POST["jwt"];
$jwt = $webToken->decode($jwt);
$query = "SELECT * FROM `model`";
$result = mysqli_query($connection, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $response["results"][] = array('id' => $row["id_model"], 'name' => $row["name"]);
}
$response["status"] = "ok";
print json_encode($response);