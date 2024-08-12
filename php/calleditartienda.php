<?php
    include '../php/conexion.php';
    $conexion=new Database();
    $conexion->conectarBD();
    extract($_POST);
    if($_POST){
        $nombretrim=trim($nombre);
        $diretrim=trim($direccion);

        $conexion->ejecutar("CALL EDITAR_TIENDA('$IDTI','$nombretrim','$diretrim',@mensaje)");
        $consulta=$conexion->selectConsulta("SELECT @mensaje as resultado");
        $mensaje = $consulta[0]->resultado;
        echo $mensaje;
    
    }
?>