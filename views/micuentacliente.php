<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/micuenta.css">
        <title>MI CUENTA</title>
    </head>
    <body>
        <div class="fondo"></div>
        <div >
            <?php
        include '../php/conexion.php';
        $conexion=new Database();
        $conexion->conectarBD();
        session_start();
        $persona = $_SESSION['ID']; // esta id es de usuario no de persona
        $info=$conexion->selectConsulta("call Ver_Informacion_Usuario($persona)");
        
        foreach($info as $perso){
            echo "<div>
            
            <div class=\"card contenedor\">
            <div class=\"titulo\">
            <svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"currentColor\" width='64' heigth='64' class=\"icono\" viewBox=\"0 0 16 16\">
            <path d=\"M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0\"/>
            </svg>
            <h1 class=\"card-title\">MI INFORMACIÓN</h1>
            </div>
            
            <div class=\"card-body\">
            <p class=\"card-text\">
            <h3><b>Usuario:</b> {$perso->usuariop}<h3>
            <h3><b>Nombre:</b> {$perso->Nombre}<h3>
            <h3><b>Fecha Nacimiento:</b> {$perso->Fecha_nacimiento}</h3>
            <h3><b>Genero:</b> {$perso->Genero}</h3>
            <h3><b>Telefono:</b> {$perso->Telefono}</h3>
            <h3><b>Cliente Desde:</b> {$perso->FECHA}</h3>
            </p>
            <div class=\"div\">
            <a href=\"ajaxcambioinfo.php
            \" >Editar Información Aquí!</a>
            </div>
            </div>
            </div>
            </div>";
        }
        echo"<div id=\"contenedor\"></div>";
        
        ?>
    </div>  
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
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
                url: '../php/habiydesatienda.php',
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

<script>
    function cargarInformacionUsuario(){
        $.ajax({
            type:'POST',
            url:'../php/cargatienda.php',
            success: function(response) {
                $('#contenedor').html(response);
            }
        });
    }
    cargarInformacionUsuario()
</script>

<script>
    function editarinfotienda(IDTI,nombretienda,direccion) {
        var nombre = document.getElementById(nombretienda);
        var direccion = document.getElementById(direccion);
        $.ajax({
            type:'POST',
            url:'../php/calleditartienda.php',
            data: { IDTI: IDTI, nombre: nombre.value, direccion:direccion.value },
            success: function(response) {
                cargarInformacionUsuario()
                var toastContainer = document.getElementById('imprimirnoti');
                var newToast = document.createElement('div'); 
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
                toastContainer.appendChild(newToast);  
                var toast = new bootstrap.Toast(newToast, {
                    delay: 7000 
                });
                toast.show();
            }
        });
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>  

</body>
</html>


