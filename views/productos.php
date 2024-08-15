<?php
require '../php/conexion.php';

$categoria_seleccionado = isset($_GET['categoria']) ? $_GET['categoria'] : 0;


$Conexion = new Database();
$Conexion->conectarBD();
try {
    $categorias = $Conexion->selectConsulta("select id_categoria as ID, nombre as Nombre from CATEGORIAS where estado = 1");
    
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
    <link rel="stylesheet" href="../css/estiloIndex.css">
    <link rel="stylesheet" href="../css/header.css">
    <title>Document</title>
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
            <div class="login-registro">
                <?php
                    if (isset($_SESSION['Rol'])){
                        session_start();
                        include '../php/conexion.php';
                        $conexion=new Database();
                        $conexion->conectarBD();
                        $id = $_SESSION['ID'];
                        $cuenta = $conexion->selectConsulta('select USUARIOS.username as Nombre from USUARIOS where USUARIOS.id_usuario = $id');

                        echo "
                        <div class='dropdown text-end'>
                            <a href='#' class='d-block link-dark text-decoration-none perfil' id='dropdownUser1' data-bs-toggle='dropdown' aria-expanded='false'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='32' height='32' fill='currentColor' class='bi bi-person-fill' viewBox='0 0 16 16'>
                                    <path d='M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6'/>
                                </svg>
                            </a>
                            <ul class='dropdown-menu text-small' aria-labelledby='dropdownUser1' data-popper-placement='bottom-end' style='position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(-112px, 34px);'>
                                <li>hola</li>
                                <li>{$cuenta->Nombre}</li>";
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
                                <li><a class='dropdown-item' href='php/cerrarSeccion.php'>Cerrar Sesion</a></li>
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
            <a href="../index.php">Menu</a>
            <a href="#">Productos</a>
            <a href="">Sobre Nosotros</a>
            <a href="">Contactos</a>
            <?php
            if (isset($_SESSION['Rol'])){
                if ($_SESSION['Rol'] = 2){
                    echo "<a href='Cliente.php'>Realizar Pedido</a>";
                }
            }
            else {
                echo "<a href='login.php'>Realizar Pedido</a>";
            }
            
            ?>
            <a href="http://localhost/Boostrap/Vistas/cliente.php">Administrador</a>
        </div>
    </div>

    <div class="TodoLosProductos" id="Productos">

        <div class="BarraBusqueda">
                <button type="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                </button>
                <input type="text" placeholder="buscar">
                <button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>
                </button>
        </div>
        <div class="Categorias">
            <h1>Categorias</h1>
            <div class="Contenido">
                <a href="?categoria=0">Todos</a>
                <?php foreach ($categorias as $categoria): ?>
                    <a href="?categoria=<?php echo ($categoria->ID)?>"><?php echo ($categoria->Nombre)?></a>
                <?php endforeach ?>
            </div>
        </div>
        <div class="Productos" id="productos">
            
        </div>
        <div class="paginacion" id="paginacion">
            
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var pagina;
        
        if (pagina == null){
            pagina = 1;
        }

        function cargarProductos(pagina) {
            $.ajax({
                type: 'POST',
                url: '../php/cargarProductosIndex.php',
                data: { current_page: pagina, categoria_seleccionado: <?php echo $categoria_seleccionado ?> },
                success: function(response) {
                    $('#productos').html(response);
                }
            });
        }
        cargarProductos(pagina);
        function mostrarPaginacion() {
            $.ajax({
                type: 'POST',
                url: '../php/MostrarPaginacion.php',
                data: { pagina: pagina, categoria_seleccionado: <?php echo $categoria_seleccionado?>, tipo: 3 },
                success: function(response) {
                    $('#paginacion').html(response);
                }
            });
        }
        mostrarPaginacion()
        function cambiarPaginacion(cambio) {
            pagina = cambio;
            mostrarPaginacion()
            cargarProductos(pagina)
        }

        // Menu toggle
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
