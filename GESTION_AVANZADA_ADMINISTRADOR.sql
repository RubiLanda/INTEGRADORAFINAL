-- 3.1.3 Ver repartidores por Estatus
DELIMITER //
create procedure Ver_Repartidores(
	in p_estatus boolean
)
begin
	if p_estatus is not null then
    	select REPARTIDORES.id_repartidor as ID, concat(PERSONAS.nombre, ' ', PERSONAS.a_p, ' ', PERSONAS.a_m) as Nombre, personas.f_nac as Fecha_nacimiento, PERSONAS.genero as Genero, personas.telefono as Telefono, 
		repartidores.f_ingreso as Fecha_Ingreso, REPARTIDORES.fol_liconducir as licencia_conducir, REPARTIDORES.estatus as Estatus, REPARTIDORES.f_ingreso as Fecha_ingreso
		from REPARTIDORES
		inner join PERSONAS on REPARTIDORES.id_persona = PERSONAS.id_persona
		where REPARTIDORES.estatus = p_estatus;
    else
    	select REPARTIDORES.id_repartidor as ID, concat(PERSONAS.nombre, ' ', PERSONAS.a_p, ' ', PERSONAS.a_m) as Nombre, PERSONAS.f_nac as Fecha_nacimiento, PERSONAS.genero as Genero, PERSONAS.telefono as Telefono, 
		REPARTIDORES.f_ingreso as Fecha_Ingreso, REPARTIDORES.fol_liconducir as licencia_conducir, REPARTIDORES.estatus as Estatus, REPARTIDORES.f_ingreso as Fecha_ingreso
		from REPARTIDORES
		inner join PERSONAS on REPARTIDORES.id_persona = PERSONAS.id_persona;
    end if;
end //
DELIMITER ;

-- * 3.7.1 Ver pedidos de clientes con tienda por estado 
DELIMITER //
create procedure Ver_Pedidos_Clientes_ConTienda_Estado(
	in p_estado varchar(30)
)
begin

if p_estado is not null then
	select PEDIDOS.id_pedido as ID, TIENDAS.nombre_tienda as Tienda, TIENDAS.direccion as Direccion, concat(PERSONAS.nombre, ' ', PERSONAS.a_p, ' ', PERSONAS.a_m) as Cliente, PEDIDOS.f_pedido as Fecha_Pedido, 
    case when PEDIDOS.f_requerido = date(now()) then 'Hoy' 
    when PEDIDOS.f_requerido =  DATE_ADD(date(now()), INTERVAL 1 day) then 'Mañana'
    else PEDIDOS.f_requerido
    end as Fecha_Requerido,
	PEDIDOS.f_entrega as Fecha_entregada, PEDIDOS.estado_pedido as Estado, 
    case when PEDIDOS.estado_pedido not in ('cancelado', 'entregado') then PEDIDOS.id_repartidor
    else R.Nombre end as Repartidor
	from PEDIDOS
	inner join TIENDAS on PEDIDOS.id_tiendas = TIENDAS.id_tienda
	inner join CLIENTE_TIENDA on TIENDAS.id_tienda = CLIENTE_TIENDA.id_tienda
	inner join CLIENTES on CLIENTE_TIENDA.id_cliente = CLIENTES.id_cliente
	inner join PERSONAS on CLIENTES.id_persona = PERSONAS.id_persona
    left join (select PEDIDOS.id_pedido as ID, concat(PERSONAS.nombre, ' ', PERSONAS.a_p, ' ', PERSONAS.a_m) as Nombre
			   from PEDIDOS
			   left join REPARTIDORES on PEDIDOS.id_repartidor = REPARTIDORES.id_repartidor
			   left join PERSONAS on REPARTIDORES.id_persona = PERSONAS.id_persona) as R on PEDIDOS.id_pedido = R.ID
	where PEDIDOS.estado_pedido = p_estado
    order by PEDIDOS.f_requerido;
else
	select PEDIDOS.id_pedido as ID, TIENDAS.nombre_tienda as Tienda, TIENDAS.direccion as Direccion, concat(PERSONAS.nombre, ' ', PERSONAS.a_p, ' ', PERSONAS.a_m) as Cliente, PEDIDOS.f_pedido as Fecha_Pedido,
    case when PEDIDOS.f_requerido = date(now()) then 'Hoy' 
    when PEDIDOS.f_requerido =  DATE_ADD(date(now()), INTERVAL 1 day) then 'Mañana'
    else PEDIDOS.f_requerido
    end as Fecha_Requerido,
	PEDIDOS.f_entrega as Fecha_entregada, PEDIDOS.estado_pedido as Estado, 
    case when PEDIDOS.estado_pedido not in ('cancelado', 'entregado') then PEDIDOS.id_repartidor
    else R.Nombre end as Repartidor
	from PEDIDOS
	inner join TIENDAS on PEDIDOS.id_tiendas = TIENDAS.id_tienda
	inner join CLIENTE_TIENDA on TIENDAS.id_tienda = CLIENTE_TIENDA.id_tienda
	inner join CLIENTES on CLIENTE_TIENDA.id_cliente = CLIENTES.id_cliente
	inner join PERSONAS on CLIENTES.id_persona = PERSONAS.id_persona
    left join (select PEDIDOS.id_pedido as ID, concat(PERSONAS.nombre, ' ', PERSONAS.a_p, ' ', PERSONAS.a_m) as Nombre
			   from PEDIDOS
			   left join REPARTIDORES on PEDIDOS.id_repartidor = REPARTIDORES.id_repartidor
			   left join PERSONAS on REPARTIDORES.id_persona = PERSONAS.id_persona) as R on PEDIDOS.id_pedido = R.ID
	order by PEDIDOS.f_requerido;
end if;

end //
DELIMITER ;

-- * 1.3.2 Ver los detalles de los productos de un pedido
DELIMITER //
create procedure Ver_Detalle_Pedido(
    in p_id_pedido int
)
begin 
	select PRODUCTOS.nombre as Producto, DETALLE_PEDIDO.cantidad as Cantidad, concat('$', (DETALLE_PEDIDO.cantidad * PRODUCTOS.precio)) as Total
    from DETALLE_PEDIDO
    inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
    inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
    where id_pedido = p_id_pedido;
end //
DELIMITER ;

-- * 3.7.5 Calcular el Total a pagar de un pedido
DELIMITER //
create procedure Calcular_Total_Pagar_Pedido(
	in p_id_pedido int
)
begin
	select case 
    when PEDIDOS.id_tiendas is null then sum(PRODUCTOS.precio * DETALLE_PEDIDO.cantidad) 
    else sum((PRODUCTOS.precio * DETALLE_PEDIDO.cantidad) - 1) 
    end as Total
    from PEDIDOS
    inner join DETALLE_PEDIDO on PEDIDOS.id_pedido = DETALLE_PEDIDO.id_pedido
    inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
    inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
    where PEDIDOS.id_pedido = p_id_pedido;
end //
DELIMITER ;


-- Calcular Fechas de Temporada
DELIMITER //
create procedure Calcular_Fechas_Temporada(
	in p_fecha_pedido date,
	out p_habilitar_Temporada boolean
)
begin

	declare p_estado int;
    
    select estado into p_estado
    from categorias
    where id_categoria = 4;
    
	if p_estado = 1 then
    
		if p_fecha_pedido >= '2024-10-01' and p_fecha_pedido <= '2024-11-01' then
			
			set p_habilitar_Temporada = 1;
			
			update productos
			set estado = 1
			where id_producto = 29;
			
			update productos
			set estado = 0
			where id_producto = 30;
            
            update productos
			set estado = 0
			where id_producto >= 32 and id_producto <= 38;
		
		elseif p_fecha_pedido >= '2024-07-01' and p_fecha_pedido <= '2024-08-01' then
		
			set p_habilitar_Temporada = 1;
			
			update productos
			set estado = 0
			where id_producto = 29;
			
			update productos
			set estado = 1
			where id_producto = 30;
            
            update productos
			set estado = 1
			where id_producto >= 32 and id_producto <= 38;
			
		else 
		
			set p_habilitar_Temporada = 0;
		
		end if;
        
	else 
    
    set p_habilitar_Temporada = 0;
    end if;
end //
DELIMITER ;

-- * 3.7.2 Ver pedidos de clientes sin tienda por estado
DELIMITER //
create procedure Ver_Pedidos_Clientes_SinTienda_Estado(
	in p_estado varchar(30)
)
begin

if p_estado is not null then
	select PEDIDOS.id_pedido as ID, concat(PERSONAS.nombre, ' ', PERSONAS.a_p, ' ', PERSONAS.a_m) as Cliente, PEDIDOS.f_pedido as Fecha_Pedido,
    case when PEDIDOS.f_requerido = now() then 'Hoy' 
    when PEDIDOS.f_requerido =  DATE_ADD(now(), INTERVAL 1 day) then 'Mañana'
    else PEDIDOS.f_requerido
    end as Fecha_Requerido,
	PEDIDOS.f_limitepago as Fecha_Limite_Pagar, PEDIDOS.f_entrega as Fecha_entregada, PEDIDOS.estado_pedido as Estado
	from PEDIDOS
	inner join CLIENTES on PEDIDOS.id_cliente = CLIENTES.id_cliente
	inner join PERSONAS on CLIENTES.id_persona = PERSONAS.id_persona
	inner join (select CLIENTES.id_cliente as ID, CLIENTE_TIENDA.id_tienda as Tienda
				from CLIENTES
				left join CLIENTE_TIENDA on CLIENTES.id_cliente = CLIENTE_TIENDA.id_cliente
                group by ID) as C on PEDIDOS.id_cliente = C.ID
	where C.Tienda is null and PEDIDOS.estado_pedido = p_estado;
else
	select PEDIDOS.id_pedido as ID, concat(PERSONAS.nombre, ' ', PERSONAS.a_p, ' ', PERSONAS.a_m) as Cliente, PEDIDOS.f_pedido as Fecha_Pedido,
    case when PEDIDOS.f_requerido = now() then 'Hoy' 
    when PEDIDOS.f_requerido =  DATE_ADD(now(), INTERVAL 1 day) then 'Mañana'
    else PEDIDOS.f_requerido
    end as Fecha_Requerido,
	PEDIDOS.f_limitepago as Fecha_Limite_Pagar, PEDIDOS.f_entrega as Fecha_entregada, PEDIDOS.estado_pedido as Estado
	from PEDIDOS
	inner join CLIENTES on PEDIDOS.id_cliente = CLIENTES.id_cliente
	inner join PERSONAS on CLIENTES.id_persona = PERSONAS.id_persona
	inner join (select CLIENTES.id_cliente as ID, CLIENTE_TIENDA.id_tienda as Tienda
				from CLIENTES
				left join CLIENTE_TIENDA on CLIENTES.id_cliente = CLIENTE_TIENDA.id_cliente
                group by ID) as C on PEDIDOS.id_cliente = C.ID
	where C.Tienda is null;
end if;

end //
DELIMITER ;


-- * 3.7.4 Asignar repartidor a pedidos
DELIMITER //
create procedure asignar_repartidor(
in idpedido int,
in idrepar int
)
begin
update PEDIDOS
set PEDIDOS.id_repartidor=idrepar
where PEDIDOS.id_pedido=idpedido;
end //
DELIMITER ;