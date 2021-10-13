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
$search_jwt = $_POST["search"];
$search_token = $webToken->decode($search_jwt);
$collection = $search_token->collection;
$model = $search_token->model;
$category = $search_token->category;
$search = $search_token->search;
$limit = $_POST["limit"];
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
$where .= " AND `photo`.`order_priority` = 1 GROUP BY `group_photo`.`id_group` ORDER BY `group_photo`.`id_group` DESC";
$query = "SELECT `product`.`id_product`, `product`.`id_group`, `group_photo`.`id_collection`, `group_photo`.`id_model`, `product`.`id_category`, `photo`.`id_photo`, `product`.`name`, `product`.`barcode`, `photo`.`asset_id`, `photo`.`original_filename`, `photo`.`url` FROM `product` INNER JOIN `photo` ON `product`.`id_group` = `photo`.`id_group` INNER JOIN `group_photo` ON `product`.`id_group` = `group_photo`.`id_group`".$where.$limit;
$response['query'][] = $query;
$result = mysqli_query($connection, $query);
$response['results'] = array();
while ($row = mysqli_fetch_assoc($result)) {
    if (!isset($response["results"][$row["id_group"]])) {
        $response["results"][$row["id_group"]] = array("id_group" => $row["id_group"], "url" => $row["url"]);
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
$response['query'][] = $query;
$result = mysqli_query($connection,$query);
$rows = mysqli_num_rows($result);
if ($rows > 0) {
    $response["after"] = "on";
} else {
    $response["after"] = "off";
}
$response["status"] = "ok";
print json_encode($response);