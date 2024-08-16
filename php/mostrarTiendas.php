<?php

session_start();
require '../php/conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();

$records_per_page = 5;
$current_page = $_POST['current_page'];

$offset = ($current_page - 1) * $records_per_page;

$Tiendas = $Conexion->selectConsulta("call Ver_Tiendas($offset, $records_per_page)");  

    foreach ($Tiendas as $fila) {
        echo "<div class=\"Tienda\">";
            echo "<h3>{$fila->Tienda}</h3>";
            echo "<button type=\"button\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalInformacion{$fila->ID}\">
                    Ver Información
                </button>";
            echo "<a href=\"?estado=7&&PedidosTienda={$fila->ID}&&NombreTienda={$fila->Tienda}\">Ver pedidos</a>";
        echo "</div>";
        echo "<div class=\"modal fade\" id=\"ModalInformacion{$fila->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
                <div class=\"modal-dialog modal-dialog-centered\">
                    <div class=\"modal-content\">
                        <div class=\"modal-header\">
                            <h1>Información</h1>
                            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                        </div>
                        <div class=\"modal-body\">
                            <h3><b>Tienda:</b> {$fila->Tienda}<h3>
                            <h3><b>Dirección:</b> {$fila->Dirección}</h3>
                            <h3><b>Propietario:</b> {$fila->Propietario}</h3>
                            <h3><b>Teléfono:</b> {$fila->Telefono}</h3>
                        </div>
                    </div>
                </div>
            </div>";
        }

?>