<?php
session_start();
include 'conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $fecha = $_POST['fecha'];
    $fecha_limite = $Conexion->selectConsulta("call Calcular_Fecha_Limite($fecha);");
    $resultado = $fecha_limite[0]->Fecha;
}

?>