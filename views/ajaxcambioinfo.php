<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/editarinfo.css">
    <title>CAMBIAR INFO</title>
</head>
<body>
<div class ="fondo"></div>
<div id="infoconsulta"></div>
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11" id="imprimirnoti"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

</script>
<script>
    function recargarinfo()
    {
        $.ajax({
            type:'POST',
            url:'../php/editarinfo.php',
            success: function(response) {
                $('#infoconsulta').html(response);
            }
        });
    }
    recargarinfo()
   
    function CambiarInfo(ID) {
        var nombre = document.getElementById('nombre');
        var ApellidoP = document.getElementById('paterno');
        var ApellidoM = document.getElementById('materno');
        var Telefono = document.getElementById('telefono');
                $.ajax({
                type:'POST',
                url:'../php/calleditarinfo.php',
                data: { ID: ID, nombre: nombre.value, ApellidoP:ApellidoP.value, ApellidoM:ApellidoM.value, Telefono:Telefono.value },
                success: function(response) {
                recargarinfo()
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

function validartelefono(input){
    input.value = input.value.replace(/\D/g, '');
}
function validarinputs(input){
    input.value = input.value.replace(/[^a-zA-Z\sàèìòùáéíóúñÀÈÌÒÙÁÉÍÓÚÑ]/g, '');
}


</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>