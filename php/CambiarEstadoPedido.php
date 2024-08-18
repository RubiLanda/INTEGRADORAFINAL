<?php
session_start();
include 'conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $idPedido = $_POST['idPedido'];
    $estado = $_POST['estado'];

    switch ($estado) {
        case 1:
            $Conexion->ejecutar("call cambiar_estado($idPedido, 'aceptado')");
            break;
        case 2:
            $Conexion->ejecutar("call cambiar_estado($idPedido, 'cancelado')");
            break;
        case 3:
            $Conexion->ejecutar("call cambiar_estado($idPedido, 'pendiente a pagar')");
            break;
        case 4:
            $Conexion->ejecutar("call cambiar_estado($idPedido, 'entregado')");
            break;
    }

}

?>