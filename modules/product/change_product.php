<?php
require_once "../../php/database.php";
require_once "../../php/web_token.php";
date_default_timezone_set("America/Mexico_City");
$database = new Database();
$webToken = new WebToken();
$connection = $database->conexion();
$dateTime = date("Y-m-d H:i:s");
$id_product = $_POST["id"];
$jwt = $_POST["jwt"];
$jwt = $webToken->decode($jwt);
$query = "SELECT * FROM `product` WHERE `id_product` = $id_product";
$row = mysqli_fetch_assoc(mysqli_query($connection, $query));
$response["barcode"] = $row["barcode"];
print json_encode($response);