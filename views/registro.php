<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrate Ahora!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/Diseñologin.css">
</head>
<body>
    <div class="formulario">
        <h1>Registrate Ahora!</h1>
        <form action="../scripts/registro.php" method="post" onsubmit=" return validarFormulario()">
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
                        <input type="text" name="nombre" class="nombre" minlength="3" maxlength="50" required autocomplete="off">
                        <label>Nombre Completo:</label>
                    </div>
                    <div class="username">
                        <input type="text" name="paterno" class="nombre" minlength="3" maxlength="50" required autocomplete="off">
                        <label>Apellido Paterno:</label>
                    </div>
                    <div class="username">
                        <input type="text" name="materno" minlength="3" class="nombre" maxlength="50"  autocomplete="off">
                        <label>Apellido Materno:</label>
                    </div>
                </div>
                <div class="cajas">
                    <div class="username">
                        <input type="date" name="nacimiento" id="nacimiento" required >
                    </div>
                    <div class="username">
                    <select class="form-select" aria-label="Default select example" name="genero" required>
                            <option disabled selected>Género</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                            <option value="O">Otro</option>
                        </select>
                    </div>
                    <div class="username">
                        <input type="tel" name="telefono" maxlength="10" required autocomplete="off" oninput="validartelefono(this)" >
                        <label>Teléfono:</label>
                    
                    </div>
                </div>
            </div>
            <input type="submit" value="Registrar">
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
    

    
    function validarFormulario() {
    const contraseña = document.getElementById('contraseña').value;
    const vercontra = document.getElementById('vercontra').value;
    const errorVerificarContra = document.getElementById('errorVerificarContra');

    if (contraseña !== vercontra) {
        errorVerificarContra.style.display = 'block';
        return false; 
    } else {
        errorVerificarContra.style.display = 'none';
        return true; 
    }
};
function validartelefono(input){
    input.value = input.value.replace(/\D/g, '');

};
      
</script>
</body>
</html>