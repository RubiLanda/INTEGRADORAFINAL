
<?php
  include '../php/conexion.php';
  $Conexion = new Database();
  $Conexion->conectarBD();
  extract($_POST);
     if($_POST){             //estas variables $ vienen de los .value del script AJAX
      
      $Conexion->ejecutar("CALL actualizarproducto('$IDPorducto', '$nombre','$precio','$descripcion', @mensaje)");
      $consulta = $Conexion->selectConsulta("SELECT @mensaje as resultado");
      $mensaje = $consulta[0]->resultado;
      echo $mensaje;
     }
?>