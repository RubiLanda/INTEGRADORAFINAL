<?php
include 'conexion.php';
$conexion=new Database();
$conexion->conectarBD();

$id = $_POST['ID'];

$resultado = $conexion->selectConsulta("call Contar_Pedidos_Tienda_Aceptados($id)");

echo $resultado[0]->Total;


// ContarPedidoTiendaAceptados

?>