<?php
session_start();

require '../php/conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();
$id_usuario = $_SESSION['ID'];

$consulta = $Conexion->selectConsulta("call verificarEstadoCuenta($id_usuario)");
$estado = $consulta[0]->Estatus;
if ($estado == 0) {
    header("Location: ../php/cerrarSeccion.php");
}

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

$estado = isset($_GET['estado']) ? $_GET['estado'] : 1;
$TipoCliente = isset($_GET['TipoCliente']) ? $_GET['TipoCliente'] : 1;



?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function MostrarPedidos(){
        const inputCheckbox = document.getElementById('PorSemana');
        const buscarID = document.getElementById('buscarID');
        var ID;
        var checkbox;
        if (buscarID.value != '') {
            ID = buscarID.value;
        }
        else {
            ID = 'null';
        }
        if (inputCheckbox) {
            checkbox = inputCheckbox.checked;
        }
        else {
            checkbox = false;
        }
        $.ajax({
            type: 'POST',
            url: '../php/MostrarPedidos.php',
            data: { estado: <?php echo $estado?>, TipoCliente: <?php echo $TipoCliente?>, buscarID: ID, PorSemana: checkbox},
            success: function(response) {
                $('#pedidos' + <?php echo $estado?>).html(response);
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
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/administradorVerPedidos.css">
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
            <?php
                    if (isset($_SESSION['Rol'])){

                        $cuenta = $Conexion->selectConsulta("select USUARIOS.username as Nombre from USUARIOS where USUARIOS.id_usuario = '$id_usuario'");

                        echo "
                        <div class='dropdown text-end'>
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
            <a href="#" class="opcionSeleccionado">Ver pedidos</a>
            <a href="Administradorganancias.php">Ver Ganancias</a>
            <a href="AdministradorGestionProductos.php">Gestionar productos y categorías</a>
            <a href="AdminInventario.php">Añadir inventario</a>
            <a href="habiydesarepa.php">Ver Repartidores</a>
            <a href="AdminAñadir.php">Ver Administradores</a>
            <a href="AdministradorVerTiendas.php">Ver Tiendas</a>
            <a href="administrador.php">Mi cuenta</a>
        </div>
    </div>
    <div style="height: 170px;"></div>
    <div class="Titulo">
        <?php
        switch ($estado) {
            case 1:
                if ($TipoCliente == 1){
                    echo "<h1>Pedidos Pendientes de clientes con Tienda</h1>";
                }
                else {
                    echo "<h1>Pedidos Pendientes</h1>";
                }
                break;
            case 2:
                if ($TipoCliente == 1){
                    echo "<h1>Pedidos Pendientes a Pagar de clientes con Tienda</h1>";
                }
                else {
                    echo "<h1>Pedidos Pendientes a Pagar</h1>";
                }
                break;
            case 3:
                if ($TipoCliente == 1){
                    echo "<h1>Pedidos Aceptados de clientes con Tienda</h1>";
                }
                else {
                    echo "<h1>Pedidos Aceptados</h1>";
                }
                break;
            case 4:
                if ($TipoCliente == 1){
                    echo "<h1>Pedidos Cancelados de clientes con Tienda</h1>";
                }
                else {
                    echo "<h1>Pedidos Cancelados</h1>";
                }
                break;
            case 5:
                if ($TipoCliente == 1){
                    echo "<h1>Pedidos Entregados de clientes con Tienda</h1>";
                }
                else {
                    echo "<h1>Pedidos Entregados</h1>";
                }
                break;
        }
        echo "<div class=\"OpcionesFiltro\">";
            echo "<input type=\"text\" id=\"buscarID\" placeholder=\"Buscar por ID\" oninput=\"if (document.getElementById('PorSemana')) { PorSemana.checked = false; } buscarNombre.value = ''; MostrarPedidos();\">";
            echo "<input type=\"text\" id=\"buscarNombre\" placeholder=\"Buscar por Nombre\" oninput=\"if (document.getElementById('PorSemana')) { PorSemana.checked = false; } buscarID.value = ''; MostrarPedidos();\">";

            if ($estado == 1) {
                echo "<div style=\"display: flex; justify-content: space-around; align-items: center;\">";
                echo "<h3>Por semana</h3>";
                echo "<input style=\"margin-left: 20px; height: 20px; width: 20px; \" type=\"checkbox\" id=\"PorSemana\" onchange=\"buscarID.value = ''; MostrarPedidos();\" checked>";
                echo "</div>";
            }
        echo "</div>";
        ?>
    </div>
    
    <div class="Contenedor">
        <div class="Pedidos">
            <script>
                MostrarPedidos()
            </script>
            <div id="pedidos<?php echo $estado?>"></div>
        </div>
        <div class="ContenedorOpciones">
            <div class="OpcionesPedidos">
                <div class="OpcionesAcomodadas">
                    <?php
                    if ($estado != 2){
                        echo "<div class=\"ApartadoPedidos moverDerecha\">";
                        echo "<a href=\"?estado=$estado&&TipoCliente=1\" class=". ($TipoCliente == 1 ? "seleccionado" : "") .">Con Tienda</a>";
                        echo "<a href=\"?estado=$estado&&TipoCliente=2\" class=". ($TipoCliente == 2 ? "seleccionado" : "") .">Sin Tienda</a>";
                        echo "</div>";
                        echo "<hr>";
                    }
                    ?>
                    <div class="ApartadoPedidos">
                        <a href="?estado=1" class="<?php echo $estado == 1 ? "seleccionado" : "" ?>" >Pendiente</a>
                        <a href="?estado=2&&TipoCliente=2" class="<?php echo $estado == 2 ? "seleccionado" : "" ?>" >Pendiente a Pagar</a>
                        <a href="?estado=3" class="<?php echo $estado == 3 ? "seleccionado" : "" ?>" >Aceptados</a>
                        <a href="?estado=4" class="<?php echo $estado == 4 ? "seleccionado" : "" ?>" >Cancelados</a>
                        <a href="?estado=5" class="<?php echo $estado == 5 ? "seleccionado" : "" ?>" >Entregados</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        function cambiarRepartidor(idPedido, select){
            const h1 = document.getElementById('EnProceso' + idPedido);
            if (select.value == "NULL"){
                h1.innerHTML = "#" + idPedido;
            }
            else {
                h1.innerHTML = "#" + idPedido + " En proceso " + "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"32\" height=\"32\" fill=\"currentColor\" class=\"bi bi-truck\" viewBox=\"0 0 16 16\"><path d=\"M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5zm1.294 7.456A2 2 0 0 1 4.732 11h5.536a2 2 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456M12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2\"/></svg>";
            }
            $.ajax({
                type: 'POST',
                url: '../php/CambiarRepartidor.php',
                data: { idPedido: idPedido, select: select.value },
                success: function() {
                }
            });
        }
        function cambiarEstadoPedido(idPedido, estado){
            
            $.ajax({
                type: 'POST',
                url: '../php/CambiarEstadoPedido.php',
                data: { idPedido: idPedido, estado: estado },
                success: function() {
                    MostrarPedidos()
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
