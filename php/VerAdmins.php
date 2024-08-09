<?php
include '../php/conexion.php';
$conexion = new Database();
$conexion->conectarBD();
extract($_POST);
if($_POST){
$listaAdmin = $conexion->selectConsulta("CALL Ver_Administrador_Estado($estatus)");


echo" <div class='cont'>
    <div class='container'>
        <div class='table-responsive'>
            <table>
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Género</th>
                        <th>Teléfono</th>
                        <th>Fecha de Cargo</th>
                        <th>Cambiar Estatus</th>
                    </tr>
                </thead>
                <tbody>";
                foreach ($listaAdmin as $fila){
                echo"    <tr>
                        <td>{$fila->Usuario}</td>
                        <td>{$fila->Administrador}</td>
                        <td>{$fila->Fecha_nacimiento}</td>
                        <td>{$fila->Genero}</td>
                        <td>{$fila->Telefono}</td>
                        <td>{$fila->Fecha_cargo}</td>";
                        if($fila->Estatus){
                        echo "<td>
                        <input type='checkbox' onclick=\"cambiarEstatus(this,{$fila->ID})\" checked>
                        </td>";
                        }else
                        {
                        echo"<td>
                        <input type='checkbox' onclick=\"cambiarEstatus(this,{$fila->ID})\">
                        </td>
                    </tr>";}
                    }
                echo"</tbody>
            </table>
        </div>
    </div>
</div>";
}
?>