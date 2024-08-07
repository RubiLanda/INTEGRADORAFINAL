<?php
  include '../php/conexionBD.php';
  $conexion= new Database();
  $conexion->conectarBD();
  $imagenPuesto = $_POST['imagenPuesto'];
  if ($imagenPuesto == 1) {
     $DireccionTemporal = $_FILES['imagen']['tmp_name'];
     $NombreArchivo = $_FILES['imagen']['name'];
     $Direccion = '../img/productos/';
     $DireccionConImagen = $Direccion.$NombreArchivo;
  }
  $panombre=$_POST['nombre'];
  $pancategoria=$_POST['categoria'];
  $panprecio=$_POST['precio'];
  $pandescripcion=$_POST['descripcion'];
  $panombre2 = trim($panombre);
  $pancategoria2=trim($pancategoria);
  $panprecio2 = trim($panprecio);
  $pandescripcion2 = trim($pandescripcion);

  
 if ($imagenPuesto == 1) {
    if (move_uploaded_file($DireccionTemporal,$DireccionConImagen)){
       $conexion->ejecutar("CALL NUEVO_PRODUCTO('$panombre2','$pancategoria2','$NombreArchivo','$panprecio2','$pandescripcion2',@mensaje)");
       $consulta = $conexion->selectConsulta("SELECT @mensaje as resultado");
       $mensaje = $consulta[0]->resultado;
       echo $mensaje;
    }
 }
 else {
   $conexion->ejecutar("CALL NUEVO_PRODUCTO('$panombre2','$pancategoria2', '','$panprecio2','$pandescripcion2',@mensaje)");
   $consulta = $conexion->selectConsulta("SELECT @mensaje as resultado");
   $mensaje = $consulta[0]->resultado;
   echo $mensaje;
 }
  
  
?>
 