<?php 
include '../php/conexionBD.php';
$Conexion= new Database();
$Conexion->conectarBD();
extract($_POST);
if($_POST){
    $Conexion->ejecutar("CALL actualizarnombrecat('$id_categoria','$Nombre', @mensaje)");
    $consulta = $Conexion->selectConsulta("SELECT @mensaje as resultado");
    $mensaje = $consulta[0]->resultado;
    echo $mensaje;
}
?>