<?php
include 'conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();
extract($_POST);

if($_POST){
$NomR = trim($nombre);
$ApellidoPa = trim($ApellidoP);
$ApellidoMa = trim($ApellidoM);
$TelefonoS = trim($Telefono);
$FolioConduciR = trim($FolioConducir);
$Conexion->ejecutar("CALL Actualizar_Informacion_Repartidor('$ID','$NomR','$ApellidoPa','$ApellidoMa','$TelefonoS','$FolioConduciR', @message)");
$consulta = $Conexion->selectConsulta("SELECT @message as mensajito");
$mensaje = $consulta[0]->mensajito;
echo $mensaje;
}