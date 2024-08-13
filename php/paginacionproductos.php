<?php

session_start();
require '../php/conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();

$id_usuario = $_SESSION['ID'];

$current_page = $_POST['pagina'];
$records_per_page = 5;

$offset = ($current_page - 1) * $records_per_page;

$productos = $Conexion->selectConsulta("call Ver_Productos_Informacion('0', null, $offset, $records_per_page)");
$productos_totales = $Conexion->selectConsulta("call Ver_Productos_Informacion('0', null, null, null)");

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