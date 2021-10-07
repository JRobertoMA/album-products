<?php
require_once "../../php/database.php";
require_once "../../php/web_token.php";
date_default_timezone_set("America/Mexico_City");
$database = new Database();
$webToken = new WebToken();
$connection = $database->conexion();
$dateTime = date("Y-m-d H:i:s");
$id_group = $_POST["id"];
$id_collection = $_POST["collection"];
$id_model = $_POST["model"];
$id_products = $_POST["id_product"];
$name_products = $_POST["name_product"];
$code_products = $_POST["code_product"];
$category_products = $_POST["category_product"];
$jwt = $_POST["jwt"];
$jwt = $webToken->decode($jwt);
$query = "UPDATE `group_photo` SET `id_collection` = '$id_collection', `id_model` = '$id_model' WHERE `group_photo`.`id_group` = $id_group";
if (mysqli_query($connection, $query)) {
    for ($i=0; $i < count($name_products); $i++) {
        $id_category = $category_products[$i];
        $name = $name_products[$i];
        $barcode = $code_products[$i];
        $id_product_form = explode("-", $id_products[$i]);
        if (count($id_product_form) > 1) {
            $id_product_form = $id_product_form[1];
            $query = "INSERT INTO `product` (`id_product`, `id_group`, `id_category`, `name`, `barcode`, `date_add`) VALUES (NULL, '$id_group', '$id_category', '$name', '$barcode', '$dateTime')";
            mysqli_query($connection, $query);
            $id_product = mysqli_insert_id($connection);
            $response["products"][] = array("id_product_form" => $id_product_form, "id_product" => $id_product);
        } else {
            $id_product_form = $id_product_form[0];
            $query = "UPDATE `product` SET `id_category` = '$id_category', `name` = '$name', `barcode` = '$barcode' WHERE `product`.`id_product` = $id_product_form";
            mysqli_query($connection, $query);
        }
    }
    $response["status"] = "ok";
    $response["answer"] = "¡Grupo actualizado con exito!";
    $response["subAnswer"] = "Puedes empezar la carga de fotos";
} else {
    $response["status"] = "error";
    $response["answer"] = "Ocurrio un error";
    $response["subAnswer"] = "Verifica tu información y vuelve a intentarlo";
}
print json_encode($response);