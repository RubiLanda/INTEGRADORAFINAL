<!DOCTYPE html>
<html lang="en"> 
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPORTE DE VENTAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/ventas.css">
    
  </head>
  <body>
    <div class="fondo"></div>
    <div class="contenedor">
      <h1>REPORTE DE VENTAS</h1>
      <div class="inicio">
        <div class=cuadro>
          <input type="date" id="fecha" name="fecha">
          

        <div class="Selects">
          <select class="select" id='años' aria-label="Default select example" onchange="Ver_Ventas_Producto()">
            <option selected>REPORTE POR AÑO</option>
            <?php
            include '../php/conexion.php';
            $conexion=new Database();
            $conexion->conectarBD();
            $consulta="SELECT distinct year(PEDIDOS.f_entrega) as Año FROM PEDIDOS
            where PEDIDOS.estado_pedido='entregado' order by Año";
            $años=$conexion->selectConsulta($consulta);
            foreach ($años as $value) {
              echo "<option value='$value->Año'>$value->Año</option>";
            }
            ?>
          </select>
          <select class="select" id="meses" aria-label="Default select example" onchange="Ver_Ventas_Producto()">
            <option selected>REPORTE POR MES</option>
            <option value="1">Enero</option>
            <option value="2">Febrero</option>
            <option value="3">Marzo</option>
            <option value="4">Abril</option>
            <option value="5">Mayo</option>
            <option value="6">Junio</option>
            <option value="7">Julio</option>
            <option value="8">Agosto</option>
            <option value="9">Septiembre</option>
            <option value="10">Octubre</option>
            <option value="11">Noviembre</option>
            <option value="12">Diciembre</option>
          </select>
        </div>
        
        <div  class="juntitos">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="a" id="productos" value="1" checked>
            <label class="form-check-label" for="productos">
              POR PRODUCTOS
            </label>
          </div>
          <select class="select" id="categorias" aria-label="Default select example" onchange="Ver_Ventas_Producto()">
            <option selected> POR CATEGORIA</option>
            <?php
            $consulta="SELECT * FROM ver_categorias;";
            $cate=$conexion->selectConsulta($consulta);
            foreach ($cate as $value) {
              echo "<option value='$value->ID'>$value->Nombre</option>";
            }
            ?>
        </select>
      </div>   
      
        <div class="form-check">
          <input class="form-check-input" type="radio" name="a"  value="2" >
          <label class="form-check-label" for="tienda">
            POR TIENDAS
          </div>
        </label>
      
      <div class="juntitos">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="a" value="3">
          <label class="form-check-label" for="repartidores">
            POR REPARTIDORES
          </label>
        </div>
      </select>
      <select class="select" id="repartidores" aria-label="Default select example" onchange="Ver_Ventas_Producto()"> 
        <option selected>POR REPARTIDORES</option>
        <?php
            $consulta="SELECT  REPARTIDORES.id_repartidor as id, PERSONAS.nombre as repartidor FROM PERSONAS inner join REPARTIDORES on REPARTIDORES.id_persona=PERSONAS.id_persona where REPARTIDORES.estatus=1;";
            $repa=$conexion->selectConsulta($consulta);
            foreach ($repa as $value) {
              echo "<option value='$value->id'>$value->repartidor</option>";
            }
            ?>
        </select>
      </div>
        </div>
       
      
      <div class="tabla" id="Consulta">
        
        
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
          var radiusActivo = 1;
          function Ver_Ventas_Producto(){
            const inputMeses = document.getElementById('meses');
            var meses = inputMeses.value;
            const selectaños=document.getElementById('años');
            var años= selectaños.value;
            const selectcate=document.getElementById('categorias');
            var categorias=selectcate.value;
            const selectrepa=document.getElementById('repartidores');
            var repas=selectrepa.value;
            const inputdate=document.getElementById('fecha');
            var fechas=inputdate.value;
            $.ajax({
              type: 'POST',                                               //con el metodo ajax hacemos que se envien los datos de esta 
              url: '../php/scriptventas.php',                                       // pagina a otra, con el url, declaramos la constante de 
              data: {radiusActivo: radiusActivo, 
                meses: meses, años:años, 
                categorias:categorias,repas:repas,
              fechas:fechas},                                    //input meses q nos va a tomar los inputs con esa id el cual la Id
                success: function(response) {                              // es 'meses', en donde tenemos 'Consulta' es el Id de donde se va 
                  $('#Consulta').html(response);                             // a imprimir el resultado, en data ponemos despues de los puntos
                }  
              })
              //la variable de 
            }
            // Selecciona todos los botones de radio y el select
            const radios = document.querySelectorAll('input[name="a"]');
            const select = document.getElementById('categorias');
            const tienda = document.getElementById('tiendas');
            const repa = document.getElementById('repartidores');
            const fecha=document.getElementById('fecha');
            // Función para habilitar o deshabilitar el select
            function toggleSelect() {
              // Verifica si el radio con valor "1" está seleccionado
              if (document.querySelector('input[name="a"][value="1"]').checked) {
                select.disabled = false; // Habilitar select
                select.style.opacity=1;
                radiusActivo = 1;
              } else {
                select.disabled = true; // Deshabilitar select
                select.style.opacity=0.5;
                select.value='POR CATEGORIA';
              }
              if (document.querySelector('input[name="a"][value="2"]').checked) {
                radiusActivo = 2;
              }
              if (document.querySelector('input[name="a"][value="3"]').checked) {
                repa.disabled = false; // Habilitar select
                repa.style.opacity=1;
                radiusActivo = 3;
              } else {
                repa.disabled = true; // Deshabilitar select
                repa.style.opacity=0.5;
                repa.selectedIndex = 0;
              }
            
              Ver_Ventas_Producto();
            }
           
            // Asocia la función a todos los radios
            radios.forEach(radio => radio.addEventListener('change', toggleSelect));
            // Inicializa el estado del select
            toggleSelect();
            
            const inputTextos = document.querySelectorAll(".nombre");
            inputTextos.forEach(function(inputTexto) {
              inputTexto.addEventListener("input", function() {
                this.value = this.value.replace(/[^a-zA-Z\sàèìòùáéíóúñÀÈÌÒÙÁÉÍÓÚÑ]/g, '');
              });
            });
            
            
            </script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
