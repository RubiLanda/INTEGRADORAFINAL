<?php
include 'conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();

session_start();
$id_usuario = $_SESSION['ID'];
$idTienda = $_POST['idTienda'];

if ($idTienda == 0) {
    $fechas = $Conexion->selectConsulta("call Ver_Fechas_Pedidos_Cliente($id_usuario, null);");
}
else {
    $fechas = $Conexion->selectConsulta("call Ver_Fechas_Pedidos_Cliente($id_usuario, $idTienda);");
} 

$fechasBloqueadas = [];
foreach ($fechas as $fila) {
    $fechasBloqueadas[] = $fila->Fecha;
}

echo $fechasBloqueadasJSON = json_encode($fechasBloqueadas);
?>