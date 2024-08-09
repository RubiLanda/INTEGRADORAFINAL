<?php 
include '../php/conexion.php';
$conexion=new Database();
$conexion->conectarBD();
extract($_POST);
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
$conexion->ejecutar("CALL INSERTAR_REPARTIDORES('$usertrim', '$contraencri', '$nomtrim', '$patetrim', '$matetrim', '$naci', '$gene', '$teletrim', '$folitrim','$ingreso', @mensaje)");
$consulta=$conexion->selectConsulta("SELECT @mensaje as resultado");
$mensaje = $consulta[0]->resultado;
echo $mensaje;

?>
