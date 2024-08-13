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
    <div class=\"pedido\">
        <h1>#{$h->ID}</h1>
        <h3><b>Estado:</b> {$h->Estado}</h3>
        <h3><b>Fecha Realizada:</b> {$h->Fecha_Pedido}</h3>
        <h3><b>Fecha Envío:</b> {$h->Fecha_Requerida}</h3>";
        if($h ->f_limitepago != NULL){
            echo"<h3><b>Fecha limite de pago:</b> {$h->f_limitepago}</h3>";
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