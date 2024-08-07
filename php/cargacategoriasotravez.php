<?php
 include '../php/conexionBD.php';
 $conexion = new Database();
 $conexion->conectarBD();
 $consulta = "SELECT categorias.id_categoria , categorias.nombre as nombreC FROM categorias
 WHERE categorias.estado = 1";
 $reg = $conexion->selectConsulta($consulta);
 echo "<label class='control-label'>
 Categoria</label>";
 echo "<select id='selectcatpan' name='panescategoria' class='form-select'>";
 echo "<option value='0' >Categoria</option>";
 foreach($reg as $value)
 {
     echo "<option value='".$value->id_categoria."'>".$value->nombreC."</option>";
 }
 echo "</select>";
?>