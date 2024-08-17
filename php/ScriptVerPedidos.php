<?php 
// ESTE YA ESTA CON LA CLASE DE CONEXION BUENA

session_start();
$ID = $_SESSION['ID'];
include '../php/conexion.php';
$conexion = new Database();
$conexion->conectarBD();
extract($_POST);
if($_POST)
{
  // AQUI LLAMAMOS EL PROCEDIMIENTO ALMACENADO PARA FILTRAR LOS PRODUCTOS   
  if ($E == "NULL"){
    $consulta = $conexion->selectConsulta("CALL mispedidos_cliente($ID,$E)");
}
else {
    $consulta = $conexion->selectConsulta("CALL mispedidos_cliente($ID,'$E')");
}
    if (count($consulta) == 0) {
        echo true;
    }
    else {
foreach($consulta as $h){
    // PROCEDIMIENTO ALMACENADO PARA CALCULAR EL TOTAL A PAGAR DEL PEDIDO MEDIANTE LA ID
    $Total_Pagar = $conexion->selectConsulta("call Calcular_Total_Pagar_Pedido({$h->ID})");
    // PROCEDIMIENTO ALMACENADO PARA VER LOS DETALLES DE LOS PEDIDOS MEDIANTE LA ID
    $Cons = $conexion->selectConsulta("call Ver_Detalle_Pedido({$h->ID})");
    // AQUI ESTA EL MODAL LA INFORMACION DEL PEDIDO AL PRINCIPIO (IDPEDIDO,ESTADO,FECHA REALIZADA Y CON QUE TIENDA)
    // EN EL BOTON ESTA EL ID DEL PEDIDO PARA USARLO DESPUES PARA MOSTRAR UNICAMENTE LOS DETALLES DE ESE ID
    echo "
    <div class=\"pedido\">";
    if ($h->Repartidor != NULL) {
        echo"<h1>#{$h->ID} En proceso
            <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"32\" height=\"32\" fill=\"currentColor\" class=\"bi bi-truck\" viewBox=\"0 0 16 16\">
                <path d=\"M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5zm1.294 7.456A2 2 0 0 1 4.732 11h5.536a2 2 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456M12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2\"/>
            </svg>
        </h1>";
    }
    else {
        echo"<h1>#{$h->ID}</h1>";
    }


    echo "<h3><b>Estado:</b> {$h->Estado}</h3>
        <h3><b>Fecha Realizada:</b> {$h->Fecha_Pedido}</h3>
        <h3><b>Fecha Envío:</b> {$h->Fecha_Requerida}</h3>";
        if($h->Fecha_limite != NULL){
            echo"<h3><b>Fecha limite de pago:</b> {$h->Fecha_limite}</h3>";
        }
        if($h ->Repartidor != NULL){
            echo"<h3><b>Repartidor:</b> {$h->Repartidor}</h3>";
        }
        if($h ->Tienda != NULL){
            echo"<h3><b>Tienda:</b> {$h->Tienda}</h3>";
        }
        if($h ->Direccion != NULL){
            echo"<h3><b>Dirección:</b> {$h->Direccion}</h3>";
        }

        echo"
        <br>
        <button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalDetalles{$h->ID}\">
            Ver detalles del pedido
        </button>
        </div>
            <div class=\"modal fade\" id=\"ModalDetalles{$h->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                <div class=\"modal-dialog ModalDetalles modal-dialog-centered modal-dialog-scrollable\">
                    <div class=\"modal-content\">
                        <div class=\"modal-header\">
                            <h1>Detalle del pedido #{$h->ID}</h1>
                            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                        </div>
                        <div class=\"modal-body\">";
                        // UN IF PARA COMPROBAR EN EL CASO DE QUE EL PEDIDO NO TENGA PRODUCTOS
                        if (count($Cons) == 0){
                            echo "<h2>El pedido no tiene productos</h2>";
                        }
                        else{
                        echo "<div class=\"info\">
                                    <h2>Producto</h2>
                                    <h2>Cantidad</h2>
                                    <h2>Subtotal</h2>";
                        // MEDIANTE ESTE FOREACH IMPRIMIMOS LOS PRODUCTOS ASI COMO SU CANTIDAD Y EL TOTAL DE ESA CANTIDAD DE PRODUCTOS
                        // ASI COMO EL TOTAL A PAGAR MEDIANTE UN ARREGLO PARA IMPRIMIR EL TOTAL DE ESE PEDIDO
                                    foreach ($Cons as $fi) {
                                        echo "<h3>{$fi->Producto}</h3>";
                                        echo "<h3>{$fi->Cantidad}</h3>";
                                        echo "<h3>{$fi->Total}</h3>";
                                    }
                            echo   "<h2></h2>
                                    <h2>Total</h2>
                                    <h2>\${$Total_Pagar[0]->Total}</h2>
                                </div>";
                        }
                    echo "</div>
                    </div>
                </div>
            </div>
        </div>
    </div>";
    }
}
}

?>