<?php
  include '../php/conexion.php';
  $Conexion= new Database();
  $Conexion->conectarBD();
  $imagenPuesto = $_POST['imagenPuesto'];
  if ($imagenPuesto == 1) {
     $DireccionTemporal = $_FILES['imagen']['tmp_name'];
     $NombreArchivo = basename($_FILES['imagen']['name']);
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
       $Conexion->ejecutar("CALL NUEVO_PRODUCTO('$panombre2','$pancategoria2','$NombreArchivo',$panprecio2,'$pandescripcion2',@mensaje)");
       $consulta = $Conexion->selectConsulta("SELECT @mensaje as resultado");
       $mensaje = $consulta[0]->resultado;
       echo $mensaje;
    }
 }
 else {
   $Conexion->ejecutar("CALL NUEVO_PRODUCTO('$panombre2','$pancategoria2', '',$panprecio2,'$pandescripcion2',@mensaje)");
   $consulta = $Conexion->selectConsulta("SELECT @mensaje as resultado");
   $mensaje = $consulta[0]->resultado;
   echo $mensaje;
 }
  
  
?>
 