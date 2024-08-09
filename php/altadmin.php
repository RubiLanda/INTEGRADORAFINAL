<?php 
include '../php/conexion.php';
$conexion = new Database();
$conexion->conectarBD();
extract($_POST);

if (isset($nameuser, $contra, $nom, $pate, $mate, $naci, $gene, $tele)) {
    $contraencrypt = password_hash($contra, PASSWORD_DEFAULT);
    $usertrim = trim($nameuser);
    $nomtrim = trim($nom);
    $aptrim = trim($pate);
    $amtrim = trim($mate);
    $teltrim = trim($tele);

    $conexion->ejecutar("CALL INSERTAR_ADMINISTRADORES('$usertrim', '$contraencrypt', '$nomtrim', '$aptrim', '$amtrim', '$naci', '$gene', '$teltrim', @message)");
    $consulta = $conexion->selectConsulta("SELECT @message as noti");
    $noti = $consulta[0]->noti;
    echo $noti;
}
?>
