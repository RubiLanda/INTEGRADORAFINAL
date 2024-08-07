<?php
session_start();
include 'conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $id_usuario = $_SESSION['ID'];
    $ID = $_POST['id_producto'];
    $cantidad = $_POST['cantidad'];
    $verificarStock = $_POST['mostrarStock'];
    switch ($_POST['tipo']) {
        case 1:
            $Conexion->ejecutar("call Insertar_Modificar_Carrito('$id_usuario', '$ID', '$cantidad', '$verificarStock')");
            break;
        case 2:
            $Conexion->ejecutar("call Cancelar_Carrito('$id_usuario', '$ID')");
            break;
    }
}

?>