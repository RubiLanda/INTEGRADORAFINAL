
<?php
session_start();

include '../php/conexion.php';
$Conexion=new Database();
$Conexion->conectarBD();
$persona = $_SESSION['ID'];

$consulta = $Conexion->selectConsulta("call verificarEstadoCuenta($persona)");
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
    <link rel="stylesheet" href="../css/AdministradorGestionProduct.css">
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

                        $cuenta = $Conexion->selectConsulta("select USUARIOS.username as Nombre from USUARIOS where USUARIOS.id_usuario = '$persona'");

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
            <a href="#" class="opcionSeleccionado">Gestionar productos y categorías</a>
            <a href="AdminInventario.php">Añadir inventario</a>
            <a href="habiydesarepa.php">Ver Repartidores</a>
            <a href="AdminAñadir.php">Ver Administradores</a>
            <a href="AdministradorVerTiendas.php">Ver Tiendas</a>
            <a href="administrador.php">Mi cuenta</a>
        </div>
    </div>
    <div style="height: 170px;"></div>

        <!-- SCRIPT PARA QUE FUNCIONES LAS FUNCIONES DE JAVA -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
       
        
    </style>

    <!-- DIV UNIVERSAL DE LAS NOTIFICACIONES DEL TOAST-->
   <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11" id="toastContainer"></div>
     
        <!-- TITULO DE AÑADIR PRODUCTOS-->
        <div class="radical2"><h2>Añadir Productos</h2></div>
            <br>
            <div class="extremo">
                <form class="padre"  method="post" >
                <!-- div1 -->
                    <div class="div1" >
                         <!-- este es un label para transformar una imagen  a un input de tipo file-->
                         <label for="panesimagen">  
                         <img  src="../img/Icono de Mas.avif" id="VistaPrevia" width="200px" height="180px" alt="">
                         </label>
                         <input class="btn" id="panesimagen" type="file" name="panesimagen" >
                    </div>
                              <!-- div2 -->
                        <div class="div2">
                        <!-- este es mi imput para ponerle nombre al nuevo producto -->
                            <input class="np btn" id="inputnombrepan" type="text" name="panesnombre"  placeholder="Nombre del Producto" maxlength="40" required>
                            <br>
                        <!-- este es mi imput para ponerle descripcion  al nuevo producto -->
                            <textarea class="form-control w-30 btn ;" id="inputdescpan" name="panesdesc" rows="2" maxlength="200" placeholder="Escribe la descripción aquí..."  required></textarea>
                        </div>
                            <!-- div3 -->
                            <div class="div2">
                            <!-- este es mi imput para asignarle el precio al nuevo producto -->
                                <div class="simbolo">
                                    <span>$</span>
                                    <input class="dineros btn" type="text" id="inputpreciopan" name="panesprecio" placeholder="Precio" oninput="validarprecio(this)">
                                </div>
                            <!-- este es mi select para asignarle una categoria al nuevo producto -->
                                <?php
                               
                                echo "<div class= 'mb-3' id='consultacategoriasotravez'>";
                               
                                echo "</div>";
                                ?>
                            </div>
                                  
                              <!-- div4 -->
                        <div class="div2 botoncat">
                        <!-- aqui esta un icono convertido a boton el cual es un imput de tipo submit -->
                          <button type="button" name="liveToast10" onclick="AñadirProducto()">
                          <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="green" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                          <circle cx="8" cy="8" r="8" fill="green"/> 
                          <path fill="white" d="M12.03 4.97a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                          </svg>
                          </button>
                        <!-- aqui esta un icono convertido a boton el cual es un imput de tipo reset -->
                        <label for="BotonCancelarC">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="red" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                        <circle cx="8" cy="8" r="8" fill="red"/>
                        <path fill="white" d="M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                        </svg>
                        </label>
                        <input id="BotonCancelarC" type="reset" value="" name="BotonCancelarC">                 
                        </div>
             </form>
        </div>

    <hr>
                            <!--PAGINACION-->
    <?php
      $records_per_page = 6;
      $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
      $categoria_seleccionado = isset($_GET['categoria']) ? $_GET['categoria'] : 0;
      
      $offset = ($current_page - 1) * $records_per_page;
      try {
          $productos = $Conexion->selectConsulta("call Ver_Productos_Informacion($categoria_seleccionado, null, $offset, $records_per_page)");
          
          $productos_totales = $Conexion->selectConsulta("call Ver_Productos_Informacion($categoria_seleccionado, null, null, null)");
          
          $total_records = count($productos_totales);
          $total_pages = ceil($total_records / $records_per_page);
          
        } catch (Exception $e) {
            echo $e->getMessage();
        }      
        ?>
          <div class="radical2"><h2>Gestionar productos</h2></div>
          <br>
        <div class="modificarproductos">                 
         <input type="hidden" value="<?php echo $current_page?>" id="paginageneral">
               <!-- Empieza el php-->
      <?php
      
        echo "<div class= 'radical' id='consultageneral'>";
        
        echo "</div>";          
    ?>

        <div class="paginacion" id="paginacion"></div>

        <!--FUNCION DE JAVA PARA INSERTAR IMAGENES -->
        <script>
            var pagina;
        
        if (pagina == null){
             pagina = 1;
        }
        function cargarproductos(pagina){
            $.ajax({
                type: 'POST',
                url: '../php/cargaconsulta.php',
                // en el apartado de data se encuentran el nombre de las variables que van a mandar los valores al procedimiento almacenado
                data: {pagina: pagina},
                success:function(response){
                    $('#consultageneral').html(response);
                    // alert(response);
                }
            })
        }
        
        cargarproductos(pagina)
        function mostrarPaginacion() {
            $.ajax({
                type: 'POST',
                url: '../php/paginacionproductos.php',
                data: { pagina: pagina },
                success: function(response) {
                    $('#paginacion').html(response);
                }
            });
        }
        mostrarPaginacion()
        function cambiarPaginacion(cambio) {
            pagina = cambio;
            mostrarPaginacion()
            cargarproductos(pagina)
        }
            var imagenPuesto = 0;
             document.getElementById('panesimagen').addEventListener('change', function(event){
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                   reader.onload =function(e) {
                   document.getElementById('VistaPrevia').src = e.target.result;
                    }
                   reader.readAsDataURL(file);
                   imagenPuesto = 1;
                 mostrarPaginacion()
                  cargarproductos(pagina)

               }
             });
        </script>
 </div>

<script>
       function AñadirProducto(){
           var id_nombre =document.getElementById('inputnombrepan');
           var nombre =id_nombre.value;
            var id_desc =document.getElementById('inputdescpan');
            var descripcion =id_desc.value;
            var id_precio =document.getElementById('inputpreciopan');
            var precio =id_precio.value;
            var id_categoria =document.getElementById('selectcatpan'); 
            var categoria =id_categoria.value;
            var id_imagen =document.getElementById('panesimagen');
            var fileInput = $(id_imagen)[0];
            var file = fileInput.files[0];
            
            var formData = new FormData();
            formData.append('nombre', nombre);
            formData.append('categoria',categoria);
            if (file){
            
                formData.append('imagen', file);
            }
            formData.append('precio',precio);
            formData.append('descripcion',descripcion);
            formData.append('imagenPuesto',imagenPuesto);
            $.ajax({
                    url: '../php/guardapan.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response){
                       
                    if (response == "Producto añadido correctamente "){
                         id_nombre.value = '';
                         id_desc.value = '';
                         id_precio.value = '';
                         id_categoria.value = '0';
                         document.getElementById('VistaPrevia').src = "../img/Icono de Mas.avif";
                         fileInput.value = '';
                        }
                        cargarproductos(pagina)
                        mostrarPaginacion()
                         
                        var toastContainer = document.getElementById('toastContainer');
            var newToast = document.createElement('div');  // Crear un nuevo elemento toast
            newToast.className = 'toast';
            newToast.setAttribute('role', 'alert');
            newToast.setAttribute('aria-live', 'assertive');
            newToast.setAttribute('aria-atomic', 'true');
            newToast.innerHTML = `  
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
            if(response === "Producto añadido correctamente")
            {
                alert(1);

                id_nombre.value = '';
                id_desc.value = '';
                id_precio.value = '0';
                id_categoria.value = '0';
                document.getElementById('VistaPrevia').src = "../img/Icono de Mas.avif";  
           
                 fileInput.value = "";  
             }
                }
             });
            
       }
    </script>

       
       <!--FUNCION DE JAVA PARA MODIFCAR LA INFORMACION DE LOS PRODUCTOS-->
       <script>
                    function ModificarProducto (IDPorducto,IDNombre, IDPrecio, IDDescipcion){  
                       var nombre = document.getElementById(IDNombre);
                       var precio = document.getElementById(IDPrecio);
                       var descripcion = document.getElementById(IDDescipcion);

                       $.ajax({
                           type: 'POST',
                           url: '../php/editaproducto.php',
                           // en el apartado de data se encuentran el nombre de las variables que van a mandar los valores al procedimiento almacenado
                           data: {IDPorducto: IDPorducto, nombre: nombre.value, precio: precio.value, descripcion: descripcion.value },
                           success:function(response){
                            cargarproductos(pagina)
                            mostrarPaginacion()
                            var toastContainer = document.getElementById('toastContainer');
            var newToast = document.createElement('div');  // Crear un nuevo elemento toast
            newToast.className = 'toast';
            newToast.setAttribute('role', 'alert');
            newToast.setAttribute('aria-live', 'assertive');
            newToast.setAttribute('aria-atomic', 'true');
            newToast.innerHTML = `  
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
        
                </script>

     <hr>

                <!--AÑADIR CATEGORIAS-->
    
<div class="radical2"><h2>Añadir Categoría</h2></div>
<br>
<div class="padre">
    <form>
        <div class="botoncat">
            <input class="nc btn" type="text" name="CatNombre" id="inputNombreCategoria" placeholder="Nombre" maxlength="35" required>
            <!-- ICONO CONVERTIDO A BOTON PARA ACEPTAR LA CATEGORIA -->
            <button type="button" name="liveToast6" onclick="AñadirCategorias()">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="green" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                <circle cx="8" cy="8" r="8" fill="green"/> 
                <path fill="white" d="M12.03 4.97a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
            </button>
            <!-- aquí está un icono convertido a botón el cual es un input de tipo reset -->
            <label for="BotonCancelarC">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="red" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                <circle cx="8" cy="8" r="8" fill="red"/>
                <path fill="white" d="M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                </svg>
            </label>
            <input id="BotonCancelarP" type="reset" value="" name="BotonCancelarP">
        </div>
    </form>
</div>   

             
      
      <!-- GESTIONAR CATEGORIAS-->
       <hr>
       <div class="radical2"><h2>Gestionar Categoría</h2></div>
       <br>
       <div class="centrarcategorias">
    <?php
         // EN ESTE DIV SE ENCUENTRA LA ID DE UNA FUNCION DE JAVA PARA QUE SE REALICE LA CONSULTA DE LAS CATEGORIAS
        echo "<div class='radical' id='consultacategorias'>";
        echo"</div>"; 
    ?>
        </div>; 

   
</div>
        <!--SCRIPT DE UNA CONSULTA DE AJAX PARA QUE LOS CAMBIOS SE REFLEJEN SIN LA NECESIDAD DE RECARGAR LA PAGINA (EN EL APARTADO DE AÑADIR CATEGORIA)-->
<script> 
    function cargarcategorias(){
            $.ajax({
                type: 'POST',
                url: '../php/cargacategorias.php',
                // en el apartado de data se encuentran el nombre de las variables que van a mandar los valores al procedimiento almacenado
                success:function(response){
                    $('#consultacategorias').html(response);
                    // alert(response);
                }
            })
        }
        cargarcategorias()
</script>

    <!--SCRIPT DE UNA CONSULTA DE AJAX PARA QUE LOS CAMBIOS SE REFLEJEN SIN LA NECESIDAD DE RECARGAR LA PAGINA (EN EL MENU DESPLEGABLE DE AÑADIR UN NUEVO PRODUCTO)-->
<script>
    function cargarcategoriasotravez(){
        $.ajax({
                type: 'POST',
                url: '../php/cargacategoriasotravez.php',
                success:function(response){
                    $('#consultacategoriasotravez').html(response);
                }
            })
        }
        cargarcategoriasotravez()
</script>

    <!--FUNCION DE JAVA PARA MODIFICAR LA IMAGEN DE UN PRODUCTO-->
<script>
       function ModificarImagen(input, id_producto){
        var fileInput = $(input)[0];
        var file = fileInput.files[0];
        if (file)
        {
            var formData = new FormData();
            formData.append('imagen', file);
            formData.append('id', id_producto);
            $.ajax({
                    url: '../php/nuevaimagen.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response){
                        cargarproductos(pagina)

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
    
        
    <!--MENSAJE TOAST PARA HABILITAR Y DESHABILITAR PRODUCTOS-->
    
<script>
            function CambioEstadoProducto(checkbox, id_producto)
        {
                 var Estado;
                 if (checkbox.checked){
                    Estado = 1;
                 }
                 else {
                    Estado = 0;
                 }
                 $.ajax({
                    type:'POST',
                        url:'../php/estadoproducto.php',
                            data: {id_producto: id_producto, Estado: Estado},
                    success:function(response){
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
</script>


<!--TODO EL TEMA DE CATEGORIAS AÑADIR MODIFICAR Y HABILITAR/DESHABILITAR-->


<!-- FUNCION DE JAVA MAS TOAST PARA AÑADIR CATEGORIAS + MENSAJE TOAST -->
<script>
    function AñadirCategorias() 
    {
        var id = document.getElementById('inputNombreCategoria');
        var nombre = id.value;
        $.ajax({
            type: 'POST',
            url: '../php/guardacategoria.php',
            data: { nombre: nombre },
            success: function(response) {
                cargarcategorias()
                cargarcategoriasotravez()
                id.value='';
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
</script>
   

        <!--FUNCION DE JAVA + TOAST  PARA HABILITAR Y DESACTIVAR CATEGORIAS-->
<script>
        function cambiarEstado(checkbox, id_categoria)
    {
                var Estado;
                if (checkbox.checked)
                {
                    Estado = 1;
                }
                else 
                {
                    Estado = 0;
                }
                $.ajax({
                type: 'POST',
                url: '../php/cambiocategoria.php',
                data: { id_categoria: id_categoria, Estado: Estado },
                success: function(response) {
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
</script>


        <!--FUNCION DE JAVA + TOAST DE CAMBIO DE NOMBRE DE UNA CATEGORIA-->
<script>
            function NuevoNombreCategoria(id_categoria, nombrecat)
    {
                var nombre = document.getElementById(nombrecat).value;
                $.ajax({
                type:'POST',
                url:'../php/nombrecategoria.php',
                data: { id_categoria: id_categoria, Nombre: nombre },
                success: function(response) {
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
</script>

        <!--SCRIPT DE JAVA PARA LIMITAR EL USO SOLO DE PALABRAS Y NO NUMEROS EN EL NOMBRE DE LA CATEGORIA--->
        <script>
        const inputcat = document.querySelectorAll(".nc");
         inputcat.forEach(function (inputcat){
         inputcat.addEventListener("input",function(){
         this.value = this.value.replace(/[^a-zA-Z\sàèìòùáéíóúñÀÈÌÒÙÁÉÍÓÚÑ]/g, '');
          });
        });
       </script>
         
        <!--SCRIPT DE JAVA PARA LIMITAR EL USO DE SOLO PALABRAS Y NO NUMEROS EN AÑADIR NUEVO PRODUCTO-->
        <script>
        const inputpan = document.querySelectorAll(".np");
        inputpan.forEach(function (inputpan){
        inputpan.addEventListener("input",function(){
        this.value = this.value.replace(/[^a-zA-Z\sàèìòùáéíóúñÀÈÌÒÙÁÉÍÓÚÑ]/g, '');
         });
        });
        </script>
    
        <!--SCRIPT DE JAVA PARA LIMITAR EL USO SOLO NUMEROS Y NO PALABRAS USADO EN AÑADIR NUEVO PRODUCTO Y GESTIONAR PRODUCTO--->   
        <script>
        function validarprecio(inputElement) {
        let value = inputElement.value;

        // Elimina caracteres no numéricos excepto el punto decimal
        value = value.replace(/[^0-9.]/g, '');

        // Dividir la cadena por el punto decimal
        const parts = value.split('.');

        // Si hay más de un punto decimal, reconstruir la cadena con solo el primer punto
        if (parts.length > 2) {
            value = parts.shift() + '.' + parts.join('');
        }

        // Limitar los dígitos decimales a solo dos
        if (parts[1] && parts[1].length > 2) {
            parts[1] = parts[1].substring(0, 2);
            value = parts.join('.');
        }

        if (value > 999.99) {
            value = 999.99;
        }

        // Actualiza el valor del campo de entrada
        inputElement.value = value;
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
