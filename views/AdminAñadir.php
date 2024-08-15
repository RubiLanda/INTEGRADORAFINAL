<?php
session_start();

require '../php/conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();
$id = $_SESSION['ID'];

$consulta = $Conexion->selectConsulta("call verificarEstadoCuenta($id)");
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
    <link rel="stylesheet" href="../css/AñadirAdmi.css">
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
                        $cuenta = $Conexion->selectConsulta("select USUARIOS.username as Nombre from USUARIOS where USUARIOS.id_usuario = '$id'");

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
                                <li><a class='dropdown-item' href='../php/cerrarSeccion.php'>Cerrar Sesion</a></li>
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
            <img src="../img/logo.png">
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
            <a href="AdminInventario.php">Añadir inventario</a>
            <a href="habiydesarepa.php">Ver Repartidores</a>
            <a href="#">Ver Administradores</a>
            <a href="AdministradorVerTiendas.php">Ver Tiendas</a>
            <a href="administrador.php">Mi cuenta</a>
        </div>
    </div>
    <div style="height: 170px;"></div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11" id="toastContainer"></div>
    <div class="conte">
        <form class="contenedor">
            <h1>REGISTRAR NUEVO ADMINISTRADOR</h1>
            <div class=contenedoruno>
                <div class="dentro1 solo">
                    <label> Crea el usuario:</label>
                    <input type="text" name="usuario" id="usuario" required autocomplete="off">
                    <label>Contraseña:</label>
                    <input type="password" minlength="7" name="contraseña" id="contraseña" required autocomplete="off">
                    <label>Confirmar Contraseña:</label>
                    <input type="password" minlength="7" name="vercontra" id="vercontra" required autocomplete="off">

                   
                    <label>Nombre(s):</label>
                    <input type="text" name="nombre" class="nombre" id="nombre" minlength="3" maxlength="50" required autocomplete="off">
                    <label>Apellido Paterno:</label>
                    <input type="text" name="paterno" class="nombre" id="paterno" minlength="3" maxlength="50" required autocomplete="off">
                    <label>Apellido Materno:</label>
                    <input type="text" name="materno" class="nombre" id="materno" minlength="3" maxlength="50" autocomplete="off">
                </div>
                <div class="solo">
                    <div class="dentro1">
                        <label for=""></label>
                        <select class="select" id="genero" aria-label="Default select example" name="genero" required>
                        <option disabled selected value="0">Género</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                            <option value="O">Otro</option>
                        </select>
                        <label>Fecha De Nacimiento:</label>
                        <input type="date" name="nacimiento" id="nacimiento" required>
                        <label>Teléfono:</label>
                        <input type="tel" name="telefono" id="telefono" maxlength="10" required autocomplete="off" oninput="validartelefono(this)">
                    </div>
                </div>
            </div>
            <button class="btn" type="button" onclick="validarFormulario(this)">REGISTRAR</button>
        </form>
    </div>
    <div class="contebut">
        <div class="cuadrado2">
            <select name="order" class="form-select" id="order-select" onchange="filtroestatus(this)">
                <option value="NULL" selected>Filtrar por ...</option>
                <option value="1">Habilitados</option>
                <option value="0">Deshabilitados</option>
            </select>
        </div>
    </div>
    <div id="tabla"></div>
    <script>
        const inputTextos = document.querySelectorAll(".nombre");
        inputTextos.forEach(function(inputTexto) {
            inputTexto.addEventListener("input", function() {
                this.value = this.value.replace(/[^a-zA-Z\sàèìòùáéíóúñÀÈÌÒÙÁÉÍÓÚÑ]/g, '');
            });
        });

        function validarFormulario() {
            

                añadiradmin();
                return true;
            
        };

        function validartelefono(input) {
            input.value = input.value.replace(/\D/g, '');
        };
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- ESTE ES EL SCRIPT QUE HACE QUE FUNCIONEN LAS FUNCIONES DE JS-->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11" id="imprimirnoti"></div>
    <script>
        function añadiradmin() {
            var user = document.getElementById('usuario');
            var nameuser = user.value;
            var contraseña = document.getElementById('contraseña');
            var contra = contraseña.value;
            var vericontraseña = document.getElementById('vercontra');
            var vericontra = vericontraseña.value;
            var nombre = document.getElementById('nombre');
            var nom = nombre.value;
            var paterno = document.getElementById('paterno');
            var pate = paterno.value;
            var materno = document.getElementById('materno');
            var mate = materno.value;
            var genero = document.getElementById('genero');
            var gene = genero.value;
            var nacimiento = document.getElementById('nacimiento');
            var naci = nacimiento.value;
            var telefono = document.getElementById('telefono');
            var tele = telefono.value;
            if (contra.length <= 7) {
                var toastContainer = document.getElementById('toastContainer');
                var newToast = document.createElement('div'); // Crear un nuevo elemento toast
                newToast.className = 'toast';
                newToast.setAttribute('role', 'alert');
                newToast.setAttribute('aria-live', 'assertive');
                newToast.setAttribute('aria-atomic', 'true');
                newToast.innerHTML =
                    `<div class="toast-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
                    </svg>
                    <strong class="me-auto">Nueva Notificación</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    La contraseña debe tener mas de 8 caracteres
                </div>`;
                toastContainer.appendChild(newToast); // Añadir el nuevo toast al contenedor
                var toast = new bootstrap.Toast(newToast, { // Inicializar y mostrar el nuevo toast
                    delay: 3000 // Duración del toast en milisegundos
                });
                toast.show();
            } else {
                $.ajax({
                    type: 'POST',
                    url: '../php/altadmin.php',
                    data: {
                        nameuser: nameuser,
                        contra: contra,
                        vericontra:vericontra,
                        nom: nom,
                        pate: pate,
                        mate: mate,
                        gene: gene,
                        naci: naci,
                        tele: tele
                    },
                    success: function(response) {
                        if (response == "REGISTRO EXITOSO") {
                            user.value = '';
                            contraseña.value = '';
                            vercontra.value = '';
                            nombre.value = '';
                            nombre.value = '';
                            paterno.value = '';
                            materno.value = '';
                            genero.value = '';
                            nacimiento.value = '';
                            telefono.value = '';
                            filtroestatus()
                        }
                        var toastContainer = document.getElementById('toastContainer');
                        var newToast = document.createElement('div'); // Crear un nuevo elemento toast
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
                </div>`;
                        toastContainer.appendChild(newToast); // Añadir el nuevo toast al contenedor
                        var toast = new bootstrap.Toast(newToast, { // Inicializar y mostrar el nuevo toast
                            delay: 3000 // Duración del toast en milisegundos
                        });
                        toast.show();
                    }
                });
            }
        }
    </script>
    <script>
        function cargarfiltro() {
            $.ajax({
                type: 'POST',
                url: '../php/altadmin.php',
            })
        }
    </script>
    <script>
        function filtroestatus() {
            var estatus = document.getElementById('order-select').value;
            $.ajax({
                type: 'POST',
                url: '../php/VerAdmins.php',
                data: {
                    estatus: estatus
                },
                success: function(response) {
                    $('#tabla').html(response);
                }
            })
        }
        filtroestatus()
    </script>
    <script>
        function cambiarEstatus(checkbox, ID) {
            var Estado;
            if (checkbox.checked) {
                Estado = 1;
            } else {
                Estado = 0;
            }
            $.ajax({
                type: 'POST',
                url: '../php/ScriptEstatus.php',
                data: {
                    ID: ID,
                    Estado: Estado
                },
                success: function(response) {
                    filtroestatus()
                    var toastContainer = document.getElementById('toastContainer');
                    var newToast = document.createElement('div'); // Crear un nuevo elemento toast
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
                    toastContainer.appendChild(newToast); // Añadir el nuevo toast al contenedor
                    var toast = new bootstrap.Toast(newToast, { // Inicializar y mostrar el nuevo toast
                        delay: 3000 // Duración del toast en milisegundos
                    });
                    toast.show();
                }
            });
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
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</html>