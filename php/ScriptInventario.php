<?php
include '../php/conexion.php';
$conexion = new DataBase();
$conexion->conectarBD();
extract($_POST);

$conexion->ejecutar("CALL AÑADIR_STOCK('$id_producto', '$cantidad')");

?>