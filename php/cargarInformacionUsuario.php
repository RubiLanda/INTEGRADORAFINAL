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
    echo "<div>
    <div class=\"card contenedor2\">
        <div class=\"titulo2\">
            <div class=\"botonV\">
                <a href=\"../views/administrador.php\" class=\"button\">Volver</a>
            </div>
        </div>
        <div class=\"titulo\">
            <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"64\" height=\"64\" fill=\"currentColor\" class=\"icono\" viewBox=\"0 0 16 16\">
            <path d=\"M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325\"/>
            </svg>
            <h1 class=\"card-title\">EDITAR</h1>
        </div>
    <div class=\"tiende\">";
echo "<p class=\"card-text\">";
echo "<div class=\"div\">";

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