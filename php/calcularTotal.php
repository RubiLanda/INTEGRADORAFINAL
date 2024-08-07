<?php
session_start();
include 'conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $id_usuario = $_SESSION['ID'];
    $idTienda = $_POST['idTienda'];

    $total_pagar = $Conexion->selectConsulta("call Calcular_Total('$id_usuario', '$idTienda')");
    $productos_totales = $Conexion->selectConsulta("call Ver_Carrito($id_usuario, null, null)"); 

    echo "<div class=\"tabla\">";
    if ($idTienda == 0) {
        echo "<h2></h2>";
        echo "<h2>Monto</h2>";
        echo "<h2>Subtotal</h2>";
        foreach ($productos_totales as $fila) {
            $subTotal = $fila->Total - $fila->Cantidad;
            echo "<h3></h3>";
            echo "<h3>$".$fila->Total."</h3>";
            echo "<h3>+ $".$subTotal.".00</h3>";
        }
    }
    else {
        echo "<h2>Monto</h2>";
        echo "<h2>Descuento</h2>";
        echo "<h2>Subtotal</h2>";
        foreach ($productos_totales as $fila) {
            $subTotal = $fila->Total - $fila->Cantidad;
            echo "<h3>$".$fila->Total."</h3>";
            echo "<h3>- $".$fila->Cantidad."</h3>";
            echo "<h3>+ $".$subTotal.".00</h3>";
        }
    }
    echo "</div>";
    echo "<h1 style=\"text-align: end; padding-top: 10px;\">Total $".$total_pagar[0]->Total."</h1>";

}

?>