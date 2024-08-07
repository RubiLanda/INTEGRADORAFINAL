<?php 
include 'conexion.php';
$db=new Database();
$db->conectarBD();
extract($_POST);
$contraencri= password_hash($contraseña, PASSWORD_DEFAULT);
        $cadena="call INSERTAR_CLIENTES('$usuario', '$contraencri', '$nombre', '$paterno', '$materno', '$nacimiento', '$genero', '$telefono')";
        $db->ejecutar($cadena);
        header("Location: ../index.php");
?>