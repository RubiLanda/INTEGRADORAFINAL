<?php
session_start();
if (isset($_SESSION['Rol'])){
    if ($_SESSION['Rol'] != 2){
        switch ($_SESSION['Rol']){
            case 1:
                header("Location: AdministradorVerPedidos.php");
                break;
            case 3:
                header("Location: mispedidosrepa.php");
                break;
        }
    }
}
else {
    header("Location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/VerPedido.css">
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
            <a href="ClienteHistorial.php">Ver historial</a>
            <a href="#">Ver pedido</a>
            <a href="">Mi cuenta</a>
        </div>
    </div>
    <div style="height: 170px;"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <div class='conte'>
        <div class='radical2'>
            <h1 >PEDIDOS</h1>
            <div class='conthistorial'>
                <div class='div10'>
                    <label for ='SeleccionaEstado' class='control-label'>Estado</label>
                    <select id='SeleccionaEstado' name='SeleccionaEstado' class='form-select' style='width: 150px' onchange="filtrarPedidos()">
                        <option value="NULL" selected >Estado..</option>
                        <option value="aceptado" >Aceptado</option>
                        <option value="pendiente" >Pendiente</option>
                        <option value="pendiente a pagar" >Pendiente a Pagar</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div id='resultados' class="tabla"></div>
    


    <!--FUNCION DE JAVA PARA PODER FILTRAR POR MES Y AÑO LOS PEDIDOS DE LOS CLIENTES-->
    <script>
        function filtrarPedidos() {
            var E = document.getElementById('SeleccionaEstado');
            $.ajax({
                type: 'POST',
                url: '../php/ScriptVerPedidos.php',
                data: {
                    E: E.value
                },
                success: function(response) {
                    if (response == true) {
                        resultados.style.display = 'block';
                        // MENSAJDE DE SIN RESULTADOS 
                        $('#resultados').html("<div class=\"sinPedidos\">No se encontraron resultados</div>");
                    } else {
                        resultados.style.display = 'grid';
                        // MENSAJE CON RESULTADOS 
                        $('#resultados').html(response);

                    }
                }
            });
        }
        filtrarPedidos()

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