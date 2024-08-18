<?php
session_start();

require '../php/conexion.php';
$conexion = new Database();
$conexion->conectarBD();
$persona = $_SESSION['ID'];
$consulta = $conexion->selectConsulta("call verificarEstadoCuenta($persona)");
$estado = $consulta[0]->Estatus;
if ($estado == 0) {
    header("Location: ../php/cerrarSeccion.php");
}

if (isset($_SESSION['Rol'])){
    if ($_SESSION['Rol'] != 3){
        switch ($_SESSION['Rol']){
            case 1:
                header("Location: AdministradorVerPedidos.php");
                break;
            case 2:
                header("Location: ClienteRealizarPedido.php");
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
    <link rel="stylesheet" href="../css/InfoRepa.css">
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
                <?php 
                if (isset($_SESSION['Rol'])){
                        $cuenta = $conexion->selectConsulta("select USUARIOS.username as Nombre from USUARIOS where USUARIOS.id_usuario = '$persona'");

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
            <img src="../img/logo.png">
            <button id="regresar" class="boton">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                </svg>
            </button>
        </div>
        <div class="opciones">
        <a href="mispedidosrepa.php?mostrarMenu=0">Ver pedido</a>
        <a href="#" class="opcionSeleccionado">Mi cuenta</a>
        </div>
    </div>
    <div style="height: 170px;"></div>
<body>
    <?php
    $stmt = $conexion->selectConsulta("call Ver_Informacion_Repartidor($persona)");

    foreach ($stmt as $st) {

        echo "<div>
        <div class=\"card contenedor\" >
        <div class=\"titulo\">
        <svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"currentColor\" class=\"tarjeta\" viewBox=\"0 0 16 16\">
        <path d=\"M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0\"/>
        </svg>
        <h1 class=\"card-title\">MI INFORMACIÓN</h1>
        </div>
        <div class=\"card-body\">
        <p class=\"card-text\">
        <h4><b>Usuario:</b> {$st->Usuario}<h4>
        <h4><b>Nombre:</b> {$st->Nombre}<h4>
        <h4><b>Fecha Nacimiento:</b> {$st->Fecha_nacimiento}</h4>
        <h4><b>Género:</b> {$st->Genero}</h4>
        <h4><b>Licencia de conducir:</b> {$st->Licencia_conducir}</h4>
        <h4><b>Teléfono:</b> {$st->Telefono}</h4>
        <h4><b>Repartidor Desde:</b> {$st->Fecha_Ingreso}</h4>
        <h4><b>INE:</b> <img src='../img/infopersonal/{ $st->INE}'></h4>
        
        </p>
        <div class=\"div\">
        <a href=\"../views/RepartidorCargarVista.php
        \" >EDITAR</a>
        </div>
        </div>
        </div>
        
        </div>";
    }
    ?>
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

    <!-- FUNCION PARA MODIFICAR LA INE DE UN REPARTIDOR-->
    <script>
        function modificarimagenine(input,idrepa){
        var fileInput = $(input)[0];
        var file = fileInput.files[0];
        if (file)
        {
            var formData = new FormData();
            formData.append('imagen', file);
            formData.append('id', idrepa);
            $.ajax({
                    url: '../php/nuevaine.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response){
                      

                        var toastContainer = document.getElementById('toastContainer');
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
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>