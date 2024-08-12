<?php
include '../php/conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();

if (isset($_GET['usuario']) && isset($_GET['password'])){
    $usuario = $_GET['usuario'];
    $contraseña = $_GET['password'];
    $Conexion->verifica($usuario, $contraseña);

    if (isset($_SESSION['Rol']))
    {
        switch ($_SESSION['Rol'])
        {
            case 1:
                header("Location: ../views/AdministradorVerPedidos.php");
                break;
            case 2:
                header("Location: ../views/ClienteRealizarPedido.php");
                break;
            case 3:
                header("Location: ../views/mispedidosrepa.php");
                break;
        }
    }
}
else {
    extract($_POST);
    if ($_POST)
    {
        if ($usuario != '' && $contraseña != '') {
            $mensaje = $Conexion->verifica($usuario, $contraseña);
            echo $mensaje;
        }
        else {
            echo "No dejes campos vacios.";
        }
    }
}


?>