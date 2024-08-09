<?php 
    include '../php/conexion.php';
    $conexion=new Database();
    $conexion->conectarBD();
    extract($_POST);
    if($_POST){
        $conexion->ejecutar("CALL Habilitar_Deshabilitar_Repartidor($ID,$Estado,@mensaje)");
        $consulta=$conexion->selectConsulta("SELECT @mensaje as resultado");
        $mensaje = $consulta[0]->resultado;
        echo $mensaje;
    }
?>