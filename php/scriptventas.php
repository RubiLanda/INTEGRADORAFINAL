<?php
include '../php/conexion.php';
$conexion=new Database();
$conexion->conectarBD();
extract($_POST);
// esto es de productos
if ($meses=="REPORTE POR MES"){
    
    $mes = "NULL";
}
else{
    $mes = $meses;
} 
if($años=="REPORTE POR AÑO"){
    $año= "NULL";
}
else{
    $año= $años;
}
if($categorias == "POR CATEGORIA"){
    $categoria= "NULL";
}
else{
    $categoria= $categorias;
}
if($repas=="POR REPARTIDORES"){
 $repa="NULL";
}
else{
$repa=$repas;
}
if(empty($fechas)){
    $fecha="NULL";
}
else{
    $fecha=$fechas;
}
switch ($radiusActivo) {
    case 1:
        if ($fecha == "NULL"){
            $consulta = $conexion->selectConsulta("call Ventas_Productos($mes,$año,$fecha,$categoria)"); //cuando es nulo con comillas y cuando no es nulo sin comillas
        }else{
            $consulta = $conexion->selectConsulta("call Ventas_Productos($mes,$año,'$fecha',$categoria)");

        }
        foreach($consulta as $fila){
            echo"<h3>{$fila->Producto}</h3>";
            echo"<h3>{$fila->Total}</h3>";
        }
        break;
    case 2:
        if($fecha=="NULL"){
            $consulta = $conexion->selectConsulta("call Ver_ventas_Tiendas($mes,$año, $fecha)");
        }else{
            $consulta = $conexion->selectConsulta("call Ver_ventas_Tiendas($mes,$año,'$fecha')"); 
        }
        foreach($consulta as $fila){
            echo"<h3>{$fila->Nombre_tienda}</h3>";
            echo"<h3>{$fila->Total}</h3>";
        }

        break;
    case 3:
        if($fecha=="NULL"){
            $consulta = $conexion->selectConsulta("call Ver_Ventas_Repartidores($mes,$año,$fecha,$repa)");
        }else{
            $consulta = $conexion->selectConsulta("call Ver_Ventas_Repartidores($mes,$año,'$fecha',$repa)");
        }
        foreach($consulta as $fila){
            echo"<h3>{$fila->Nombre_repartidor}</h3>";
            echo"<h3>{$fila->Total}</h3>";
        }
        break;
}
?>