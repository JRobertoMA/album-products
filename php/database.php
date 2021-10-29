<?php
class DATABASE {
    public function conexion() {
        $conexion = mysqli_connect('localhost', 'root', 'TenzaZangetsu$16', 'photos');
        if (mysqli_connect_errno($conexion)) {
            return mysqli_connect_error();
        } else {
            mysqli_set_charset($conexion, 'utf8mb4');
            return $conexion;
        }
    }
}