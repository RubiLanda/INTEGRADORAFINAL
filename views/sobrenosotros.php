<?php
require '../php/conexion.php';

$categoria_seleccionado = isset($_GET['categoria']) ? $_GET['categoria'] : 0;
session_start();

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
    <link rel="stylesheet" href="../css/nosotros.css">
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
                        
                        $id = $_SESSION['ID'];
                        $cuenta = $Conexion->selectConsulta("select USUARIOS.username as Nombre from USUARIOS where USUARIOS.id_usuario = '$id'");

                        echo "
                        <div class='dropdown text-end'>
                            <a href='#' class='d-block link-dark text-decoration-none perfil' id='dropdownUser1' data-bs-toggle='dropdown' aria-expanded='false'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='32' height='32' fill='currentColor' class='bi bi-person-fill' viewBox='0 0 16 16'>
                                    <path d='M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6'/>
                                </svg>
                            </a>
                            <ul class='dropdown-menu text-small' aria-labelledby='dropdownUser1' data-popper-placement='bottom-end' style='position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(-112px, 34px);'>
                                <li><h6>hola</h6></li>
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
                        echo "<a href='login.php' class = 'boto'>Login</a>";
                        echo "<a href='registro.php' class = 'boto'>Sign-up</a>";
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
            <a href="productos.php" >Productos</a>
            <a href="#" class="opcionSeleccionado">Sobre Nosotros</a>
            <?php
            if (isset($_SESSION['Rol'])){
                switch ($_SESSION['Rol']){
                    case 1:
                        echo "<a href='AdministradorVerPedidos.php'>Mi Perfil</a>";
                        break;
                    case 2:
                        echo "<a href='ClienteRealizarPedido.php'>Realizar Pedido</a>";
                        break;
                    case 3:
                        echo "<a href='Repartidor.php'>Mi Perfil</a>";
                        break;
                }
            }
            else {
                echo "<a href='login.php'>Realizar Pedido</a>";
            }
            
            ?>
        </div>
    </div>

    <title>¡Sobre Nosotros!</title>
</head>
<body>
    <div class ="fondo"></div>
    <div class="inicio">
        <div>
            <h1>¡Sobre Nosotros!</h1>
            
        </div>
    </div>
    <div class="container">
        
    <p>
            <h3>HISTORIA</h3>    
            <h4>Panadería La Espiga de Matamoros fundada en 1969 por el Sr. Raymundo González García en Matamoros Coahuila. Seguimos manteniendo su tradición ofreciendo un pan casero hecho a la leña mantenido ese sabor tan especial que nos ha caracterizado durante más de 50 años.</h4>
        </p>
        <div class="card">
            <img src="../img/history.jpg"  class="card-img-top custom-img" alt="...">
        </div>

        <div class="row margen">
            <div class="col-sm-12 col-lg-7">
                <img src="../img/collage.jpg"  class="card-img-top custom-img" alt="...">
            </div>
            <div class="col-sm-12 col-lg-5">
                <h3>Los mejores en calidad y sabor</h3>    
                <h4>¡Pan casero, eso quiero!, panaderia La Espiga cuenta con los mejores sabores de la región</h4>
            </div>
        </div>

        <div class="row margen">
            <div class="col-12 text-center">
                <h3>Visítanos</h3>    
            </div>
            <div class="col-12">
                <iframe style="width:100%" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14400.863196657432!2d-103.22790241520258!3d25.531187681911483!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x868fc12a5a312709%3A0xfb270d2b4f37edff!2sAv%20Cuauht%C3%A9moc%20601-609%2C%20Vegas%20de%20Marrufo%20Oriente%2C%20Matamoros%2C%20Coah.!5e0!3m2!1ses-419!2smx!4v1723765448234!5m2!1ses-419!2smx" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>            
            </div>
        </div>



    </div>
    <script>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>