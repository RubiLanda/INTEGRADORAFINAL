<?php
session_start();

include '../php/conexion.php';
$conexion=new Database();
$conexion->conectarBD();
$persona = $_SESSION['ID'];

$consulta = $conexion->selectConsulta("call verificarEstadoCuenta($persona)");
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
    <link rel="stylesheet" href="../css/ventasadmin.css">
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
            <a href="AdministradorVerPedidos.php?mostrarMenu=0">Ver pedidos</a>
            <a href="#" class="opcionSeleccionado">Ver Ganancias</a>
            <a href="AdministradorGestionProductos.php">Gestionar productos y categorías</a>
            <a href="AdminInventario.php">Añadir inventario</a>
            <a href="habiydesarepa.php">Ver Repartidores</a>
            <a href="AdminAñadir.php">Ver Administradores</a>
            <a href="AdministradorVerTiendas.php">Ver Tiendas</a>
            <a href="administrador.php">Mi cuenta</a>
        </div>
    </div>
    <div style="height: 170px;"></div>

    <div class="contenedor">
      <h1>REPORTE DE VENTAS</h1>
      <div class="inicio">
        <div class=cuadro>
          <input type="date" id="fecha" name="fecha" onchange="bloquear(this); Ver_Ventas_Producto()">
          
          <div class="Selects">
          <select class="select" id='años' aria-label="Default select example" onchange="Ver_Ventas_Producto()">
            <option selected>REPORTE POR AÑO</option>
            <?php
            $consulta="SELECT distinct year(f_entrega) as Año FROM PEDIDOS
            where estado_pedido='entregado' order by Año";
            $años=$conexion->selectConsulta($consulta);
            foreach ($años as $value) {
              echo "<option value='$value->Año'>$value->Año</option>";
            }
            ?>
          </select>
          <select class="select" id="meses" aria-label="Default select example" onchange="Ver_Ventas_Producto()">
            <option selected>REPORTE POR MES</option>
            <option value="1">Enero</option>
            <option value="2">Febrero</option>
            <option value="3">Marzo</option>
            <option value="4">Abril</option>
            <option value="5">Mayo</option>
            <option value="6">Junio</option>
            <option value="7">Julio</option>
            <option value="8">Agosto</option>
            <option value="9">Septiembre</option>
            <option value="10">Octubre</option>
            <option value="11">Noviembre</option>
            <option value="12">Diciembre</option>
          </select>
        </div>
        
        <div  class="juntitos">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="a" id="productos" value="1" checked>
            <label class="form-check-label" for="productos">
              POR PRODUCTOS
            </label>
          </div>
          <select class="select" id="categorias" aria-label="Default select example" onchange="Ver_Ventas_Producto()">
            <option selected> POR CATEGORÍA</option>
            <?php
            $consulta="SELECT * FROM ver_categorias;";
            $cate=$conexion->selectConsulta($consulta);
            foreach ($cate as $value) {
              echo "<option value='$value->ID'>$value->Nombre</option>";
            }
            ?>
        </select>
      </div>   
      
      <div class="form-check">
        <input class="form-check-input" type="radio" name="a"  value="2" >
          <label class="form-check-label" for="tienda">
            POR TIENDAS
          </div>
        </label>
        
        <div class="juntitos">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="a" value="3">
            <label class="form-check-label" for="repartidores">
              POR REPARTIDORES
            </label>
          </div>
      </select>
      <select class="select" id="repartidores" aria-label="Default select example" onchange="Ver_Ventas_Producto()"> 
        <option selected>POR REPARTIDORES</option>
        <?php
            $consulta="SELECT  REPARTIDORES.id_repartidor as id, PERSONAS.nombre as repartidor FROM PERSONAS inner join REPARTIDORES on REPARTIDORES.id_persona=PERSONAS.id_persona where REPARTIDORES.estatus=1;";
            $repa=$conexion->selectConsulta($consulta);
            foreach ($repa as $value) {
              echo "<option value='$value->id'>$value->repartidor</option>";
            }
            ?>
        </select>
      </div>
        </div>
        
      </div>
        
      </div>
      <div class="contenedor tabla" id="Consulta">
      
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script>
          var radiusActivo = 1;
          
          function Ver_Ventas_Producto(){ 

            const inputMeses = document.getElementById('meses');
          const selectaños=document.getElementById('años');
            var meses = inputMeses.value;
            var años= selectaños.value;
            const selectcate=document.getElementById('categorias');
            var categorias=selectcate.value;
            const selectrepa=document.getElementById('repartidores');
            var repas=selectrepa.value;
            const inputdate=document.getElementById('fecha');
            var fechas=inputdate.value;

            if(meses!='REPORTE POR MES' || años!='REPORTE POR AÑO'){
              inputdate.disabled=true;
              inputdate.style.opacity=0.5;
            }else{
              inputdate.disabled=false;
              inputdate.style.opacity=1;
            }

            alert(radiusActivo);                              
            alert(meses);                             
            alert(años);                              
            alert(categorias);                              
            alert(repas);                              
            alert(fechas);                              
            $.ajax({
              type: 'POST',                                               //con el metodo ajax hacemos que se envien los datos de esta 
              url: '../php/scriptventas.php',                                       // pagina a otra, con el url, declaramos la constante de 
              data: {radiusActivo: radiusActivo, 
                meses: meses, años:años, 
                categorias:categorias,repas:repas,
              fechas:fechas},                                    //input meses q nos va a tomar los inputs con esa id el cual la Id
                success: function(response) {
                  $('#Consulta').html(response);                             // a imprimir el resultado, en data ponemos despues de los puntos
                }  
              })
              //la variable de 
            }
            // Selecciona todos los botones de radio y el select
            const radios = document.querySelectorAll('input[name="a"]');
            const select = document.getElementById('categorias');
            const tienda = document.getElementById('tiendas');
            const repa = document.getElementById('repartidores');
            const fecha=document.getElementById('fecha');
            // Función para habilitar o deshabilitar el select
            function toggleSelect() {
              // Verifica si el radio con valor "1" está seleccionado
              if (document.querySelector('input[name="a"][value="1"]').checked) {
                select.disabled = false; // Habilitar select
                select.style.opacity=1;
                radiusActivo = 1;
              } else {
                select.disabled = true; // Deshabilitar select
                select.style.opacity=0.5;
                select.value='POR CATEGORIA';
              }
              if (document.querySelector('input[name="a"][value="2"]').checked) {
                radiusActivo = 2;
              }
              if (document.querySelector('input[name="a"][value="3"]').checked) {
                repa.disabled = false; // Habilitar select
                repa.style.opacity=1;
                radiusActivo = 3;
              } else {
                repa.disabled = true; // Deshabilitar select
                repa.style.opacity=0.5;
                repa.selectedIndex = 0;
              }
              
              Ver_Ventas_Producto();
            }
           
            // Asocia la función a todos los radios
            radios.forEach(radio => radio.addEventListener('change', toggleSelect));
            // Inicializa el estado del select
            toggleSelect();
            
            const inputTextos = document.querySelectorAll(".nombre");
            inputTextos.forEach(function(inputTexto) {
              inputTexto.addEventListener("input", function() {
                this.value = this.value.replace(/[^a-zA-Z\sàèìòùáéíóúñÀÈÌÒÙÁÉÍÓÚÑ]/g, '');
              });
            });
            
            function bloquear(input) {
              const inputMeses = document.getElementById('meses');
          const selectaños=document.getElementById('años');
              if (input.value != '') {
                selectaños.disabled = true;
                inputMeses.disabled = true;
                selectaños.style.opacity = 0.5;
                inputMeses.style.opacity = 0.5;
              }
              else {
                selectaños.disabled = false;
                inputMeses.disabled = false;
                selectaños.style.opacity = 1;
                inputMeses.style.opacity = 1;
              }
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


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>


