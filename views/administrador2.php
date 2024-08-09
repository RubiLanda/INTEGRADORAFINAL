<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Informacion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/AdministradorGestionProductos.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <div class="fondo"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    
    <style>
         input[type="reset"]{
           display: none;
        }
    </style>
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11" id="toastContainer"></div>

    <div id="informacionUsuario">





    </div>
    
<!--FUNCION DE JAVA + TOAST PARA MODIFICAR LA INFORMACION DE UN USUARIO TIPO ADMIN-->
    <script>
    function cargarInformacionUsuario(){
        $.ajax({
            type:'POST',
            url:'../php/cargarInformacionUsuario.php',
            success: function(response) {
                $('#informacionUsuario').html(response);
            }
        });
    }
    cargarInformacionUsuario()
   
    function CambiarInfo(ID) {
        var nombre = document.getElementById('NombreA');
        var ApellidoP = document.getElementById('ApellidoP');
        var ApellidoM = document.getElementById('ApellidoM');
        var Telefono = document.getElementById('TelefonoA');
                $.ajax({
                type:'POST',
                url:'../php/modinfousuario.php',
                data: { ID: ID, nombre: nombre.value, ApellidoP:ApellidoP.value, ApellidoM:ApellidoM.value, Telefono:Telefono.value },
                success: function(response) {
                cargarInformacionUsuario()
                var toastContainer = document.getElementById('toastContainer');
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


<!--SCRIPT DE JAVA PARA LIMITAR EL USO SOLO NUMEROS Y NO PALABRAS-->   
<script>
        function validarprecio(input){
        input.value = input.value.replace(/\D/g, '');
        }
        </script>

 <!--SCRIPT DE JAVA PARA LIMITAR EL USO DE SOLO PALABRAS Y NO NUMEROS -->
        
<script>
    function Validarletras(input){
        input.value = input.value.replace(/[^a-zA-Z\sàèìòùáéíóúñÀÈÌÒÙÁÉÍÓÚÑ]/g, '');
    }
</script>

    
         

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>l  
</body>
</html>