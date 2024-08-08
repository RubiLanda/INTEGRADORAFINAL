<?php

session_start();
require '../php/conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();

$id_usuario = $_SESSION['ID'];

$Conexion->ejecutar("call Verificar_Carrito($id_usuario, @mensaje)");

$consulta = $Conexion->selectConsulta("SELECT @mensaje as resultado");
$mensaje = $consulta[0]->resultado;
echo $mensaje;

?>