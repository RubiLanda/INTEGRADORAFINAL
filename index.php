<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="img/logo.png">
    <link rel="stylesheet" href="css/IndexEstilo.css">
    <link rel="stylesheet" href="css/header.css">
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
            <div class="login-registro">
                <?php
                    if (isset($_SESSION['Rol'])){
                        session_start();
                        include 'php/conexion.php';
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
                                <li>{$cuenta[0]->Nombre}</li>";
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
            <a href="#" class="opcionSeleccionado">Menu</a>
            <a href="views/productos.php">Productos</a>
            <a href="">Sobre Nosotros</a>
            <?php
            if (isset($_SESSION['Rol'])){
                switch ($_SESSION['Rol']){
                    case 1:
                        echo "<a href='views/AdministradorVerPedidos.php'>Mi Perfil</a>";
                        break;
                    case 2:
                        echo "<a href='views/ClienteRealizarPedido.php'>Realizar Pedido</a>";
                        break;
                    case 3:
                        echo "<a href='views/Repartidor.php'>Mi Perfil</a>";
                        break;
                }
            }
            else {
                echo "<a href='views/login.php'>Realizar Pedido</a>";
            }
            
            ?>
        </div>
    </div>
    
    <div class="inicio">
        <img src="img/logo.png" alt="">
        <div></div>
    </div>

    <div class="ProductosDestacados">
        <h1>Panes destacados</h1>
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php
                require 'php/conexion.php';

                $Conexion = new Database();
                $Conexion->conectarBD();
                $TopProductos = $Conexion->selectConsulta("select * from TopProductos");

                for ($i=0; $i < count($TopProductos); $i++) { 
                    if ($i == 0) {
                        echo "<button type=\"button\" data-bs-target=\"#carouselExampleCaptions\" data-bs-slide-to=\"0\" class=\"active\" aria-current=\"true\" aria-label=\"Slide 1\"></button>";
                    }
                    else {
                        echo "<button type=\"button\" data-bs-target=\"#carouselExampleCaptions\" data-bs-slide-to=\"{$i}\" aria-label=\"Slide {($i + 1)}\"></button>";
                    }
                }
                
                ?>
            </div>
            <div class="carousel-inner">
                <?php
                
                for ($i=0; $i < count($TopProductos); $i++) { 
                    if ($i == 0) {
                        echo "
                        <div class=\"carousel-item active\">
                            <img src=\"img/productos/{$TopProductos[$i]->Imagen}\" class=\"d-block w-100\" id=\"sombra\">
                            <div class=\"carousel-caption d-none d-md-block\">
                                <h5>{$TopProductos[$i]->Nombre}</h5>
                                <p>{$TopProductos[$i]->Precio}</p>
                            </div>
                        </div>
                        ";
                    }
                    else {
                        echo "
                        <div class=\"carousel-item\">
                            <img src=\"img/productos/{$TopProductos[$i]->Imagen}\" class=\"d-block w-100\" id=\"sombra\">
                            <div class=\"carousel-caption d-none d-md-block\">
                                <h5>{$TopProductos[$i]->Nombre}</h5>
                                <p>{$TopProductos[$i]->Precio}</p>
                            </div>
                        </div>
                        ";
                    }
                }
                
                ?>
                
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>        
    </div>

    <div class="inbitacion">

    </div>
    
    <script>
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
