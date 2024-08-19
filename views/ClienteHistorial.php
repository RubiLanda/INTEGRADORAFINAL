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

include '../php/conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();
// VARIABLE DE SESSION 
$usuario = $_SESSION['ID'];

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
    <link rel="stylesheet" href="../css/AdministradorGestionProduct.css">
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
                        $cuenta = $Conexion->selectConsulta("select USUARIOS.username as Nombre from USUARIOS where USUARIOS.id_usuario = '$usuario'");

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

    <div class="menu oculto" id="menu">
        <div class="inicioMenu">
            <?php
            if (count($Conexion->selectConsulta("call Ver_Tiendas_Cliente('$usuario', 0)")) > 0) {
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
            <a href="ClienteRealizarPedido.php?mostrarMenu=0">Realizar Pedido</a>
            <a href="#" class="opcionSeleccionado">Ver historial</a>
            <a href="ClienteVerPedidos.php">Ver pedido</a>
            <a href="micuentacliente.php">Mi cuenta</a>
        </div>
    </div>
    <div style="height: 150px;"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     
            <?php
              
                $consulta = "SELECT distinct year(PEDIDOS.f_pedido) as AÑO  FROM PEDIDOS inner join CLIENTES ON PEDIDOS.id_cliente = CLIENTES.id_cliente 
                inner join PERSONAS on CLIENTES.id_persona = PERSONAS.id_persona inner join USUARIOS ON PERSONAS.id_persona = USUARIOS.id_usuario
                WHERE (PEDIDOS.estado_pedido = 'entregado' or PEDIDOS.estado_pedido ='cancelado') and  USUARIOS.id_usuario = $usuario
                order by AÑO";
                $reg = $Conexion->selectConsulta($consulta);
                // DIV GENERAL DE LA CLASE  RADICAL2
                echo "<div class='radical2'>";
                         echo "<h2>HISTORIAL</h2>";
                         // DIV CON LA CLASE CONTHISTORIAL
                     echo "<div class='conthistorial'>";
                         // DIV DE LA CLASE 10 (SELECT DE AÑO Y MES)
                         echo "<div class='div10'>";
                         // INICIO DEL SELECT DEL AÑO
                         echo "<label for ='seleccionaAño' class='control-label'>Año</label>"; 
                         echo "<select id = 'seleccionaAño' name='seleccionaAño' class='form-select' onchange=\"filtrarPedidos()\" >";
                         echo "<option value=\"NULL\" selected  >Año</option>";
                             foreach($reg as $value)
                              {
                               echo "<option value='".$value->AÑO."'>".$value->AÑO."</option>";
                              }
                         echo "</select>";
                               // INICIO DEL SELECT DEL MES
                                echo "<label for ='seleccionaMes' class='control-label'>Mes</label>"; 
                                echo "<select id='seleccionaMes' name='seleccionaMes' class='form-select' style='width: 150px' onchange=\"filtrarPedidos()\">";
                                echo "<option value=\"NULL\" selected >Mes</option>";
                                echo "<option value=\"1\" >Enero</option>";
                                echo "<option value=\"2\" >Febrero</option>";
                                echo "<option value=\"3\" >Marzo</option>";
                                echo "<option value=\"4\" >Abril</option>";
                                echo "<option value=\"5\" >Mayo</option>";
                                echo "<option value=\"6\" >Junio</option>";
                                echo "<option value=\"7\" >Julio</option>";
                                echo "<option value=\"8\" >Agosto</option>";
                                echo "<option value=\"9\" >Septiembre</option>";
                                echo "<option value=\"10\" >Octubre</option>";
                                echo "<option value=\"11\" >Noviembre</option>";
                                echo "<option value=\"12\" >Diciembre</option>";
                                echo "</select>";
                          // FIN DEL DIV DIV10      
                         echo "</div>";  
                     // FIN DEL DIV DE CLASE CONTHISTORIAL     
                     echo "</div>";
                // FIN DEL DIV CLASE RADICAL2
                 echo "</div>";
                 echo "<br>";
                // DIV DE LA IMPRESION DE LOS PEDIDOS

                 echo "<div id='resultados' class=\"tabla\"></div>";
                 ?>


    <!--FUNCION DE JAVA PARA PODER FILTRAR POR MES Y AÑO LOS PEDIDOS DE LOS CLIENTES-->
    <script>
        var seleccionando_fecha;
        var mostrarStock;
        if (sessionStorage.getItem("seleccionando_fecha") != null) {
            seleccionando_fecha = sessionStorage.getItem("seleccionando_fecha");
        }
        else {
            seleccionando_fecha = false;
        }
        if (sessionStorage.getItem("mostrarStock") != null) {
            mostrarStock = sessionStorage.getItem("mostrarStock");
        }
        else {
            mostrarStock = 0;
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

        function filtrarPedidos() {
            var a = document.getElementById('seleccionaAño').value;
            var m = document.getElementById('seleccionaMes').value;
            $.ajax({
                type: 'POST',
                url: '../php/historial.php',
                data: { a: a, m: m },
                success: function(response) {
                    if (response == true) {
                        resultados.style.display = 'block';
                        // MENSAJDE DE SIN RESULTADOS 
                        $('#resultados').html("<div class=\"sinPedidos\">No se encontraron resultados</div>");
                    }
                    else {
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
    </script>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> 
</body>
</html> 





 