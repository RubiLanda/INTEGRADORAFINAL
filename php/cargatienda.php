<?php
 include 'conexion.php';
 $conexion=new Database();
 $conexion->conectarBD();
 session_start();
 $persona = $_SESSION['ID'];
 $tiendass=$conexion->selectConsulta("call Ver_Tiendas_Cliente($persona, 0)");
 echo "<div>
 
 <div class=\"card contenedor\">
 <div class=\"titulo\">
<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"64\" height=\"64\" fill=\"currentColor\" class=\"icono\" viewBox=\"0 0 16 16\">
  <path d=\"M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h12V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5m2 .5a.5.5 0 0 1 .5.5V13h8V9.5a.5.5 0 0 1 1 0V13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5a.5.5 0 0 1 .5-.5\"/>
</svg>
 <h1 class=\"card-title\">MIS TIENDAS</h1>
 </div>
 
 <div class=\"tiende\">";
 echo "<p class=\"card-text\">";
 echo "<div class=\"div\">
 <a class=\"botonesA\" href=\"daraltatienda.php
 \" >Quiero dar de alta una nueva tienda!</a>
 </div>";
 foreach($tiendass as $tienda){
     
     echo "<h3><b>Nombre de la tienda:</b> <input type=\"text\" name=\"nombre\" class=\"inputs\" id='nombretienda{$tienda->ID}' value='{$tienda->Nombre}' minlength=\"3\" maxlength=\"50\"><h3>
     <h3><b>Dirección: </b> <textarea name=\"direccion\" class=\"inputs direccion\" id='direccion{$tienda->ID}'  minlength=\"20\" maxlength=\"100\">{$tienda->Direccion}</textarea><h3>";
     if($tienda->Estatus){
         echo"<h3><b>Estado Actual:</b><input type='checkbox' id=\"checkbox{$tienda->ID}\" class=\"check\" onclick=\"HABILITAR(this,{$tienda->ID}, 'ModalConfirmarCheckBox{$tienda->ID}', 'botonConfirmar{$tienda->ID}', 'texto{$tienda->ID}')\" checked></h3>";
        }else{
            echo"<h3><b>Estado Actual:</b><input type='checkbox' id=\"checkbox{$tienda->ID}\" class=\"check\" onclick=\"HABILITAR(this,{$tienda->ID}, 'ModalConfirmarCheckBox{$tienda->ID}', 'botonConfirmar{$tienda->ID}', 'texto{$tienda->ID}')\"</h3>";
        }
        echo"<button type='button' class=\"inputs\" onclick=\"editarinfotienda({$tienda->ID},'nombretienda{$tienda->ID}','direccion{$tienda->ID}')\" class=\"div\">EDITAR</button>";
        echo"<hr>
        <div class=\"modal fade\" id=\"ModalConfirmarCheckBox{$tienda->ID}\" tabindex=\"-1\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\" data-bs-backdrop=\"static\" data-bs-keyboard=\"false\">
            <div class=\"modal-dialog ModalDetalles modal-dialog-centered modal-dialog-scrollable\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <h1>Desactivar {$tienda->Nombre}</h1>
                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" onclick=\"cancelarCheckbox('ModalConfirmarCheckBox{$tienda->ID}', 'checkbox{$tienda->ID}')\"></button>
                    </div>
                    <div class=\"modal-body\">
                        <p id=\"texto{$tienda->ID}\"></p>
                        <div style=\"display: flex;\">
                            <button type=\"button\" style=\"margin-right: 5px;\" onclick=\"cancelarCheckbox('ModalConfirmarCheckBox{$tienda->ID}', 'checkbox{$tienda->ID}')\">Cancelar</button>
                            <button type=\"button\" style=\"margin-left: 5px;\" id=\"botonConfirmar{$tienda->ID}\" onclick=\"ContinuarCheckbox('ModalConfirmarCheckBox{$tienda->ID}', 'checkbox{$tienda->ID}', {$tienda->ID})\">Continuar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>";
    }
    echo "</p>";
    
    
    
    "</div>
    </div>
    </div>
    
    
    ";
    
    
    
    ?>
    