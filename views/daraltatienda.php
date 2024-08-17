<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/daraltatienda.css">
        <title>Dar Alta Tienda!</title>
    </head>
    <body>
        <div class="fondo"></div>
        <form class="contenedor" action="" method="">
        <div class="divrefe">
            <a class="refe" href="micuentacliente.php
            " >Volver</a>
            </div>
            <h1>REGISTRAR NUEVA TIENDA</h1>
            <div class=contenedoruno>
                <div class="dentro1 solo" >
                    <label> Nombre de la Tienda: </label>
                    <input type="text" name="tienda" id="tienda" maxlength="50" required autocomplete="off">
                    <label>Dirección: </label>
                    <textarea name="direccion" id="direccion"  minlength="20" maxlength="100"></textarea>
                </div>  
            </div>
            <div class="boton">
                    <button type="button" onclick="añadirtienda()">REGISTRAR</button>
                </div>  
            
            
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- ESTE ES EL SCRIPT QUE HACE QUE FUNCIONEN LAS FUNCIONES DE JS--> 
            <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11" id="imprimirnoti"></div>
            
            <script>
                function añadirtienda() 
                {
                    var tiendas = document.getElementById('tienda');
                    var tienda = tiendas.value;
                    
                    var direcciones=document.getElementById('direccion');
                    var direccion=direcciones.value;
                    
                    $.ajax({
                        type: 'POST',
                        url: '../php/altatienda.php',
                        data: { tienda:tienda, direccion:direccion },
                        success: function(response) {
                            if(response=="Registro Exitoso"){
                                tiendas.value='';
                                direcciones.value='';
                            }
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
                    });                 
                    
                    
                }
                </script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>  
            
        </body>
        </html>
        