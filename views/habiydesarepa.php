<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/pedidosrepartiodor.css">
        <link rel="stylesheet" href="../css/header.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <title>HABILITAR Y DESHABILITAR REPAS</title>
    </head>
    <body>
        <div class="fondo"></div>
        <?php 



    include '../php/conexion.php';
    $conexion=new Database();
    $conexion->conectarBD();
    
    //EN ESTE IF EL ISSET VA A VERIFICAR CUANDO UN VALOR ES NULO O NO ES NULO, DENTRO DE LA VARIABLE $id_repa guardo el get 
    // de la id para saber si es nulo o no es, cuando es nulo me va a mandar a ver repartidores, osea que es cuando ningun repartidor esta seleccionado
    // y cuando no es nulo me va mandar a ver los pedlidos de dicho repartidor seleccionado, o bueno de esa ID
    if (isset($_GET['idrepa'])){
        $id_repa = $_GET['idrepa'];
        $pedidosrepa=$conexion->selectConsulta("call Ver_Pedidos_Cliente_SinTienda_Repartidor('$id_repa',null)");
        
        echo"<div class=\"TituloPedidosTienda\">
        <h1> PEDIDOS</h1>
            <a href=\"?apartado=5&&estado=5\">
                <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"50\" height=\"50\" fill=\"currentColor\" class=\"bi bi-arrow-left\" viewBox=\"0 0 16 16\">
                    <path fill-rule=\"evenodd\" d=\"M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8\"/>
                </svg>
            </a>
        </div>";

        if(count($pedidosrepa)==0){
            echo "<div class=\"SinPedidos\">Este repartidor no ha entregado ningún pedido</div>";
        }
        else{
            echo "<div class=\"tabla\">";
            foreach($pedidosrepa as $pedidos){
                $Cons = $conexion->selectConsulta("call Ver_Detalle_Pedido({$pedidos->ID})");
                $Total_Pagar = $conexion->selectConsulta("call Calcular_Total_Pagar_Pedido({$pedidos->ID})");
                echo"<div class= \"pedido\">
                <h1>#{$pedidos->ID}</h1>
                <h3><b>Tienda:</b> {$pedidos->Tienda}</h3>
                <h3><b>Direccion:</b> {$pedidos->Direccion}</h3>
                <h3><b>Cliente:</b> {$pedidos->Cliente}</h3>
                <h3><b>Fecha Pedido:</b> {$pedidos->Fecha_Pedido}</h3>
                <h3><b>Fecha Requerido:</b> {$pedidos->Fecha_Requerido}</h3>";          
                if($pedidos->Estado=='entregado'){
                    
                    echo"<h3><b>Fecha entregada:</b> {$pedidos->Fecha_entregada}</h3>";
                }
                echo"<h3><b>Estado del Pedido:</b> {$pedidos->Estado}</h3>";
                echo "<button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalDetalles{$pedidos->ID}\">
                Ver detalles del pedido
                </button>";
                echo "</div>";
                echo "<div class=\"modal fade\" id=\"ModalDetalles{$pedidos->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                <div class=\"modal-dialog ModalDetalles modal-dialog-centered modal-dialog-scrollable\">
                <div class=\"modal-content\">
                <div class=\"modal-header\">
                <h1>Detalle del pedido #{$pedidos->ID}</h1>
                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                </div>
                <div class=\"modal-body\">";
                if (count($Cons) == 0){
                    echo "<h2>El pedido no tiene productos</h2>";
                }
                else{
                    echo "<div class=\"info\">
                    <h2>Producto</h2>
                    <h2>Cantidad</h2>
                    <h2>Subtotal</h2>";
                    foreach ($Cons as $fi) {
                        echo "<h3>{$fi->Producto}</h3>";
                        echo "<h3>{$fi->Cantidad}</h3>";
                        echo "<h3>{$fi->Total}</h3>";
                    }
                    echo "<h2></h2>
                    <h2>Total</h2>
                    <h2>\${$Total_Pagar[0]->Total}</h2>
                    </div>";
                }
                echo "</div>
            </div>
        </div>
    </div>";
        }
        echo"</div>";
    }
}
else{
        $repartidor=$conexion->selectConsulta("call Ver_Repartidores(null)");

        echo"<div class='Tiendas'>";
        echo"<h1 class='TituloTienda'>INFORMACIÓN DE REPARTIDORES</h1>";
       
    
        foreach($repartidor as $repa){
            echo"<div class='Tienda'>";
            echo"<h3>$repa->Nombre</h3>";
            if($repa->Estatus){
                echo"<h3>Estado: <input type='checkbox' class=\"check\"  onclick=\"HABILITAR(this,{$repa->ID})\" checked></h3>";
            }else{
                echo"<h3>Estado: <input type='checkbox' class=\"check\" onclick=\"HABILITAR(this,{$repa->ID})\" ></h3>";

            }
            echo"<button type=\"button\" class=\"boton\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalInformacion{$repa->ID}\">
            Ver Informacion
            </button>";

            //declaro mi variable y esa variable va a valer el id del repartidor que ya habia sacado anterioremente de mi consulta
             //AQUI EN VES DE QUE ME REDIRECCIONE A OTRA PAGINA ME DIRECCIONA A OTRA DENTRO DE ESTA MISMA PERO DEPENDIENDO DEL ID DEL REPARTIDOR

            echo"<a href=\"?idrepa={$repa->ID}
            \" >Ver Pedidos!</a>
            </div>";
            
            
            echo "<div class=\"modal fade\" id=\"ModalInformacion{$repa->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
            <div class=\"modal-dialog modal-dialog-centered\">
            <div class=\"modal-content\">
            <div class=\"modal-header\">
            <h1>Informacion Del Repartidor</h1>
            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
            </div>

            <div class=\"modal-body\">
            <h3><b>Usuario:</b> {$repa->usuario}<h3>
            <h3><b>Nombre:</b> {$repa->Nombre}<h3>
            <h3><b>Fecha Nacimiento:</b> {$repa->Fecha_nacimiento}</h3>
            <h3><b>Genero:</b> {$repa->Genero}</h3>
            <h3><b>Telefono:</b> {$repa->Telefono}</h3>
            <h3><b>Fecha de Ingreso:</b> {$repa->Fecha_Ingreso}</h3>
            <h3><b>Licencia:</b> {$repa->licencia_conducir}</h3>
            </div>
            </div>
            </div>
            </div>";
            
        }
        echo"</div>";
    }
    ?>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- ESTE ES EL SCRIPT QUE HACE QUE FUNCIONEN LAS FUNCIONES DE JS--> 
 <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11" id="imprimirnoti"></div>
 <script>
     function HABILITAR(checkbox, ID){
         var Estado;
         if (checkbox.checked){
             Estado = 1;
            }
            else {
                Estado = 0;
            }
            $.ajax({
                  type: 'POST',
                  url: '../php/cambiorepa.php',
                  data: { ID: ID, Estado: Estado },
                  success: function(response) {
                      var toastContainer = document.getElementById('imprimirnoti');
                      var newToast = document.createElement('div');  // Crear un nuevo elemento toast
                      newToast.className = 'toast';
                      newToast.setAttribute('role', 'alert');
            newToast.setAttribute('aria-live', 'assertive');
            newToast.setAttribute('aria-atomic', 'true');
            newToast.innerHTML = `  
                <div class="toast-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
</svg><br>
<strong class="me-auto"> NOTIFICACION </strong>
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
</body>
</html>

