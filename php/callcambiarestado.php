<?php
session_start();
include 'conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();
if ($_POST){
    $id = $_POST['id'];
    $estado = $_POST['estado'];

    switch ($estado) {
        case 1:
            $Conexion->ejecutar("call cambiar_estado($id, 'entregado')");
            break;
        case 2:
            $Conexion->ejecutar("call cambiar_estado($id, 'cancelado')");
            break;
        
    }

}

?>