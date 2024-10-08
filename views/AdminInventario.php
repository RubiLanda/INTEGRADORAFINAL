
<?php
session_start();

include '../php/conexion.php';
$conexion = new Database();
$conexion->conectarBD();
$id = $_SESSION['ID'];

$consulta = $conexion->selectConsulta("call verificarEstadoCuenta($id)");
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

$menu1 = isset($_GET['estado']) ? false : true;
$menu2 = isset($_GET['estado']) ? false : true;

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/AdministradorInventario.css">
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

                        $cuenta = $conexion->selectConsulta("select USUARIOS.username as Nombre from USUARIOS where USUARIOS.id_usuario = '$id'");

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

    <div class="menu oculto" id="menu">
        <div class="inicioMenu">
            <img src="../img/LOGOAdministrador.png">
            <button id="regresar" class="boton">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                </svg>
            </button>
        </div>
        <div class="opciones">
            <a href="AdministradorVerPedidos.php?mostrarMenu=0">Ver pedidos</a>
            <a href="Administradorganancias.php">Ver Ganancias</a>
            <a href="AdministradorGestionProductos.php">Gestionar productos y categorías</a>
            <a href="#" class="opcionSeleccionado">Añadir inventario</a>
            <a href="habiydesarepa.php">Ver Repartidores</a>
            <a href="AdminAñadir.php">Ver Administradores</a>
            <a href="AdministradorVerTiendas.php">Ver Tiendas</a>
            <a href="administrador.php">Mi cuenta</a>
        </div>
    </div>
    <div style="height: 170px;"></div>
    <?php


    // consulta para obtener el stock de productos
    $consultastock = "SELECT INVENTARIO.id_producto, INVENTARIO.stock as Stock, PRODUCTOS.nombre AS NombreProd
                FROM INVENTARIO
                JOIN PRODUCTOS ON PRODUCTOS.id_producto = INVENTARIO.id_producto
                WHERE PRODUCTOS.estado = 1";
    // Ejecuta la consulta y almacena los resultados
    $stock = $conexion->selectConsulta($consultastock);
    ?>
    <div class="conte">
        <h1>STOCK PRODUCTOS</h1>
        <h2>Producto</h2>
        <div class="tabla">
            <?php foreach ($stock as $value) {
                echo "<h3>$value->NombreProd</h3>
                    <div class=\"cantidad\">
                        <button class=\"cantidad-button\" type=\"button\" onclick=\"cambiarCantidad(-1, this.nextElementSibling, {$value->id_producto}, 'input{$value->id_producto}')\" style=\"border-radius: 15px 0 0 15px;\">
                            <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"25\" height=\"25\" fill=\"currentColor\" class=\"bi bi-caret-left-fill\" viewBox=\"0 0 16 16\">
                                <path d=\"m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z\" />
                            </svg>
                        </button>
                        <input class=\"cantidad-input\" type=\"text\" name=\"n_stock\" id=\"input{$value->id_producto}\" value=\"{$value->Stock}\" oninput=\"validarNumero(this)\" onblur=\"ejecutarBoton({$value->id_producto}, 'input{$value->id_producto}')\" onkeydown=\"if (this.value <= 0 || this.value=='' ) {return event.key !='Enter' ;}\">
                        <button class=\"cantidad-button2\" type=\"button\" onclick=\"cambiarCantidad(1, this.previousElementSibling, {$value->id_producto}, 'input{$value->id_producto}')\" style=\"border-radius: 0 15px 15px 0;\">
                            <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"25\" height=\"25\" fill=\"currentColor\" class=\"bi bi-caret-right-fill\" viewBox=\"0 0 16 16\">
                                <path d=\"m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z\" />
                            </svg>
                        </button>
                    </div>";
            }
            ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function ejecutarBoton(id_producto, id_input) {
            var input = document.getElementById(id_input);
            let cantidad = input.value;
            $.ajax({
                type: 'POST',
                url: '../php/ScriptInventario.php',
                data: {
                    cantidad: cantidad,
                    id_producto: id_producto
                },
                success: function() {}
            });
        }

        function cambiarCantidad(numero, inputCantidad, id_producto, id_input) {
            let valor = inputCantidad.value;
            let cantidad = parseInt(valor, 10) + numero;
            if (valor.trim() == '') {
                cantidad = 1;
            }
            if (cantidad < 0) {
                cantidad = 0;
            }
            if (cantidad >= 100) {
                cantidad = 100;
            }
            inputCantidad.value = cantidad;
            ejecutarBoton(id_producto, id_input);
        }

        function validarNumero(input, valorInicial) {
            input.value = input.value.replace(/\D/g, '');
            let cantidad = parseInt(input.value, 10);
            if (cantidad > 100) {
                input.value = 100;
            }
        }
    </script>
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