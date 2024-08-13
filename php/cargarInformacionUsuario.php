<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php
     include '../php/conexion.php';
     $Conexion = new Database();
     $Conexion->conectarBD();
    
     session_start();
     $persona = $_SESSION['ID']; // esta id es de usuario no de persona
     $consulta = "SELECT  PERSONAS.nombre as Nombre_A, PERSONAS.a_p as AP_A, PERSONAS.a_m as AM_A, PERSONAS.telefono as T_A, PERSONAS.f_nac as Fecha,
                  PERSONAS.genero as Genero
                  FROM PERSONAS INNER JOIN USUARIOS ON PERSONAS.id_usuario = USUARIOS.id_usuario
                  WHERE USUARIOS.id_usuario = $persona";
     $reg = $Conexion->selectConsulta($consulta);
    
    
    //  echo "<div class='fondoAdmin'>";
    //     echo "<div class='fondoAdmin2'>";
    //     echo "<div class='d-grid gap-2 d-md-flex justify-content-md-end '>";
    //     echo "<button class='botones' type='button'>";
    //     echo "<a href='administrador.php' class='button'>Volver</a>";
    //     echo "</button>";
    //     echo "</div>";
    //     echo "<h2>Mi Cuenta</h2>";
    //     echo "</div>";
    //     echo "<br>";
    //     echo "<div>";
    //     echo "<form>";
        
    //     foreach($reg as $r){
    //         echo "<div>";
    //         echo "<label>Nombre completo:</label>";
    //         echo "<input class='ad form-control' type='text' id='NombreA' name='nombreA' class='form-control' oninput='Validarletras(this)' maxlength='40' value='{$r->Nombre_A}'>";
    //        // echo "<input type='text' id='N{$r->Nombre_A}' name='nombreA' class='form-control' value='{$r->Nombre_A}' >";
    //        echo "<br>";
    //         echo "</div>";
    //         // DIV DE DONDE VA A PODER EDITAR EL APELLIDO PATERNO
    //         echo "<div>";
    //         echo "<label>Apellido Paterno:</label>";
    //         echo "<input type='text' id='ApellidoP' name='ApellidoP' class='form-control' oninput='Validarletras(this)' maxlength='40' value='{$r->AP_A}'>";
    //         echo "<br>";
    //        // echo "<input type='text' id='AP{$r->AP_A}' name='Apellido1' class='form-control' value='{$r->AP_A}' >";
    //         echo "</div>";
    //          // DIV DE DONDE VA A PODER EDITAR EL APELLIDO MATERNO
    //         echo "<div>";
    //         echo "<label>Apellido Materno:</label>";
    //         echo "<input  type='text' id='ApellidoM' name='ApellidoM' class='form-control' oninput='Validarletras(this)' maxlength='40' value='{$r->AM_A}'>";
    //         echo "<br>";
    //        // echo "<input type='text' id='AM{$r->AM_A}' name='Apellido2' class='form-control' value='{$r->AM_A}' >";
    //         echo "</div>";
    //         // DIV DE DONDE VA A PODER EDITAR EL NUMERO DE TELEFONO
    //         echo "<div>";
    //         echo "<label>Telefono:</label>";
    //         echo "<input  type='text' id='TelefonoA' name='TelefonoA' class='form-control' oninput='validarprecio(this)' maxlength='10' value='{$r->T_A}'>";
    //         echo "<br>";
    //         echo "</div>";
    //     }
    //      // DIV DE LOS BOTONES PARA GUARDAR Y CANCELAR LOS CAMBIOS
    //         echo "<br>";
    //         echo "<div class='botoncat'>";
    //         echo "<button type='button'  onclick=\"CambiarInfo($persona)\" >";
    //         echo "<svg xmlns='http://www.w3.org/2000/svg' width='40' height='40' fill='green' class='bi bi-check-circle-fill' viewBox='0 0 16 16'>";
    //         echo "<path d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z'/>";
    //         echo "</svg>";
    //         echo "</button>";
    //         echo " <label for='BotonNoGuardar'>";      
    //         echo "<svg xmlns='http://www.w3.org/2000/svg' width='40' height='40' fill='red' class='bi bi-x-circle-fill' viewBox='0 0 16 16'>";
    //         echo " <path d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z'/>";         
    //         echo " </svg>";
    //         echo "</label>";        
    //         echo "<input id='BotonNoGuardar' type='reset' name='BotonNoGuardar'>";   
    //         echo "</div>"; 
       
    //     echo "</form>";
    //     echo "</div>";
    //     echo "</div>";
    echo"<div>
   <div class='card contenedor2'>
   <div class='titulo2'>
   <svg xmlns='http://www.w3.org/2000/svg' width='64' height='64' fill='currentColor' class='icono' viewBox='0 0 16 16'>
<path d=\"M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325\"/>
</svg>
   <h1 class='card-title'>EDITAR</h1>
   </div>
   <div class='tiende'>";
echo "<p class='card-text'>";
echo "<div class='div'>";


foreach($reg as $r){
   
   echo"
    <h3><b>Nombre:</b><input type='text' name='nombre' class='inputs' id='nombre' value='{$r->Nombre_A}'oninput=Validarletras(this) minlength='3' maxlength='40'></h3>
    <h3><b>Apellido Paterno:</b><input type='text' name='paterno' class='inputs' id='paterno' value='{$r->AP_A}' oninput=Validarletras(this) minlength='3' maxlength='40'></h3>
    <h3><b>Apellido Materno:</b><input type='text' name='materno' class='inputs' id='materno' value='{$r->AM_A}' oninput=Validarletras(this) minlength='3' maxlength='40'></h3>
    <h3><b>Telefono:</b> <input type='tel' name='telefono' id='telefono' class='inputs' value='{$r->T_A}' oninput=validarprecio(this) minlength='10' maxlength='10'> </h3>
    <div class='botoncat'>
    <button type='button' onclick=\"CambiarInfo($persona)\" >
    <svg xmlns='http://www.w3.org/2000/svg' width='40' height='40' fill='green' class='bi bi-check-circle-fill' viewBox='0 0 16 16'>
    <path d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z'/>
    </svg>
    </button>
    <label for='BotonNoGuardar'>  
    <svg xmlns='http://www.w3.org/2000/svg' width='40' height='40' fill='red' class='bi bi-x-circle-fill' viewBox='0 0 16 16'>
    <path d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z'/>     
    </svg>
            </label>      
            <input id='BotonNoGuardar' type='reset' name='BotonNoGuardar'> 
          </div>
          
          ";
        }
        echo "</p>";
    
    
    
        "</div>
        </div>
        </div>";
     ?>