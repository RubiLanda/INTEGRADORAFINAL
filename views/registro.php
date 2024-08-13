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
            header("Location: repartidor.php");
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
    <title>Registrate Ahora!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/registro.css">
    
</head>
<body>
<form action="">

<h2>Registrate Ahora!</h2>
              

                <div class="inputs">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#BA4A00" class="icono" viewBox="0 0 16 16">
                    <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"/>
                    </svg>
                <input type="text" name="nombre" id="nombre" class="nombre" minlength="3" maxlength="50" placeholder="Nombre(s)" required autocomplete="off">
                </div>
                
                <div class="inputs">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="icono" viewBox="0 0 16 16">
                        <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"/>
                    </svg>
                    <input type="text" name="paterno" id="paterno" class="nombre" placeholder="Apellido Paterno" minlength="3" maxlength="50" required autocomplete="off">
                </div>
                
                <div class="inputs">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="icono" viewBox="0 0 16 16">
                        <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"/>
                    </svg>
                    <input type="text" name="materno" id="materno" minlength="3" placeholder="Apellido Materno" class="nombre" maxlength="50"  autocomplete="off">
                </div>
                
                <div class="inputs">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="icono" viewBox="0 0 16 16">
                    <path d="m7.994.013-.595.79a.747.747 0 0 0 .101 1.01V4H5a2 2 0 0 0-2 2v3H2a2 2 0 0 0-2 2v4a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-4a2 2 0 0 0-2-2h-1V6a2 2 0 0 0-2-2H8.5V1.806A.747.747 0 0 0 8.592.802zM4 6a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v.414a.9.9 0 0 1-.646-.268 1.914 1.914 0 0 0-2.708 0 .914.914 0 0 1-1.292 0 1.914 1.914 0 0 0-2.708 0A.9.9 0 0 1 4 6.414zm0 1.414c.49 0 .98-.187 1.354-.56a.914.914 0 0 1 1.292 0c.748.747 1.96.747 2.708 0a.914.914 0 0 1 1.292 0c.374.373.864.56 1.354.56V9H4zM1 11a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.793l-.354.354a.914.914 0 0 1-1.293 0 1.914 1.914 0 0 0-2.707 0 .914.914 0 0 1-1.292 0 1.914 1.914 0 0 0-2.708 0 .914.914 0 0 1-1.292 0 1.914 1.914 0 0 0-2.708 0 .914.914 0 0 1-1.292 0L1 11.793zm11.646 1.854a1.915 1.915 0 0 0 2.354.279V15H1v-1.867c.737.452 1.715.36 2.354-.28a.914.914 0 0 1 1.292 0c.748.748 1.96.748 2.708 0a.914.914 0 0 1 1.292 0c.748.748 1.96.748 2.707 0a.914.914 0 0 1 1.293 0Z"/>
                </svg>
                <input type="date" name="nacimiento" placeholder="Fecha de Nacimiento"  id="nacimiento" required >
            </div>
                
            <div class="inputs">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="icono" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M11.5 1a.5.5 0 0 1 0-1h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V1.707l-3.45 3.45A4 4 0 0 1 8.5 10.97V13H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V14H6a.5.5 0 0 1 0-1h1.5v-2.03a4 4 0 1 1 3.471-6.648L14.293 1zm-.997 4.346a3 3 0 1 0-5.006 3.309 3 3 0 0 0 5.006-3.31z"/>
                </svg>
                <select id="genero" name="genero" required>
                    <option disabled selected value="0">Género</option>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
                <option value="O">Otro</option>
            </select>
        </select>
                </div>

                <div class="inputs">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="icono" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/>
                    </svg>
                <input type="tel" name="telefono" id="telefono" maxlength="10" required autocomplete="off" placeholder="Télefono" oninput="validartelefono(this)" >
            </div>

                <div class="inputs">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="icono" viewBox="0 0 16 16">
                        <path d="M9 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h10s1 0 1-1-1-4-6-4-6 3-6 4m13.5-8.09c1.387-1.425 4.855 1.07 0 4.277-4.854-3.207-1.387-5.702 0-4.276Z"/>
                    </svg>
                    <input type="text" name="usuario" id="usuario" placeholder="Usuario" required autocomplete="off">
                </div>

                <div class="inputs">
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" class="icono" width="32" height="32" viewBox="0 0 26 26">
                    <path d="M 13 0 C 10.320313 0 8.195313 0.832031 6.84375 2.34375 C 5.492188 3.855469 5 5.839844 5 7.90625 L 5 9 L 8 9 L 8 7.90625 C 8 6.3125 8.359375 5.128906 9.0625 4.34375 C 9.765625 3.558594 10.898438 3 13 3 C 15.105469 3 16.238281 3.535156 16.9375 4.3125 C 17.636719 5.089844 18 6.296875 18 7.90625 L 18 9 L 21 9 L 21 7.90625 C 21 5.828125 20.511719 3.820313 19.15625 2.3125 C 17.800781 0.804688 15.675781 0 13 0 Z M 5 10 C 3.34375 10 2 11.34375 2 13 L 2 23 C 2 24.65625 3.34375 26 5 26 L 21 26 C 22.65625 26 24 24.65625 24 23 L 24 13 C 24 11.34375 22.65625 10 21 10 Z M 7 16 C 8.105469 16 9 16.894531 9 18 C 9 19.105469 8.105469 20 7 20 C 5.894531 20 5 19.105469 5 18 C 5 16.894531 5.894531 16 7 16 Z M 13 16 C 14.105469 16 15 16.894531 15 18 C 15 19.105469 14.105469 20 13 20 C 11.894531 20 11 19.105469 11 18 C 11 16.894531 11.894531 16 13 16 Z M 19 16 C 20.105469 16 21 16.894531 21 18 C 21 19.105469 20.105469 20 19 20 C 17.894531 20 17 19.105469 17 18 C 17 16.894531 17.894531 16 19 16 Z"></path>
                    </svg>
                    <input type="password" minlength="7" name="contraseña" id="contraseña" placeholder="Contraseña" required autocomplete="off" >
                </div>
                
                <div class="inputs">
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" class="icono" width="32" height="32" viewBox="0 0 26 26">
                    <path d="M 13 0 C 10.320313 0 8.195313 0.832031 6.84375 2.34375 C 5.492188 3.855469 5 5.839844 5 7.90625 L 5 9 L 8 9 L 8 7.90625 C 8 6.3125 8.359375 5.128906 9.0625 4.34375 C 9.765625 3.558594 10.898438 3 13 3 C 15.105469 3 16.238281 3.535156 16.9375 4.3125 C 17.636719 5.089844 18 6.296875 18 7.90625 L 18 9 L 21 9 L 21 7.90625 C 21 5.828125 20.511719 3.820313 19.15625 2.3125 C 17.800781 0.804688 15.675781 0 13 0 Z M 5 10 C 3.34375 10 2 11.34375 2 13 L 2 23 C 2 24.65625 3.34375 26 5 26 L 21 26 C 22.65625 26 24 24.65625 24 23 L 24 13 C 24 11.34375 22.65625 10 21 10 Z M 7 16 C 8.105469 16 9 16.894531 9 18 C 9 19.105469 8.105469 20 7 20 C 5.894531 20 5 19.105469 5 18 C 5 16.894531 5.894531 16 7 16 Z M 13 16 C 14.105469 16 15 16.894531 15 18 C 15 19.105469 14.105469 20 13 20 C 11.894531 20 11 19.105469 11 18 C 11 16.894531 11.894531 16 13 16 Z M 19 16 C 20.105469 16 21 16.894531 21 18 C 21 19.105469 20.105469 20 19 20 C 17.894531 20 17 19.105469 17 18 C 17 16.894531 17.894531 16 19 16 Z"></path>
                    </svg>
                <input type="password"  minlength="7" name="vercontra" id="vercontra" placeholder="Confirmar Contraseña" required autocomplete="off" >
                </div>
                                   
                
                <button type="button" class="botonn" onclick="validarFormulario()">REGISTRAR</button>
                
                ¿Ya tienes cuenta? <a href="login.php"> Inicia Sesión Aquí!</a><br>
</form>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        const inputTextos = document.querySelectorAll(".nombre");
    inputTextos.forEach(function(inputTexto) {
        inputTexto.addEventListener("input", function() {
            this.value = this.value.replace(/[^a-zA-Z\sàèìòùáéíóúñÀÈÌÒÙÁÉÍÓÚÑ]/g, '');
        });
    });
    

function validartelefono(input){
    input.value = input.value.replace(/\D/g, '');
    
}; 

function validarFormulario() {

    // Si todo es válido, proceder con el registro
    registro();
    return true;

};

</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- ESTE ES EL SCRIPT QUE HACE QUE FUNCIONEN LAS FUNCIONES DE JS--> 
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11" id="imprimirnoti"></div>
<script>
    function registro() 
    {
        var user = document.getElementById('usuario');
        var nameuser = user.value;
        
        var contraseña=document.getElementById('contraseña');
        var contra=contraseña.value;

        var vericontraseña=document.getElementById('vercontra');
        var vericontra=vericontraseña.value;
        
        var nombre= document.getElementById('nombre');
        var nom=nombre.value;
        
        var paterno= document.getElementById('paterno');
        var pate=paterno.value;

        var materno= document.getElementById('materno');
        var mate=materno.value;
        
        var genero= document.getElementById('genero');
        var gene=genero.value;

        var nacimiento= document.getElementById('nacimiento');
        var naci=nacimiento.value;

        var telefono= document.getElementById('telefono');
        var tele=telefono.value;
                
if (contra.length<=7){
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
                            La contraseña debe tener mas de 8 caracteres :)
                            `;
                            toastContainer.appendChild(newToast);  // Añadir el nuevo toast al contenedor
                            var toast = new bootstrap.Toast(newToast, {  // Inicializar y mostrar el nuevo toast
                                delay: 5000 // Duración del toast en milisegundos
                            });
                            toast.show();
}else{
    $.ajax({
            type: 'POST',
            url: '../php/registro.php',
            data: { nameuser: nameuser,contra:contra,vericontra:vericontra, nom:nom, pate:pate,
             mate:mate, gene:gene, naci:naci, tele:tele },
            success: function(response) {
                
                if (response == 1){
                    window.location.href = "../php/verificarlogin.php?usuario=" + nameuser + "&&password=" + contra;
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
        
        
        }
</script>
</body>
</html>
        