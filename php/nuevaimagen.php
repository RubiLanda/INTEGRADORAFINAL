<?php
include '../php/conexion.php';

$Conexion = new Database();
$Conexion->conectarBD();

$DireccionTemporal = $_FILES['imagen']['tmp_name'];
$NombreArchivo = $_FILES['imagen']['name'];
$Direccion = '/var/www/html/img/productos/';
$DireccionConImagen = $Direccion . $NombreArchivo;

// Obtener el ID de forma segura
$id = (int)$_POST['id'];

// Consultar la imagen existente usando consultas preparadas
$Imagenes = $Conexion->selectConsulta("SELECT imagen FROM PRODUCTOS WHERE id_producto = $id");
if (!$Imagenes || !isset($Imagenes[0]->imagen)) {
    die('No se encontró el producto o la imagen.');
}

$ImagenAntigua = $Imagenes[0]->imagen;

if ($ImagenAntigua && $ImagenAntigua !== $NombreArchivo) {
    $DireccionAntigua = $Direccion . $ImagenAntigua;
    if (file_exists($DireccionAntigua)) {
        if (!unlink($DireccionAntigua)) {
            die('No se pudo eliminar la imagen antigua.');
        }
    }
}

if (move_uploaded_file($DireccionTemporal, $DireccionConImagen)) {
    // Llamada segura a la consulta
    $resultado = $Conexion->ejecutar("CALL Modificar_Imagen_Producto($id, '$NombreArchivo', @mensaje)");
    
    // Obtener el mensaje
    $consulta = $Conexion->selectConsulta("SELECT @mensaje as resultado");
    if ($consulta && isset($consulta[0]->resultado)) {
        $mensaje = $consulta[0]->resultado;
        echo $mensaje;
    } else {
        echo 'No se pudo obtener el mensaje de resultado.';
    }
} else {
    echo 'Error al mover el archivo.';
}
?>
