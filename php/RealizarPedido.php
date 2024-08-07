<?php
session_start();
include 'conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $id_usuario = $_SESSION['ID'];
    $fecha = $_POST['fecha'];
    $idTienda = $_POST['idTienda'];
    $verificarStock = $_POST['mostrarStock'];
    $Conexion->ejecutar("call Realizar_Pedido('$id_usuario', '$fecha', '$idTienda', '$verificarStock', @mensaje)");

    $consulta = $Conexion->selectConsulta("SELECT @mensaje as resultado");
    $mensaje = $consulta[0]->resultado;
    echo $mensaje;
}

?>