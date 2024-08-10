<?php 
include '../php/conexion.php';
$conexion=new Database();
$conexion->conectarBD();
extract($_POST);
$contraencri= password_hash($contra, PASSWORD_DEFAULT);

$nameusertrim=trim($nameuser);
$nomtrim=trim($nom);
$patetrim=trim($pate);
$matetrim=trim($mate);
$teletrim=trim($tele);
if(trim($naci)==''){
    $naci='0000-00-00';
}

if($contra==$vericontra){
    $conexion->ejecutar("CALL INSERTAR_CLIENTES('$nameusertrim', '$contraencri', '$nomtrim', '$patetrim', '$matetrim', '$naci', '$gene', '$teletrim', @mensaje)");
    $consulta=$conexion->selectConsulta("SELECT @mensaje as resultado");
    $mensaje = $consulta[0]->resultado;
    if ($mensaje == "REGISTRO EXITOSO") {
        echo true;    
    }
    else {
        echo $mensaje;
    }
}else{
    echo "Las contraseñas no coinciden";
}

?>
