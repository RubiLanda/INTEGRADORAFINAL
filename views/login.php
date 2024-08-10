<?php
session_start();
if (isset($_SESSION['Rol']))
{
    switch ($_SESSION['Rol'])
    {
        case 1:
            header("Location: ../views/AdministradorVerPedidos.php");
            break;
        case 2:
            header("Location: ../views/ClienteRealizarPedido.php");
            break;
        case 3:
            header("Location: ../views/mispedidosrepa.php");
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/diseñologin.css">
</head>
<body>

    <div class="formulario">
        <div class="form">
            <h1>Inicio de Sesión</h1>
            <div class="username">
                <input type="text" id="usuario" required autocomplete="off">
                <label>Ingresa tu usuario:</label>
            </div>
            <div class="username">
                <input type="password" id="contraseña" required >
                <label>Contraseña:</label>
            </div>
            <button onclick="iniciarSesion()">Iniciar</button>
            <div class="registrarse">
            ¿No tienes cuenta? <a href="registro.php"> Registrate aquí</a><br>
            </div>
        </div>
    
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1050" id="imprimirnoti"></div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function iniciarSesion(){
            
            $.ajax({
                type: 'POST',
                url: '../php/verificarlogin.php',
                data: { usuario: usuario.value, contraseña: contraseña.value },
                success: function(response) {
                    if (response == 1 || response == 2 || response == 3) {
                        if (response == 1) {
                            window.location.href = 'Administrador.php';
                        }
                        if (response == 2) {
                            window.location.href = 'Cliente.php';
                        }
                        if (response == 3) {
                            window.location.href = 'repartidor.php';
                        }
                    }
                    else {
                        
                        var toastContainer = document.getElementById('imprimirnoti');
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
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>