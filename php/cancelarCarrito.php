<?php
session_start();
include 'conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $id_usuario = $_SESSION['ID'];
    $Conexion->ejecutar("call Cancelar_Todo_Carrito('$id_usuario')");
}

?>