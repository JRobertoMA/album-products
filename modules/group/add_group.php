<?php
require_once "../../php/database.php";
require_once "../../php/web_token.php";
date_default_timezone_set("America/Mexico_City");
$database = new Database();
$webToken = new WebToken();
$connection = $database->conexion();
$dateTime = date("Y-m-d H:i:s");
$id_collection = $_POST["collection"];
$id_model = $_POST["model"];
$id_products = $_POST["id_product"];
$name_products = $_POST["name_product"];
$code_products = $_POST["code_product"];
$category_products = $_POST["category_product"];
$jwt = $_POST["jwt"];
$jwt = $webToken->decode($jwt);
if ($jwt->id_user == 1) {
    $query = "INSERT INTO `group_photo` (`id_group`, `id_collection`, `id_model`, `date_add`) VALUES (NULL, '$id_collection', '$id_model', '$dateTime')";
    if (mysqli_query($connection, $query)) {
        $id_group = mysqli_insert_id($connection);
        $response["id_group"] = $id_group;
        for ($i=0; $i < count($name_products); $i++) {
            $id_category = $category_products[$i];
            $name = $name_products[$i];
            $barcode = $code_products[$i];
            $id_product_form = explode("-", $id_products[$i]);
            $id_product_form = $id_product_form[1];
            $query = "INSERT INTO `product` (`id_product`, `id_group`, `id_category`, `name`, `barcode`, `date_add`) VALUES (NULL, '$id_group', '$id_category', '$name', '$barcode', '$dateTime')";
            mysqli_query($connection, $query);
            $id_product = mysqli_insert_id($connection);
            $response["products"][] = array("id_product_form" => $id_product_form, "id_product" => $id_product);
        }
        $response["status"] = "ok";
        $response["answer"] = "¡Grupo agregado con exito!";
        $response["subAnswer"] = "Puedes empezar la carga de fotos";
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