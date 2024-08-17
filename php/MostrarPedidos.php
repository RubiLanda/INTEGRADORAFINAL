<?php
session_start();
include 'conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $estado = $_POST['estado'];
    $TipoCliente = $_POST['TipoCliente'];

    $Repartidores = $Conexion->selectConsulta("call Ver_Repartidores(1)");

    switch ($estado) {
        case 1:
            if ($TipoCliente == 1) {
                $Consulta = $Conexion->selectConsulta("call Ver_Pedidos_Clientes_ConTienda_Estado('pendiente')");
            } 
            else {
                $Consulta = $Conexion->selectConsulta("call Ver_Pedidos_Clientes_SinTienda_Estado('pendiente')");
            }
            echo "<div class=\"tabla\">";
            if (count($Consulta) == 0) {
                echo "<div class=\"pedido\">";
                echo "<h1>No hay pedidos Pendientes</h1>";
                echo "</div>";
            }
            else {
                foreach ($Consulta as $fila) {
                    $Cons = $Conexion->selectConsulta("call Ver_Detalle_Pedido({$fila->ID})");
                    $Total_Pagar = $Conexion->selectConsulta("call Calcular_Total_Pagar_Pedido({$fila->ID})");
                    echo "<div class=\"pedido\">
                        <h1>#{$fila->ID}</h1>
                        <h3><b>Cliente:</b> {$fila->Cliente}</h3>
                        <h3><b>Fecha Realizado:</b> {$fila->Fecha_Pedido}</h3>
                        <h3><b>Fecha Requerida:</b> {$fila->Fecha_Requerido}</h3>";
                    if ($TipoCliente == 2) {
                        echo "<h3><b>Fecha Limite a Pagar:</b> {$fila->Fecha_Limite_Pagar}</h3>";
                    } 
                    else {
                        echo "<h3><b>Tienda:</b> {$fila->Tienda}</h3>";
                        echo "<h3><b>Direccion:</b> {$fila->Direccion}</h3>";
                    }
                    echo "<br>";
                    echo "<button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalDetalles{$fila->ID}\">
                                Ver detalles del pedido
                        </button>";
                    if ($TipoCliente == 2) {
                        echo "<button type=\"button\" onclick=\"cambiarEstadoPedido({$fila->ID}, 3)\">Aceptar</button>";
                    }
                    else {
                        echo "<button type=\"button\" onclick=\"cambiarEstadoPedido({$fila->ID}, 1)\">Aceptar</button>";
                    }
                    echo "<button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalCancelar{$fila->ID}\">
                            Cancelar
                        </button>";
                    echo "</div>";
                    echo "<div class=\"modal fade\" id=\"ModalDetalles{$fila->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                            <div class=\"modal-dialog ModalDetalles modal-dialog-centered modal-dialog-scrollable\">
                                <div class=\"modal-content\">
                                    <div class=\"modal-header\">
                                        <h1>Detalle del pedido #{$fila->ID}</h1>
                                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                                    </div>
                                    <div class=\"modal-body\">";
                                    if (count($Cons) == 0){
                                        echo "<h2>El pedido no tiene productos</h2>";
                                    }
                                    else{
                                        echo "<div class=\"info\">
                                                <h2>Producto</h2>
                                                <h2>Cantidad</h2>
                                                <h2>Subtotal</h2>";
                                                foreach ($Cons as $fi) {
                                                    echo "<h3>{$fi->Producto}</h3>";
                                                    echo "<h3>{$fi->Cantidad}</h3>";
                                                    echo "<h3>{$fi->Total}</h3>";
                                                }
                                        echo "<h2></h2>
                                                <h2>Total</h2>
                                                <h2>\${$Total_Pagar[0]->Total}</h2>
                                            </div>";
                                    }
                                echo "</div>
                                </div>
                            </div>
                        </div>";
                    echo "<div class=\"modal fade\" id=\"ModalCancelar{$fila->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                            <div class=\"modal-dialog modal-dialog-centered\">
                                <div class=\"modal-content\">
                                <div class=\"modal-header\">
                                    <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                                </div>
                                <div class=\"modal-body\">
                                    <h3>Seguro que quieres cancelar este pedido</h3>
                                    <button class=\"Cancelar\" type=\"button\" onclick=\"cambiarEstadoPedido({$fila->ID}, 2)\" data-bs-dismiss=\"modal\" aria-label=\"Close\">Cancelar</button>
                                </div>
                                </div>
                            </div>
                        </div>";
                }
                echo "</div>";
            }
            break;
        case 2:
            $Consulta = $Conexion->selectConsulta("call Ver_Pedidos_Clientes_SinTienda_Estado('pendiente a pagar')");
            echo "<div class=\"tabla\">";
            if (count($Consulta) == 0) {
                echo "<div class=\"pedido\">";
                echo "<h1>No hay pedidos Pendientes a Pagar</h1>";
                echo "</div>";
            }
            else {
                foreach ($Consulta as $fila) {
                    $Cons = $Conexion->selectConsulta("call Ver_Detalle_Pedido({$fila->ID})");
                    $Total_Pagar = $Conexion->selectConsulta("call Calcular_Total_Pagar_Pedido({$fila->ID})");
                    echo "<div class=\"pedido\">
                        <h1>#{$fila->ID}</h1>
                        <h3><b>Cliente:</b> {$fila->Cliente}</h3>
                        <h3><b>Fecha Realizado:</b> {$fila->Fecha_Pedido}</h3>
                        <h3><b>Fecha Requerida:</b> {$fila->Fecha_Requerido}</h3>
                        <h3><b>Fecha Limite a Pagar:</b> {$fila->Fecha_Limite_Pagar}<h3>
                        <br>
                        <button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalDetalles{$fila->ID}\">
                                Ver detalles del pedido
                        </button>
                        <button type=\"button\" onclick=\"cambiarEstadoPedido({$fila->ID}, 1)\">Aceptar</button>
                        <button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalCancelar{$fila->ID}\">
                            Cancelar
                        </button>
                    </div>
                    <div class=\"modal fade\" id=\"ModalCancelar{$fila->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                        <div class=\"modal-dialog modal-dialog-centered\">
                            <div class=\"modal-content\">
                            <div class=\"modal-header\">
                                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                            </div>
                            <div class=\"modal-body\">
                                <h3>Seguro que quieres cancelar este pedido</h3>
                                <button class=\"Cancelar\" type=\"button\" onclick=\"cambiarEstadoPedido({$fila->ID}, 2)\" data-bs-dismiss=\"modal\" aria-label=\"Close\">Cancelar</button>
                            </div>
                            </div>
                        </div>
                    </div>";
                    echo "<div class=\"modal fade\" id=\"ModalDetalles{$fila->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                            <div class=\"modal-dialog ModalDetalles modal-dialog-centered modal-dialog-scrollable\">
                                <div class=\"modal-content\">
                                    <div class=\"modal-header\">
                                        <h1>Detalle del pedido #{$fila->ID}</h1>
                                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                                    </div>
                                    <div class=\"modal-body\">";
                                    if (count($Cons) == 0){
                                        echo "<h2>El pedido no tiene productos</h2>";
                                    }
                                    else {
                                    echo "<div class=\"info\">
                                            <h2>Producto</h2>
                                            <h2>Cantidad</h2>
                                            <h2>Subtotal</h2>";
                                            foreach ($Cons as $fi) {
                                                echo "<h3>{$fi->Producto}</h3>";
                                                echo "<h3>{$fi->Cantidad}</h3>";
                                                echo "<h3>{$fi->Total}</h3>";
                                            }
                                    echo "<h2></h2>
                                            <h2>Total</h2>
                                            <h2>\${$Total_Pagar[0]->Total}</h2>
                                        </div>";
                                    }
                                echo "</div>
                                </div>
                            </div>
                        </div>";
                }
                echo "</div>";
            }
            break;
        case 3: //Aceptados
            if ($TipoCliente == 1) {
                $Consulta = $Conexion->selectConsulta("call Ver_Pedidos_Clientes_ConTienda_Estado('aceptado')");
            } 
            else {
                $Consulta = $Conexion->selectConsulta("call Ver_Pedidos_Clientes_SinTienda_Estado('aceptado')");
            }
            echo "<div class=\"tabla\">";
            if (count($Consulta) == 0) {
                echo "<div class=\"pedido\">";
                echo "<h1>No hay pedidos Aceptados</h1>";
                echo "</div>";
            }
            else {
                foreach ($Consulta as $fila) {
                    $Cons = $Conexion->selectConsulta("call Ver_Detalle_Pedido({$fila->ID})");
                    $Total_Pagar = $Conexion->selectConsulta("call Calcular_Total_Pagar_Pedido({$fila->ID})");
                    echo "<div class=\"pedido\">";
                    if ($TipoCliente == 2){
                        echo "<h1>#{$fila->ID}</h1>";
                    }
                    else {
                        if ($fila->Repartidor == null) {
                            echo "<h1 id=\"EnProceso{$fila->ID}\">#{$fila->ID}</h1>";
                        }
                        else {
                            echo "<h1 id=\"EnProceso{$fila->ID}\">#{$fila->ID} En proceso
                                <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"32\" height=\"32\" fill=\"currentColor\" class=\"bi bi-truck\" viewBox=\"0 0 16 16\">
                                    <path d=\"M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5zm1.294 7.456A2 2 0 0 1 4.732 11h5.536a2 2 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456M12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2\"/>
                                </svg>
                            </h1>";
                        }
                    }
                    echo "<h3><b>Cliente:</b> {$fila->Cliente}</h3>
                        <h3><b>Fecha Realizado:</b> {$fila->Fecha_Pedido}</h3>
                        <h3><b>Fecha Requerida:</b> {$fila->Fecha_Requerido}</h3>";
                    if ($TipoCliente == 2) {
                        echo "<h3><b>Fecha Limite a Pagar:</b> {$fila->Fecha_Limite_Pagar}<h3>";
                    }
                    else {
                        echo "<h3><b>Tienda:</b> {$fila->Tienda}</h3>";
                        echo "<h3><b>Direccion:</b> {$fila->Direccion}</h3>";
                        echo "<h3><b>Repartidor:</b> <select onchange=\"cambiarRepartidor({$fila->ID}, this)\">";
                        echo "<option value=\"NULL\">No Asignado</option>";
                        foreach ($Repartidores as $R) {
                            if ($R->ID == $fila->Repartidor) {
                                echo "<option value=\"{$R->ID}\" selected>{$R->Nombre}</option>";
                            }
                            else {
                                echo "<option value=\"{$R->ID}\">{$R->Nombre}</option>";
                            }
                        }
                        echo "</select></h3>";
                    }
                    echo "<br>";
                    echo "<button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalDetalles{$fila->ID}\">
                                Ver detalles del pedido
                        </button>";
                    echo "<button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalCancelar{$fila->ID}\">
                                Cancelar
                        </button>";
                    if ($TipoCliente == 2) {
                        echo "<button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalEntregar{$fila->ID}\">
                                Entregar
                        </button>";
                    }
                    echo "</div>";
                    
                    echo "<div class=\"modal fade\" id=\"ModalDetalles{$fila->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                            <div class=\"modal-dialog ModalDetalles modal-dialog-centered modal-dialog-scrollable\">
                                <div class=\"modal-content\">
                                    <div class=\"modal-header\">
                                        <h1>Detalle del pedido #{$fila->ID}</h1>
                                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                                    </div>
                                    <div class=\"modal-body\">";
                                    if (count($Cons) == 0){
                                        echo "<h2>El pedido no tiene productos</h2>";
                                    }
                                    else {
                                    echo "<div class=\"info\">
                                            <h2>Producto</h2>
                                            <h2>Cantidad</h2>
                                            <h2>Subtotal</h2>";
                                            foreach ($Cons as $fi) {
                                                echo "<h3>{$fi->Producto}</h3>";
                                                echo "<h3>{$fi->Cantidad}</h3>";
                                                echo "<h3>{$fi->Total}</h3>";
                                            }
                                    echo "<h2></h2>
                                            <h2>Total</h2>
                                            <h2>\${$Total_Pagar[0]->Total}</h2>
                                        </div>";
                                    }
                                echo "</div>
                                </div>
                            </div>
                        </div>

                        <div class=\"modal fade\" id=\"ModalCancelar{$fila->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                                <div class=\"modal-dialog modal-dialog-centered\">
                                    <div class=\"modal-content\">
                                    <div class=\"modal-header\">
                                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                                    </div>
                                    <div class=\"modal-body\">
                                        <h3>Seguro que quieres cancelar este pedido</h3>
                                        <button class=\"Cancelar\" type=\"button\" onclick=\"cambiarEstadoPedido({$fila->ID}, 2)\" data-bs-dismiss=\"modal\" aria-label=\"Close\">Cancelar</button>
                                    </div>
                                    </div>
                                </div>
                        </div>

                        <div class=\"modal fade\" id=\"ModalEntregar{$fila->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                                <div class=\"modal-dialog modal-dialog-centered\">
                                    <div class=\"modal-content\">
                                    <div class=\"modal-header\">
                                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                                    </div>
                                    <div class=\"modal-body\">
                                        <h3>Seguro que quieres entregar este pedido</h3>
                                        <button class=\"Cancelar\" type=\"button\" onclick=\"cambiarEstadoPedido({$fila->ID}, 4)\" data-bs-dismiss=\"modal\" aria-label=\"Close\">Entregar</button>
                                    </div>
                                    </div>
                                </div>
                        </div>";
                    }
                echo "</div>";
            }
            break;
        case 4: //Cancelados
            if ($TipoCliente == 1) {
                $Consulta = $Conexion->selectConsulta("call Ver_Pedidos_Clientes_ConTienda_Estado('cancelado')");
            } 
            else {
                $Consulta = $Conexion->selectConsulta("call Ver_Pedidos_Clientes_SinTienda_Estado('cancelado')");
            }
            echo "<div class=\"tabla\">";
            if (count($Consulta) == 0) {
                echo "<div class=\"pedido\">";
                echo "<h1>No hay pedidos Cancelados</h1>";
                echo "</div>";
            }
            else {
                foreach ($Consulta as $fila) {
                    $Cons = $Conexion->selectConsulta("call Ver_Detalle_Pedido({$fila->ID})");
                    $Total_Pagar = $Conexion->selectConsulta("call Calcular_Total_Pagar_Pedido({$fila->ID})");
                    echo "<div class=\"pedido\">
                        <h1>#{$fila->ID}</h1>
                        <h3><b>Cliente:</b> {$fila->Cliente}</h3>
                        <h3><b>Fecha Realizada:</b> {$fila->Fecha_Pedido}</h3>
                        <h3><b>Fecha Requerida:</b> {$fila->Fecha_Requerido}</h3>";
                    if ($TipoCliente == 2) {
                        echo "<h3><b>Fecha Limite a Pagar:</b> {$fila->Fecha_Limite_Pagar}<h3>";
                    }
                    else {
                        echo "<h3><b>Tienda:</b> {$fila->Tienda}</h3>";
                        echo "<h3><b>Direccion:</b> {$fila->Direccion}</h3>";
                        echo "<h3><b>Repartidor:</b> {$fila->Repartidor}</h3>";
                    }
                    echo "<br>";
                    echo "<button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalDetalles{$fila->ID}\">
                                Ver detalles del pedido
                        </button>";
                    echo "</div>";
                    echo "<div class=\"modal fade\" id=\"ModalDetalles{$fila->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                            <div class=\"modal-dialog ModalDetalles modal-dialog-centered modal-dialog-scrollable\">
                                <div class=\"modal-content\">
                                    <div class=\"modal-header\">
                                        <h1>Detalle del pedido #{$fila->ID}</h1>
                                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                                    </div>
                                    <div class=\"modal-body\">";
                                    if (count($Cons) == 0){
                                        echo "<h2>El pedido no tiene productos</h2>";
                                    }
                                    else {
                                    echo "<div class=\"info\">
                                            <h2>Producto</h2>
                                            <h2>Cantidad</h2>
                                            <h2>Subtotal</h2>";
                                            foreach ($Cons as $fi) {
                                                echo "<h3>{$fi->Producto}</h3>";
                                                echo "<h3>{$fi->Cantidad}</h3>";
                                                echo "<h3>{$fi->Total}</h3>";
                                            }
                                    echo "<h2></h2>
                                            <h2>Total</h2>
                                            <h2>\${$Total_Pagar[0]->Total}</h2>
                                        </div>";
                                    }
                                echo "</div>
                                </div>
                            </div>
                        </div>";
                }
                echo "</div>";
            }
            break;
        case 5: //Entregados
            if ($TipoCliente == 1) {
                $Consulta = $Conexion->selectConsulta("call Ver_Pedidos_Clientes_ConTienda_Estado('entregado')");
            } 
            else {
                $Consulta = $Conexion->selectConsulta("call Ver_Pedidos_Clientes_SinTienda_Estado('entregado')");
            }
            echo "<div class=\"tabla\">";
            if (count($Consulta) == 0) {
                echo "<div class=\"pedido\">";
                echo "<h1>No hay pedidos Entregados</h1>";
                echo "</div>";
            }
            else {
                foreach ($Consulta as $fila) {
                    $Cons = $Conexion->selectConsulta("call Ver_Detalle_Pedido({$fila->ID})");
                    $Total_Pagar = $Conexion->selectConsulta("call Calcular_Total_Pagar_Pedido({$fila->ID})");
                    echo "<div class=\"pedido\">
                        <h1>#{$fila->ID}</h1>
                        <h3><b>Cliente:</b> {$fila->Cliente}</h3>
                        <h3><b>Fecha Realizada:</b> {$fila->Fecha_Pedido}</h3>
                        <h3><b>Fecha Requerida:</b> {$fila->Fecha_Requerido}</h3>";
                    if ($TipoCliente == 2) {
                        echo "<h3><b>Fecha Limite a pagar:</b> {$fila->Fecha_Limite_Pagar}<h3>";
                        echo "<h3><b>Fecha Entregado:</b> {$fila->Fecha_entregada}<h3>";
                    }
                    else {
                        echo "<h3><b>Tienda:</b> {$fila->Tienda}</h3>";
                        echo "<h3><b>Direccion:</b> {$fila->Direccion}</h3>";
                        echo "<h3><b>Repartidor:</b> {$fila->Repartidor}</h3>";
                    }
                    echo "<br>";
                    echo "<button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalDetalles{$fila->ID}\">
                                Ver detalles del pedido
                        </button>";
                    echo "</div>";
                    echo "<div class=\"modal fade\" id=\"ModalDetalles{$fila->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                            <div class=\"modal-dialog ModalDetalles modal-dialog-centered modal-dialog-scrollable\">
                                <div class=\"modal-content\">
                                    <div class=\"modal-header\">
                                        <h1>Detalle del pedido #{$fila->ID}</h1>
                                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                                    </div>
                                    <div class=\"modal-body\">";
                                    if (count($Cons) == 0){
                                        echo "<h2>El pedido no tiene productos</h2>";
                                    }
                                    else {
                                    echo "<div class=\"info\">
                                            <h2>Producto</h2>
                                            <h2>Cantidad</h2>
                                            <h2>Subtotal</h2>";
                                            foreach ($Cons as $fi) {
                                                echo "<h3>{$fi->Producto}</h3>";
                                                echo "<h3>{$fi->Cantidad}</h3>";
                                                echo "<h3>{$fi->Total}</h3>";
                                            }
                                    echo "<h2></h2>
                                            <h2>Total</h2>
                                            <h2>\${$Total_Pagar[0]->Total}</h2>
                                        </div>";
                                    }
                                echo "</div>
                                </div>
                            </div>
                        </div>";
                }
                echo "</div>";
            }
            break;
    }
}

?>