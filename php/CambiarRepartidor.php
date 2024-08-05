<?php
session_start();
include 'conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $idPedido = $_POST['idPedido'];
    $select = $_POST['select'];

    $Conexion->ejecutar("call asignar_repartidor($idPedido, $select)");
}

?>