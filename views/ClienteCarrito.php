<?php
session_start();
if (isset($_SESSION['Rol'])){
    if ($_SESSION['Rol'] != 2){
        switch ($_SESSION['Rol']){
            case 1:
                header("Location: Administrador.php");
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
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

$id_usuario = $_SESSION['ID'];

$offset = ($current_page - 1) * $records_per_page;

$Conexion = new Database();
$Conexion->conectarBD();
try {
    $productos = $Conexion->selectConsulta("call Ver_Carrito($id_usuario, $offset, $records_per_page)");
    
    $productos_totales = $Conexion->selectConsulta("call Ver_Carrito($id_usuario, null, null)"); 

    $total_pagar = $Conexion->selectConsulta("call Calcular_Total($id_usuario, 0)"); 

    $total_records = count($productos_totales);
    $total_pages = ceil($total_records / $records_per_page);

    if (empty($productos) && $current_page > 1) {
        $current_page = $current_page - 1;
    }

} catch (Exception $e) {
    echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <link rel="stylesheet" href="../css/HEAder.css">
    <link rel="stylesheet" href="../css/clienteRealizarPedidoCarrito.css">
    <title>La Espiga</title>
</head>
<body>
    <div class="fondo"></div>
    <header>
        <div>
            <button id="buttonMenu" class="boton">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                </svg>
            </button>
            <div>
                <div class='dropdown text-end'>
                    <button id="carrito">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-cart4" viewBox="0 0 16 16">
                            <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5M3.14 5l.5 2H5V5zM6 5v2h2V5zm3 0v2h2V5zm3 0v2h1.36l.5-2zm1.11 3H12v2h.61zM11 8H9v2h2zM8 8H6v2h2zM5 8H3.89l.5 2H5zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0"/>
                        </svg>
                    </button>
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

    <div class="menu oculto" id="menu">
        <div class="inicioMenu">
            <img src="../img/logo.png">
            <button id="regresar" class="boton">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                  </svg>
            </button>
        </div>
        <div class="opciones">
            <a href="ClienteRealizarPedido.php">Realizar Pedido</a>
            <a href="">Ver historial</a>
            <a href="">Ver pedido</a>
            <a href="">Mi cuenta</a>
        </div>
    </div>
    <div style="height: 170px;"></div>
    <div class="confirmarCarritoFondo" style="display: flex; justify-content: space-around;">
        <div class="realizarPedido" id="divProductos">
            <hr>
            <?php
        
            foreach ($productos as $fila) {
                $total_Producto = $fila->Cantidad * $fila->Precio;
                echo "
                <div class=\"producto\" id=\"div{$fila->Producto}\">
                    <img src=\"../img/productos/{$fila->Imagen}\">
                    <div class=\"info\">
                        <div class=\"info2\">
                            <h3>{$fila->Nombre}</h3>
                        </div>
                        <div class=\"cantidad\">
                            <button type=\"button\" onclick=\"cambiarCantidad(-1, this.nextElementSibling, {$fila->Producto}, 'input{$fila->Producto}', 'precio{$fila->Producto}', {$fila->Precio}, {$fila->Disponible})\" style=\"border-radius: 15px 0 0 15px;\">
                                <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"25\" height=\"25\" fill=\"currentColor\" class=\"bi bi-caret-left-fill\" viewBox=\"0 0 16 16\">
                                    <path d=\"m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z\"/>
                                </svg>
                            </button>
                            <input type=\"text\" name=\"cantidad\" id=\"input{$fila->Producto}\" value=\"{$fila->Cantidad}\" oninput=\"validarNumero(this, {$fila->Disponible})\" onblur=\"ejecutarBoton(1, {$fila->Producto}, 'input{$fila->Producto}', 'precio{$fila->Producto}', {$fila->Precio})\" onkeydown=\"if (this.value <= 0 || this.value == '') {return event.key != 'Enter';}\">
                            <button type=\"button\" onclick=\"cambiarCantidad(1, this.previousElementSibling, {$fila->Producto}, 'input{$fila->Producto}', 'precio{$fila->Producto}', {$fila->Precio}, {$fila->Disponible})\" style=\"border-radius: 0 15px 15px 0;\">
                                <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"25\" height=\"25\" fill=\"currentColor\" class=\"bi bi-caret-right-fill\" viewBox=\"0 0 16 16\">
                                    <path d=\"m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z\"/>
                                </svg>
                            </button>
                            <h3 class=\"infoPago\" id=\"precio{$fila->Producto}\"> x \${$fila->Precio} = \${$total_Producto} </h3>
                        </div>";
                        if ($_GET['mostrarStock'] == 1) {
                            echo "<h4><b>Disponible:</b> {$fila->Disponible} piezas</h4>";
                        }
                echo "</div> 
                    <div class=\"botones\">
                        <button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalDescipcion{$fila->Producto}\">
                            Descripcion
                        </button>
                        <button type=\"button\" onclick=\"ejecutarBoton(2, {$fila->Producto}, 'input{$fila->Producto}', 'precio{$fila->Producto}', {$fila->Precio})\">Eliminar</button>
                    </div>
                </div>
                <div class=\"modal fade\" id=\"ModalDescipcion{$fila->Producto}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
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
                <hr id=\"hr{$fila->Producto}\">
                ";
            }
            ?>
    
            <div class="paginacion">
                <?php if ($total_pages > 1): ?>
                    <?php if ($current_page > 1): ?>
                        <div>
                            <a style="border-radius: 30px 0 0 30px;" href="?page=<?php echo $current_page - 1; ?>">Anterior</a>
                        </div>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if ($current_page == 1 && $i == 1): ?>
                            <div <?php echo ($i == $current_page) ? 'active' : ''; ?>>
                                <a style="border-radius: 30px 0 0 30px; background-color: #724a32; color: #ddb892; box-shadow: none;" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </div>
                        <?php elseif ($i == 1): ?>
                            <div <?php echo ($i == $current_page) ? 'active' : ''; ?>>
                                <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </div>
                        <?php endif ?>
    
                        <?php if ($current_page == $total_pages && $i == $current_page): ?>
                            <div <?php echo ($i == $current_page) ? 'active' : ''; ?>>
                                <a style="border-radius: 0 30px 30px 0; background-color: #724a32; color: #ddb892; box-shadow: none;" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </div>
                        <?php elseif ($i == $total_pages): ?>
                            <div <?php echo ($i == $current_page) ? 'active' : ''; ?>>
                                <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </div>
                        <?php endif ?>
                                
                        <?php if ($i != 1 && $i != $total_pages): ?>
                            <?php if ($i == $current_page): ?>
                                <div <?php echo ($i == $current_page) ? 'active' : ''; ?>>
                                    <a style="background-color: #724a32; color: #ddb892; box-shadow: none;" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </div>
                            <?php else: ?>
                                <div <?php echo ($i == $current_page) ? 'active' : ''; ?>>
                                    <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    <?php endfor; ?>
                    <?php if ($current_page < $total_pages): ?>
                        <div>
                            <a style="border-radius: 0 30px 30px 0;" href="?page=<?php echo $current_page + 1; ?>">Siguiente</a>
                        </div>
                    <?php endif; ?>
                <?php endif ?>
            </div>
            <hr>
        </div>
    
        <div>
            <div class="Total_Pagar" id="divPagoTotal">
                <h3 id="fecha_seleccionado"></h3>
                <h3 id="tienda_seleccionado"></h3>
                <button type="button" data-bs-toggle="modal" data-bs-target="#ModalConfirmarCambioFecha">
                    Cambiar Fecha o Destino
                </button>
                <br>
                <div class="info_pago">
                    <h1 style="border-bottom: solid 3px #ddb892; padding: 15px 0;">Total a Pagar</h1>
                    <div id="Total_Pagar"></div>
                </div>
                <h1>Realizar Pedido</h1>
                <button type="button" data-bs-toggle="modal" data-bs-target="#ModalConfirmarPedido">
                    Confirmar Pedido
                </button>
                <div class="modal fade" id="ModalConfirmarPedido" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog ModalDetalles modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1>Realizar Pedido</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="botonclose"></button>
                            </div>
                            <div class="modal-body">
                                <p>Estas seguro que quieres hacer este pedido</p>
                                <p>ya no vas a poder modificar ni cancelar el pedido</p>
                                <button onclick="RealizarPedido()" saria-label="Close">Confirmar Pedido</button>
                            </div>
                        </div>
                    </div>
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
                                <button onclick="CambiarFecha()">Continuar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1050" id="imprimirnoti"></div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var mostrarStock;
        var idTienda;
        var fecha;
        var formaDePago;

        if (sessionStorage.getItem("mostrarStock") != null) {
            mostrarStock = sessionStorage.getItem("mostrarStock");
        }
        if (sessionStorage.getItem("fecha") != null) {
            fecha = sessionStorage.getItem("fecha");
        }
        if (sessionStorage.getItem("formaDePago") != null) {
            formaDePago = sessionStorage.getItem("formaDePago");
        }
        idTienda = sessionStorage.getItem("idTienda");
        function cancelarCarrito() {
            $.ajax({
                type: 'POST',
                url: '../php/cancelarCarrito.php',
                success: function() {
                }
            });
        }
        var Total_registros = <?php echo $total_records?>;
        fecha_seleccionado.innerHTML = "<b>Fecha seleccionado: </b> " + fecha;
        if (formaDePago == 1){
            tienda_seleccionado.innerHTML = "Forma de recibir pedido: Recoger en sucursal";
        }
        else {
            tienda_seleccionado.innerHTML = "<b>Destino seleccionado: </b> " + sessionStorage.getItem("opcion_seleccionado");
        }
        function mensaje_Sin_Carrito(){
            const divProductos = document.getElementById('divProductos');
            const divPagoTotal = document.getElementById('divPagoTotal');
            if (Total_registros === 0) {
                divProductos.innerHTML = `
                <div class='sinCarrito'>
                    <h1>No tienes ningun producto en el carrito</h1>
                    <div>
                        <h3>Para agregar productos dirijite a </h3>
                        <a href='Cliente.php?apartado=1'>Realizar Pedido</a>
                    </div>
                </div>`;
                divProductos.style.paddingBottom = '90px';
                divPagoTotal.style.display = 'none';
            }
        }
        mensaje_Sin_Carrito();
        function calcular_Total(id_precio, cantidad, Precio){
            var elemento = document.getElementById(id_precio);
            var calculo = (cantidad * Precio);
            elemento.innerHTML = " x $" + Precio + ".00 = $" + calculo;
            $.ajax({
                type: 'POST',
                url: '../php/calcularTotal.php',
                data: { idTienda: idTienda },
                success: function(response) {
                    $('#Total_Pagar').html(response);
                }
            });
        }
        $.ajax({
            type: 'POST',
            url: '../php/calcularTotal.php',
            data: { idTienda: idTienda },
            success: function(response) {
                $('#Total_Pagar').html(response);
            }
        });
        function ejecutarBoton(tipo, id_producto, id_input, id_precio, Precio){
            var input = document.getElementById(id_input);
            var div = document.getElementById('div' + id_producto);
            var hr = document.getElementById('hr' + id_producto);
            if (tipo == 2) {
                input.value = 0;
            }
            let cantidad = input.value;
            if (cantidad == 0) {
                div.style.display = 'none';
                hr.style.display = 'none';
                Total_registros = Total_registros - 1;
                mensaje_Sin_Carrito();
            }
            $.ajax({
                type: 'POST',
                url: '../php/ModificarCarrito.php',
                data: { tipo: tipo, cantidad: cantidad, id_producto: id_producto, mostrarStock: mostrarStock },
                success: function() {
                    calcular_Total(id_precio, cantidad, Precio);
                }
            });
        }
        function cambiarCantidad(numero, inputCantidad, id_producto, id_input, id_precio, Precio, Disponible) {
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
            ejecutarBoton(1, id_producto, id_input, id_precio, Precio);
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
        function CambiarFecha() {
            sessionStorage.setItem("seleccionando_fecha", false);
            window.location.href = "ClienteRealizarPedido.php";
            cancelarCarrito()
        }
        function RealizarPedido() {
            $.ajax({
                type: 'POST',
                url: '../php/RealizarPedido.php',
                data: { fecha: fecha, idTienda: idTienda, mostrarStock: mostrarStock },
                success: function(response) {
                    if (response == "pedido realizado") {
                        sessionStorage.clear();
                        sessionStorage.setItem("mensaje", response)
                        window.location.href = "ClienteRealizarPedido.php";
                    }
                    var toastContainer = document.getElementById('imprimirnoti');
                    var newToast = document.createElement('div');  // Crear un nuevo elemento toast
                    newToast.className = 'toast';
                    newToast.setAttribute('role', 'alert');
                    newToast.setAttribute('aria-live', 'assertive');
                    newToast.setAttribute('aria-atomic', 'true');
                    newToast.innerHTML = 
                    `  
                    <div class="toast-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
                        </svg>
                        <strong class="me-auto">Nueva Notificación</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        ${response}
                    </div>
                    `;
                    toastContainer.appendChild(newToast);  // Añadir el nuevo toast al contenedor
                    var toast = new bootstrap.Toast(newToast, {  // Inicializar y mostrar el nuevo toast
                        delay: 5000 // Duración del toast en milisegundos
                    });
                    toast.show();
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
        });

        document.addEventListener('click', function(event) {
            if (!menu.contains(event.target) && !buttonMenu.contains(event.target)) {
                menu.classList.add('oculto');
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
