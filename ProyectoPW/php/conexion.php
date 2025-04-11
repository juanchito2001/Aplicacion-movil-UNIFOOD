<?php
$conexion = mysqli_connect("localhost", "root", "", "unifoodbd");

if (!$conexion) {
    die('No se pudo conectar a la base de datos: ' . mysqli_connect_error());
}
?>
