
<?php
include '../class/conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();
extract($_POST);

if ($_POST)
{
    $Conexion->verifica($usuario, $contraseña);
    if (isset($_SESSION['Rol']))
    {
        switch ($_SESSION['Rol'])
        {
            case 1:
                header("Location: ../views/administrador.php");
                break;
            case 2:
                header("Location: ../views/cliente.php");
                break;
            case 3:
                header("Location: ../views/repartidor.php");
                break;
        }
    }
}
?>