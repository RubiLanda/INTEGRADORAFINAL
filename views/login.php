
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
        <h1>Inicio de Sesión</h1>
        <form action="../php/verificarlogin.php" method="post">
            <div class="username">
                <input type="text" name="usuario" required autocomplete="off">
                <label>Ingresa tu usuario:</label>
            </div>
            <div class="username">
                <input type="password" name="contraseña" required >
                <label>Contraseña:</label>
            </div>
            <input type="submit" value="Iniciar">
            <div class="registrarse">
            ¿No tienes cuenta? <a href="registro.php"> Registrate aquí</a><br>
            </div>
        </form>
    
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>