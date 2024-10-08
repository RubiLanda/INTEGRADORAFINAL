<?php
session_start();
$id = $_SESSION['ID'];
include '../php/conexion.php';
$conexion = new Database();
$conexion->conectarBD();
extract($_POST);
if ($_POST) {
    $listaAdmin = $conexion->selectConsulta("CALL Ver_Administrador_Estado($estatus)");
    echo "<div class=\"Tiendas\">";
    echo "<h1 class=\"TituloTienda\">INFORMACIÓN ADMINISTRADOR</h1>";
    foreach ($listaAdmin as $fila) {
        echo "<div class=\"Tienda\">";
        echo "<h3>$fila->Administrador</h3>";
        if ($id == 1) {
            if ($fila->Estatus) {
                echo "<input type='checkbox' class='check' onclick=\"cambiarEstatus(this,{$fila->ID})\" checked>";
            } else {
                echo "<input type='checkbox' class='check' onclick=\"cambiarEstatus(this,{$fila->ID})\">";
            }
        }
        echo "<button type=\"button\" class=\"boton\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalInformacion{$fila->ID}\">
    Ver Información
    </button>";
        echo "</div>";
        echo "<div class=\"modal fade\" id=\"ModalInformacion{$fila->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                    <div class=\"modal-dialog modal-dialog-centered\">
                        <div class=\"modal-content\">
                            <div class=\"modal-header\">
                                <h1>Información</h1>
                                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                            </div>
                            <div class=\"modal-body\">
                                <h3><b>Usuario:</b>{$fila->Usuario}<h3>
                                <h3><b>Nombre:</b>{$fila->Administrador}<h3>
                                <h3><b>Fecha Nacimiento:</b>{$fila->Fecha_nacimiento}</h3>
                                <h3><b>Género:</b>{$fila->Genero}</h3>
                                <h3><b>Teléfono:</b>{$fila->Telefono}</h3>
                                <h3><b>Fecha de Ingreso:</b>{$fila->Fecha_cargo}</h3>
                            </div>
                        </div>
                    </div>
                </div>";
    }
    echo "</div>";
}
