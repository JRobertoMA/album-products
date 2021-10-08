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
$collection = $_POST["collection"];
$model = $_POST["model"];
$category = $_POST["category"];
$search = $_POST["search"];
$limit = $_POST["limit"];
$search_token = array("collection" => $collection, "model" => $model, "category" => $category, "search" => $search);
$response["search"] = $webToken->encode($search_token, "");
if ($limit == "" || $limit == "1" || $limit == "0") {
    $limit = " LIMIT 24";
    $limit_val = 24;
} else {
    $limit_val = intval($limit);
    $limit = intval($limit) - 1;
    $limit = $limit * 24;
    $limit_val = $limit_val*24;
    $limit = " LIMIT $limit,24";
}
if ($collection == "") {
    $wheres[] = "";
} else {
    $wheres[] = "`id_collection` = '$collection'";
}
if ($model == "") {
    $wheres[] = "";
} else {
    $wheres[] = "`id_model` = '$model'";
}
if ($category == "") {
    $wheres[] = "";
} else {
    $wheres[] = "`id_category` = '$category'";
}
if ($search == "") {
    $wheres[] = "";
} else {
    $wheres[] = "(`name` LIKE '%$search%' OR `barcode` LIKE '%$search%')";
}
$where = "";
for ($i=0; $i < 4; $i++) { 
    if ($wheres[$i] != "") {
        if ($where == "") {
            $where = " WHERE ".$wheres[$i];
        } else {
            $where .= " AND ".$wheres[$i];
        }
    }
}
$models = array();
$query = 'SELECT * FROM `model`';
$result = mysqli_query($connection, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $models[$row['id_model']] = $row['name'];
}
$collections = array();
$query = 'SELECT * FROM `collection`';
$result = mysqli_query($connection, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $collections[$row['id_collection']] = $row['name'];
}
$where .= " GROUP BY `group_photo`.`id_group` ORDER BY `group_photo`.`id_group` DESC";
$query = "SELECT `product`.`id_product`, `product`.`id_group`, `group_photo`.`id_collection`, `group_photo`.`id_model`, `product`.`id_category`, `photo`.`id_photo`, `product`.`name`, `product`.`barcode`, `photo`.`asset_id`, `photo`.`original_filename`, `photo`.`url` FROM `product` INNER JOIN `photo` ON `product`.`id_group` = `photo`.`id_group` INNER JOIN `group_photo` ON `product`.`id_group` = `group_photo`.`id_group`".$where.$limit;
$result = mysqli_query($connection, $query);
$response['results'] = array();
while ($row = mysqli_fetch_assoc($result)) {
    if (!isset($response["results"][$row["id_group"]])) {
        $query = 'SELECT COUNT(id_product) AS `products` FROM `product` WHERE id_group = '.$row['id_group'];
        $row_p = mysqli_fetch_assoc(mysqli_query($connection, $query));
        $response["results"][$row["id_group"]] = array("id_group" => $row["id_group"], "url" => $row["url"], 'original_filename' => $row['original_filename'], 'products' => $row_p['products'], 'model' => $models[$row['id_model']], 'collection' => $collections[$row['id_collection']]);
    }
}
if ($limit_val > 24) {
    $response["before"] = "on";
    $response["before_count"] = intval((($limit_val-1)/24));
} else {
    $response["before"] = "off";
}
$response["after_count"] = intval($limit_val/24) + 1;
$limit = "LIMIT $limit_val,24";
$query = "SELECT `product`.`id_product`, `product`.`id_group`, `group_photo`.`id_collection`, `group_photo`.`id_model`, `product`.`id_category`, `photo`.`id_photo`, `product`.`name`, `product`.`barcode`, `photo`.`asset_id`, `photo`.`original_filename`, `photo`.`url` FROM `product` INNER JOIN `photo` ON `product`.`id_group` = `photo`.`id_group` INNER JOIN `group_photo` ON `product`.`id_group` = `group_photo`.`id_group`".$where." $limit";
$result = mysqli_query($connection,$query);
$rows = mysqli_num_rows($result);
if ($rows > 0) {
    $response["after"] = "on";
} else {
    $response["after"] = "off";
}
$response["status"] = "ok";
print json_encode($response);