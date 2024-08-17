<?php
include 'conexion.php';
$conexion=new Database();
$conexion->conectarBD();

$id = $_POST['ID'];

$conexion->ejecutar("call Cancelar_Pedidos_Tienda_Pendiente($id)");


?>