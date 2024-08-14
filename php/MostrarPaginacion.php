<?php

session_start();
require '../php/conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();

$id_usuario = $_SESSION['ID'];

$current_page = $_POST['pagina'];

if ($_POST['tipo'] == 1) {
    $records_per_page = 5;
    
    $offset = ($current_page - 1) * $records_per_page;
    $categoria_seleccionado = $_POST['categoria_seleccionado'];
    $productos_totales = $Conexion->selectConsulta("call Ver_Productos_Realizar_Pedido($id_usuario, $categoria_seleccionado, null, null, null)");
    $productos = $Conexion->selectConsulta("call Ver_Productos_Realizar_Pedido($id_usuario, $categoria_seleccionado, null, $offset, $records_per_page)");
}
else if ($_POST['tipo'] == 2) {
    $records_per_page = 5;
    
    $offset = ($current_page - 1) * $records_per_page;
    $productos_totales = $Conexion->selectConsulta("call Ver_Carrito($id_usuario, null, null)"); 
    $productos = $Conexion->selectConsulta("call Ver_Carrito($id_usuario, $offset, $records_per_page)");
}
else if ($_POST['tipo'] == 3) {
    $records_per_page = 12;
    
    $offset = ($current_page - 1) * $records_per_page;
    $categoria_seleccionado = $_POST['categoria_seleccionado'];
    $productos_totales = $Conexion->selectConsulta("call Ver_Productos_Filtros($categoria_seleccionado, null, null, null)");
    $productos = $Conexion->selectConsulta("call Ver_Productos_Filtros($categoria_seleccionado, null, $offset, $records_per_page)");
}
else if ($_POST['tipo'] == 4) {
    $records_per_page = 5;
    
    $offset = ($current_page - 1) * $records_per_page;
    $productos_totales = $Conexion->selectConsulta("call Ver_Tiendas(null, null)");
    $productos = $Conexion->selectConsulta("call Ver_Tiendas($offset, $records_per_page)");
}

if (empty($productos) && $current_page > 1) {
    $current_page = $current_page - 1;
}

$total_records = count($productos_totales);
$total_pages = ceil($total_records / $records_per_page);

$max_links = 5;
$start = max(1, $current_page - floor($max_links / 2));
$end = min($total_pages, $current_page + floor($max_links / 2));



if ($current_page > 1): ?>
    <button onclick="cambiarPaginacion(<?php echo ($current_page - 1)?>)"> < </button>
<?php endif;

if ($start > 1): ?>
    <button onclick="cambiarPaginacion(1)">1</button>
    <?php if ($start > 2): ?>
        ...
    <?php endif?>
<?php endif;

for($i = $start; $i <= $end; $i++): ?>
    <button onclick="cambiarPaginacion(<?php echo $i?>)" class="<?php if($i == $current_page) echo 'activo'; ?>"><?php echo $i ?></button>
<?php endfor;

if ($end < $total_pages): ?>
    <?php if ($end < $total_pages - 1): ?>
        ...
    <?php endif ?>
    <button onclick="cambiarPaginacion(<?php echo $total_pages?>)"><?php echo $total_pages?></button>
<?php endif;

if($current_page < $total_pages): ?>
    <button onclick="cambiarPaginacion(<?php echo ($current_page + 1)?>)"> > </button>
<?php endif;
?>