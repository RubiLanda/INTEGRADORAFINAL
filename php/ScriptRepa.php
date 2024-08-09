<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php
include '../php/conexion.php';
$conexion = new Database();
$conexion->conectarBD();

session_start();

$persona = $_SESSION['ID']; // esta id es de usuario no de persona
$consulta = "SELECT  PERSONAS.nombre as Nombre_A, PERSONAS.a_p as AP_A, PERSONAS.a_m as AM_A, PERSONAS.telefono as T_A, PERSONAS.f_nac as Fecha, REPARTIDORES.fol_liconducir as FL_R,
                PERSONAS.genero as Genero
                FROM PERSONAS INNER JOIN USUARIOS ON PERSONAS.id_usuario = USUARIOS.id_usuario
                INNER JOIN REPARTIDORES ON PERSONAS.id_persona = REPARTIDORES.id_persona
                WHERE USUARIOS.id_usuario = $persona";
$reg = $conexion->selectConsulta($consulta);


echo "<div class='fondoAdmin'>";
echo "<div class='fondoAdmin2'>";
echo "<div class='d-grid gap-2 d-md-flex justify-content-md-end '>";
echo "<button class='botones' type='button'>";
echo "<a href='../views/RepartidorMicuenta.php' class='button'>Volver</a>";
echo "</button>";
echo "</div>";
echo "<h2>Mi Cuenta</h2>";
echo "</div>";
echo "<br>";
echo "<div>";
echo "<form>";

foreach ($reg as $r) {
    echo "<div>";
    echo "<label>Nombre completo:</label>";
    echo "<input class='ad form-control' type='text' id='NombreA' name='nombreA' class='form-control' oninput='Validarletras(this)' maxlength='40' value='{$r->Nombre_A}'>";
    // echo "<input type='text' id='N{$r->Nombre_A}' name='nombreA' class='form-control' value='{$r->Nombre_A}' >";
    echo "<br>";
    echo "</div>";
    // DIV DE DONDE VA A PODER EDITAR EL APELLIDO PATERNO
    echo "<div>";
    echo "<label>Apellido Paterno:</label>";
    echo "<input type='text' id='ApellidoP' name='ApellidoP' class='form-control' oninput='Validarletras(this)' maxlength='40' value='{$r->AP_A}'>";
    echo "<br>";
    // echo "<input type='text' id='AP{$r->AP_A}' name='Apellido1' class='form-control' value='{$r->AP_A}' >";
    echo "</div>";
    // DIV DE DONDE VA A PODER EDITAR EL APELLIDO MATERNO
    echo "<div>";
    echo "<label>Apellido Materno:</label>";
    echo "<input  type='text' id='ApellidoM' name='ApellidoM' class='form-control' oninput='Validarletras(this)' maxlength='40' value='{$r->AM_A}'>";
    echo "<br>";
    // echo "<input type='text' id='AM{$r->AM_A}' name='Apellido2' class='form-control' value='{$r->AM_A}' >";
    echo "</div>";
    // DIV DE DONDE VA A PODER EDITAR EL NUMERO DE TELEFONO
    echo "<div>";
    echo "<label>Telefono:</label>";
    echo "<input  type='text' id='TelefonoA' name='TelefonoA' class='form-control' oninput='validarprecio(this)' minlength='10' maxlength='10' value='{$r->T_A}'>";
    echo "<br>";
    echo "</div>";
    // DIV DONDE VA A MODIFICAR EL FOLIO DE CONDUCIR
    echo "<div>";
    echo "<label>Folio de conducir:</label>";
    echo "<input  type='text' id='FolCondu' name='FolCondu' class='form-control' oninput='validarprecio(this)' minlength='11' maxlength='11' value='{$r->FL_R}'>";
    echo "<br>";
    echo "</div>";
}
// DIV DE LOS BOTONES PARA GUARDAR Y CANCELAR LOS CAMBIOS
echo "<br>";
echo "<div class='botoncat'>";
echo "<button type='button'  onclick=\"CambiarInfo($persona)\" )\">";
echo "<svg xmlns='http://www.w3.org/2000/svg' width='40' height='40' fill='green' class='bi bi-check-circle-fill' viewBox='0 0 16 16'>";
echo "<path d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z'/>";
echo "</svg>";
echo "</button>";
echo " <label for='BotonNoGuardar'>";
echo "<svg xmlns='http://www.w3.org/2000/svg' width='40' height='40' fill='red' class='bi bi-x-circle-fill' viewBox='0 0 16 16'>";
echo " <path d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z'/>";
echo " </svg>";
echo "</label>";
echo "<input id='BotonNoGuardar' type='reset' name='BotonNoGuardar'>";
echo "</div>";

echo "</form>";
echo "</div>";
echo "</div>";
?>