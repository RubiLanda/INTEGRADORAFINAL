<?php
     include '../php/conexion.php';
     $Conexion = new Database();
     $Conexion->conectarBD();
     extract($_POST);
     if($_POST){
          $NOMBRE2 = trim($nombre);
          $APELLIDOP2 = trim($ApellidoP);
          $APELLIDOM2 = trim($ApellidoM);
          $TELEFONO2 = trim($Telefono);
         $Conexion->ejecutar("CALL actualizarinfo('$ID','$NOMBRE2','$APELLIDOP2','$APELLIDOM2','$TELEFONO2', @mensaje)");
         $consulta = $Conexion->selectConsulta("SELECT @mensaje as resultado");
         $mensaje = $consulta[0]->resultado;
         echo $mensaje;
     }

?>