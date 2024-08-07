<?php

session_start();
require '../php/conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();

$records_per_page = 5;

$current_page = $_POST['current_page'];
$categoria_seleccionado = $_POST['categoria_seleccionado'];
$mostrarStock = $_POST['mostrarStock'];

$offset = ($current_page - 1) * $records_per_page;

$id_usuario = $_SESSION['ID'];

$productos = $Conexion->selectConsulta("call Ver_Productos_Realizar_Pedido($id_usuario, $categoria_seleccionado, null, $offset, $records_per_page)");
        
foreach ($productos as $fila) {
    echo "
    <div class=\"producto\">
        <img src=\"../img/productos/{$fila->Imagen}\">
        <div class=\"info\">
            <div class=\"info2\">
                <h3>{$fila->Nombre}</h3>
                <h3>\${$fila->Precio}</h3>
            </div>
            <div class=\"cantidad\">";
            if ($fila->Disponible > 0 or $mostrarStock == 0) {
            echo "<button type=\"button\" onclick=\"cambiarCantidad(-1, this.nextElementSibling, {$fila->ID}, 'input{$fila->ID}', {$fila->Disponible})\" style=\"border-radius: 15px 0 0 15px;\">
                    <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"25\" height=\"25\" fill=\"currentColor\" class=\"bi bi-caret-left-fill\" viewBox=\"0 0 16 16\">
                        <path d=\"m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z\"/>
                    </svg>
                </button>
                <input type=\"text\" name=\"cantidad\" id=\"input{$fila->ID}\" value=\"{$fila->CantidadCarrito}\" oninput=\"validarNumero(this, {$fila->Disponible})\" onblur=\"ejecutarBoton(1, {$fila->ID}, 'input{$fila->ID}')\" onkeydown=\"if (this.value <= 0 || this.value == '') {return event.key != 'Enter';}\">
                <button type=\"button\" onclick=\"cambiarCantidad(1, this.previousElementSibling, {$fila->ID}, 'input{$fila->ID}', {$fila->Disponible})\" style=\"border-radius: 0 15px 15px 0;\">
                    <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"25\" height=\"25\" fill=\"currentColor\" class=\"bi bi-caret-right-fill\" viewBox=\"0 0 16 16\">
                        <path d=\"m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z\"/>
                </svg>
                </button>";
            }
            if ($fila->CantidadCarrito == 0){
                echo "<div class=\"enCarritoDesactivado\" id=\"enCarrito{$fila->ID}\">";
            }
            else{
                echo "<div class=\"enCarritoActivo\" id=\"enCarrito{$fila->ID}\">";
            }
            echo "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"40\" height=\"40\" fill=\"currentColor\" class=\"bi bi-cart-check-fill\" viewBox=\"0 0 16 16\">
                    <path d=\"M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m-1.646-7.646-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L8 8.293l2.646-2.647a.5.5 0 0 1 .708.708\"/>
                </svg>
                </div>
            </div>";
            if ($_POST['mostrarStock'] == 1) {
                if ($fila->Disponible == 0) {
                    echo "<h4>
                    No Disponible
                    <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"50\" height=\"50\" viewBox=\"0 0 20 20\">
                        <defs>
                            <mask id=\"hole-mask\">
                                <rect width=\"100%\" height=\"100%\" fill=\"white\"/>
                                <circle cx=\"15.5\" cy=\"13.5\" r=\"5\" fill=\"black\"/>
                            </mask>
                        </defs>
                        <path d=\"M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2zm3.564 1.426L5.596 5 8 5.961 14.154 3.5zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z\" fill=\"#ddb892\" mask=\"url(#hole-mask)\"/>
                        
                        <g transform=\"translate(10, 8) scale(0.6)\">
                            <path fill=\"#ddb892\" d=\"M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16\"/>
                            <path fill=\"#ddb892\" d=\"M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708\"/>
                        </g>
                    </svg>
                    </h4>";
                }
                else {
                    echo "<h4><b>Disponible:</b> {$fila->Disponible} piezas</h4> ";
                }
            }
        echo "
        </div> 
        <div class=\"botones\">
            <button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalDescipcion{$fila->ID}\">
                Descripcion
            </button>";
            if ($fila->CantidadCarrito == 0){
                echo "<button type=\"button\" style=\"display: none;\" id=\"botonEliminar{$fila->ID}\" onclick=\"ejecutarBoton(2, {$fila->ID}, 'input{$fila->ID}')\">Eliminar</button>";
            }
            else{
                echo "<button type=\"button\" id=\"botonEliminar{$fila->ID}\" onclick=\"ejecutarBoton(2, {$fila->ID}, 'input{$fila->ID}')\">Eliminar</button>";
            }
echo   "</div>
    </div>
    <div class=\"modal fade\" id=\"ModalDescipcion{$fila->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
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
    <hr>
    ";
}


?>
