<?php 
include '../php/conexion.php';
$conexion=new Database();
$conexion->conectarBD();
$imagenPuesto = $_POST['imagenpuesta'];
  if ($imagenPuesto == 1) {
     $DireccionTemporal = $_FILES['imagen']['tmp_name'];
     $NombreArchivo = basename($_FILES['imagen']['name']);
     $Direccion = '../img/infopersonal/';
     $DireccionConImagen = $Direccion.$NombreArchivo;
  }
  $nameuser=$_POST['nameuser'];
  $nom=$_POST['nom'];
  $pate=$_POST['pate'];
  $mate=$_POST['mate'];
  $contra=$_POST['contra'];
  $vericontra=$_POST['vericontra'];
  $gene=$_POST['gene'];
  $naci=$_POST['naci'];
  $tele=$_POST['tele'];
  $ingreso=$_POST['ingreso'];
  $folio=$_POST['folio'];
  

$usertrim=trim($nameuser);
$nomtrim=trim($nom);
$patetrim=trim($pate);
$matetrim=trim($mate);
$teletrim=trim($tele);
$folitrim=trim($folio);
if(trim($naci)==''){
    $naci='0000-00-00';
}
if(trim($ingreso)==''){
    $ingreso='0000-00-00';
}

$contraencri= password_hash($contra, PASSWORD_DEFAULT);

if ($contra == $vericontra ) {
    if ($imagenPuesto == 1) {
        if (move_uploaded_file($DireccionTemporal,$DireccionConImagen)){
            $conexion->ejecutar("CALL INSERTAR_REPARTIDORES('$usertrim', '$contraencri','$nomtrim', '$patetrim', '$matetrim', '$naci', '$gene', '$teletrim', '$folitrim','$ingreso','$NombreArchivo', @mensaje)");
            $consulta=$conexion->selectConsulta("SELECT @mensaje as resultado");
            $mensaje = $consulta[0]->resultado;
            echo $mensaje;
        }
        }
        else{
            $conexion->ejecutar("CALL INSERTAR_REPARTIDORES('$usertrim', '$contraencri','$nomtrim', '$patetrim', '$matetrim', '$naci', '$gene', '$teletrim', '$folitrim','$ingreso','' ,@mensaje)");
            $consulta=$conexion->selectConsulta("SELECT @mensaje as resultado");
            $mensaje = $consulta[0]->resultado;
            echo $mensaje;
        }
     }
     else {
        echo "Las contraseñas no coinciden";
    }

    

  

?>
