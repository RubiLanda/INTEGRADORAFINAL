<?php 
require '../php/conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();

$Fecha_Pedido = $_POST['fecha'];

if ($Fecha_Pedido == "Hoy") {
    $Fecha_Pedido = date('Y-m-d');
}
else if ($Fecha_Pedido == "Mañana") {
    $Fecha_Pedido = date('Y-m-d', strtotime('+1 day'));
}

$categorias = $Conexion->selectConsulta("SELECT CATEGORIAS.id_categoria as ID, CATEGORIAS.nombre as Nombre from CATEGORIAS where CATEGORIAS.estado = 1");

$temporada = $Conexion->selectConsulta("call Calcular_Fechas_Temporada('$Fecha_Pedido', @habilitado)");

$resultado = $Conexion->selectConsulta("SELECT @habilitado as resultado");
$habilitarTemporada = $resultado[0]->resultado;

echo "<a href=\"?categoria=0\">Todos</a>";
foreach ($categorias as $categoria){
    if ($categoria->ID != 4) {
        echo "<a href=\"?categoria={$categoria->ID}\">{$categoria->Nombre}</a>";
    }
    if ($categoria->ID == 4 && $habilitarTemporada == 1) {
        echo "<a href=\"?categoria={$categoria->ID}\">{$categoria->Nombre}</a>";
    }
}
?>