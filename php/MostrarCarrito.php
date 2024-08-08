<?php

session_start();
require '../php/conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();

$records_per_page = 5;

$current_page = $_POST['current_page'];
$mostrarStock = $_POST['mostrarStock'];

$offset = ($current_page - 1) * $records_per_page;

$id_usuario = $_SESSION['ID'];

$productos = $Conexion->selectConsulta("call Ver_Carrito($id_usuario, $offset, $records_per_page)");
        
foreach ($productos as $fila) {
    $total_Producto = $fila->Cantidad * $fila->Precio;
    echo "
    <div class=\"producto\" id=\"div{$fila->Producto}\">
        <img src=\"../img/productos/{$fila->Imagen}\">
        <div class=\"info\">
            <div class=\"info2\">
                <h3>{$fila->Nombre}</h3>
            </div>
            <div class=\"cantidad\">
                <button type=\"button\" onclick=\"cambiarCantidad(-1, this.nextElementSibling, {$fila->Producto}, 'input{$fila->Producto}', 'precio{$fila->Producto}', {$fila->Precio}, {$fila->Disponible})\" style=\"border-radius: 15px 0 0 15px;\">
                    <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"25\" height=\"25\" fill=\"currentColor\" class=\"bi bi-caret-left-fill\" viewBox=\"0 0 16 16\">
                        <path d=\"m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z\"/>
                    </svg>
                </button>
                <input type=\"text\" name=\"cantidad\" id=\"input{$fila->Producto}\" value=\"{$fila->Cantidad}\" oninput=\"validarNumero(this, {$fila->Disponible})\" onblur=\"ejecutarBoton(1, {$fila->Producto}, 'input{$fila->Producto}', 'precio{$fila->Producto}', {$fila->Precio})\" onkeydown=\"if (this.value <= 0 || this.value == '') {return event.key != 'Enter';}\">
                <button type=\"button\" onclick=\"cambiarCantidad(1, this.previousElementSibling, {$fila->Producto}, 'input{$fila->Producto}', 'precio{$fila->Producto}', {$fila->Precio}, {$fila->Disponible})\" style=\"border-radius: 0 15px 15px 0;\">
                    <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"25\" height=\"25\" fill=\"currentColor\" class=\"bi bi-caret-right-fill\" viewBox=\"0 0 16 16\">
                        <path d=\"m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z\"/>
                    </svg>
                </button>
                <h3 class=\"infoPago\" id=\"precio{$fila->Producto}\"> x \${$fila->Precio} = \${$total_Producto} </h3>
            </div>";
            if ($mostrarStock == 1) {
                echo "<h4><b>Disponible:</b> {$fila->Disponible} piezas</h4>";
            }
    echo "</div> 
        <div class=\"botones\">
            <button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalDescipcion{$fila->Producto}\">
                Descripcion
            </button>
            <button type=\"button\" onclick=\"ejecutarBoton(2, {$fila->Producto}, 'input{$fila->Producto}', 'precio{$fila->Producto}', {$fila->Precio})\">Eliminar</button>
        </div>
    </div>
    <div class=\"modal fade\" id=\"ModalDescipcion{$fila->Producto}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
        <div class=\"modal-dialog modal-dialog-centered\">
            <div class=\"modal-content\">
            <div class=\"modal-header\">
                <h5 class=\"modal-title\" id=\"exampleModalLabel\">{$fila->Nombre}</h5>
                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
            </div>
            <div class=\"modal-body\">
                {$fila->Descripcion}
            </div>
            </div>
        </div>
    </div>
    <hr id=\"hr{$fila->Producto}\">
    ";
}
?>