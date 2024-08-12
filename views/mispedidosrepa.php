<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/pedirepa.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <title>Mis pedidos!</title>
    </head>
    <body>
        <div class="fondo"></div>
       <div id="pedidos"></div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
     function MostrarPedidos(){
        $.ajax({
            type: 'POST',
            url: '../php/verpedidosrepa.php',
        
            success: function(response) {
                $('#pedidos').html(response);
            }
        });
    }
MostrarPedidos();

function cambiarestado(id,estado){
    $.ajax({
                type: 'POST',
                url: '../php/callcambiarestado.php',
                data: { id: id, estado: estado },
                success: function() {
                    MostrarPedidos()
                }
            });
}
  </script>
  
</body>
</html>

