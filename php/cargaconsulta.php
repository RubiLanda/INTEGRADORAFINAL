<?php 
 include '../php/conexion.php';
 $conexion = new Database();
 $conexion->conectarBD();
 $records_per_page = 5;
 $current_page = $_POST['pagina'];
 
$offset = ($current_page - 1) * $records_per_page;

 try {
     $productos = $conexion->selectConsulta("call Ver_Productos_Informacion('0', null, $offset, $records_per_page)");
     
   } catch (Exception $e) {
       echo $e->getMessage();
   } 

foreach ($productos AS $reg)
{      // div general
         // form clase padre
 echo "<form class='padre2'>";
          // div para la imagen
    echo "<div>";
    // imagen en espera
    echo "<label for='I{$reg->ID}'>";
    echo "<img class='' src='../img/productos/{$reg->Imagen}' alt='' >";
    echo " </label>";
    echo " <input class='' id='I{$reg->ID}' name='I{$reg->ID}' type='file' onchange=\"ModificarImagen(this,{$reg->ID} )\" >";
    echo "</div>";
    // div de nombre de pan y precio
    echo "<div class='div2'>";
    // nombre de los panes
                 echo "<label for='n'>Nombre del Producto</label>";
                 echo "<input class='btn' id='N{$reg->ID}' type='text' maxlength='40' value='{$reg->Nombre}'>";
             // precio de los panes
                 echo "<label for='p'>Precio del Producto</label>";
                 echo "<input class='btn' id='P{$reg->ID}' type='text' maxlength='3' oninput='validarprecio(this)' value='{$reg->Precio}'>";
           echo "</div>";
           echo "<div class='div2'>";
              // descripcion de los panes
              echo "<label for='d'>Descripcion del Producto</label>";
              echo "<textarea class='form-control w-30 btn' maxlength='100' id='D{$reg->ID}'>$reg->Descripcion </textarea>";
              echo "</div>";
                    
              // div para los botones aceptar y cancelar cambios
              echo "<div class='ajax1'>";
              echo "<button type='button'id='liveToastBtn{$reg->ID}' onclick=\"ModificarProducto({$reg->ID},'N{$reg->ID}','P{$reg->ID}','D{$reg->ID}' )\">";
              echo "<svg xmlns='http://www.w3.org/2000/svg' width='40' height='40' fill='green' class='bi bi-check-circle-fill' viewBox='0 0 16 16'>";
              echo "<path d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z'/>";
                  echo "</svg>";
                  echo "</button>";
            
                  // boton cancelar
                  echo " <button id='BotonNoGuardar' type='reset' name='BotonNoGuardar'>";      
                  echo "<svg xmlns='http://www.w3.org/2000/svg' width='40' height='40' fill='red' class='bi bi-x-circle-fill' viewBox='0 0 16 16'>";
                  echo " <path d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z'/>";         
                  echo " </svg>";
                  echo "</button>";         
                  echo "</div>"; 
                  // div del input para habilitar y deshabilitar panes 
                  echo "<div class='div2 checkbox-contenedor'>";
                  echo "<label for='liveToastBtn8' >Estado del Producto</label>";
                  if($reg->Estado){
                      echo "<input class='div4' type='checkbox'  name='liveToastBtn8' id='liveToastBtn8{$reg->ID}' onclick=\"CambioEstadoProducto(this, {$reg->ID})\" checked>";
                    }
                    else{
                        echo "<input type='checkbox' name='liveToastBtn8' id='liveToastBtn8{$reg->ID}'  onclick=\"CambioEstadoProducto(this, {$reg->ID})\">";
                    }
                    echo "</div>";  
 echo "</form>";
}

?>