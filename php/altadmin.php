<?php 
include '../php/conexion.php';
$conexion = new Database();
$conexion->conectarBD();
extract($_POST);

if (isset($nameuser, $contra, $nom, $pate, $mate, $naci, $gene, $tele)) {

    $usertrim = trim($nameuser);
    $nomtrim = trim($nom);
    $aptrim = trim($pate);
    $amtrim = trim($mate);
    $teltrim = trim($tele);
    if(trim($naci)==''){
        $naci='0000-00-00';
    }
    $contraencrypt = password_hash($contra, PASSWORD_DEFAULT);

    if($contra==$vericontra){
        $conexion->ejecutar("CALL INSERTAR_ADMINISTRADORES('$usertrim', '$contraencrypt', '$nomtrim', '$aptrim', '$amtrim', '$naci', '$gene', '$teltrim', @message)");
        $consulta = $conexion->selectConsulta("SELECT @message as noti");
        $noti = $consulta[0]->noti;
        echo $noti;
    }
    else{
        echo "Las contraseñas no coinciden";
    }

   
}
?>