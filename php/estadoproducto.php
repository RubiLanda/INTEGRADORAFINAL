<?php
     include '../php/conexionBD.php';
     $conexion= new Database();
     $conexion->conectarBD();
     extract($_POST);
     if($_POST){
         $conexion->ejecutar("CALL baja_productos('$id_producto','$Estado', @mensaje)");
         $consulta = $conexion->selectConsulta("SELECT @mensaje as resultado");
         $mensaje = $consulta[0]->resultado;
         echo $mensaje;
     }

?>