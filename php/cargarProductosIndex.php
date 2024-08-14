<?php
    include 'conexion.php';
    $conexion=new Database();
    $conexion->conectarBD();

    // $records_per_page = 12;

    // $current_page = $_POST['current_page'];
    // $categoria_seleccionado = $_POST['categoria_seleccionado'];

    // $offset = ($current_page - 1) * $records_per_page;

    // $productos = $Conexion->selectConsulta("call Ver_Productos_Filtros($categoria_seleccionado, null, $offset, $records_per_page)");

    echo "si";

    // foreach ($productos as $producto){
    // echo "<div class=\"Producto\">
    //         <img src=\"../img/productos/{$producto->Imagen}\">
    //         <div class=\"sombra\">
    //             <h1>{$producto->Nombre}</h1>
    //             <div>
    //                 <h4>{$producto->Precio}</h4>
    //                 <p>Ver descripcion</p>
    //             </div>
    //         </div>
    //     </div>";

    // }
?>
    