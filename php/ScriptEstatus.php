<?php 
include '../php/conexion.php';
$conexion = new Database();
$conexion->conectarBD();
extract($_POST);
if($_POST){
    $conexion->ejecutar("CALL Habilitar_Deshabilitar_Administrador('$ID','$Estado',@message)");
    $consulta = $conexion->selectConsulta("SELECT @message as resultado");
    $mensaje = $consulta[0]->resultado;
    echo $mensaje;
}
?>