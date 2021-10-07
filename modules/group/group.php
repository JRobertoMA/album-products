<?php
require_once "../../php/database.php";
require_once "../../php/web_token.php";
date_default_timezone_set("America/Mexico_City");
$database = new Database();
$webToken = new WebToken();
$connection = $database->conexion();
$dateTime = date("Y-m-d H:i:s");
$id_group = $_POST["group"];
$jwt = $_POST["jwt"];
$jwt = $webToken->decode($jwt);
$query = "SELECT `collection`.`name` AS 'collection', `model`.`name` AS 'model' FROM `group_photo` INNER JOIN `collection` ON `group_photo`.`id_collection` = `collection`.`id_collection` INNER JOIN `model` ON `group_photo`.`id_model` = `model`.`id_model` WHERE `id_group` = $id_group";
$row = mysqli_fetch_assoc(mysqli_query($connection, $query));
$response["collection"] = $row["collection"];
$response["model"] = $row["model"];
$query = "SELECT * FROM `photo` WHERE `id_group` = $id_group";
$result = mysqli_query($connection, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $response["photo"][] = array("original_filename" => $row["original_filename"], "url" => $row["url"]);
}
$query = "SELECT * FROM `product` WHERE `id_group` = $id_group";
$result = mysqli_query($connection, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $response["product"][] = array("id_product" => $row["id_product"], "name" => $row["name"], "barcode" => $row["barcode"]);
}
$query = "SELECT `note`.`id_note`, `note`.`note`, `note`.`date_add`, `product`.`name`, `barcode` FROM `note` INNER JOIN `product` ON `note`.`id_product` = `product`.`id_product` WHERE `note`.`id_group` = $id_group";
$result = mysqli_query($connection, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $response["note"][] = array("id_note" => $row["id_note"], "note" => $row["note"], "name" => $row["name"], "barcode" => $row["barcode"], "date_add" => $row["date_add"]);
}
print json_encode($response);