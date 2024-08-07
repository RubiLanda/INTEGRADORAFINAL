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


-- * 3.7.3 Cambiar estado de pedido
DELIMITER //
create procedure cambiar_estado(
in id int,
in estadonuev varchar(25)
)
begin 
update PEDIDOS
set PEDIDOS.estado_pedido=estadonuev
where PEDIDOS.id_pedido=id;
end //
DELIMITER ;















/* GESTION DEL MODULO DE PRODUCTOS*/
-- 3.4.3 Agregar un nuevo producto
DELIMITER //
CREATE PROCEDURE NUEVO_PRODUCTO (
IN n_nombre varchar(40), -- parametros para el nuevo producto
IN n_id_categoria int,
IN n_imagen varchar(255),
IN n_precio decimal(10,2),
IN n_descripcion varchar(255),
out mensaje text
)
begin 
declare ultimaid_producto int; 
declare contador int; 

declare MensajeError text;
declare contadorError int;

declare Imagen_actual varchar(255);
declare Imagen_existente int;

set MensajeError = '';
set contadorError = 0;


select COUNT(*) into Imagen_existente
from PRODUCTOS
where imagen = n_imagen;

select count(PRODUCTOS.nombre) into contador
from PRODUCTOS
where nombre = n_nombre;

		if n_nombre = '' then
			set MensajeError = 'No puedes dejar el nombre del producto vacio';
			set contadorError = contadorError + 1;
		end if;
     
		if n_id_categoria = 0 then 
           if contadorError = 0 then
				set MensajeError = 'No puedes dejar la categoria del  producto vacio';
			elseif contadorError = 1 then 
				set MensajeError = concat(MensajeError, ' ni tampoco la catgoria del producto');
			elseif contadorError > 1 then 
			set MensajeError = concat(MensajeError, ', ni la categoria del producto');
			end if;
			set contadorError = contadorError + 1;
		end if;
    
    if n_imagen = '' then 
		if contadorError = 0 then
			set MensajeError = 'No puedes dejar la imagen del producto vacia';
     elseif contadorError = 1 then 
			set MensajeError = concat(MensajeError, ' ni tampoco la imagen del producto');
	elseif contadorError > 1 then 
				set MensajeError = concat(MensajeError, ', ni la imagen del producto');
		end if;
			set contadorError = contadorError + 1;
	end if;
    
    if n_precio = '' then 
		if contadorError = 0 then
			set MensajeError = 'No puedes dejar el precio del producto vacio';
     elseif contadorError = 1 then 
			set MensajeError = concat(MensajeError, ' ni tampoco el precio del producto');
	elseif contadorError > 1 then 
				set MensajeError = concat(MensajeError, ', ni el precio del producto');
		end if;
			set contadorError = contadorError + 1;
	end if;
    
     if n_descripcion = '' then 
		if contadorError = 0 then
			set MensajeError = 'No puedes dejar la descripcion del producto vacia';
     elseif contadorError = 1 then 
			set MensajeError = concat(MensajeError, ' ni tampoco la descripcion del prodcuto');
	elseif contadorError > 1 then 
				set MensajeError = concat(MensajeError, ', ni la descripcion del producto');
		end if;
			set contadorError = contadorError + 1;
	end if;
     
	if contador > 0 then 
      if contadorError = 0 then
	set MensajeError = 'Este producto ya existe';
		elseif contadorError = 1 then 
			set MensajeError = concat(MensajeError, ' Este nombre de producto ya existe');
		elseif contadorError > 1 then 
				set MensajeError = concat(MensajeError, ',  nombre del producto ya existe');
		end if;
			set contadorError = contadorError + 1;
	end if;
    
    if Imagen_existente > 0 then
		if contadorError = 0 then
     set MensajeError = 'Esta imagen ya esta asignada en otro producto';
       elseif contadorError = 1 then 
          set  MensajeError = concat(MensajeError, ' Esta imagen ya esta asignada en otro producto');
		elseif contadorError > 1 then
            set MensajeError = concat(MensajeError, ',  imagen del producto asignada en otro producto');
		end if;
			set contadorError = contadorError + 1;
		end if;

    if contadorError = 0 then
	Insert into PRODUCTOS (nombre,id_categoria,imagen,precio,estado,descripcion)
	values (n_nombre, n_id_categoria, n_imagen, n_precio, 1, n_descripcion);
	set ultimaid_producto = last_insert_id(); -- guardar el id para mandarla al inventario
	insert into INVENTARIO (id_producto, stock)
	values
	(ultimaid_producto, 0);
	set mensaje = 'Producto añadido correctamente';
      else
        set mensaje = MensajeError;
    end if;

end //
DELIMITER ;


-- 3.4.4  PROCEDIMIENTO PARA ACTUALIZAR LA INFORMACION DE LOS PRODUCTOS CORREGIDO Y CONCATENADO :(
DROP PROCEDURE actualizarproducto
DELIMITER //
CREATE PROCEDURE actualizarproducto(
in id int,                          
in nuevo_nombre varchar(40),
in nuevo_precio decimal(10,2),
in nueva_descripcion varchar(255),
out mensaje text
)
begin

declare ModificacionNombre boolean;
declare ModificacionPrecio boolean;
declare ModificacionDescripcion boolean;

declare mensajeError text;
declare mensajeBueno text;
    
declare contadorError int;
declare contadorBueno int;
    
set mensajeError = '';
set mensajeBueno = '';
    
set contadorError = 0;
set contadorBueno = 0;

set ModificacionNombre = 0;
set ModificacionPrecio = 0;
set ModificacionDescripcion = 0;

select true into ModificacionNombre 
from PRODUCTOS
where PRODUCTOS.nombre = nuevo_nombre and PRODUCTOS.id_producto = id;

select true into ModificacionPrecio 
from PRODUCTOS 
where PRODUCTOS.precio = nuevo_precio and PRODUCTOS.id_producto = id;

select true into ModificacionDescripcion 
from PRODUCTOS 
where PRODUCTOS.descripcion = nueva_descripcion and PRODUCTOS.id_producto = id;

if ModificacionNombre = 1 and ModificacionPrecio = 1 and ModificacionDescripcion = 1 then
		set mensaje = 'No se hizo ninguna modificacion';
	else 
		if nuevo_nombre = '' then
			set mensajeError = 'No puedes dejar el nombre del producto vacio';
			set contadorError = contadorError + 1;
		end if;
        
        
         if nuevo_precio = '' then
			if contadorError = 0 then 
				set mensajeError = 'No puedes dejar el precio del producto vacio';
			elseif contadorError = 1 then
				set mensajeError = concat(mensajeError, ' ni tampoco el precio del producto');
			elseif contadorError >1 then 
				set mensajeError = concat(mensajeError, ', precio del producto');
			end if;
			set contadorError = contadorError + 1;
		end if;
        
        if nueva_descripcion = '' then
			if contadorError = 0 then 
				set mensajeError = 'No puedes dejar la descripcion del producto vacia';
			elseif contadorError = 1 then 
				set mensajeError = concat(mensajeError, ' ni tampoco la descripcion del producto');
			elseif contadorError >1 then 
				set mensajeError = concat(mensajeError, ', descripcion del producto');
			end if;
			set contadorError = contadorError + 1;
		end if;
        
        if ModificacionNombre = 0 then
			if nuevo_nombre != '' then
				update PRODUCTOS
				set PRODUCTOS.nombre= nuevo_nombre
				where PRODUCTOS.id_producto = id;
				
                if contadorError = 0 then
					set mensajeBueno = 'Nombre del producto actualizado corectamente';
                elseif contadorError > 0 then
					set mensajeBueno = ' pero si se modifico el nombre del producto';
                end if;
                set contadorBueno = contadorBueno + 1;
			end if;
        end if;
        
        if ModificacionPrecio = 0 then 
			if nuevo_precio != '' then
				update PRODUCTOS
				set PRODUCTOS.precio = nuevo_precio
				where PRODUCTOS.id_producto = id;
                if contadorError = 0 and contadorBueno = 0 then 
					set mensajeBueno = 'Precio del producto actualizado corectamente';
				end if;
                
				if contadorError > 0 and contadorBueno = 0 then
					set mensajeBueno = ' pero si se modifico el precio del producto';
				elseif contadorError > 0 and contadorBueno > 1 then
					set mensajeBueno = concat(mensajeBueno, ', precio delproducto');
				end if;
                set contadorBueno = contadorBueno + 1;
			end if;
        end if;
        
        if ModificacionDescripcion = 0 then
			if nueva_descripcion != '' then
				update PRODUCTOS
				set PRODUCTOS.descripcion = nueva_descripcion
				where PRODUCTOS.id_producto = id;
                if contadorError = 0 and contadorBueno = 0 then 
					set mensajeBueno = 'Descripcion del producto actualizado corectamente';
				end if;
                
                if contadorError > 0 and contadorBueno = 0 then
					set mensajeBueno = ' pero si se modifico la descripcion del producto';
                elseif contadorError > 0 and contadorBueno > 1 then
					set mensajeBueno = concat(mensajeBueno, ', descripcion del producto');
				end if;
                set contadorBueno = contadorBueno + 1;
			end if;
        end if;
        
    set mensaje = concat(mensajeError, mensajeBueno);
	end if;
end //
DELIMITER ;
         

-- 3.4.8  procedimiento para actualizar el nombre de la categoria
DELIMITER //
CREATE PROCEDURE actualizarnombrecat(
in id int,
in nuevo_nombre varchar(40),
out mensaje text
)
begin 
declare Modificacion boolean;
set Modificacion = 0;
select true into Modificacion
from CATEGORIAS
where nombre = nuevo_nombre;

if Modificacion = 1 then 
	set mensaje = 'No se hizo ninguna modificacion';
	else 
	if nuevo_nombre is not null
	then 
	update CATEGORIAS
	set nombre = nuevo_nombre
	where id_categoria = id;
	set mensaje = 'Cambios realizados correctamente';
     end if;
end if;
end //
DELIMITER ;

-- 3.4.2 procedimiento para dar de baja categorias
drop procedure baja_categorias
DELIMITER // 
CREATE PROCEDURE  baja_categorias(
idecategoria int,    -- parametros de entrada
N_estado boolean,
out mensaje text
)
begin 
if N_estado = 0  -- si el parametro de estado no es nulo que se actualice
then 
update CATEGORIAS
set estado = N_estado
where id_categoria = idecategoria and id_categoria is not null;
set mensaje = 'Categoria dada de baja Exitosamente';

end if;
if N_estado = 1 
then 
update CATEGORIAS
set estado = N_estado
where id_categoria = idecategoria and id_categoria is not null;

set mensaje = 'Categoria habilitada Exitosamente';
end if;
end //
DELIMITER ;


-- *3.4.5 - Procedimiento. Habilitar y Deshabilitar productos
drop procedure baja_productos
  DELIMITER // 
CREATE PROCEDURE baja_productos (
ideproducto int,
N_estado boolean,
out mensaje text
)
begin 
if N_estado = 0
then 
update PRODUCTOS
set estado = N_estado
where id_producto = ideproducto;
set mensaje = 'Producto dado de baja Exitosamente';

end if;
if N_estado = 1
then 
update PRODUCTOS
set estado = N_estado
where id_producto = ideproducto;
set mensaje = 'Producto habilitado Exitosamente';

end if;
end //
DELIMITER ;


 -- 3.4.1 Procedimiento. Añadir categorías
DELIMITER //
create procedure NUEVA_CATEGORIA (
IN N_CATEGORIA varchar(40),
out mensaje text -- nombre de la categoria nueva deseada
)
begin 
DECLARE repetido int; -- variable para contar
IF N_CATEGORIA is null or N_CATEGORIA = '' -- si el nombre de la categoria es nula mandar error de que no puede ser nula 
then 
set mensaje = 'Nombre de categoria Invalida';
else
select count(CATEGORIAS.nombre) into repetido -- contar si el nombre de la tabla categoria nombre es se encuentra en 
from CATEGORIAS 
where nombre = N_CATEGORIA;

IF repetido > 0 then -- si el contador es mayor a 0 (1 o mas) significa que dentro de la variable repetido conto 1 con el nombre 
set mensaje = 'Esa categoria ya existe';
else
insert into categorias (nombre,estado) -- insertar en la tabla categorias si paso los filtros
values(N_CATEGORIA,1);
set mensaje = 'Categoria Añadida Exitosamente'; -- el nombre nuevo y dado de alta
end if;
end if;
end //
DELIMITER ;

-- 3.4.9 MODIFICAR LA IMAGEN DE UN PRODUCTO
DELIMITER //
create procedure Modificar_Imagen_Producto(
in p_id_producto int,
in p_imagen varchar(255),
out mensaje text
)
begin
declare Imagen_actual varchar(255);
declare Imagen_existente int;

select  imagen into Imagen_actual 
from PRODUCTOS
where  id_producto = p_id_producto;
select COUNT(*) into Imagen_existente
from PRODUCTOS
where imagen = p_imagen and id_producto != p_id_producto;

if Imagen_existente > 0 
	then
		set mensaje = 'La Imagen ya esta asignada en otro producto';
	else
		if Imagen_actual = p_imagen 
			then
				set mensaje = 'Error Imagen Repetida';
		else
			update PRODUCTOS
			set imagen = p_imagen
			where id_producto = p_id_producto;
			set mensaje = 'Imagen Agregada Correctamente';
		end if;
 end if;
end //
DELIMITER ;

-- * 3.5.2 Procedimiento. Ver Productos filtrado por Categoria y por Nombre de Producto para modificar Informacion
DROP PROCEDURE Ver_Productos_Informacion
DELIMITER //
create procedure Ver_Productos_Informacion(
	in p_categoria int,
    in p_nombre_producto varchar(50),
    in p_offset int,
    in p_records_per_page int
)
begin
	if p_offset is null and p_records_per_page is null then
		if p_categoria != 0 and p_nombre_producto is null then
			select id_producto as ID, nombre as Nombre, imagen as Imagen, precio as Precio, descripcion as Descripcion, estado as Estado
			from PRODUCTOS   
			where id_categoria = p_categoria;
		end if;
		
		if p_categoria = 0 and p_nombre_producto is not null then
			select id_producto as ID, nombre as Nombre, imagen as Imagen, precio as Precio, descripcion as Descripcion, estado as Estado
			from PRODUCTOS
			where  nombre = p_nombre_producto;
		end if;
		
		if p_categoria = 0 and p_nombre_producto is null then
			select id_producto as ID, nombre as Nombre, imagen as Imagen, precio as Precio, descripcion as Descripcion, estado as Estado
			from PRODUCTOS;
		end if;
    else 
		if p_categoria != 0 and p_nombre_producto is null then
			select id_producto as ID, nombre as Nombre, imagen as Imagen, precio as Precio, descripcion as Descripcion, estado as Estado
			from PRODUCTOS
			where id_categoria = p_categoria
			limit p_records_per_page
			offset p_offset;
		end if;
		
		if p_categoria = 0 and p_nombre_producto is not null then
			select id_producto as ID, nombre as Nombre, imagen as Imagen, precio as Precio, descripcion as Descripcion, estado as Estado
			from PRODUCTOS
			where nombre = p_nombre_producto
			limit p_records_per_page
			offset p_offset;
		end if;
		
		if p_categoria = 0 and p_nombre_producto is null then
			select id_producto as ID, nombre as Nombre, imagen as Imagen, precio as Precio, descripcion as Descripcion, estado as Estado
			from PRODUCTOS
			limit p_records_per_page
			offset p_offset;
		end if;
    end if;
end //
DELIMITER ;