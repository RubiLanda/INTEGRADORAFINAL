<?php 
// ESTE YA ESTA CON LA CLASE DE CONEXION BUENA
session_start();
$ID = $_SESSION['ID'];
include '../php/conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();
extract($_POST);
if($_POST)
{
  // AQUI LLAMAMOS EL PROCEDIMIENTO ALMACENADO PARA FILTRAR LOS PRODUCTOS   
// $historial = $Conexion->selectConsulta("CALL historial($ID, $a, $m)");
$historial = $Conexion->selectConsulta("CALL historial($ID, null, null)");

    echo $historial[0]->IDPEDIDO;

    // if (count($historial) == 0) {
    //     echo true;
    // }
    // else {
    //     foreach($historial as $h){
    //         // PROCEDIMIENTO ALMACENADO PARA CALCULAR EL TOTAL A PAGAR DEL PEDIDO MEDIANTE LA ID
    //         $Total_Pagar = $Conexion->selectConsulta("call Calcular_Total_Pagar_Pedido({$h->IDPEDIDO})");
    //         // PROCEDIMIENTO ALMACENADO PARA VER LOS DETALLES DE LOS PEDIDOS MEDIANTE LA ID
    //         $Cons = $Conexion->selectConsulta("call Ver_Detalle_Pedido({$h->IDPEDIDO})");
    //         // AQUI ESTA EL MODAL LA INFORMACION DEL PEDIDO AL PRINCIPIO (IDPEDIDO,ESTADO,FECHA REALIZADA Y CON QUE TIENDA)
    //         // EN EL BOTON ESTA EL ID DEL PEDIDO PARA USARLO DESPUES PARA MOSTRAR UNICAMENTE LOS DETALLES DE ESE ID
    //         echo "
    //         <div class=\"pedido\">
    //             <h1>#{$h->IDPEDIDO}</h1>
    //             <h3><b>Estado:</b> {$h->ESTADO}</h3>
    //             <h3><b>Fecha Realizada:</b> {$h->FECHAREALIZADA}</h3>
    //             <h3><b>Fecha Envío:</b> {$h->FECHAENVIO}</h3>
    //             <h3><b>Tienda:</b> {$h->TIENDA}</h3>
    //             <br>
    //             <button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalDetalles{$h->IDPEDIDO}\">
    //                 Ver detalles del pedido
    //             </button>
    //             </div>
    //                 <div class=\"modal fade\" id=\"ModalDetalles{$h->IDPEDIDO}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
    //                     <div class=\"modal-dialog ModalDetalles modal-dialog-centered modal-dialog-scrollable\">
    //                         <div class=\"modal-content\">
    //                             <div class=\"modal-header\">
    //                                 <h1>Detalle del pedido #{$h->IDPEDIDO}</h1>
    //                                 <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
    //                             </div>
    //                             <div class=\"modal-body\">";
    //                             // UN IF PARA COMPROBAR EN EL CASO DE QUE EL PEDIDO NO TENGA PRODUCTOS
    //                             if (count($Cons) == 0){
    //                                 echo "<h2>El pedido no tiene productos</h2>";
    //                             }
    //                             else{
    //                             echo "<div class=\"info\">
    //                                         <h2>Producto</h2>
    //                                         <h2>Cantidad</h2>
    //                                         <h2>Subtotal</h2>";
    //                             // MEDIANTE ESTE FOREACH IMPRIMIMOS LOS PRODUCTOS ASI COMO SU CANTIDAD Y EL TOTAL DE ESA CANTIDAD DE PRODUCTOS
    //                             // ASI COMO EL TOTAL A PAGAR MEDIANTE UN ARREGLO PARA IMPRIMIR EL TOTAL DE ESE PEDIDO
    //                                         foreach ($Cons as $fi) {
    //                                             echo "<h3>{$fi->Producto}</h3>";
    //                                             echo "<h3>{$fi->Cantidad}</h3>";
    //                                             echo "<h3>{$fi->Total}</h3>";
    //                                         }
    //                                 echo   "<h2></h2>
    //                                         <h2>Total</h2>
    //                                         <h2>\${$Total_Pagar[0]->Total}</h2>
    //                                     </div>";
    //                             }
    //                         echo "</div>
    //                         </div>
    //                     </div>
    //                 </div>
    //             </div>
    //         </div>";
    //     }
    // }
}

?>

