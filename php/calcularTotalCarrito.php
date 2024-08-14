<?php
session_start();
include 'conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $id_usuario = $_SESSION['ID'];
    
    $resultado = $Conexion->selectConsulta("call Calcular_Total_Carrito($id_usuario)");
    echo $resultado[0]->Total;
}

?>