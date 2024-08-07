<?php
     
     include '../php/conexion.php';
     $Conexion = new Database();
     $Conexion->conectarBD();
     extract($_POST);
     if($_POST){
        $Conexion->ejecutar("CALL NUEVA_CATEGORIA('$nombre',@mensaje)");
        $consulta = $Conexion->selectConsulta("SELECT @mensaje as resultado");
        $mensaje = $consulta[0]->resultado;
        echo $mensaje;
      
     }
?>
