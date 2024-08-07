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
            header("Location: ../views/repartidor.php");
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
    <link rel="stylesheet" href="../css/diseñologin.css">
</head>
<body>
    <div class="formulario">
        <h1>Registrate Ahora!</h1>
        <form >
            <div class="inputs">
                <div class="cajas">
                    <div class="username">
                        <input type="text" name="usuario" id="usuario" required autocomplete="off">
                        <label>Ingresa tu usuario:</label>
                    </div>
                    <div class="username">
                        <input type="password" minlength="7" name="contraseña" id="contraseña" required autocomplete="off" >
                        <label>Contraseña:</label>
                    </div>
                    <div class="username">
                        <input type="password"  minlength="7" name="vercontra" id="vercontra" required autocomplete="off" >
                        <label>Confirmar Contraseña:</label>
                        <span id="errorVerificarContra" style="color: red; display: none;">las contraseñas no coinciden </span>
                    </div>
                </div>
                <div class="cajas">
                    <div class="username">
                        <input type="text" name="nombre" id="nombre" class="nombre" minlength="3" maxlength="50" required autocomplete="off">
                        <label>Nombre Completo:</label>
                    </div>
                    <div class="username">
                        <input type="text" name="paterno" id="paterno" class="nombre" minlength="3" maxlength="50" required autocomplete="off">
                        <label>Apellido Paterno:</label>
                    </div>
                    <div class="username">
                        <input type="text" name="materno" id="materno" minlength="3" class="nombre" maxlength="50"  autocomplete="off">
                        <label>Apellido Materno:</label>
                    </div>
                </div>
                <div class="cajas">
                    <div class="username">
                        <input type="date" name="nacimiento" id="nacimiento" required >
                    </div>
                    <div class="username">
                    <select class="form-select" aria-label="Default select example" id="genero" name="genero" required>
                            <option disabled selected>Género</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                            <option value="O">Otro</option>
                        </select>
                    </div>
                    <div class="username">
                        <input type="tel" name="telefono" id="telefono" maxlength="10" required autocomplete="off" oninput="validartelefono(this)" >
                        <label>Teléfono:</label>                    
                    </div>
                </div>
            </div>
            <button type="button" onclick="validarFormulario()">REGISTRAR</button>
            <div class="registrarse">
            ¿Ya tienes cuenta? <a href="login.php"> Inicia Sesión Aquí!</a><br>
            </div>
        </form>
    
    </div>
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
    const contraseña = document.getElementById('contraseña').value;
    const vercontra = document.getElementById('vercontra').value;
    const errorVerificarContra = document.getElementById('errorVerificarContra');
        //validar contraseñas
        if (contraseña !== vercontra) {
        errorVerificarContra.style.display = 'block';
        return false;
    } else {
        errorVerificarContra.style.display = 'none';
    }

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
            data: { nameuser: nameuser,contra:contra, nom:nom, pate:pate,
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