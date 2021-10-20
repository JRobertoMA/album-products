<?php
require_once "../../php/database.php";
require_once "../../php/web_token.php";
date_default_timezone_set("America/Mexico_City");
$database = new Database();
$webToken = new WebToken();
$connection = $database->conexion();
$jwt = $_POST["jwt"];
$jwt = $webToken->decode($jwt);
$query = "SELECT `note`.`id_note`, `note`.`id_group`, `user`.`name`, `product`.`barcode`, `note`.`note`, `note`.`read`, `note`.`date_add`, `photo`.`url`, `photo`.`original_filename` FROM `note` INNER JOIN `product` ON `note`.`id_product` = `product`.`id_product` INNER JOIN `user` ON `note`.`id_user` = `user`.`id_user` INNER JOIN `photo` ON `note`.`id_group` = `photo`.`id_group` WHERE `note`.`read` = 0 GROUP BY `note`.`id_group`";
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) > 0) {
    $response['status'] = 'ok';
} else {
    $response['status'] = 'error';
    $response['answer'] = 'Sin resultados';
}
while ($row = mysqli_fetch_assoc($result)) {
    $response['results'][] = array('id_note' => $row['id_note'], 'id_group' => $row['id_group'], 'name' => $row['name'], 'barcode' => $row['barcode'], 'note' => $row['note'], 'read' => $row['read'], 'date_add' => $row['date_add']);
}
print json_encode($response);