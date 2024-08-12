<?php 
include '../php/conexion.php';
$conexion=new Database();
$conexion->conectarBD();
session_start();
 $persona = $_SESSION['ID'];
extract($_POST);

$nomtrim=trim($tienda);
$diretrim=trim($direccion);

$conexion->ejecutar("CALL ALTA_TIENDAS('$persona', '$nomtrim', '$diretrim', @mensaje)");
$consulta=$conexion->selectConsulta("SELECT @mensaje as resultado");
$mensaje = $consulta[0]->resultado;
echo $mensaje;
?>
