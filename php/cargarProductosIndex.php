<?php
    include 'conexion.php';
    $conexion=new Database();
    $conexion->conectarBD();

    $records_per_page = 12;

    $current_page = $_POST['current_page'];
    $categoria_seleccionado = $_POST['categoria_seleccionado'];

    $offset = ($current_page - 1) * $records_per_page;

    $productos = $conexion->selectConsulta("call Ver_Productos_Filtros($categoria_seleccionado, null, $offset, $records_per_page)");

    foreach ($productos as $producto){
    echo "<div class=\"Producto\">
            <img src=\"../img/productos/{$producto->Imagen}\">
            <div class=\"sombra\">
                <h1>{$producto->Nombre}</h1>
                <div>
                    <h4>\${$producto->Precio}</h4>
                    <button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalDescipcion{$producto->ID}\">
                        Ver descripción
                    </button>
                </div>
            </div>
        </div>
        <div class=\"modal fade\" id=\"ModalDescipcion{$producto->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
            <div class=\"modal-dialog modal-dialog-centered\">
                <div class=\"modal-content\">
                <div class=\"modal-header\">
                    <h5 class=\"modal-title\" id=\"exampleModalLabel\">{$producto->Nombre}</h5>
                    <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                </div>
                <div class=\"modal-body\">
                    {$producto->Descripcion}
                </div>
                </div>
            </div>
        </div>";

    }
?>
    