<?php 
    include '../php/conexion.php';
    $conexion = new Database();
    $conexion->conectarBD();
   $DireccionTemporal = $_FILES['imagen']['tmp_name'];
   $NombreArchivo = $_FILES['imagen']['name'];
   $Direccion = '../img/productos/';
   $DireccionConImagen = $Direccion.$NombreArchivo;

   $id = $_POST['id'];
   $Imagenes = $conexion->selectConsulta("SELECT * FROM REPARTIDORES WHERE id_producto = $id");
   $ImagenAntigua = $Imagenes[0]->imagen;

   if($ImagenAntigua && $ImagenAntigua !== $NombreArchivo){
      $DireccionAntigua = $Direccion . $ImagenAntigua;
      if (file_exists($DireccionAntigua)){
       unlink($DireccionAntigua);
      }
   }
   
   if (move_uploaded_file($DireccionTemporal,$DireccionConImagen)){
      $conexion->ejecutar("CALL Modificar_Imagen_Producto($id,'$NombreArchivo',@mensaje)");
      $consulta = $conexion->selectConsulta("SELECT @mensaje as resultado");
      $mensaje = $consulta[0]->resultado;
      echo $mensaje;
   }
?>