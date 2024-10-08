<?php
session_start();
if (isset($_SESSION['Rol'])){
    if ($_SESSION['Rol'] != 2){
        switch ($_SESSION['Rol']){
            case 1:
                header("Location: AdministradorVerPedidos.php");
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

$records_per_page = 5;
$current_page = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$categoria_seleccionado = isset($_GET['categoria']) ? $_GET['categoria'] : 0;

$id_usuario = $_SESSION['ID'];

$Conexion = new Database();
$Conexion->conectarBD();
try {
    $Tiendas = $Conexion->selectConsulta("call Ver_Tiendas_Cliente('$id_usuario', 1)");

} catch (Exception $e) {
    echo $e->getMessage();
}

function limitarTexto($texto, $limite) {
    if (strlen($texto) > $limite) {
        return substr($texto, 0, $limite) . '...';
    } else {
        return $texto;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $.datepicker.setDefaults($.datepicker.regional['es']);
    </script>
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/EstiloRealizarPedidoCarrito.css">
    <title>La Espiga</title>
</head>
<body style="display: flex; align-items: center; flex-direction: column;">
    <div class="fondo"></div>
    <header>
        <div>
            <button id="buttonMenu" class="boton">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                </svg>
            </button>
            <div>
                <?php
                    if (isset($_SESSION['Rol'])){
                        $cuenta = $Conexion->selectConsulta("select USUARIOS.username as Nombre from USUARIOS where USUARIOS.id_usuario = '$id_usuario'");

                        echo "
                        <div class='dropdown text-end'>
                            <button id=\"carrito\" onclick=\"IrCarrito(1)\">
                                <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"50\" height=\"50\" fill=\"currentColor\" class=\"bi bi-cart4\" viewBox=\"0 0 16 16\">
                                    <path d=\"M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5M3.14 5l.5 2H5V5zM6 5v2h2V5zm3 0v2h2V5zm3 0v2h1.36l.5-2zm1.11 3H12v2h.61zM11 8H9v2h2zM8 8H6v2h2zM5 8H3.89l.5 2H5zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0\"/>
                                </svg>
                            </button>
                            <a href='#' class='d-block link-dark text-decoration-none perfil' id='dropdownUser1' data-bs-toggle='dropdown' aria-expanded='false'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='32' height='32' fill='currentColor' class='bi bi-person-fill' viewBox='0 0 16 16'>
                                    <path d='M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6'/>
                                </svg>
                            </a>
                            <ul class='dropdown-menu text-small' aria-labelledby='dropdownUser1' data-popper-placement='bottom-end' style='position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(-112px, 34px);'>
                                <li><h6>hola,</h6></li>
                                <li><h6>{$cuenta[0]->Nombre}</h6></li>
                                <li><hr class='dropdown-divider'></li>";
                            switch ($_SESSION['Rol']){
                                case 1:
                                    echo "<li><a class='dropdown-item' href='administrador.php'>Mi cuenta</a></li>";
                                    break;
                                case 2:
                                    echo "<li><a class='dropdown-item' href='micuentacliente.php'>Mi cuenta</a></li>";
                                    break;
                                case 3:
                                    echo "<li><a class='dropdown-item' href='RepartidorMicuenta.php'>Mi cuenta</a></li>";
                                    break;
                            }
                            echo "<li><hr class='dropdown-divider'></li>
                                <li><a class='dropdown-item' href='../php/cerrarSeccion.php'>Cerrar Sesión</a></li>
                            </ul>
                        </div>";
                    }
                    else {
                        echo "<a href='views/login.php' class = 'boto'>Login</a>";
                        echo "<a href='views/registro.php' class = 'boto'>Sign-up</a>";
                    }
                ?>
            </div>
        </div>
    </header>

    <?php
    if (isset($_GET['mostrarMenu'])) {
        echo "<div class=\"menu oculto\" id=\"menu\">";
    }
    else {
        echo "<div class=\"menu mostrar\" id=\"menu\">";
    }
    ?>
        <div class="inicioMenu">
            <?php
            if (count($Conexion->selectConsulta("call Ver_Tiendas_Cliente('$id_usuario', 0)")) > 0) {
                echo "<img src=\"../img/LOGOTConTienda.png\">";
            }
            else {
                echo "<img src=\"../img/logo.png\">";
            }
            ?>
            <button id="regresar" class="boton">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                  </svg>
            </button>
        </div>
        <div class="opciones">
            <a href="#" class="opcionSeleccionado">Realizar Pedido</a>
            <a href="ClienteHistorial.php">Ver historial</a>
            <a href="ClienteVerPedidos.php">Ver pedido</a>
            <a href="micuentacliente.php">Mi cuenta</a>
        </div>
    </div>
    <div style="height: 150px;"></div>

    <div class="realizarPedido">
        <div id="fechas">
            <div>
                <h1>Realizar Pedido</h1>
            </div>
            <div id="opcionesFormaPedido">
                <h2>¿Cómo te gustaría recibir el pedido?</h2>
                <div class="formaDeRecibirPedido">
                    <?php
                    if (count($Tiendas) > 0) {
                        echo "<input type=\"radio\" class=\"radius\" name=\"formaRecibir\" id=\"radiusConTienda\" onchange=\"mostrarInformacion(); Consulta_Fechas()\" checked>";
                        echo "<h3>Recibir en tienda</h3>";
                        echo "<input type=\"radio\" class=\"radius\" name=\"formaRecibir\" id=\"radiusSinTienda\" onchange=\"mostrarInformacion(); Consulta_Fechas()\">";
                        echo "<h3>Recoger en sucursal</h3>";
                    }
                    else {
                        echo "<input type=\"radio\" class=\"radius\" name=\"formaRecibir\" id=\"radiusConTienda\" onchange=\"mostrarInformacion(); Consulta_Fechas()\">";
                        echo "<h3>Recibir en tienda</h3>";
                        echo "<input type=\"radio\" class=\"radius\" name=\"formaRecibir\" id=\"radiusSinTienda\" onchange=\"mostrarInformacion(); Consulta_Fechas()\" checked>";
                        echo "<h3>Recoger en sucursal</h3>";
                    }
                    ?>
                </div>
            </div>
            <div id="h2FormaPagoSinTienda">
                <h2>Forma de recibir pedido: Recoger en sucursal</h2>
            </div>
            <div class="SeleccionarTienda">
                <div id="divConTienda">
                    <select id="tiendaSeleccionado" onchange="Consulta_Fechas()">
                        <option value="0">Seleccionar Tienda</option>
                        <?php
                        foreach ($Tiendas as $fila) {
                            echo "<option value=\"{$fila->ID}\">".  limitarTexto(($fila->Nombre." ".$fila->Direccion), 60) ."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div id="divSinTienda">
                    <button type="button" class="VerTienda" data-bs-toggle="modal" data-bs-target="#ModalVerSucursal">
                        Ver ubicacion
                    </button>
                    <div class="modal fade" id="ModalVerSucursal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog ModalDetalles modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1>Ubicacion de la sucursal Cuauhtémoc</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="botonclose"></button>
                                </div>
                                <div class="modal-body">
                                    <iframe class="mapa" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3600.290029541205!2d-103.22238589999999!3d25.528714299999997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x868fc1922bca8a8b%3A0xd46a3d6e6de0eb7c!2sPanader%C3%ADa%20La%20Espiga%20de%20Matamoros!5e0!3m2!1ses!2smx!4v1722617619324!5m2!1ses!2smx" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="fechas" id="soloseleccionarFecha">
                <div class="fecha_sercanos">
                    <div>
                        <input type="radio" class="radius" name="fecha" id="fechaHoy" value="Hoy">
                        <h3>Hoy</h3>
                    </div>
                    <div>
                        <input type="radio" class="radius" name="fecha" id="fechaMañana" value="Mañana">
                        <h3>Mañana</h3>
                    </div>
                </div>
                <div class="calendario">
                    <h3 id="h3soloseleccionarFecha">Otra fecha:</h3>
                    <input type="text" id="fecha_pedido" autocomplete="off" placeholder="Introduce una fecha" onfocus="inputFecha()" readonly>
                </div>
            </div>
            <div class="informacionPedido" id="sinTienda">
                <p><b>Pago:</b> Deberás ir a la sucursal para pagar tu pedido.</p>
                <p><b>Plazo de pago:</b> Tienes hasta un día antes de la fecha seleccionada para realizar el pago.</p>
                <p><b>Entrega en tienda:</b> Una vez que hayas pagado, podrás regresar a la sucursal para la entrega en tienda de tu pedido.</p>
                <p><b>Importante:</b> Si no realizas el pago en el tiempo establecido, tu pedido será cancelado.</p>
            </div>
            <div class="informacionPedido" id="conTienda">
                <p><b>Pago:</b> Podrás pagar tu pedido cuando sea entregado directamente en la tienda que seleccionaste.</p>
                <p><b>Pedidos para el mismo día:</b> Si deseas que el pedido llegue a tu tienda el mismo día, asegúrate de realizarlo antes de las 11:00 a.m. para que podamos procesarlo y entregarlo a tiempo.</p>
            </div>
            <button type="button" onclick="actualizarResultado(); verificarFecha_Tienda()">Confirmar Fecha</button>
        </div>
        <div class="OcultarProductos" id="realizarPedido">
        <hr>
        <div class="Categorias">
            <div class="fecha_seleccionado">
                <div>
                    <h3 id="fecha_seleccionado"></h3>
                    <h3 id="tienda_seleccionado"></h3>
                </div>
                <div class="divOpcionesRealizarPedido">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#ModalConfirmarCambioFecha">
                        Cambiar Fecha o Destino
                    </button>
                    <button onclick="IrCarrito(1)">Confirmar pedido</button>
                </div>
            </div>
            <h1>Categorías</h1>
            <div class="Contenido" id="categorias"></div>
        </div>
        <div class="modal fade" id="ModalConfirmarCambioFecha" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog ModalDetalles modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1>Cambiar Fecha o tienda</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="botonclose"></button>
                    </div>
                    <div class="modal-body">
                        <p>Estas seguro que quieres cambiar de fecha o de tienda</p>
                        <p>Si continua se le borrara el carrito</p>
                        <button type="button" data-bs-dismiss="modal" aria-label="Close" onclick="CambiarFecha()">Continuar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="ModalIrCarrito" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog ModalDetalles modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1>Ir al Carrito</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="botonclose"></button>
                    </div>
                    <div class="modal-body">
                        <p>No tienes suficiente productos para realizar un pedido</p>
                        <p>Tienen que ser mas de 20 productos y menos de 50 productos</p>
                        <button type="button" onclick="IrCarrito(0)">Continuar</button>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        
        <div id="productos">

        </div>

        <div class="paginacion" id="paginacion"></div>
        <hr>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1050" id="imprimirnoti"></div>
    <script>
        const radioButtons = document.querySelectorAll('input[type="radio"][name="fecha"]');
        const textInput = document.getElementById('fecha_pedido');
        const div = document.getElementById('realizarPedido');
        const fechas = document.getElementById('fechas');
        const fecha_seleccionado = document.getElementById('fecha_seleccionado');
        const tienda_seleccionado = document.getElementById('tienda_seleccionado');
        const select = document.getElementById('tiendaSeleccionado');
        const hoy = new Date();
        const mañana = new Date();
        const horas = hoy.getHours();
        mañana.setDate(hoy.getDate() + 1);
        var hoyStr = $.datepicker.formatDate('yy-mm-dd', hoy);
        var mañanaStr = $.datepicker.formatDate('yy-mm-dd', mañana);
        var valorSeleccionado = '';
        var TiendaSeleccionado = '';
        var seleccionando_fecha;
        var formaDePago;
        var mostrarStock;
        var fechasBloqueadas;
        var TotalCarrito;
        
        var pagina;
        
        if (pagina == null){
            pagina = 1;
        }

        if (sessionStorage.getItem("mostrarStock") != null) {
            mostrarStock = sessionStorage.getItem("mostrarStock");
        }
        document.addEventListener('DOMContentLoaded', function() {
            if (sessionStorage.getItem("mensaje") != null) {
                var mensaje = sessionStorage.getItem("mensaje");
                sessionStorage.clear();
                var toastContainer = document.getElementById('imprimirnoti');
                var newToast = document.createElement('div');
                newToast.className = 'toast';
                newToast.setAttribute('role', 'alert');
                newToast.setAttribute('aria-live', 'assertive');
                newToast.setAttribute('aria-atomic', 'true');
                newToast.innerHTML = `
                    <div class="toast-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
                        </svg>
                        <strong class="me-auto">Nueva Notificación</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        ${mensaje}
                    </div>`;
                toastContainer.appendChild(newToast);
                var toast = new bootstrap.Toast(newToast, { delay: 5000 });
                toast.show();
            }
        });

        function verificarFecha_Tienda() {
            if (radiusConTienda.checked) {
                if (select.value == 0 && fechaHoy.checked == false && fechaMañana.checked == false && textInput.value == ''){
                    var toastContainer = document.getElementById('imprimirnoti');
                        var newToast = document.createElement('div');
                        newToast.className = 'toast';
                        newToast.setAttribute('role', 'alert');
                        newToast.setAttribute('aria-live', 'assertive');
                        newToast.setAttribute('aria-atomic', 'true');
                        newToast.innerHTML = `
                            <div class="toast-header">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
                                </svg>
                                <strong class="me-auto">Nueva Notificación</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                Necesitas seleccionar una tienda y una fecha 
                            </div>`;
                        toastContainer.appendChild(newToast);
                        var toast = new bootstrap.Toast(newToast, { delay: 5000 });
                        toast.show();
                }
                else {
                    if (select.value != 0) {
                        if (fechaHoy.checked == false && fechaMañana.checked == false && textInput.value == '') {
                            var toastContainer = document.getElementById('imprimirnoti');
                            var newToast = document.createElement('div');
                            newToast.className = 'toast';
                            newToast.setAttribute('role', 'alert');
                            newToast.setAttribute('aria-live', 'assertive');
                            newToast.setAttribute('aria-atomic', 'true');
                            newToast.innerHTML = `
                                <div class="toast-header">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                                        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
                                    </svg>
                                    <strong class="me-auto">Nueva Notificación</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                                <div class="toast-body">
                                    Necesitas seleccionar una fecha
                                </div>`;
                            toastContainer.appendChild(newToast);
                            var toast = new bootstrap.Toast(newToast, { delay: 5000 });
                            toast.show();
                        }
                    }
                    if (select.value == 0) {
                        var toastContainer = document.getElementById('imprimirnoti');
                        var newToast = document.createElement('div');
                        newToast.className = 'toast';
                        newToast.setAttribute('role', 'alert');
                        newToast.setAttribute('aria-live', 'assertive');
                        newToast.setAttribute('aria-atomic', 'true');
                        newToast.innerHTML = `
                            <div class="toast-header">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
                                </svg>
                                <strong class="me-auto">Nueva Notificación</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                Necesitas seleccionar una tienda
                            </div>`;
                        toastContainer.appendChild(newToast);
                        var toast = new bootstrap.Toast(newToast, { delay: 5000 });
                        toast.show();
                    }
                }
            }
            else if (radiusSinTienda.checked) {
                if (textInput.value == '') {
                    var toastContainer = document.getElementById('imprimirnoti');
                    var newToast = document.createElement('div');
                    newToast.className = 'toast';
                    newToast.setAttribute('role', 'alert');
                    newToast.setAttribute('aria-live', 'assertive');
                    newToast.setAttribute('aria-atomic', 'true');
                    newToast.innerHTML = `
                        <div class="toast-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
                            </svg>
                            <strong class="me-auto">Nueva Notificación</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            Necesitas seleccionar una fecha
                        </div>`;
                    toastContainer.appendChild(newToast);
                    var toast = new bootstrap.Toast(newToast, { delay: 5000 });
                    toast.show();
                }
            }
        }
        function cancelarCarrito() {
            $.ajax({
                type: 'POST',
                url: '../php/cancelarCarrito.php',
                success: function() {
                }
            });
        }
        function mostrarInformacion(){
            
            if (<?php echo count($Tiendas)?> > 0) {
                opcionesFormaPedido.style.display = 'block';
                h2FormaPagoSinTienda.style.display = 'none';
                if (radiusConTienda.checked) {
                    fechaMañana.disabled = false;
                    fechaMañana.parentElement.children[1].style.color = '#ddb892';
                    
                    if (horas > 10) {
                        fechaHoy.disabled = true;
                        fechaHoy.parentElement.children[1].style.color = '#ddb8929c';
                    }
                    else {
                        fechaHoy.disabled = false;
                        fechaHoy.parentElement.children[1].style.color = '#ddb892';
                    }

                    divConTienda.style.display = 'block';
                    divSinTienda.style.display = 'none';
                    sinTienda.style.display = 'none';
                    conTienda.style.display = 'block';
                }
                if (radiusSinTienda.checked) {
                    select.value = 0;
                    fechaHoy.disabled = true;
                    fechaMañana.disabled = true;
                    fechaHoy.parentElement.children[1].style.color = '#ddb8929c';
                    fechaMañana.parentElement.children[1].style.color = '#ddb8929c';
                    divConTienda.style.display = 'none';
                    divSinTienda.style.display = 'block';
                    sinTienda.style.display = 'block';
                    conTienda.style.display = 'none';
                }
            }
            else {
                h3soloseleccionarFecha.innerHTML = 'Seleccionar Fecha:';
                fechaHoy.style.display = 'none';
                fechaHoy.parentElement.children[1].style.display = 'none';
                fechaMañana.style.display = 'none';
                fechaMañana.parentElement.children[1].style.display = 'none';
                soloseleccionarFecha.style.justifyContent = 'start';
                soloseleccionarFecha.style.marginLeft = '65px';
                opcionesFormaPedido.style.display = 'none';
                h2FormaPagoSinTienda.style.display = 'block';
                divConTienda.style.display = 'none';
                conTienda.style.display = 'none';
            }
        }
        mostrarInformacion();
        function mostrarProductos(current_page, categoria_seleccionado){
            $.ajax({
                type: 'POST',
                url: '../php/MostrarProductos.php',
                data: { current_page: current_page, categoria_seleccionado: categoria_seleccionado, mostrarStock: mostrarStock },
                success: function(response) {
                    $('#productos').html(response);
                }
            });
        }
        mostrarProductos(pagina, <?php echo $categoria_seleccionado ?>)
        function mostrarPaginacion() {
            $.ajax({
                type: 'POST',
                url: '../php/MostrarPaginacion.php',
                data: { pagina: pagina, categoria_seleccionado: <?php echo $categoria_seleccionado?>, tipo: 1 },
                success: function(response) {
                    $('#paginacion').html(response);
                }
            });
        }
        mostrarPaginacion()
        function cambiarPaginacion(cambio) {
            pagina = cambio;
            mostrarPaginacion()
            mostrarProductos(pagina, <?php echo $categoria_seleccionado ?>)
        }

        function cambiarValorParametroUrl(parametro, valor){
            const url = new URL(window.location.href); 
            const urlCambiado = new URLSearchParams(url.search); 

            urlCambiado.set(parametro, valor); 

            url.search = urlCambiado.toString(); 
            history.replaceState({}, document.title, url.toString()); 
        }

        function mostrarCategorias() {
            $.ajax({
                type: 'POST',
                url: '../php/MostrarCategoiras.php',
                data: { fecha: valorSeleccionado, categoria_seleccionado: <?php echo $categoria_seleccionado ?> },
                success: function(response) {
                    $('#categorias').html(response);
                }
            });
        }

        function actualizarResultado() {
            var hoy = new Date();
            var mañana = new Date();
            mañana.setDate(hoy.getDate() + 1);

            if (sessionStorage.getItem("fecha") != null) {
                valorSeleccionado = sessionStorage.getItem("fecha");
            }
            if (sessionStorage.getItem("seleccionando_fecha") != null) {
                seleccionando_fecha = sessionStorage.getItem("seleccionando_fecha");
            }
            if (sessionStorage.getItem("formaDePago") != null) {
                formaDePago = sessionStorage.getItem("formaDePago");
            }
            if (sessionStorage.getItem("mostrarStock") != null) {
                mostrarStock = sessionStorage.getItem("mostrarStock");
            }
            if (seleccionando_fecha == 'true') {
                div.style.display = 'block';
                fechas.style.display = 'none';

                opcion_seleccionado = sessionStorage.getItem("opcion_seleccionado");

                fecha_seleccionado.textContent = "Fecha seleccionado: " + valorSeleccionado;
                if (formaDePago == 1) {
                    tienda_seleccionado.textContent = "Forma de recibir pedido: Recoger en sucursal";
                }
                else {
                    tienda_seleccionado.textContent = "Destino seleccionado: " + opcion_seleccionado;
                }
            }

            radioButtons.forEach(radio => {
                if (radio.checked) {
                    TiendaSeleccionado = select.value;
                    sessionStorage.setItem("idTienda", TiendaSeleccionado);
                    if (select.value != 0) {
                        valorSeleccionado = radio.value;
                        
                        seleccionando_fecha = true;

                        if (valorSeleccionado == "Hoy") {
                            sessionStorage.setItem("fecha", hoyStr);
                        }
                        else if (valorSeleccionado == "Mañana") {
                            sessionStorage.setItem("fecha", mañanaStr);
                        }
                        
                        sessionStorage.setItem("seleccionando_fecha", seleccionando_fecha);
                        sessionStorage.setItem("formaDePago", 2);
                        sessionStorage.setItem("mostrarStock", 1);
                        mostrarStock = 1;

    
                        opcion_seleccionado = select.options[select.selectedIndex];
    
                        sessionStorage.setItem("opcion_seleccionado", opcion_seleccionado.textContent);
    
                        fecha_seleccionado.textContent = "Fecha seleccionado: " + valorSeleccionado;
                        tienda_seleccionado.textContent = "Destino seleccionado: " + opcion_seleccionado.textContent;
    
                        cancelarCarrito()
                        mostrarCategorias()
                        mostrarProductos(1, 0)

                        div.style.display = 'block';
                        fechas.style.display = 'none';
                    }
                }
            });

            if (textInput.value !== '') {
                TiendaSeleccionado = select.value;
                sessionStorage.setItem("idTienda", TiendaSeleccionado);
                if (radiusSinTienda.checked){
                    valorSeleccionado = textInput.value;
                    
                    seleccionando_fecha = true;
                    
                    sessionStorage.setItem("fecha", valorSeleccionado);
                    sessionStorage.setItem("seleccionando_fecha", seleccionando_fecha);
                    sessionStorage.setItem("formaDePago", 1);
                    sessionStorage.setItem("mostrarStock", 0);
                    mostrarStock = 0;

                    fecha_seleccionado.textContent = "Fecha seleccionado: " + valorSeleccionado;
                    tienda_seleccionado.textContent = "Forma de recibir pedido: Recoger en sucursal";

                    cancelarCarrito()
                    mostrarCategorias()
                    mostrarProductos(1, 0)

                    div.style.display = 'block';
                    fechas.style.display = 'none';
                }
                else {
                    if (select.value != 0) {
                        valorSeleccionado = textInput.value;
        
                        seleccionando_fecha = true;
        
                        sessionStorage.setItem("fecha", valorSeleccionado);
                        sessionStorage.setItem("seleccionando_fecha", seleccionando_fecha);
                        sessionStorage.setItem("formaDePago", 2);
                        sessionStorage.setItem("mostrarStock", 0);

                        mostrarStock = 0;
        
                        opcion_seleccionado = select.options[select.selectedIndex];
        
                        sessionStorage.setItem("opcion_seleccionado", opcion_seleccionado.textContent);
        
                        fecha_seleccionado.textContent = "Fecha seleccionado: " + valorSeleccionado;
                        tienda_seleccionado.textContent = "Destino seleccionado: " + opcion_seleccionado.textContent;
        
                        cancelarCarrito()
                        mostrarCategorias()
                        mostrarProductos(1, 0)

                        div.style.display = 'block';
                        fechas.style.display = 'none';
                    }
                }
            }
        }
        actualizarResultado();
        if (sessionStorage.getItem("seleccionando_fecha") != null) {
            mostrarCategorias()
        }
        
        function Consulta_Fechas() {
            $.ajax({
                type: 'POST',
                url: '../php/Consulta_Fechas.php',
                data: { idTienda: select.value },
                success: function(response) {
                    fechasBloqueadas = response;
                    if (radiusConTienda.checked) {
                        if (select.value != 0) {
                            if (response.includes(hoyStr) && select.value == 0){
                                fechaHoy.disabled = true;
                                fechaHoy.parentElement.children[1].style.color = '#ddb8929c';
                            }
                            else {
                                if (!(horas > 10)) {
                                    fechaHoy.disabled = false;
                                    fechaHoy.parentElement.children[1].style.color = '#ddb892';
                                    fechaHoy.checked = false;
                                }
                            }
                            if (response.includes(mañanaStr) && select.value == 0){
                                fechaMañana.disabled = true;
                                fechaMañana.parentElement.children[1].style.color = '#ddb8929c';
                            }
                            else {
                                fechaMañana.disabled = false;
                                fechaMañana.parentElement.children[1].style.color = '#ddb892';
                                fechaMañana.checked = false;
                            }
                        }
                    }
                }
            });

            if (select.value == 0) {
                textInput.value = '';
            }

            if (select.value == 0) {
                fechaHoy.disabled = true;
                fechaHoy.parentElement.children[1].style.color = '#ddb8929c';
                fechaHoy.checked = false;
                fechaMañana.disabled = true;
                fechaMañana.parentElement.children[1].style.color = '#ddb8929c';
                fechaMañana.checked = false;
            }
        }
        Consulta_Fechas()

        function mensajeCalendario() {
            if (select.value == 0) {
                var toastContainer = document.getElementById('imprimirnoti');
                var newToast = document.createElement('div');
                newToast.className = 'toast';
                newToast.setAttribute('role', 'alert');
                newToast.setAttribute('aria-live', 'assertive');
                newToast.setAttribute('aria-atomic', 'true');
                newToast.innerHTML = `
                    <div class="toast-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
                        </svg>
                        <strong class="me-auto">Nueva Notificación</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        Necesitas seleccionar una tienda
                    </div>`;
                toastContainer.appendChild(newToast);
                var toast = new bootstrap.Toast(newToast, { delay: 5000 });
                toast.show();
            }
        }
        
        radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    textInput.value = ''; 
                }
            });
        });

        function inputFecha(){
            radioButtons.forEach(radio => {
                radio.checked = false; 
            });

            if (radiusConTienda.checked && select.value == 0) {
                mensajeCalendario()
                textInput.blur();
            }
        }

        function CambiarFecha(){
            div.style.display = 'none';
            fechas.style.display = 'flex';
            seleccionando_fecha = false;
            sessionStorage.setItem("seleccionando_fecha", seleccionando_fecha);
            cambiarValorParametroUrl("categoria", 0)
            cancelarCarrito()
        }

        $(document).ready(function() {
            $.datepicker.regional['es'] = {
                closeText: 'Cerrar',
                prevText: 'Anterior',
                nextText: 'Siguiente',
                currentText: 'Hoy',
                monthNames: ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'],
                monthNamesShort: ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'],
                dayNames: ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'],
                dayNamesShort: ['dom', 'lun', 'mar', 'mié', 'jue', 'vie', 'sáb'],
                dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
                weekHeader: 'Sm',
                dateFormat: 'dd/mm/yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['es']);
        });
        function cargarCalendario() {
            $("#fecha_pedido").datepicker({
                beforeShowDay: function(date) {
                    var string = $.datepicker.formatDate('yy-mm-dd', date);
                    var today = new Date();
                    var mañana = new Date();
                    var sixMonthsLater = new Date();
                    sixMonthsLater.setMonth(today.getMonth() + 6);
                    mañana.setDate(today.getDate() + 1);

                    // Convertir las fechas a formato 'yy-mm-dd'
                    var todayStr = $.datepicker.formatDate('yy-mm-dd', today);
                    var mañanaStr = $.datepicker.formatDate('yy-mm-dd', mañana);
                    var sixMonthsLaterStr = $.datepicker.formatDate('yy-mm-dd', sixMonthsLater);

                    // Deshabilitar fechas pasadas
                    if (string < todayStr) {
                        return [false, 'ui-state-disabled'];
                    }

                    // Deshabilitar fechas más allá de 6 meses
                    if (string > sixMonthsLaterStr) {
                        return [false, 'ui-state-disabled'];
                    }

                    if (select.value == 0) {
                        // Deshabilitar fechas bloqueadas y aplicar un estilo especial
                        if (fechasBloqueadas.includes(string)) {
                            return [false, 'ui-state-blocked'];
                        }
                    }

                    if (string == todayStr || string == mañanaStr) {
                        return [false, 'ui-state-disabled'];
                    }

                    // Permitir fechas que no están bloqueadas ni son pasadas
                    return [true, ''];
                },
                dateFormat: 'yy-mm-dd', // Formato de fecha
                beforeShow: function(input, inst) {
                    if (radiusConTienda.checked && select.value == 0) {
                        // Ocultar el datepicker si la variable es falsa
                        setTimeout(function() {
                            $(inst.dpDiv).hide();
                        }, 0);
                        return false; // Prevenir que se muestre el datepicker
                    }
                }
            });
        }
        cargarCalendario()

        function ejecutarBoton(tipo, id_producto, id_input){
            var input = document.getElementById(id_input);
            var enCarrito = document.getElementById('enCarrito' + id_producto);
            var botonEliminar =document.getElementById('botonEliminar' + id_producto)
            if (tipo == 2) {
                input.value = 0;
            }
            let cantidad = input.value;
            if (cantidad == 0){
                enCarrito.classList.add('enCarritoDesactivado');
                enCarrito.classList.remove('enCarritoActivo');
                botonEliminar.style.display = 'none';
            }
            else {
                enCarrito.classList.add('enCarritoActivo');
                enCarrito.classList.remove('enCarritoDesactivado');
                botonEliminar.style.display = 'block';
            }
            $.ajax({
                type: 'POST',
                url: '../php/ModificarCarrito.php',
                data: { tipo: tipo, cantidad: cantidad, id_producto: id_producto, mostrarStock: mostrarStock }, 
                success: function() {
                }
            });
        }
        function cambiarCantidad(numero, inputCantidad, id_producto, id_input, Disponible) {
            let valor = inputCantidad.value;
            let cantidad = parseInt(valor, 10) + numero;
            if (valor.trim() == ''){
                cantidad = 1;
            }
            if (cantidad < 0) {
                cantidad = 0;
            }
            if (mostrarStock == 1) {
                if (cantidad > 50 && Disponible >= 50) {
                    cantidad = 50;
                }
                else if (cantidad > Disponible){
                    cantidad = Disponible;
                }
            }
            else {
                if (cantidad > 50) {
                    cantidad = 50;
                }
            }
            inputCantidad.value = cantidad;
            ejecutarBoton(1, id_producto, id_input);
        }
        function validarNumero(input, Disponible) {
            input.value = input.value.replace(/\D/g, '');
            let cantidad = parseInt(input.value, 10);
            if (mostrarStock == 1) {
                if (cantidad > 50 && Disponible >= 50) {
                    input.value = 50;
                }
                else if (cantidad > Disponible){
                    input.value = Disponible;
                }
            }
            else {
                if (cantidad > 50) {
                    input.value = 50;
                }
            }
        }
        function IrCarrito(mostrarModal) {
            $.ajax({
                type: 'POST',
                url: '../php/calcularTotalCarrito.php',
                success: function(response) {
                    if (mostrarModal == 1 && seleccionando_fecha == true) {
                        var modal = new bootstrap.Modal(document.getElementById('ModalIrCarrito'));
                        if (response > 19 && response < 51) {
                            window.location.href = "ClienteCarrito.php?mostrarStock=" + mostrarStock;
                        }
                        else {
                            modal.show();
                        }
                    }
                    else {
                        window.location.href = "ClienteCarrito.php?mostrarStock=" + mostrarStock;
                    }
                }
            });
        }

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
