
<?php
  include '../php/conexionBD.php';
  $conexion = new Database();
  $conexion->conectarBD();
  extract($_POST);
     if($_POST){             //estas variables $ vienen de los .value del script AJAX
      $conexion->ejecutar("CALL actualizarproducto('$IDPorducto', '$nombre','$precio','$descripcion', @mensaje)");
      $consulta = $conexion->selectConsulta("SELECT @mensaje as resultado");
      $mensaje = $consulta[0]->resultado;
      echo $mensaje;
     }
?>