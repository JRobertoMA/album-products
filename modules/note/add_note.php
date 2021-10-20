<?php
require_once "../../php/database.php";
require_once "../../php/web_token.php";
date_default_timezone_set("America/Mexico_City");
$database = new Database();
$webToken = new WebToken();
$connection = $database->conexion();
$dateTime = date("Y-m-d H:i:s");
$product = $_POST["product"];
$barcode = $_POST["barcode"];
$note = $_POST["note"];
$id_group = $_POST["group"];
$jwt = $_POST["jwt"];
$jwt = $webToken->decode($jwt);
$query = "INSERT INTO `note` (`id_note`, `id_user`, `id_group`, `id_product`, `note`, `read`, `date_add`) VALUES (NULL, '$jwt->id_user', '$id_group', '$product', '$note', '0', '$dateTime')";
if (mysqli_query($connection, $query)) {
    $id_note = mysqli_insert_id($connection);
    $response["status"] = "ok";
    $query = "UPDATE `product` SET `barcode` = '$barcode' WHERE `product`.`id_product` = $product";
    mysqli_query($connection, $query);
    $query = "SELECT `note`.`id_note`, `note`.`note`, `note`.`date_add`, `product`.`name`, `product`.`barcode` FROM `note` INNER JOIN `product` ON `note`.`id_product` = `product`.`id_product` WHERE `note`.`id_note` = $id_note";
    $row = mysqli_fetch_assoc(mysqli_query($connection, $query));
    $response["note"] = array("id_note" => $row["id_note"], "note" => $row["note"], "name" => $row["name"], "barcode" => $row["barcode"], "date_add" => $row["date_add"]);
} else {
    $response["status"] = "error";
    $response["status"] = "Verifica la información";
}
print json_encode($response);