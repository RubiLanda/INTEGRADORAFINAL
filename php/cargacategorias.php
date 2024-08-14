<?php 
include '../php/conexion.php';
$Conexion = new Database();
$Conexion->conectarBD();
$consulta = "SELECT CATEGORIAS.id_categoria,CATEGORIAS.nombre as nombrecat, CATEGORIAS.estado as estadocat from CATEGORIAS";
$tabla =$Conexion->selectConsulta($consulta);
foreach ($tabla as $reg)
{
  // DIV GENERAL DE LA CLASE PADRE2
  echo"<div class='padre2'>";

  // DIV DEL NOMBRE DE LA CATEGORIA
  echo "<div class='div2'>";
  echo "<label>Nombre de la categoría</label>";
  echo "<input class='btn' id='C{$reg->nombrecat}'  type='text' maxlength='35' value=\"{$reg->nombrecat}\">";
  echo "</div>";

     //DIV DE LOS BOTONES PARA CAMBIARLE EL NOMBRE A LA CATEGORIA
             echo "<div class='ajax1'>";
                    echo "<button type='button' id='liveToastBtn3{$reg->id_categoria}' onclick=\"NuevoNombreCategoria('{$reg->id_categoria}','C{$reg->nombrecat}' )\">";
                    echo "<svg xmlns='http://www.w3.org/2000/svg' width='40' height='40' fill='green' class='bi bi-check-circle-fill' viewBox='0 0 16 16'>";
                    echo "<path d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z'/>";
                    echo "</svg>";
                    echo "</button>";

    // BOTON PARA CANCELAR EL POSIBLE CAMBIO DE LA CATEGORIA
                   echo "<button id='CancelarCambio' type='reset' name='CancelarCambio'>";    
                   echo "<svg xmlns='http://www.w3.org/2000/svg' width='40' height='40' fill='red' class='bi bi-x-circle-fill' viewBox='0 0 16 16'>";
                   echo " <path d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z'/>";         
                   echo " </svg>";
                   echo "</button>"; 
             echo "</div>";    

                      // DIV DEL INPUT DE TIPO CHECKBOX
                        echo "<div class='div2 checkbox-contenedor'>";
                        echo "<label>Estado de la categoría</label>";
                        if($reg->estadocat){
                        echo "<input class='' type='checkbox'  onclick=\"cambiarEstado(this, {$reg->id_categoria})\" checked>";
                        }
                      else{
                        echo "<input type='checkbox' onclick=\"cambiarEstado(this, {$reg->id_categoria})\">"; 
                        }
                        echo "</div>";
echo "</div>";
}

?>