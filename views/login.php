<?php
session_start();
if (isset($_SESSION['Rol']))
{
    switch ($_SESSION['Rol'])
    {
        case 1:
            header("Location: AdministradorVerPedidos.php");
            break;
            case 2:
                header("Location: ClienteRealizarPedido.php");
                break;
                case 3:
                    header("Location: mispedidosrepa.php");
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
        <link rel="stylesheet" href="../css/registro.css">
    </head>
    <body>
        <div class="form">
            <div class="divrefe">
                <a href="../index.php"> <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="regresar" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
</svg>
</a>
            </div>
            <h2>Inicio de Sesión!</h2>
            
            <div class="inputs">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="icono" viewBox="0 0 16 16">
                <path d="M9 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h10s1 0 1-1-1-4-6-4-6 3-6 4m13.5-8.09c1.387-1.425 4.855 1.07 0 4.277-4.854-3.207-1.387-5.702 0-4.276Z"/>
            </svg>
            <input type="text" id="usuario" placeholder="Usuario" required autocomplete="off">
        </div>
        
        <div class="inputs">
        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" class="icono" width="32" height="32" viewBox="0 0 26 26">
                                <path d="M 13 0 C 10.320313 0 8.195313 0.832031 6.84375 2.34375 C 5.492188 3.855469 5 5.839844 5 7.90625 L 5 9 L 8 9 L 8 7.90625 C 8 6.3125 8.359375 5.128906 9.0625 4.34375 C 9.765625 3.558594 10.898438 3 13 3 C 15.105469 3 16.238281 3.535156 16.9375 4.3125 C 17.636719 5.089844 18 6.296875 18 7.90625 L 18 9 L 21 9 L 21 7.90625 C 21 5.828125 20.511719 3.820313 19.15625 2.3125 C 17.800781 0.804688 15.675781 0 13 0 Z M 5 10 C 3.34375 10 2 11.34375 2 13 L 2 23 C 2 24.65625 3.34375 26 5 26 L 21 26 C 22.65625 26 24 24.65625 24 23 L 24 13 C 24 11.34375 22.65625 10 21 10 Z M 7 16 C 8.105469 16 9 16.894531 9 18 C 9 19.105469 8.105469 20 7 20 C 5.894531 20 5 19.105469 5 18 C 5 16.894531 5.894531 16 7 16 Z M 13 16 C 14.105469 16 15 16.894531 15 18 C 15 19.105469 14.105469 20 13 20 C 11.894531 20 11 19.105469 11 18 C 11 16.894531 11.894531 16 13 16 Z M 19 16 C 20.105469 16 21 16.894531 21 18 C 21 19.105469 20.105469 20 19 20 C 17.894531 20 17 19.105469 17 18 C 17 16.894531 17.894531 16 19 16 Z"></path>
                                </svg>
        <input type="password" id="contraseña" placeholder="Contraseña" required >
        </div>
        
        <button  class="botonn"  onclick="iniciarSesion()">Iniciar</button>
        
        ¿No tienes cuenta? <a href="registro.php"> Registrate aquí</a><br>
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
                                window.location.href = 'AdministradorVerPedidos.php';
                            }
                            if (response == 2) {
                                window.location.href = 'ClienteRealizarPedido.php';
                            }
                            if (response == 3) {
                                window.location.href = 'mispedidosrepa.php';
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
