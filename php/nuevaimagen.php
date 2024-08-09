<?php 
    include '../php/conexion.php';
    $Conexion = new Database();
    $Conexion->conectarBD();
   $DireccionTemporal = $_FILES['imagen']['tmp_name'];
   $NombreArchivo = $_FILES['imagen']['name'];
   // $DireccionTemporal = '/var/www/html/img/temporal'.$NombreArchivo;
   $Direccion = 'var/www/html/img/productos/';
   $DireccionConImagen = $Direccion.$NombreArchivo;

   $id = $_POST['id'];
   $Imagenes = $Conexion->selectConsulta("SELECT * FROM PRODUCTOS WHERE PRODUCTOS.id_producto = $id");
   $ImagenAntigua = $Imagenes[0]->imagen;

   if($ImagenAntigua && $ImagenAntigua !== $NombreArchivo){
      $DireccionAntigua = $Direccion . $ImagenAntigua;
      if (file_exists($DireccionAntigua)){
         unlink($DireccionAntigua);
      }
   }

   if (move_uploaded_file($DireccionTemporal,$DireccionConImagen)){
      $Conexion->ejecutar("CALL Modificar_Imagen_Producto($id,'$NombreArchivo',@mensaje)");
      $consulta = $Conexion->selectConsulta("SELECT @mensaje as resultado");
      $mensaje = $consulta[0]->resultado;
      echo $mensaje;
   }
?>