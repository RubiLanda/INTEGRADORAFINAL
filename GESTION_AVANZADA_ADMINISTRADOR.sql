-- 3.1.3 Ver repartidores por Estatus
DELIMITER //
create procedure Ver_Repartidores(
in p_estatus boolean
)
begin
if p_estatus is not null then
select REPARTIDORES.id_repartidor as ID, USUARIOS.username as usuario, concat(PERSONAS.nombre, ' ', PERSONAS.a_p, ' ', PERSONAS.a_m) as Nombre, PERSONAS.f_nac as Fecha_nacimiento, PERSONAS.genero as Genero, PERSONAS.telefono as Telefono, 
REPARTIDORES.f_ingreso as Fecha_Ingreso, REPARTIDORES.fol_liconducir as licencia_conducir, REPARTIDORES.estatus as Estatus
from PERSONAS
inner join USUARIOS on PERSONAS.id_usuario=USUARIOS.id_usuario
inner join REPARTIDORES on PERSONAS.id_persona = REPARTIDORES.id_persona
where REPARTIDORES.estatus = p_estatus;
else
select REPARTIDORES.id_repartidor as ID, USUARIOS.username as usuario, concat(PERSONAS.nombre, ' ', PERSONAS.a_p, ' ', PERSONAS.a_m) as Nombre, PERSONAS.f_nac as Fecha_nacimiento, PERSONAS.genero as Genero, PERSONAS.telefono as Telefono, 
REPARTIDORES.f_ingreso as Fecha_Ingreso, REPARTIDORES.fol_liconducir as licencia_conducir, REPARTIDORES.estatus as Estatus, REPARTIDORES.f_ingreso as Fecha_ingreso
from PERSONAS
inner join USUARIOS on PERSONAS.id_usuario=USUARIOS.id_usuario
inner join REPARTIDORES on PERSONAS.id_persona = REPARTIDORES.id_persona;
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
    
select CATEGORIAS.estado into p_estado
from CATEGORIAS
where CATEGORIAS.id_categoria = 4;
    
if p_estado = 1 then
    
if p_fecha_pedido >= '2024-10-01' and p_fecha_pedido <= '2024-11-01' then
			
set p_habilitar_Temporada = 1;
			
update PRODUCTOS
set estado = 1
where PRODUCTOS.id_producto = 29;
			
update PRODUCTOS
set estado = 0
where PRODUCTOS.id_producto = 30;
            
update PRODUCTOS
set estado = 0
where PRODUCTOS.id_producto >= 32 and PRODUCTOS.id_producto <= 38;
		
elseif p_fecha_pedido >= '2024-07-01' and p_fecha_pedido <= '2024-08-01' then
		
set p_habilitar_Temporada = 1;
			
update PRODUCTOS
set estado = 0
where PRODUCTOS.id_producto = 29;
			
update PRODUCTOS
set estado = 1
where PRODUCTOS.id_producto = 30;
            
update PRODUCTOS
set estado = 1
where PRODUCTOS.id_producto >= 32 and PRODUCTOS.id_producto <= 38;
			
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

 -- *3.1.4 ver los pedidos de un repartidor desde la vista del admin
DELIMITER //
create PROCEDURE Ver_Pedidos_Cliente_SinTienda_Repartidor(
in idrepar int,
in estado varchar(30)
)
begin

if estado is not null then
select PEDIDOS.id_pedido as ID, TIENDAS.nombre_tienda as Tienda, TIENDAS.direccion as Direccion, concat(PERSONAS.nombre, ' ', PERSONAS.a_p, ' ', PERSONAS.a_m) as Cliente, PEDIDOS.f_pedido as Fecha_Pedido, PEDIDOS.f_requerido as Fecha_Requerido, 
PEDIDOS.f_entrega as Fecha_entregada, PEDIDOS.estado_pedido as Estado
from PEDIDOS
inner join TIENDAS on PEDIDOS.id_tiendas = TIENDAS.id_tienda
inner join CLIENTE_TIENDA on TIENDAS.id_tienda = CLIENTE_TIENDA.id_tienda
inner join CLIENTES on CLIENTE_TIENDA.id_cliente = CLIENTES.id_cliente
inner join PERSONAS on CLIENTES.id_persona = PERSONAS.id_persona
where PEDIDOS.id_repartidor = idrepar and PEDIDOS.estado_pedido = estado;
else 
select PEDIDOS.id_pedido as ID, TIENDAS.nombre_tienda as Tienda, TIENDAS.direccion as Direccion, concat(PERSONAS.nombre, ' ', PERSONAS.a_p, ' ', PERSONAS.a_m) as Cliente, PEDIDOS.f_pedido as Fecha_Pedido, PEDIDOS.f_requerido as Fecha_Requerido, 
PEDIDOS.f_entrega as Fecha_entregada, PEDIDOS.estado_pedido as Estado
from PEDIDOS
inner join TIENDAS on PEDIDOS.id_tiendas = TIENDAS.id_tienda
inner join CLIENTE_TIENDA on TIENDAS.id_tienda = CLIENTE_TIENDA.id_tienda
inner join CLIENTES on CLIENTE_TIENDA.id_cliente = CLIENTES.id_cliente
inner join PERSONAS on CLIENTES.id_persona = PERSONAS.id_persona
where PEDIDOS.id_repartidor = idrepar;
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


DELIMITER //
CREATE PROCEDURE Ver_Informacion_Usuario(
IN p_id_usuario INT 
)
BEGIN
SELECT USUARIOS.username AS usuariop, CONCAT(nombre, ' ', a_p, ' ', a_m) AS Nombre, f_nac AS Fecha_nacimiento, genero AS Genero, telefono AS Telefono, DATE(USUARIOS.f_registro) AS FECHA
FROM PERSONAS 
INNER JOIN USUARIOS
ON PERSONAS.id_usuario = USUARIOS.id_usuario
WHERE USUARIOS.id_usuario = p_id_usuario;
END //
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

if n_precio = 0 or n_precio is null then 

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
					elseif contadorError > 1 then 

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
where  PRODUCTOS.id_producto = p_id_producto;

select COUNT(*) into Imagen_existente
from PRODUCTOS
where PRODUCTOS.imagen = p_imagen and PRODUCTOS.id_producto != p_id_producto;

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
where PRODUCTOS.id_producto = p_id_producto;

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

-- * 3.1.2 DAR DE ALTA  REPARTIDORES
DELIMITER //
create PROCEDURE INSERTAR_REPARTIDORES (
IN N_username VARCHAR(150),    -- varibales de entrada para el registro del nuevo administrador
IN N_contraseña VARCHAR(255),
IN N_nombre VARCHAR(40),
IN N_a_p VARCHAR(40),
IN N_a_m VARCHAR(40),
IN N_f_nac DATE,
IN N_genero ENUM('M','F','O'),
IN N_telefono CHAR(10),
IN N_folioconducir CHAR(11),
IN f_ingreso date,
out mensaje text
)
BEGIN
declare ultimaid_usuario int; -- declaramos algunas variables para guardar la id de algunas cosas
declare ultimaid_persona int;
declare ultimaid_repartidor int;
declare userepetido int;
declare licrepe int;
IF N_username='' or N_contraseña=''  or N_nombre=''  or N_a_p='' or N_f_nac=''  or 
N_genero='' or N_telefono=''  or N_folioconducir='' or f_ingreso=''  -- si cualquiera de los campos son nulos entonces mandara un mensaje de error
then 
	set mensaje = 'NO PUEDES DEJAR ALGUN CAMPO VACIO';
else
	select count(USUARIOS.username) into userepetido from USUARIOS where username=N_username;
	if userepetido>0 then
		set mensaje='Ya Existe un usuario con ese nombre';
	else
		select count(REPARTIDORES.fol_liconducir) into licrepe from REPARTIDORES where fol_liconducir=N_folioconducir;
		if licrepe>0 then
			set mensaje='Ya Esta Registrada esa licencia de conducir, por favor ingresa otra';
		else
			if length(N_telefono)<10 then
				set mensaje='Numero de telefono invalido';
			else
				if length(N_folioconducir)<11 then
					set mensaje='Folio de licencia de conducir invalido';
				else
					if f_ingreso<=DATE_ADD(CURDATE(), INTERVAL -50 YEAR) or f_ingreso>DATE_ADD(date(now()), INTERVAL 1 year) then
						set mensaje='Ingresa una fecha valida';
					else
						IF N_f_nac <= DATE_ADD(CURDATE(), INTERVAL -18 YEAR)
						AND N_f_nac >= DATE_ADD(CURDATE(), INTERVAL -80 YEAR) -- si la fecha de nacimineto es menor que la fecha que se genere de hoy menos 18 años
						then
							insert into USUARIOS (username, contrasena, f_registro)  -- insertar en usuarios los datos
							values (N_username, N_contraseña,now());
							set ultimaid_usuario = last_insert_id(); -- guardar la ultima id que se genero con el auto-increment
							insert into ROL_USUARIO (id_rol,id_usuario) -- inmediatamente en la tabla de rol se le dara el rol de administrador
							values(3,ultimaid_usuario);
							insert into PERSONAS(nombre, a_p, a_m, f_nac, genero, telefono,id_usuario) -- insertar en la tabla de personas todos los datos personales
							values (N_nombre, N_a_p, N_a_m, N_f_nac, N_genero, N_telefono,ultimaid_usuario);
							set ultimaid_persona = last_insert_id(); -- guardar la ultima id de persona generada con el autoincrement 
							insert into REPARTIDORES(f_ingreso,fol_liconducir,id_persona,estatus) -- inserta en la tabla administradores
							values(f_ingreso,N_folioconducir,ultimaid_persona,1);
							set mensaje='REGISTRO EXITOSO';
						else
							set mensaje = 'DEBES SER MAYOR DE EDAD EL REPARTIDOR PARA REGISTRARLO O LA FECHA QUE INGRESASTE ES EXCESIVA';
						end if;
					end if;
				end if;
			end if;
		end if;
	end if;
end if;
end //
DELIMITER ;



-- * 3.1.1 Habilitar o Deshabilitar Repartidor
DELIMITER //
create procedure Habilitar_Deshabilitar_Repartidor(
	in p_id_repartidor int,
    in p_nuevo_estatus boolean,
    out mensaje text
)
begin 
if p_nuevo_estatus=0 then
	update REPARTIDORES
    set estatus = p_nuevo_estatus
    where id_repartidor = p_id_repartidor;
    set mensaje='Repartidor dado de baja exitosamente';
    end if ;
    if p_nuevo_estatus=1 then
	update REPARTIDORES
    set estatus = p_nuevo_estatus
    where id_repartidor = p_id_repartidor;
    set mensaje='Repartidor habilitado exitosamente';
    end if ;
    
end //
DELIMITER ;


drop procedure Ventas_Productos
-- 3.3.2 ventas por productos fechas y categorías
DELIMITER //
create procedure Ventas_Productos(
in p_mes int,
in p_año int,
in fecha_requerida date,
in p_categoria int
)
begin
if p_mes is not null and p_año is null and p_categoria is null and fecha_requerida is null then

select  PRODUCTOS.nombre as Producto, sum(DETALLE_PEDIDO.cantidad * PRODUCTOS.precio) as Total
from DETALLE_PEDIDO
inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
inner join PEDIDOS on DETALLE_PEDIDO.id_pedido = PEDIDOS.id_pedido
inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
inner join CATEGORIAS on PRODUCTOS.id_categoria = CATEGORIAS.id_categoria
where month (PEDIDOS.f_entrega) = p_mes and PEDIDOS.estado_pedido = 'entregado'
group by Producto;
end if;

if p_mes is null and p_año is not null and p_categoria is null and fecha_requerida is null then

select  PRODUCTOS.nombre as Producto, sum(DETALLE_PEDIDO.cantidad * PRODUCTOS.precio) as Total
from DETALLE_PEDIDO
inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
inner join PEDIDOS on DETALLE_PEDIDO.id_pedido = PEDIDOS.id_pedido
inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
inner join CATEGORIAS on PRODUCTOS.id_categoria = CATEGORIAS.id_categoria
where year (PEDIDOS.f_entrega) = p_año and PEDIDOS.estado_pedido = 'entregado'
group by Producto;
end if;

if p_mes is not null and p_año is not null and p_categoria is null and fecha_requerida is null then

select  PRODUCTOS.nombre as Producto, sum(DETALLE_PEDIDO.cantidad * PRODUCTOS.precio) as Total
from DETALLE_PEDIDO
inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
inner join PEDIDOS on DETALLE_PEDIDO.id_pedido = PEDIDOS.id_pedido
inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
inner join CATEGORIAS on PRODUCTOS.id_categoria = CATEGORIAS.id_categoria
where month (PEDIDOS.f_entrega) = p_mes and  year (PEDIDOS.f_entrega) = p_año and PEDIDOS.estado_pedido = 'entregado'
group by Producto;
end if;

if p_mes is null and p_año is null and p_categoria is not null and fecha_requerida is null then

select  PRODUCTOS.nombre as Producto, sum(DETALLE_PEDIDO.cantidad * PRODUCTOS.precio) as Total
from DETALLE_PEDIDO
inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
inner join PEDIDOS on DETALLE_PEDIDO.id_pedido = PEDIDOS.id_pedido
inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
inner join CATEGORIAS on PRODUCTOS.id_categoria = CATEGORIAS.id_categoria
where CATEGORIAS.id_categoria = p_categoria and  PEDIDOS.estado_pedido = 'entregado'
group by Producto;
end if;

if p_mes is not null and p_año is null and p_categoria is not null and fecha_requerida is null then

select  PRODUCTOS.nombre as Producto, sum(DETALLE_PEDIDO.cantidad * PRODUCTOS.precio) as Total
from DETALLE_PEDIDO
inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
inner join PEDIDOS on DETALLE_PEDIDO.id_pedido = PEDIDOS.id_pedido
inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
inner join CATEGORIAS on PRODUCTOS.id_categoria = CATEGORIAS.id_categoria
where  month (PEDIDOS.f_entrega) = p_mes and CATEGORIAS.id_categoria = p_categoria and PEDIDOS.estado_pedido = 'entregado'
group by Producto;
end if;

if p_mes is null and p_año is not null and p_categoria is not null and fecha_requerida is null then

select  PRODUCTOS.nombre as Producto, sum(DETALLE_PEDIDO.cantidad * PRODUCTOS.precio) as Total
from DETALLE_PEDIDO
inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
inner join PEDIDOS on DETALLE_PEDIDO.id_pedido = PEDIDOS.id_pedido
inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
inner join CATEGORIAS on PRODUCTOS.id_categoria = CATEGORIAS.id_categoria
where  year (PEDIDOS.f_entrega) = p_año and CATEGORIAS.id_categoria = p_categoria and  PEDIDOS.estado_pedido = 'entregado'
group by Producto;
end if;

if p_mes is not null and p_año is not null and p_categoria is not null and fecha_requerida is null then

select  PRODUCTOS.nombre as Producto, sum(DETALLE_PEDIDO.cantidad * PRODUCTOS.precio) as Total
from DETALLE_PEDIDO
inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
inner join PEDIDOS on DETALLE_PEDIDO.id_pedido = PEDIDOS.id_pedido
inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
inner join CATEGORIAS on PRODUCTOS.id_categoria = CATEGORIAS.id_categoria
where  month (PEDIDOS.f_entrega) = p_mes and year (PEDIDOS.f_entrega) = p_año and CATEGORIAS.id_categoria = p_categoria and  PEDIDOS.estado_pedido = 'entregado'
group by Producto;
end if;

if p_mes is null and p_año is null and p_categoria is null and fecha_requerida is null then

select  PRODUCTOS.nombre as Producto, sum(DETALLE_PEDIDO.cantidad * PRODUCTOS.precio) as Total
from DETALLE_PEDIDO
inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
inner join PEDIDOS on DETALLE_PEDIDO.id_pedido = PEDIDOS.id_pedido
inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
inner join CATEGORIAS on PRODUCTOS.id_categoria = CATEGORIAS.id_categoria
where PEDIDOS.estado_pedido = 'entregado'
group by Producto;
end if;

if fecha_requerida is not null and p_mes is null and p_año is null and p_categoria IS NULL then

select  PRODUCTOS.nombre as Producto, sum(DETALLE_PEDIDO.cantidad * PRODUCTOS.precio) as Total
from DETALLE_PEDIDO
inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
inner join PEDIDOS on DETALLE_PEDIDO.id_pedido = PEDIDOS.id_pedido
inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
inner join CATEGORIAS on PRODUCTOS.id_categoria = CATEGORIAS.id_categoria
where PEDIDOS.estado_pedido = 'entregado' and date(PEDIDOS.f_entrega) = fecha_requerida
group by Producto;
end if ;

if fecha_requerida is not null and p_mes is null and p_año is null and p_categoria IS NOT NULL then

select  PRODUCTOS.nombre as Producto, sum(DETALLE_PEDIDO.cantidad * PRODUCTOS.precio) as Total
from DETALLE_PEDIDO
inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
inner join PEDIDOS on DETALLE_PEDIDO.id_pedido = PEDIDOS.id_pedido
inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
inner join CATEGORIAS on PRODUCTOS.id_categoria = CATEGORIAS.id_categoria
where PEDIDOS.estado_pedido = 'entregado' and date(PEDIDOS.f_entrega) = fecha_requerida and CATEGORIAS.id_categoria=p_categoria 
group by Producto;
end if ;
end //
DELIMITER ;



-- * 3.3.1 Ver ventas de Tiendas filtrado por mes, año, por nombre de la tienda y ordenado por mayor número de ganancias 
DELIMITER //
create procedure Ver_ventas_Tiendas(
	in p_Mes int,
    in p_Año int,
    in fecha_requerida date
)
begin
	if p_Mes is not null and p_Año is null and fecha_requerida is null then
		select TIENDAS.nombre_tienda as Nombre_tienda, sum(PRODUCTOS.precio * DETALLE_PEDIDO.cantidad) as Total
        from TIENDAS
        inner join PEDIDOS on TIENDAS.id_tienda = PEDIDOS.id_tiendas
        inner join DETALLE_PEDIDO on PEDIDOS.id_pedido = DETALLE_PEDIDO.id_pedido
        inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
        inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
        where month(PEDIDOS.f_entrega) = p_Mes and PEDIDOS.estado_pedido = 'entregado'
        group by (Nombre_tienda);
    end if;
    
    if p_Mes is null and p_Año is not null and fecha_requerida is null then
		select TIENDAS.nombre_tienda as Nombre_tienda, sum(PRODUCTOS.precio * DETALLE_PEDIDO.cantidad) as Total
        from TIENDAS
        inner join PEDIDOS on TIENDAS.id_tienda = PEDIDOS.id_tiendas
        inner join DETALLE_PEDIDO on PEDIDOS.id_pedido = DETALLE_PEDIDO.id_pedido
        inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
        inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
        where year(PEDIDOS.f_entrega) = p_Año and PEDIDOS.estado_pedido = 'entregado'
        group by (Nombre_tienda);
    end if;
    
    if p_Mes is not null and p_Año is not null and fecha_requerida is null then
		select TIENDAS.nombre_tienda as Nombre_tienda, sum(PRODUCTOS.precio * DETALLE_PEDIDO.cantidad) as Total
        from TIENDAS
        inner join PEDIDOS on TIENDAS.id_tienda = PEDIDOS.id_tiendas
        inner join DETALLE_PEDIDO on PEDIDOS.id_pedido = DETALLE_PEDIDO.id_pedido
        inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
        inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
        where month(PEDIDOS.f_entrega) = p_Mes and year(PEDIDOS.f_entrega) = p_Año and PEDIDOS.estado_pedido = 'entregado'
        group by (Nombre_tienda);
    end if;
    
    if p_Mes is null and p_Año is null and fecha_requerida  is not null then
   select TIENDAS.nombre_tienda as Nombre_tienda, sum(PRODUCTOS.precio * DETALLE_PEDIDO.cantidad) as Total
        from TIENDAS
        inner join PEDIDOS on TIENDAS.id_tienda = PEDIDOS.id_tiendas
        inner join DETALLE_PEDIDO on PEDIDOS.id_pedido = DETALLE_PEDIDO.id_pedido
        inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
        inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
        where date(PEDIDOS.f_entrega)=fecha_requerida;
        end if;
    
    if p_Mes is null and p_Año is null and fecha_requerida is null then	
		select TIENDAS.nombre_tienda as Nombre_tienda, sum(PRODUCTOS.precio * DETALLE_PEDIDO.cantidad) as Total
        from TIENDAS
        inner join PEDIDOS on TIENDAS.id_tienda = PEDIDOS.id_tiendas
        inner join DETALLE_PEDIDO on PEDIDOS.id_pedido = DETALLE_PEDIDO.id_pedido
        inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
        inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
        where PEDIDOS.estado_pedido = 'entregado'
        group by (Nombre_tienda);
    end if;
end //
DELIMITER ;


-- * 3.3.4 Ver ventas de Repartidores filtrado por mes, año, por nombre del repartidor y ordenado por mayor número de ganancias
DELIMITER //
create procedure Ver_Ventas_Repartidores(
	in p_Mes int,
    in p_Año int,
     in fecha_requerida date,
    in p_nombre_repartidor int
)
begin
	if p_Mes is not null and p_Año is null and p_nombre_repartidor is null and fecha_requerida is null then
		select PERSONAS.nombre as Nombre_repartidor, sum(PRODUCTOS.precio * DETALLE_PEDIDO.cantidad) as Total
        from REPARTIDORES
        inner join PERSONAS on REPARTIDORES.id_persona = PERSONAS.id_persona
        inner join PEDIDOS on PEDIDOS.id_repartidor = REPARTIDORES.id_repartidor
        inner join DETALLE_PEDIDO on PEDIDOS.id_pedido = DETALLE_PEDIDO.id_pedido
        inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
        inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
        where month(PEDIDOS.f_entrega) = p_Mes and PEDIDOS.estado_pedido = 'entregado'
        group by (Nombre_repartidor);
	end if;
    
    if p_Mes is null and p_Año is not null and p_nombre_repartidor is null and fecha_requerida is null then
		select PERSONAS.nombre as Nombre_repartidor, sum(PRODUCTOS.precio * DETALLE_PEDIDO.cantidad) as Total
        from REPARTIDORES
        inner join PERSONAS on REPARTIDORES.id_persona = PERSONAS.id_persona
        inner join PEDIDOS on PEDIDOS.id_repartidor = REPARTIDORES.id_repartidor
        inner join DETALLE_PEDIDO on PEDIDOS.id_pedido = DETALLE_PEDIDO.id_pedido
        inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
        inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
        where year(PEDIDOS.f_entrega) = p_Año and PEDIDOS.estado_pedido = 'entregado'
        group by (Nombre_repartidor);
    end if;
    
    if p_Mes is not null and p_Año is not null and p_nombre_repartidor is null and fecha_requerida is null then
		select PERSONAS.nombre as Nombre_repartidor, sum(PRODUCTOS.precio * DETALLE_PEDIDO.cantidad) as Total
        from REPARTIDORES
        inner join PERSONAS on REPARTIDORES.id_persona = PERSONAS.id_persona
        inner join PEDIDOS on PEDIDOS.id_repartidor = REPARTIDORES.id_repartidor
        inner join DETALLE_PEDIDO on PEDIDOS.id_pedido = DETALLE_PEDIDO.id_pedido
        inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
        inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
        where month(PEDIDOS.f_entrega) = p_Mes and year(PEDIDOS.f_entrega) = p_Año and PEDIDOS.estado_pedido = 'entregado'
        group by (Nombre_repartidor);
    end if;
    
    if p_Mes is null and p_Año is null and p_nombre_repartidor is not null and fecha_requerida is null then
		select PERSONAS.nombre as Nombre_repartidor, sum(PRODUCTOS.precio * DETALLE_PEDIDO.cantidad) as Total
        from REPARTIDORES
        inner join PERSONAS on REPARTIDORES.id_persona = PERSONAS.id_persona
        inner join PEDIDOS on PEDIDOS.id_repartidor = REPARTIDORES.id_repartidor
        inner join DETALLE_PEDIDO on PEDIDOS.id_pedido = DETALLE_PEDIDO.id_pedido
        inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
        inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
        where REPARTIDORES.id_repartidor = p_nombre_repartidor and PEDIDOS.estado_pedido = 'entregado';
    end if;
    
    if p_Mes is not null and p_Año is null and p_nombre_repartidor is not null and fecha_requerida is null then
		select PERSONAS.nombre as Nombre_repartidor, sum(PRODUCTOS.precio * DETALLE_PEDIDO.cantidad) as Total
        from REPARTIDORES
        inner join PERSONAS on REPARTIDORES.id_persona = PERSONAS.id_persona
        inner join PEDIDOS on PEDIDOS.id_repartidor = REPARTIDORES.id_repartidor
        inner join DETALLE_PEDIDO on PEDIDOS.id_pedido = DETALLE_PEDIDO.id_pedido
        inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
        inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
        where month(PEDIDOS.f_entrega) = p_Mes and REPARTIDORES.id_repartidor = p_nombre_repartidor and PEDIDOS.estado_pedido = 'entregado';
    end if;
    
    if p_Mes is null and p_Año is not null and p_nombre_repartidor is not null and fecha_requerida is null then
		select PERSONAS.nombre as Nombre_repartidor, sum(PRODUCTOS.precio * DETALLE_PEDIDO.cantidad) as Total
        from REPARTIDORES
        inner join PERSONAS on REPARTIDORES.id_persona = PERSONAS.id_persona
        inner join PEDIDOS on PEDIDOS.id_repartidor = REPARTIDORES.id_repartidor
        inner join DETALLE_PEDIDO on PEDIDOS.id_pedido = DETALLE_PEDIDO.id_pedido
        inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
        inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
        where year(PEDIDOS.f_entrega) = p_Año and REPARTIDORES.id_repartidor = p_nombre_repartidor and PEDIDOS.estado_pedido = 'entregado';
    end if;
    
    if p_Mes is not null and p_Año is not null and p_nombre_repartidor is not null and fecha_requerida is null then
		select PERSONAS.nombre as Nombre_repartidor, sum(PRODUCTOS.precio * DETALLE_PEDIDO.cantidad) as Total
        from REPARTIDORES
        inner join PERSONAS on REPARTIDORES.id_persona = PERSONAS.id_persona
        inner join PEDIDOS on PEDIDOS.id_repartidor = REPARTIDORES.id_repartidor
        inner join DETALLE_PEDIDO on PEDIDOS.id_pedido = DETALLE_PEDIDO.id_pedido
        inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
        inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
        where month(PEDIDOS.f_entrega) = p_Mes and year(PEDIDOS.f_entrega) = p_Año and REPARTIDORES.id_repartidor = p_nombre_repartidor and PEDIDOS.estado_pedido = 'entregado';
    end if;
    
    if p_Mes is null and p_Año is null and p_nombre_repartidor is null and fecha_requerida is null then
	select PERSONAS.nombre as Nombre_repartidor, sum(PRODUCTOS.precio * DETALLE_PEDIDO.cantidad) as Total
        from REPARTIDORES
        inner join PERSONAS on REPARTIDORES.id_persona = PERSONAS.id_persona
        inner join PEDIDOS on PEDIDOS.id_repartidor = REPARTIDORES.id_repartidor
        inner join DETALLE_PEDIDO on PEDIDOS.id_pedido = DETALLE_PEDIDO.id_pedido
        inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
        inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
        where PEDIDOS.estado_pedido = 'entregado'
        group by (Nombre_repartidor);
    end if;
if fecha_requerida is not null and p_Mes is null and p_Año is null and p_nombre_repartidor IS NULL then
         select PERSONAS.nombre as Nombre_repartidor, sum(PRODUCTOS.precio * DETALLE_PEDIDO.cantidad) as Total
        from REPARTIDORES
        inner join PERSONAS on REPARTIDORES.id_persona = PERSONAS.id_persona
        inner join PEDIDOS on PEDIDOS.id_repartidor = REPARTIDORES.id_repartidor
        inner join DETALLE_PEDIDO on PEDIDOS.id_pedido = DETALLE_PEDIDO.id_pedido
        inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
        inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
        where PEDIDOS.estado_pedido= 'entregado' and date(PEDIDOS.f_entrega)=fecha_requerida
        group by Nombre_repartidor;
        end if ;
        
         if fecha_requerida is not null and p_mes is null and p_año is null and p_nombre_repartidor IS NOT NULL then
		select PERSONAS.nombre as Nombre_repartidor, sum(PRODUCTOS.precio * DETALLE_PEDIDO.cantidad) as Total
        from REPARTIDORES
        inner join PERSONAS on REPARTIDORES.id_persona = PERSONAS.id_persona
        inner join PEDIDOS on PEDIDOS.id_repartidor = REPARTIDORES.id_repartidor
        inner join DETALLE_PEDIDO on PEDIDOS.id_pedido = DETALLE_PEDIDO.id_pedido
        inner join INVENTARIO on DETALLE_PEDIDO.id_inventario = INVENTARIO.id_inventario
        inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
        where PEDIDOS.estado_pedido= 'entregado' and date(PEDIDOS.f_entrega)=fecha_requerida and REPARTIDORES.id_repartidor= p_nombre_repartidor;
        end if ;
end //
DELIMITER ;

------  * 3.5.1  AÑADIR STOCK--------------------------------------------------
DROP procedure AÑADIR_STOCK
DELIMITER //
create procedure AÑADIR_STOCK (
in iddproducto int, -- id del producto
IN n_stock int -- cantidad de stock que desea añadir
)
begin
update inventario
set stock = n_stock -- sumarle inventario
where id_producto = iddproducto; -- donde el id de la consulta sea igual a la id del producto en la tabla inventario
end //
DELIMITER ;

drop PROCEDURE INSERTAR_ADMINISTRADORES
------ * 3.6.1 INSERTAR NUEVO ADMIN--------------------------------------------
DELIMITER //
CREATE PROCEDURE INSERTAR_ADMINISTRADORES(
IN N_username VARCHAR(150),    -- varibales de entrada para el registro 
IN N_contraseña VARCHAR(255),
IN N_nombre VARCHAR(40),
IN N_a_p VARCHAR(40),
IN N_a_m VARCHAR(40),
IN N_f_nac DATE,
IN N_genero char(1),
IN N_telefono CHAR(10),
OUT message text
)
BEGIN
declare ultimaid_usuario int; -- declaramos algunas variables para guardar la id de algunas cosas
declare ultimaid_persona int;
declare ultimaid_admin int;
declare userrepetido int;
IF N_username = '' or N_contraseña = '' or N_nombre = '' or N_a_p = '' or N_f_nac = '0000-00-00' or 
N_genero = '0' or N_telefono = ''   -- si cualquiera de los campos son nulos entonces mandara un mensaje de error
then 
set message = 'NO PUEDES DEJAR CAMPOS VACIOS';
else
select count(USUARIOS.username) into userrepetido from USUARIOS where username = N_username ;
if userrepetido > 0 then
set message = 'USUARIO EXISTENTE';
else
if length(N_telefono)<10 then
set message='Numero de telefono invalido';
else
IF N_f_nac <= DATE_ADD(CURDATE(), INTERVAL -18 YEAR)
AND N_f_nac >= DATE_ADD(CURDATE(), INTERVAL -100 YEAR)
then
insert into USUARIOS (username, contrasena, f_registro)  -- insertar en usuarios los datos
values (N_username, N_contraseña,now());
set ultimaid_usuario = last_insert_id(); -- guardar la ultima id que se genero con el auto-increment
insert into ROL_USUARIO (id_rol,id_usuario) -- inmediatamente en la tabla de rol se le dara el rol de administrador
values(1,ultimaid_usuario);
insert into PERSONAS(nombre, a_p, a_m, f_nac, genero, telefono,id_usuario) -- insertar en la tabla de personas todos los datos personales
VALUES (N_nombre, N_a_p, N_a_m, N_f_nac, N_genero, N_telefono,ultimaid_usuario);
set ultimaid_persona = last_insert_id(); -- guardar la ultima id de persona generada con el autoincrement 
insert into ADMINISTRADORES(id_persona,fecha_cargo,estatus) values(ultimaid_persona,date(now()),1);
set message ='REGISTRO EXITOSO';
ELSE
SET message = 'DEBES SER MAYOR DE EDAD EL ADMIN PARA REGISTRARLO O LA EDAD ES EXCESIVA';
END IF;
end if;
end if;
end if;
END //
DELIMITER ;

-------- * 3.6.2 VER ADMINS POR ESTADO (HABILITADO/DESHABILITADO)-------------
DELIMITER //
create procedure Ver_Administrador_Estado(
	in p_estatus boolean
)
begin
	if p_estatus is null then 
		select ADMINISTRADORES.id_admin as ID, USUARIOS.username as Usuario,concat(PERSONAS.nombre, ' ', PERSONAS.a_p, ' ', PERSONAS.a_m) as Administrador, PERSONAS.f_nac as Fecha_nacimiento, PERSONAS.genero as Genero, PERSONAS.telefono as Telefono, ADMINISTRADORES.fecha_cargo as Fecha_cargo, ADMINISTRADORES.estatus as Estatus
        from ADMINISTRADORES
        inner join PERSONAS on ADMINISTRADORES.id_persona = PERSONAS.id_persona
        inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario;
    end if;
    if p_estatus = 0 then
		select ADMINISTRADORES.id_admin as ID, USUARIOS.username as Usuario, concat(PERSONAS.nombre, ' ', PERSONAS.a_p, ' ', PERSONAS.a_m) as Administrador, PERSONAS.f_nac as Fecha_nacimiento, PERSONAS.genero as Genero, PERSONAS.telefono as Telefono, ADMINISTRADORES.fecha_cargo as Fecha_cargo, ADMINISTRADORES.estatus as Estatus
        from ADMINISTRADORES
        inner join PERSONAS on ADMINISTRADORES.id_persona = PERSONAS.id_persona
        inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
        where ADMINISTRADORES.estatus = 0;
    end if;
    if p_estatus = 1 then
		select ADMINISTRADORES.id_admin as ID, USUARIOS.username as Usuario, concat(PERSONAS.nombre, ' ', PERSONAS.a_p, ' ', PERSONAS.a_m) as Administrador, PERSONAS.f_nac as Fecha_nacimiento, PERSONAS.genero as Genero, PERSONAS.telefono as Telefono, ADMINISTRADORES.fecha_cargo as Fecha_cargo, ADMINISTRADORES.estatus as Estatus
        from ADMINISTRADORES
        inner join PERSONAS on ADMINISTRADORES.id_persona = PERSONAS.id_persona
        inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
        where ADMINISTRADORES.estatus = 1;
    end if;
    
end //
DELIMITER ;

-- * 3.6.3 Procedimiento. Habilitar o Deshabilitar Administrador
DELIMITER //
create procedure Habilitar_Deshabilitar_Administrador(
	in p_id_administrador int,
    in p_nuevo_estatus boolean,
    out message text
)
begin
if p_nuevo_estatus = 0 then
	update ADMINISTRADORES
    set estatus = p_nuevo_estatus
    where id_admin = p_id_administrador;
    set message = 'Administrador Deshabilitado Exitosamente!';
    end if;
    if p_nuevo_estatus = 1 then
	update ADMINISTRADORES
    set estatus = p_nuevo_estatus
    where id_admin = p_id_administrador;
    set message = 'Administrador Habilitado Exitosamente!';
    end if;
end //
DELIMITER ;


-- * 3.2.1 Procedimiento. Consulta de tiendas filtrado por nombre de tienda.
DELIMITER //
create procedure Ver_Tiendas ()
begin
    
	-- seleccionar información de todas las tiendas
select TIENDAS.id_tienda as ID, TIENDAS.nombre_tienda as Tienda, TIENDAS.direccion as Dirección, PERSONAS.nombre as Propietario, PERSONAS.telefono as Telefono
from TIENDAS
join CLIENTE_TIENDA on TIENDAS.id_tienda = CLIENTE_TIENDA.id_tienda
join CLIENTES on CLIENTES.id_cliente = CLIENTE_TIENDA.id_cliente
join PERSONAS on CLIENTES.id_persona = PERSONAS.id_persona;
    
end //
DELIMITER ;


drop PROCEDURE INSERTAR_REPARTIDORES;
DELIMITER //
create PROCEDURE INSERTAR_REPARTIDORES (
IN N_username VARCHAR(150),    -- varibales de entrada para el registro del nuevo administrador
IN N_contraseña VARCHAR(255),
IN N_nombre VARCHAR(40),
IN N_a_p VARCHAR(40),
IN N_a_m VARCHAR(40),
IN N_f_nac DATE,
IN N_genero char(1),
IN N_telefono CHAR(10),
IN N_folioconducir CHAR(11),
IN f_ingreso date,
out mensaje text
)
BEGIN
declare ultimaid_usuario int; -- declaramos algunas variables para guardar la id de algunas cosas
declare ultimaid_persona int;
declare ultimaid_repartidor int;
declare userepetido int;
declare licrepe int;
IF N_username = '' or N_contraseña = ''  or N_nombre = ''  or N_a_p = '' or N_f_nac = '0000-00-00'  or 
N_genero = '' or N_telefono = ''  or N_folioconducir = '' or f_ingreso = ''  -- si cualquiera de los campos son nulos entonces mandara un mensaje de error
then 

set mensaje = 'No puedes dejar algún campo vacío';
else

if N_f_nac = '0000-00-00' then 

set mensaje = 'Ingresa la fecha de nacimiento';

else 

select count(USUARIOS.username) into userepetido from USUARIOS where USUARIOS.username=N_username;
if userepetido>0 then

set mensaje='Ya Existe un usuario con ese nombre';
else
select count(REPARTIDORES.fol_liconducir) into licrepe from REPARTIDORES where REPARTIDORES.fol_liconducir=N_folioconducir;
if licrepe>0 then

set mensaje='Ya Esta Registrada esa licencia de conducir, por favor ingresa otra';
else
if length(N_telefono)<10 then

set mensaje='Numero de telefono invalido';
else
if length(N_folioconducir)<11 then

set mensaje='Folio de licencia de conducir invalido';
else
if f_ingreso<=DATE_ADD(CURDATE(), INTERVAL -50 YEAR) or f_ingreso>DATE_ADD(date(now()), INTERVAL 1 year) then

set mensaje='Ingresa una fecha valida';
else
IF N_f_nac <= DATE_ADD(CURDATE(), INTERVAL -18 YEAR)
AND N_f_nac >= DATE_ADD(CURDATE(), INTERVAL -80 YEAR) -- si la fecha de nacimineto es menor que la fecha que se genere de hoy menos 18 años
then

insert into USUARIOS (username, contrasena, f_registro)  -- insertar en usuarios los datos
values (N_username, N_contraseña,now());
set ultimaid_usuario = last_insert_id(); -- guardar la ultima id que se genero con el auto-increment
insert into ROL_USUARIO (id_rol,id_usuario) -- inmediatamente en la tabla de rol se le dara el rol de administrador
values(3,ultimaid_usuario);
insert into PERSONAS(nombre, a_p, a_m, f_nac, genero, telefono,id_usuario) -- insertar en la tabla de personas todos los datos personales
values (N_nombre, N_a_p, N_a_m, N_f_nac, N_genero, N_telefono,ultimaid_usuario);
set ultimaid_persona = last_insert_id(); -- guardar la ultima id de persona generada con el autoincrement 
insert into REPARTIDORES(f_ingreso,fol_liconducir,id_persona,estatus) -- inserta en la tabla administradores
values(f_ingreso,N_folioconducir,ultimaid_persona,1);
set mensaje='Registro exitoso.';
else

set mensaje = 'Debes ser mayor de edad el repartidor para registrarlo o la fecha que ingresaste es excesiva.';
end if;
end if;
end if;
end if;
end if;
end if;
end if;
end if;
end //
DELIMITER ;




drop PROCEDURE INSERTAR_REPARTIDORES
DELIMITER //
create PROCEDURE INSERTAR_REPARTIDORES (
IN N_username VARCHAR(150),    -- varibales de entrada para el registro del nuevo administrador
IN N_contraseña VARCHAR(255),
IN N_nombre VARCHAR(40),
IN N_a_p VARCHAR(40),
IN N_a_m VARCHAR(40),
IN N_f_nac DATE,
IN N_genero ENUM('M','F','O'),
IN N_telefono CHAR(10),
IN N_folioconducir CHAR(11),
IN f_ingreso date,
out mensaje text
)
BEGIN
declare ultimaid_usuario int; -- declaramos algunas variables para guardar la id de algunas cosas
declare ultimaid_persona int;
declare ultimaid_repartidor int;
declare userepetido int;
declare licrepe int;

IF N_username = '' or N_contraseña=''  or N_nombre=''  or N_a_p='' or N_f_nac='0000-00-00'  or 
N_genero='0' or N_telefono=''  or N_folioconducir='' or f_ingreso='0000-00-00'  -- si cualquiera de los campos son nulos entonces mandara un mensaje de error
then 

set mensaje = 'NO PUEDES DEJAR ALGUN CAMPO VACIO';
else
select count(USUARIOS.username) into userepetido from USUARIOS where USUARIOS.username=N_username;
if userepetido>0 then
set mensaje='Ya Existe un usuario con ese nombre';
else
select count(REPARTIDORES.fol_liconducir) into licrepe from REPARTIDORES where REPARTIDORES.fol_liconducir=N_folioconducir;
if licrepe>0 then
set mensaje='Ya Esta Registrada esa licencia de conducir, por favor ingresa otra';
else
if length(N_telefono)<10 then
set mensaje='Numero de telefono invalido';
else
if length(N_folioconducir)<11 then
set mensaje='Folio de licencia de conducir invalido';
else
if f_ingreso<=DATE_ADD(CURDATE(), INTERVAL -50 YEAR) or f_ingreso>DATE_ADD(date(now()), INTERVAL 1 year) then
set mensaje='Ingresa una fecha valida';
else
IF N_f_nac <= DATE_ADD(CURDATE(), INTERVAL -18 YEAR)
AND N_f_nac >= DATE_ADD(CURDATE(), INTERVAL -80 YEAR) -- si la fecha de nacimineto es menor que la fecha que se genere de hoy menos 18 años
then
insert into USUARIOS (username, contrasena, f_registro)  -- insertar en usuarios los datos
values (N_username, N_contraseña,now());
set ultimaid_usuario = last_insert_id(); -- guardar la ultima id que se genero con el auto-increment
insert into ROL_USUARIO (id_rol,id_usuario) -- inmediatamente en la tabla de rol se le dara el rol de administrador
values(3,ultimaid_usuario);
insert into PERSONAS(nombre, a_p, a_m, f_nac, genero, telefono,id_usuario) -- insertar en la tabla de personas todos los datos personales
values (N_nombre, N_a_p, N_a_m, N_f_nac, N_genero, N_telefono,ultimaid_usuario);
set ultimaid_persona = last_insert_id(); -- guardar la ultima id de persona generada con el autoincrement 
insert into REPARTIDORES(f_ingreso,fol_liconducir,id_persona,estatus) -- inserta en la tabla administradores
values(f_ingreso,N_folioconducir,ultimaid_persona,1);
set mensaje='REGISTRO EXITOSO';
else
set mensaje = 'DEBES SER MAYOR DE EDAD EL REPARTIDOR PARA REGISTRARLO O LA FECHA QUE INGRESASTE ES EXCESIVA';
end if;
end if;
end if;
end if;
end if;
end if;
end if;
end //
DELIMITER ;



-- 4.2.1 Consulta de la informacion de determinado Repartidor 
call Ver_Informacion_Repartidor(2);
drop PROCEDURE Ver_Informacion_Repartidor
DELIMITER //
CREATE PROCEDURE Ver_Informacion_Repartidor(
IN p_id_usuario INT
)
BEGIN
SELECT 
USUARIOS.username AS Usuario,
CONCAT(PERSONAS.nombre, ' ', PERSONAS.a_p, ' ', PERSONAS.a_m) AS Nombre,
PERSONAS.f_nac AS Fecha_nacimiento, 
PERSONAS.genero AS Genero, 
PERSONAS.telefono AS Telefono, 
DATE(USUARIOS.f_registro) AS Fecha_registro,  
REPARTIDORES.fol_liconducir AS Licencia_conducir, 
REPARTIDORES.f_ingreso AS Fecha_Ingreso 
FROM 
REPARTIDORES
INNER JOIN 
PERSONAS ON REPARTIDORES.id_persona = PERSONAS.id_persona
INNER JOIN 
USUARIOS ON PERSONAS.id_usuario = USUARIOS.id_usuario
WHERE 
USUARIOS.id_usuario = p_id_usuario;
END //

DELIMITER ;



drop procedure Actualizar_Informacion_Repartidor
-- * 4.2.2 ACTUALIZAR INFORMACIÓN REPARTIDOR
DELIMITER //
create procedure Actualizar_Informacion_Repartidor(
in p_id_usuario int,
in n_nom varchar(40),
in n_ap varchar(40),
in n_am varchar(40),
in n_tel char(10),
in p_fol_liconducir char(11),
out message text
)
begin
declare mod_nom boolean;
declare mod_ap boolean;
declare mod_am boolean;
declare mod_fol boolean;
declare mod_tel boolean;
declare msj_b text;
declare msj_m text;
declare contB int;
declare contM int;

set msj_b='';
set msj_m='';
set contB = 0;
set contM = 0;
set mod_nom = 0;
set mod_ap = 0 ;
set mod_am = 0;
set mod_fol = 0;
set mod_tel = 0 ;

Select true into mod_nom
from PERSONAS
inner join USUARIOS ON PERSONAS.id_usuario = USUARIOS.id_usuario
where PERSONAS.nombre = n_nom
and USUARIOS.id_usuario =  p_id_usuario;

Select true into mod_ap
from PERSONAS
inner join USUARIOS ON PERSONAS.id_usuario = USUARIOS.id_usuario
where PERSONAS.a_p = n_ap
and USUARIOS.id_usuario =  p_id_usuario;

Select true into mod_am
from PERSONAS
inner join USUARIOS ON PERSONAS.id_usuario = USUARIOS.id_usuario
where PERSONAS.a_m = n_am
and USUARIOS.id_usuario =  p_id_usuario;

Select true into mod_tel
from PERSONAS
inner join USUARIOS ON PERSONAS.id_usuario = USUARIOS.id_usuario
where PERSONAS.telefono = n_tel
and USUARIOS.id_usuario =  p_id_usuario;

Select true into mod_fol
from REPARTIDORES
inner join PERSONAS on REPARTIDORES.id_persona = PERSONAS.id_persona
inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
where REPARTIDORES.fol_liconducir = p_fol_liconducir
and USUARIOS.id_usuario =  p_id_usuario;

if  mod_nom = 1 and mod_ap = 1 and mod_am = 1 and mod_fol = 1 and mod_tel = 1 then

set message = 'No se hizo ningún cambio';

else 
if n_nom = '' then

set msj_m = 'No puedes dejar el nombre vacío';
set contM = contM + 1; 

end if;
if n_ap = '' then

if contM = 0 then

set msj_m = 'No puedes dejar el apellido vacío';

elseif contM = 1 then

set msj_m = concat(msj_m, ' Ni tampoco el Apellido Paterno');

elseif contM > 1 then

set msj_m = concat(msj_m, 'Apelldo paterno');

end if;

set contM = contM + 1; 

end if;

if n_tel = '' then

if contM = 0 then

set msj_m = 'No puedes dejar el telefono vacío';

elseif contM = 1 then

set msj_m = concat(msj_m, ' Ni tampoco el Número de telefono');

elseif contM > 1 then

set msj_m = concat(msj_m, 'Número de telefono');

end if;

set contM = contM + 1; 

end if;

if length(n_tel) < 10 then

if contM = 0 then

set msj_m = 'Numero de telefono invalido';

elseif contM = 1 then

set msj_m = concat(msj_m, ' Ni tampoco el telefono');

elseif contM > 1 then

set msj_m = concat(msj_m, 'Número de telefono');

end if;

set contM = contM + 1; 

end if;

if p_fol_liconducir = '' then

if contM = 0 then

set msj_m = 'No puedes dejar el folio vacío';

elseif contM = 1 then

set msj_m = concat(msj_m, ' Ni tampoco el folio de conducir');

elseif contM > 1 then

set msj_m = concat(msj_m, 'Folio de conducir');

end if;

set contM = contM + 1; 

end if;

if length(p_fol_liconducir) < 11 then

if contM = 0 then

set msj_m = 'Folio de conducir invalido';

elseif contM = 1 then

set msj_m = concat(msj_m, ' Ni tampoco el folio de conducir');

elseif contM > 1 then

set msj_m = concat(msj_m, 'Folio de conducir');

end if;

set contM = contM + 1; 

end if;

if mod_nom = 0 then

if n_nom != '' then

update PERSONAS
inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
set PERSONAS.nombre = n_nom
where USUARIOS.id_usuario = p_id_usuario;

if contM = 0 then

set msj_b = 'Nombre atualizado exitosamente';

elseif contM > 0 then

set msj_b = ' Pero si se modifico el nombre';

end if;

set contB = contB + 1;

end if;
end if ;

if mod_ap = 0 then

iF n_ap != '' then

update PERSONAS
inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
set PERSONAS.a_p = n_ap
where USUARIOS.id_usuario = p_id_usuario;

if contM = 0 and contB = 0  then

set msj_b = 'Apellido Paterno actualizado exitosamente';

end if;

if contM > 0 and contB = 0  then

set msj_b = ' Pero si se modifico el apellido paterno';

elseif contM > 0 and contB = 1 then 

set msj_b = concat(msj_b, ' Apellido paterno');

end if;

set contB = contB + 1;

end if;
end if;
-- apellido materno --
if mod_am = 0 then

iF n_am != '' then

update PERSONAS
inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
set PERSONAS.a_m = n_am
where USUARIOS.id_usuario = p_id_usuario;

if contM = 0 and contB = 0  then

set msj_b = 'Apellido Materno actualizado exitosamente';

end if;

if contM > 0 and contB = 0  then

set msj_b = ' Pero si se modifico el apellido materno';

elseif contM > 0 and contB = 1 then 

set msj_b = concat(msj_b, ' Apellido materno');

end if;

set contB = contB + 1;

end if;
end if;
-- telefono --
if mod_tel = 0 then

iF n_tel != '' and length(n_tel) = 10 then

update PERSONAS
inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
set PERSONAS.telefono = n_tel
where USUARIOS.id_usuario = p_id_usuario;

if contM = 0 and contB = 0  then

set msj_b = 'Telefono actualizado exitosamente';

end if;

if contM > 0 and contB = 0  then

set msj_b = ' Pero si se modifico el telefono';

elseif contM > 0 and contB = 1 then 

set msj_b = concat(msj_b, ' Telefono');

end if;

set contB = contB + 1;

end if;
end if;
-- folio de conducior --
if mod_fol = 0 then

iF p_fol_liconducir != '' and  length(p_fol_liconducir) = 11 then

update REPARTIDORES
inner join PERSONAS on REPARTIDORES.id_persona = PERSONAS.id_persona
inner join USUARIOS ON PERSONAS.id_usuario = USUARIOS.id_usuario
set REPARTIDORES.fol_liconducir = p_fol_liconducir
where USUARIOS.id_usuario = p_id_usuario;

if contM = 0 and contB = 0  then

set msj_b = 'Folio actualizado exitosamente';

end if;

if contM > 0 and contB = 0  then

set msj_b = ' Pero si se modifico el folio de conducir';

elseif contM > 0 and contB = 1 then 

set msj_b = concat(msj_b, 'Folio de conducir');

end if;

set contB = contB + 1;

end if;
end if;

set message = concat (msj_m , msj_b);

end if;
end //
DELIMITER ;




DELIMITER //
create procedure BuscarUsuario(
    in p_nombre_usuario varchar(150),
    out mensaje text
)
begin

declare p_id_usuario int;
declare p_tipo_usuario int;
declare p_estatus boolean;

select ROL_USUARIO.id_rol, USUARIOS.id_usuario into p_tipo_usuario, p_id_usuario
from ROL_USUARIO
inner join USUARIOS on ROL_USUARIO.id_usuario = USUARIOS.id_usuario
where USUARIOS.username = p_nombre_usuario;

if p_tipo_usuario = 1 then

select ADMINISTRADORES.estatus into p_estatus
from USUARIOS
inner join PERSONAS on USUARIOS.id_usuario = PERSONAS.id_usuario
inner join ADMINISTRADORES on PERSONAS.id_persona = ADMINISTRADORES.id_persona
where USUARIOS.id_usuario = p_id_usuario;

end if;

if p_tipo_usuario = 3 then

select REPARTIDORES.estatus into p_estatus
from USUARIOS
inner join PERSONAS on USUARIOS.id_usuario = PERSONAS.id_usuario
inner join REPARTIDORES on PERSONAS.id_persona = REPARTIDORES.id_persona
where USUARIOS.id_usuario = p_id_usuario;

end if;

if p_estatus = 1 then

select USUARIOS.id_usuario as ID, ROL_USUARIO.id_rol as Rol, USUARIOS.contrasena as Contraseña
from USUARIOS
inner join ROL_USUARIO on USUARIOS.id_usuario = ROL_USUARIO.id_usuario
where USUARIOS.username = p_nombre_usuario;

else 

set mensaje = 'Este usuario esta desabilitado';
    
end if;

end //
DELIMITER ;


DELIMITER //
create procedure verificarEstadoCuenta(
in p_id_usuario int
)
begin
declare p_tipo_usuario int;

select ROL_USUARIO.id_rol into p_tipo_usuario
from USUARIOS
inner join ROL_USUARIO on USUARIOS.id_usuario = ROL_USUARIO.id_usuario
where USUARIOS.id_usuario = p_id_usuario;

if p_tipo_usuario = 1 then

select ADMINISTRADORES.estatus as Estatus
from ADMINISTRADORES
inner join PERSONAS on ADMINISTRADORES.id_persona = PERSONAS.id_persona
inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
where USUARIOS.id_usuario = p_id_usuario;

end if;

if p_tipo_usuario = 3 then 

select REPARTIDORES.estatus as Estatus
from REPARTIDORES
inner join PERSONAS on REPARTIDORES.id_persona = PERSONAS.id_persona
inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
where USUARIOS.id_usuario = p_id_usuario;

end if;
end //
DELIMITER ;