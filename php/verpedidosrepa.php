
<?php
 include 'conexion.php';
 $conexion = new Database();
 $conexion->conectarBD();
 session_start();
 $persona = $_SESSION['ID']; // esta id es de usuario no de persona
 $mispedidos = $conexion->selectConsulta("call pedidos_repa($persona)");
    echo "<div class=\"titulo\">
            <h1>Mis pedidos</h1>
        </div>";
    echo"<div class=\"contenedor\">
            <div class=\"pedidos\">
                    <div class=\"tabla\">";
                    if (count($mispedidos) > 0) {
                        foreach ($mispedidos as $pedido) {
                            $detalles = $conexion->selectConsulta("call Ver_Detalle_Pedido({$pedido->ID})");
                            $Total_Pagar = $conexion->selectConsulta("call Calcular_Total_Pagar_Pedido({$pedido->ID})");
                            
                            echo"<div class=\"pedido\">
                            <h1><b># </b>{$pedido->ID}</h1>
                            <h3><b>Cliente:</b> {$pedido->nombre}</h3>
                            <h3><b>Teléfono:</b> {$pedido->telefono}</h3>
                            <h3><b>Tienda:</b> {$pedido->tienda}</h3>
                            <h3><b>Dirección:</b> {$pedido->direccion}</h3>
                            <h3><b>Fecha Requerido:</b> {$pedido->fechar}</h3>
                            <h3><b>Estado:</b> {$pedido->estado}</h3>";
                            echo "<br>";
                            echo "<button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalCancelar{$pedido->ID}\">Cancelar</button>";
                        echo "<button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#Modalentregar{$pedido->ID}\"> Entregar</button>";
                             echo "<button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalDetalles{$pedido->ID}\">
                                         Ver detalles del pedido
                                 </button>";
                             
                echo   "<div class=\"modal fade\" id=\"ModalDetalles{$pedido->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                            <div class=\"modal-dialog ModalDetalles modal-dialog-centered modal-dialog-scrollable\">
                                <div class=\"modal-content\">
                                    <div class=\"modal-header\">
                                        <h1>Detalle del pedido #{$pedido->ID}</h1>
                                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                                    </div>
                                    <div class=\"modal-body\">";
                                    if (count($detalles) == 0){
                                        echo "<h2>El pedido no tiene productos</h2>";
                                    }
                                    else{
                                        echo "<div class=\"info\">
                                                <h2>Producto</h2>
                                                <h2>Cantidad</h2>
                                                <h2>Subtotal</h2>";
                                                foreach ($detalles as $deta) {
                                                    echo "<h3>{$deta->Producto}</h3>";
                                                    echo "<h3>{$deta->Cantidad}</h3>";
                                                    echo "<h3>{$deta->Total}</h3>";
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
                                            <div class=\"modal fade\" id=\"ModalCancelar{$pedido->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                                                <div class=\"modal-dialog modal-dialog-centered\">
                                                    <div class=\"modal-content\">
                                                        <div class=\"modal-header\">
                                                            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                                                        </div>
                                                        <div class=\"modal-body\">
                                                            <h3>¿Seguro que quieres cancelar este pedido?</h3>
                                                            <button class=\"Cancelar\" type=\"button\" onclick=\"cambiarestado({$pedido->ID}, 2)\" data-bs-dismiss=\"modal\" aria-label=\"Close\">Continuar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                             <div class=\"modal fade\" id=\"Modalentregar{$pedido->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                                                <div class=\"modal-dialog modal-dialog-centered\">
                                                    <div class=\"modal-content\">
                                                        <div class=\"modal-header\">
                                                            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                                                        </div>
                                                        <div class=\"modal-body\">
                                                            <h3>¿Seguro que quieres entregar este pedido?</h3>
                                                            <button class=\"Cancelar\" type=\"button\" onclick=\"cambiarestado({$pedido->ID}, 1)\" data-bs-dismiss=\"modal\" aria-label=\"Close\">Continuar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                             </div>";
                             
                             
                        }

                    }
                    else {
                        echo"<div class=\"SinPedidos\"><h1>Estas libre! no tienes pedidos pendientes</h1></div>";
                    }

                 echo"</div>
                </div>
            </div>    
        </div>";



?>
