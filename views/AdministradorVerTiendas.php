<?php
session_start();
if (isset($_SESSION['Rol'])){
    if ($_SESSION['Rol'] != 1){
        switch ($_SESSION['Rol']){
            case 2:
                header("Location: ClienteRealizarPedido.php");
                break;
            case 3:
                header("Location: Repartidor.php");
                break;
        }
    }
}
else {
    header("Location: login.php");
}
require '../php/conexion.php';

$menu1 = isset($_GET['estado']) ? false : true;
$menu2 = isset($_GET['estado']) ? false : true;
$estado = isset($_GET['estado']) ? $_GET['estado'] : 1;
$TipoCliente = isset($_GET['TipoCliente']) ? $_GET['TipoCliente'] : 1;

$id_usuario = $_SESSION['ID'];

$Conexion = new Database();
$Conexion->conectarBD();
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function MostrarPedidos(estado, TipoCliente){
        $.ajax({
            type: 'POST',
            url: '../php/MostrarPedidos.php',
            data: { estado: estado, TipoCliente: TipoCliente },
            success: function(response) {
                $('#pedidos' + estado).html(response);
            }
        });
    }
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <link rel="stylesheet" href="../css/Header.css">
    <link rel="stylesheet" href="../css/Administrador.css">
    <title>La Espiga</title>
</head>
<body style="display: flex; align-items: center; flex-direction: column;">
    <div class="fondo"></div>
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11" id="toastContainer"></div>
    <header>
        <div>
            <button id="buttonMenu" class="boton">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                </svg>
            </button>
            <div> 
                <div class='dropdown text-end'>
                    <a href='#' class='d-block link-dark text-decoration-none perfil' id='dropdownUser1' data-bs-toggle='dropdown' aria-expanded='false'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='32' height='32' fill='currentColor' class='bi bi-person-fill' viewBox='0 0 16 16'>
                            <path d='M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6'/>
                        </svg>
                    </a>
                    <ul class='dropdown-menu text-small' aria-labelledby='dropdownUser1' data-popper-placement='bottom-end' style='position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(-112px, 34px);'>
                        <li><a class='dropdown-item' href='#'>hola</a></li>
                        <li><a class='dropdown-item' href='#'>Settings</a></li>
                        <li><a class='dropdown-item' href='#'>Profile</a></li>
                        <li><hr class='dropdown-divider'></li>
                        <li><a class='dropdown-item' href='../php/cerrarSeccion.php'>Cerrar Seccion</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <?php 
    if ($menu1 && $menu2) {
        echo "<div class=\"menu mostrar\" id=\"menu\">";
    }
    else {
        echo "<div class=\"menu oculto\" id=\"menu\">";
    }
    ?>
        <div class="inicioMenu">
            <img src="../img/logo.png">
            <button id="regresar" class="boton">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                  </svg>
            </button>
        </div>
        <div class="opciones">
            <a href="AdministradorVerPedidos.php">Ver pedidos</a>
            <a href="?apartado=2">Ver Ganancias</a>
            <a href="?apartado=3">Gestionar productos y categorías</a>
            <a href="?apartado=4">Añadir inventario</a>
            <a href="?apartado=5">Ver Repartidores</a>
            <a href="?apartado=6">Ver Administradores</a>
            <a href="#">Ver Tiendas</a>
            <a href="?apartado=8">Mi cuenta</a>
        </div>
    </div>
    <div style="height: 170px;"></div>
    <?php 
    if (isset($_GET['PedidosTienda'])) {
        $id_tienda = $_GET['PedidosTienda'];
        $Nombre_tienda = $_GET['NombreTienda'];
        $pedidos_tiendas = $Conexion->selectConsulta("call pedidos_tiendas('$id_tienda')");

        echo "<div class=\"TituloPedidosTienda\">
        <h1>Pedidos de {$Nombre_tienda}</h1>
        <a href=\"?apartado=7&&estado=7\">
        <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"50\" height=\"50\" fill=\"currentColor\" class=\"bi bi-arrow-left\" viewBox=\"0 0 16 16\">
            <path fill-rule=\"evenodd\" d=\"M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8\"/>
        </svg>
        </a>
        </div>";
        if (count($pedidos_tiendas) == 0) {
            echo "<div class=\"SinPedidos\">Esta tienda aun no tiene ningun pedido</div>";
        }
        else {
            echo "<div class=\"tabla\">";
            foreach ($pedidos_tiendas as $pedido) {
                $Cons = $Conexion->selectConsulta("call Ver_Detalle_Pedido({$pedido->ID})");
                $Total_Pagar = $Conexion->selectConsulta("call Calcular_Total_Pagar_Pedido({$pedido->ID})");
                echo "<div class=\"pedido\">";
                echo "<h1>#{$pedido->ID}</h1>";
                echo "<h3><b>Fecha Realizado:</b> {$pedido->FECHA_PEDIDO}</h3>";
                echo "<h3><b>Fecha Requerida:</b> {$pedido->FECHA_REQUERIDO}</h3>";
                if ($pedido->Estado == 'entregado'){
                    echo "<h3><b>Fecha Entregada:</b> {$pedido->FECHA_ENTREGADA}</h3>";
                }
                echo "<h3><b>Estado:</b> {$pedido->Estado}</h3>";
                echo "<button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalDetalles{$pedido->ID}\">
                        Ver detalles del pedido
                    </button>";
                echo "</div>";
                echo "<div class=\"modal fade\" id=\"ModalDetalles{$pedido->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                            <div class=\"modal-dialog ModalDetalles modal-dialog-centered modal-dialog-scrollable\">
                                <div class=\"modal-content\">
                                    <div class=\"modal-header\">
                                        <h1>Detalle del pedido #{$pedido->ID}</h1>
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
            }
            echo "</div>";
        }
    }
    else {
        $Tiendas = $Conexion->selectConsulta("call Ver_Tiendas()");  
        echo "<div class=\"Tiendas\">";
        echo "<h1 class=\"TituloTienda\">Tiendas</h1>";
        foreach ($Tiendas as $fila) {
            echo "<div class=\"Tienda\">";
                echo "<h3>{$fila->Tienda}</h3>";
                echo "<button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalInformacion{$fila->ID}\">
                        Ver Informacion
                    </button>";
                echo "<a href=\"?estado=7&&PedidosTienda={$fila->ID}&&NombreTienda={$fila->Tienda}\">Ver pedidos</a>";
            echo "</div>";
            echo "<div class=\"modal fade\" id=\"ModalInformacion{$fila->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                    <div class=\"modal-dialog modal-dialog-centered\">
                        <div class=\"modal-content\">
                            <div class=\"modal-header\">
                                <h1>Informacion</h1>
                                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                            </div>
                            <div class=\"modal-body\">
                                <h3><b>Tienda:</b> {$fila->Tienda}<h3>
                                <h3><b>Dirección:</b> {$fila->Dirección}</h3>
                                <h3><b>Propietario:</b> {$fila->Propietario}</h3>
                                <h3><b>Telefono:</b> {$fila->Telefono}</h3>
                            </div>
                        </div>
                    </div>
                </div>";
            }
            echo "</div>";
    }
    ?>
    
<!-- ---------------------------------------------------------------------------------------------------------------------------------------------------- -->
    <script>
        const buttonMenu = document.getElementById('buttonMenu');
        const menu = document.getElementById('menu');
        const buttonRegresar = document.getElementById('regresar');

        buttonMenu.addEventListener('click', function() {
            menu.classList.toggle('oculto');
        });

        buttonRegresar.addEventListener('click', function() {
            menu.classList.add('oculto');
            menu.classList.remove('mostrar')
        });

        document.addEventListener('click', function(event) {
            if (!menu.contains(event.target) && !buttonMenu.contains(event.target)) {
            menu.classList.remove('mostrar')
            menu.classList.add('oculto');
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>