<?php
     
     include '../php/conexionBD.php';
     $conexion = new Database();
     $conexion->conectarBD();
     extract($_POST);
     if($_POST){
        $conexion->ejecutar("CALL NUEVA_CATEGORIA('$nombre',@mensaje)");
        $consulta = $conexion->selectConsulta("SELECT @mensaje as resultado");
        $mensaje = $consulta[0]->resultado;
        echo $mensaje;
      
     }
?>
