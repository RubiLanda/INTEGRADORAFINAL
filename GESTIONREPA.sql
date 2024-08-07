-- * 4.1.1 ver mis pedidos (repartidor)
DELIMITER //
create procedure pedidos_repa(
	in iduser INT
)
begin
declare idrepartidor int;
select REPARTIDORES.id_repartidor into idrepartidor 
from REPARTIDORES
inner join PERSONAS on REPARTIDORES.id_persona=PERSONAS.id_persona
inner join USUARIOS on PERSONAS.id_usuario=USUARIOS.id_usuario
where USUARIOS.id_usuario=iduser;

select PEDIDOS.id_pedido as ID, concat( PERSONAS.nombre,' ',PERSONAS.a_p,' ',PERSONAS.a_m) as nombre, PERSONAS.telefono as telefono, TIENDAS.nombre_tienda as tienda, TIENDAS.direccion as direccion, PEDIDOS.f_requerido as fechar, PEDIDOS.estado_pedido as estado
from PEDIDOS
inner join TIENDAS on PEDIDOS.id_tiendas = TIENDAS.id_tienda
inner join CLIENTES on PEDIDOS.id_cliente = CLIENTES.id_cliente
inner join PERSONAS on CLIENTES.id_persona = PERSONAS.id_persona
where PEDIDOS.estado_pedido = 'aceptado' and PEDIDOS.id_repartidor = idrepartidor;

END //
 DELIMITER ;