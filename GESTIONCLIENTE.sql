-- 1.3.1 ver mis pedidos
DELIMITER //
create procedure mispedidos_cliente(
	in iduser INT,
    in estado varchar(50)
)
begin
declare idcliente int;

select CLIENTES.id_cliente into idcliente 
from CLIENTES
inner join PERSONAS on CLIENTES.id_persona=PERSONAS.id_persona
inner join USUARIOS on PERSONAS.id_usuario=USUARIOS.id_usuario
where USUARIOS.id_usuario=iduser;

	if estado is not null then
		select PEDIDOS.id_pedido as ID, PEDIDOS.f_pedido as Fecha_Pedido, PEDIDOS.f_entrega as Fecha_entregada, PEDIDOS.f_requerido as Fecha_Requerida, PEDIDOS.estado_pedido as Estado, R.Nombre as Repartidor
		from PEDIDOS
		inner join (
		select PEDIDOS.id_pedido as ID, PERSONAS.nombre as Nombre
		from PEDIDOS
		left join REPARTIDORES on PEDIDOS.id_repartidor = REPARTIDORES.id_repartidor
		left join PERSONAS on REPARTIDORES.id_persona = PERSONAS.id_persona) as R on PEDIDOS.id_pedido = R.ID
		where PEDIDOS.id_cliente = idcliente and PEDIDOS.estado_pedido = estado 
		order by Fecha_Pedido desc;
	else 
		select PEDIDOS.id_pedido as ID, PEDIDOS.f_pedido as Fecha_Pedido, PEDIDOS.f_entrega as Fecha_entregada, PEDIDOS.f_requerido as Fecha_Requerida, PEDIDOS.estado_pedido as Estado, R.Nombre as Repartidor
		from PEDIDOS
		inner join (
		select PEDIDOS.id_pedido as ID, PERSONAS.nombre as Nombre
		from PEDIDOS
		left join REPARTIDORES on PEDIDOS.id_repartidor = REPARTIDORES.id_repartidor
		left join PERSONAS on REPARTIDORES.id_persona = PERSONAS.id_persona) as R on PEDIDOS.id_pedido = R.ID
		where PEDIDOS.id_cliente = idcliente 
		order by Fecha_Pedido desc;
    end if;
END //
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

-- * 1.4.4 dar de alta tiendas
DELIMITER //
CREATE PROCEDURE ALTA_TIENDAS( 
	in p_id_usuario int,
	in t_nombre varchar(50),
	in t_direccion varchar(100),
	out mensaje text
    
)
begin 
	declare ultimaidtienda int;
    declare p_id_cliente int;
    declare contadortienda int;
    

	select CLIENTES.id_cliente into p_id_cliente
	from CLIENTES
	inner join PERSONAS on CLIENTES.id_persona = PERSONAS.id_persona
	inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
	where USUARIOS.id_usuario = p_id_usuario;
    
    select  count(*) into contadortienda
    from CLIENTE_TIENDA
    where CLIENTE_TIENDA.id_cliente= p_id_cliente;
    
    if contadortienda>=5 then
		set mensaje='No puedes tener mas de 5 tiendas';
    
    else    
		if t_nombre='' or t_direccion='' then 
			set mensaje = 'NO PUEDES DEJAR ALGUN CAMPO VACIO';
			
		else
			insert into TIENDAS(nombre_tienda,direccion,estatus)values
			(t_nombre,t_direccion,true);
			
			set ultimaidtienda = last_insert_id();
			
			insert into CLIENTE_TIENDA (id_cliente,id_tienda)values
			(p_id_cliente,ultimaidtienda);
            set  mensaje='Registro Exitoso';
		
		end if;
    end if;
 end //
 DELIMITER ;
 
-- * 1.4.3 procedimiento para actualizar información de un usuario
DELIMITER //
create PROCEDURE actualizarinfo(
in id_u int,
in nombrenuev varchar(40),
in a_pnuev varchar(40),
in a_mnuev varchar(40),
in telefononuev char(10),
out mensaje text
)
begin 
	declare ModificacionNombre boolean;
    declare ModificacionA_p boolean;
    declare ModificacionA_m boolean;
    declare ModificacionTelefono boolean;
    
    declare mensajeError text;
    declare mensajeBueno text;
    
	declare contadorError int;
    declare contadorBueno int;
    
    set mensajeError = '';
    set mensajeBueno = '';
    
	set contadorError = 0;
    set contadorBueno = 0;
    
	set ModificacionNombre = 0;
    set ModificacionA_p = 0;
    set ModificacionA_m = 0;
    set ModificacionTelefono = 0;

	select true into ModificacionNombre 
    FROM PERSONAS
    inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
	where PERSONAS.nombre = nombrenuev and USUARIOS.id_usuario = id_u;
    
    select true into ModificacionA_p 
    FROM PERSONAS
    inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
	where PERSONAS.a_p = a_pnuev and USUARIOS.id_usuario = id_u;

    select true into ModificacionA_m 
    FROM PERSONAS
    inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
	where PERSONAS.a_m = a_mnuev and USUARIOS.id_usuario = id_u;
    
    select true into ModificacionTelefono 
    FROM PERSONAS
    inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
	where PERSONAS.telefono = telefononuev and USUARIOS.id_usuario = id_u;

	if ModificacionNombre = 1 and ModificacionA_p = 1 and ModificacionA_m = 1 and ModificacionTelefono = 1 then

		set mensaje = 'No se hizo ninguna modificacion';
	else 
		if nombrenuev = '' then

			set mensajeError = 'No puedes dejar el nombre vacio';
			set contadorError = contadorError + 1;
		end if;
        
        
        if a_pnuev = '' then

			if contadorError = 0 then 

				set mensajeError = 'No puedes dejar el apellido paterno vacio';

			elseif contadorError = 1 then

				set mensajeError = concat(mensajeError, ' ni tampoco apellido paterno');

			elseif contadorError > 1 then 

				set mensajeError = concat(mensajeError, ', apellido paterno');

			end if;

			set contadorError = contadorError + 1;

		end if;
        
        if telefononuev = ''  then

			if contadorError = 0 then 

				set mensajeError = 'No puedes dejar el numero de telefono vacio';

			elseif contadorError = 1 then 

				set mensajeError = concat(mensajeError, ' ni tampoco el numero de telefono');

			elseif contadorError > 1 then 

				set mensajeError = concat(mensajeError, ', numero de telefono');

			end if;

			set contadorError = contadorError + 1;

		end if;
        
        if length(telefononuev)<10 then

        		if contadorError = 0 then 

				set mensajeError = 'El numero de telefono no puede tener menos de 10 numeros';

			elseif contadorError = 1 then 

				set mensajeError = concat(mensajeError, ' numero de telefono invalido');

			elseif contadorError > 1 then 

				set mensajeError = concat(mensajeError , ', número de teléfono inválido');

			end if;

			set contadorError = contadorError + 1;

		end if;
        
        
		if ModificacionNombre = 0 then

			if nombrenuev != '' then

				update PERSONAS
				inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
				set PERSONAS.nombre = nombrenuev
				where USUARIOS.id_usuario=id_u;
				
                if contadorError = 0 then

					set mensajeBueno = 'Nombre actualizado corectamente';
                elseif contadorError > 0 then
					set mensajeBueno = ' pero si se modifico el nombre';
                end if;
                set contadorBueno = contadorBueno + 1;
			end if;
        end if;

		if ModificacionA_p = 0 then 

			if a_pnuev != '' then

				update PERSONAS
				inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
				set PERSONAS.a_p = a_pnuev
				where USUARIOS.id_usuario = id_u;

                if contadorError = 0 and contadorBueno = 0 then 
					set mensajeBueno = 'Apellido paterno actualizado corectamente';
				end if;
                
				if contadorError > 0 and contadorBueno = 0 then
					set mensajeBueno = ' pero si se modifico el apellido paterno';

				elseif contadorError > 0 and contadorBueno > 1 then
					set mensajeBueno = concat(mensajeBueno, ', apellido paterno');
				end if;
                set contadorBueno = contadorBueno + 1;
			end if;
        end if;
        
        if ModificacionA_m = 0 then 

			update PERSONAS
			inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
			set PERSONAS.a_m = a_mnuev
			where USUARIOS.id_usuario=id_u;
            
            if contadorError = 0 and contadorBueno = 0 then 

				set mensajeBueno = 'Apellido actualizado corectamente';
			end if;
            
			if contadorError > 0 and contadorBueno = 0 then

				set mensajeBueno = ' pero si se modifico el apellido materno';
			elseif contadorError > 0 and contadorBueno > 1 then

				set mensajeBueno = concat(mensajeBueno, ', apellido materno');
			end if;
            set contadorBueno = contadorBueno + 1;
        end if;
		
        if ModificacionTelefono = 0 then

			if telefononuev != ''  and length(telefononuev) = 10 then

				update PERSONAS
				inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
				set PERSONAS.telefono = telefononuev
				where USUARIOS.id_usuario = id_u;
                
                if contadorError = 0 and contadorBueno = 0 then 

					set mensajeBueno = 'Numero de telefono actualizado corectamente';
				end if;
                
                if contadorError > 0 and contadorBueno = 0 then

					set mensajeBueno = ' pero si se modifico el numero de telefono';
                elseif contadorError > 0 and contadorBueno > 1 then
					set mensajeBueno = concat(mensajeBueno, ', numero de telefono');
				end if;
                set contadorBueno = contadorBueno + 1;
			end if;

        end if;
        set mensaje = concat(mensajeError, mensajeBueno);
	end if;
end //
DELIMITER ;


-- * 1.4.6 Procedimiento para cambiar información de una tienda 
DELIMITER //
create procedure EDITAR_TIENDA(
   IN idtienda int,
   in nombrenuevo varchar(50),
   in direnueva varchar(100),
   out mensaje text
)
begin
declare modinombre boolean;
declare modidire boolean;

declare mensajeError text;
declare mensajeBueno text;

declare contadorError int;
declare contadorBueno int;

set mensajeError='';
set mensajeBueno='';

set contadorError=0;
set contadorBueno=0;

set modinombre=0;
set modidire=0;

select true into modinombre
from TIENDAS
where TIENDAS.nombre_tienda=nombrenuevo and TIENDAS.id_tienda=idtienda;

select true into modidire
from TIENDAS
where TIENDAS.direccion=direnueva and TIENDAS.id_tienda=idtienda;

if modinombre=1 and modidire=1 then
set mensaje ='No se hizo ninguna modificacion';
else
if nombrenuevo='' then
set mensajeError='No puedes dejar el nombre vacio';
set contadorError= contadorError+1;
end if;


if direnueva='' then
if contadorError=0 then
set mensajeError='No puedes dejar la direccion vacia';
elseif contadorError=1 then
set mensajeError= concat(mensajeError, ' ni tampoco la direccion');
elseif contadorError>1 then
set mensajeError= concat(mensajeError, ', direccion');
end if;
set contadorError=contadorError+1;
end if;

if length(direnueva)>100 then
if contadorError=0 then
set mensajeError='Maximo 100 caracteres ';
elseif contadorError=1 then
set mensajeError= concat(mensajeError, ' ni tampoco puedes poner mas de 100 caracteres en la direccion');
elseif contadorError>1 then
set mensajeError= concat(mensajeError, ', maximo 100 caracteres');
end if;
set contadorError=contadorError+1;
end if;

if modinombre=0 then
if nombrenuevo !='' then
update TIENDAS
set TIENDAS.nombre_tienda=nombrenuevo
where TIENDAS.id_tienda=idtienda;

if contadorError=0 then
set mensajeBueno='Nombre actualizado correctamente';
elseif contadorError>0 then
set mensajeBueno=' pero si se actualizo el nombre correctamente';
end if;
set contadorBueno=contadorBueno+1;
end if;
end if;

if modidire=0 then
if direnueva !=''  then
if length(direnueva)<=100 then
update TIENDAS
set TIENDAS.direccion=direnueva
where TIENDAS.id_tienda=idtienda;
if contadorError=0 and contadorBueno=0 then
set mensajeBueno='Direccion actualizada correctamente';
end if;

if contadorError>0 and contadorBueno=0 then
set mensajeBueno= ' pero si se modifico la direccion';
elseif contadorError>0 and contadorBueno>1 then
set mensajeBueno=concat(mensajeBueno, ', direccion');
end if;
set contadorBueno=contadorBueno+1;
end if;
end if;
end if;
set mensaje=concat(mensajeError,mensajeBueno );
end if;
end //
DELIMITER ;


-- * 1.4.2 Consulta de las tiendas de determinado cliente
DELIMITER //
create procedure Ver_Tiendas_Cliente(
	in p_id_usuario int,
    in p_activos boolean
)
begin
declare p_id_cliente int;

select CLIENTES.id_cliente into p_id_cliente
from CLIENTES
inner join PERSONAS on CLIENTES.id_persona = PERSONAS.id_persona
inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
where USUARIOS.id_usuario = p_id_usuario;
    
if p_activos = 0 then

select TIENDAS.id_tienda as ID, TIENDAS.nombre_tienda as Nombre, TIENDAS.direccion as Direccion, TIENDAS.estatus as Estatus
from TIENDAS
inner join CLIENTE_TIENDA on TIENDAS.id_tienda = CLIENTE_TIENDA.id_tienda
where CLIENTE_TIENDA.id_cliente = p_id_cliente;
    else 

select TIENDAS.id_tienda as ID, TIENDAS.nombre_tienda as Nombre, TIENDAS.direccion as Direccion, TIENDAS.estatus as Estatus
from TIENDAS
inner join CLIENTE_TIENDA on TIENDAS.id_tienda = CLIENTE_TIENDA.id_tienda
where CLIENTE_TIENDA.id_cliente = p_id_cliente and TIENDAS.estatus = 1;
end if;
end //
DELIMITER ;


 -- 1.4.5 cambiar estatus tienda
 DELIMITER //
create procedure Cambiar_Estatus_Tienda (
	in dtienda int,
    in estadoT boolean,
    out mensaje text
)
begin 
if estadoT =0 then
	update TIENDAS
    set estatus = estadoT
    where id_tienda =dtienda ;
    set mensaje='Tienda dada de baja exitosamente';
    end if ;
    if estadoT=1 then
	update TIENDAS
    set estatus = estadoT
    where id_tienda = dtienda;
    set mensaje='Tienda habilitada exitosamente';
    end if ;
    
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





-- * 1.1.4 Procedimiento. Ver Productos filtrado por Categoria y por Nombre de Producto en realizar pedido
DELIMITER //
create procedure Ver_Productos_Realizar_Pedido(
in p_id_usuario int,
in p_categoria int,
in p_nombre_producto varchar(50),
in p_offset int,
in p_records_per_page int
)
begin
declare p_id_cliente int;

select CLIENTES.id_cliente into p_id_cliente
from CLIENTES
inner join PERSONAS on CLIENTES.id_persona = PERSONAS.id_persona
inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
where USUARIOS.id_usuario = p_id_usuario;

if p_offset is null and p_records_per_page is null then

if p_categoria != 0 and p_nombre_producto is null then

select PRODUCTOS.id_producto as ID, PRODUCTOS.nombre as Nombre, PRODUCTOS.imagen as Imagen, PRODUCTOS.precio as Precio, PRODUCTOS.descripcion as Descripcion, INVENTARIO.stock as Disponible, case when CARRITO.id_cliente is not null then CARRITO.cantidad else 0 end as CantidadCarrito
from PRODUCTOS
inner join INVENTARIO on PRODUCTOS.id_producto = INVENTARIO.id_producto
left join CARRITO on PRODUCTOS.id_producto = CARRITO.id_inventario and CARRITO.id_cliente = p_id_cliente
where PRODUCTOS.estado = 1 and PRODUCTOS.id_categoria = p_categoria
order by ID;
end if;

if p_categoria = 0 and p_nombre_producto is not null then

select PRODUCTOS.id_producto as ID, PRODUCTOS.nombre as Nombre, PRODUCTOS.imagen as Imagen, PRODUCTOS.precio as Precio, PRODUCTOS.descripcion as Descripcion, INVENTARIO.stock as Disponible, case when CARRITO.id_cliente is not null then CARRITO.cantidad else 0 end as CantidadCarrito
from PRODUCTOS
inner join CATEGORIAS on PRODUCTOS.id_categoria = CATEGORIAS.id_categoria
inner join INVENTARIO on PRODUCTOS.id_producto = INVENTARIO.id_producto
left join CARRITO on PRODUCTOS.id_producto = CARRITO.id_inventario and CARRITO.id_cliente = p_id_cliente
where PRODUCTOS.estado = 1 and PRODUCTOS.nombre = p_nombre_producto and CATEGORIAS.estado = 1
order by ID;
end if;

if p_categoria = 0 and p_nombre_producto is null then

select PRODUCTOS.id_producto as ID, PRODUCTOS.nombre as Nombre, PRODUCTOS.imagen as Imagen, PRODUCTOS.precio as Precio, PRODUCTOS.descripcion as Descripcion, INVENTARIO.stock as Disponible, case when CARRITO.id_cliente is not null then CARRITO.cantidad else 0 end as CantidadCarrito
from PRODUCTOS
inner join CATEGORIAS on PRODUCTOS.id_categoria = CATEGORIAS.id_categoria
inner join INVENTARIO on PRODUCTOS.id_producto = INVENTARIO.id_producto
left join CARRITO on PRODUCTOS.id_producto = CARRITO.id_inventario and CARRITO.id_cliente = p_id_cliente
where PRODUCTOS.estado = 1 and CATEGORIAS.estado = 1
order by ID;
end if;

else 

if p_categoria != 0 and p_nombre_producto is null then

select PRODUCTOS.id_producto as ID, PRODUCTOS.nombre as Nombre, PRODUCTOS.imagen as Imagen, PRODUCTOS.precio as Precio, PRODUCTOS.descripcion as Descripcion, INVENTARIO.stock as Disponible, case when CARRITO.id_cliente is not null then CARRITO.cantidad else 0 end as CantidadCarrito
from PRODUCTOS
inner join INVENTARIO on PRODUCTOS.id_producto = INVENTARIO.id_producto
left join CARRITO on PRODUCTOS.id_producto = CARRITO.id_inventario and CARRITO.id_cliente = p_id_cliente
where PRODUCTOS.estado = 1 and PRODUCTOS.id_categoria = p_categoria
order by ID
limit p_records_per_page
offset p_offset;
end if;

if p_categoria = 0 and p_nombre_producto is not null then

select PRODUCTOS.id_producto as ID, PRODUCTOS.nombre as Nombre, PRODUCTOS.imagen as Imagen, PRODUCTOS.precio as Precio, PRODUCTOS.descripcion as Descripcion, INVENTARIO.stock as Disponible, case when CARRITO.id_cliente is not null then CARRITO.cantidad else 0 end as CantidadCarrito
from PRODUCTOS
inner join CATEGORIAS on PRODUCTOS.id_categoria = CATEGORIAS.id_categoria
inner join INVENTARIO on PRODUCTOS.id_producto = INVENTARIO.id_producto
left join CARRITO on PRODUCTOS.id_producto = CARRITO.id_inventario and CARRITO.id_cliente = p_id_cliente
where PRODUCTOS.estado = 1 and PRODUCTOS.nombre = p_nombre_producto and CATEGORIAS.estado = 1
order by ID
limit p_records_per_page
offset p_offset;
end if;
		
if p_categoria = 0 and p_nombre_producto is null then

select PRODUCTOS.id_producto as ID, PRODUCTOS.nombre as Nombre, PRODUCTOS.imagen as Imagen, PRODUCTOS.precio as Precio, PRODUCTOS.descripcion as Descripcion, INVENTARIO.stock as Disponible, case when CARRITO.id_cliente is not null then CARRITO.cantidad else 0 end as CantidadCarrito
from PRODUCTOS
inner join CATEGORIAS on PRODUCTOS.id_categoria = CATEGORIAS.id_categoria
inner join INVENTARIO on PRODUCTOS.id_producto = INVENTARIO.id_producto
left join CARRITO on PRODUCTOS.id_producto = CARRITO.id_inventario and CARRITO.id_cliente = p_id_cliente
where PRODUCTOS.estado = 1 and CATEGORIAS.estado = 1
order by ID
limit p_records_per_page
offset p_offset;
end if;
end if;
end //
DELIMITER ;

-- 1.2.9 Cancelar todo el carrito 
DELIMITER //
create procedure Cancelar_Todo_Carrito(
	in p_id_usuario int -- Variable para saber cual cliente es el carrito 
)
begin 
	declare p_id_cliente int;
    
	select CLIENTES.id_cliente into p_id_cliente 
    from CLIENTES inner join PERSONAS on CLIENTES.id_persona = PERSONAS.id_persona
	inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
	where USUARIOS.id_usuario = p_id_usuario;

	delete from CARRITO 
	where id_cliente = p_id_cliente; -- Borra todo los registros del carrito del cliente seleccionado 
end //
DELIMITER ;

-- 1.2.6 Consulta para ver fechas de los pedidos echos por un cliente dependiendo de la tienda seleccionado 
DELIMITER //
create procedure Ver_Fechas_Pedidos_Cliente(
	in p_id_usuario int,
    in p_id_tienda int 
)
begin
	declare p_id_cliente int;

	select CLIENTES.id_cliente into p_id_cliente
	from CLIENTES
	inner join PERSONAS on CLIENTES.id_persona = PERSONAS.id_persona
	inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
	where USUARIOS.id_usuario = p_id_usuario;
    
    if p_id_tienda is not null then 
		select f_requerido as Fecha
		from PEDIDOS
		where id_cliente = p_id_cliente and id_tiendas = p_id_tienda and f_requerido >= date(now());
	else
		select f_requerido as Fecha
		from PEDIDOS
		where id_cliente = p_id_cliente and id_tiendas is null and f_requerido >= date(now());
    end if;

end //
DELIMITER ;

-- 1.2.8 Insertar o Modificar un producto en el Carrito
DELIMITER //
create procedure Insertar_Modificar_Carrito(
in p_id_usuario int,
in p_id_producto int,
in p_cantidad int,
in p_verificarStock boolean
)
begin
declare p_id_cliente int;
declare p_total int;

select CLIENTES.id_cliente into p_id_cliente
from CLIENTES
inner join PERSONAS on CLIENTES.id_persona = PERSONAS.id_persona
inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
where USUARIOS.id_usuario = p_id_usuario;

select count(*) into p_total
from CARRITO
where CARRITO.id_cliente = p_id_cliente and CARRITO.id_inventario = p_id_producto;

if p_total > 0 then
call Modificar_Carrito(p_id_cliente, p_id_producto, p_cantidad, p_verificarStock);
else
call Insertar_Producto_Carrito(p_id_cliente, p_id_producto, p_cantidad, p_verificarStock);
end if;
end //
DELIMITER ;

-- * 1.2.1 Cancelar un Producto de Carrito
DELIMITER //
create procedure Cancelar_Carrito(
	in p_id_usuario int, -- Variable para saber cual cliente es el carrito 
    in p_id_producto int -- Variable para saber cual producto del carrito se va a cancelar
)
begin 
	declare p_id_cliente int;
    
	select CLIENTES.id_cliente into p_id_cliente 
    from CLIENTES inner join PERSONAS on CLIENTES.id_persona = PERSONAS.id_persona
	inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
	where USUARIOS.id_usuario = p_id_usuario;

	delete from CARRITO 
	where id_cliente = p_id_cliente and id_inventario = p_id_producto; -- Borra todo los registros del carrito del cliente seleccionado 
end //
DELIMITER ;

-- 1.2.7 Calcular el total a pagar del carrito
DELIMITER //
create procedure Calcular_Total(
	in p_id_usuario int,
    in p_tienda_seleccionado int
)
begin 
	declare p_id_cliente int;
    
    select CLIENTES.id_cliente into p_id_cliente 
	from CLIENTES
	inner join PERSONAS on CLIENTES.id_persona=PERSONAS.id_persona
	inner join USUARIOS on PERSONAS.id_usuario=USUARIOS.id_usuario
	where USUARIOS.id_usuario = p_id_usuario;
 
	if p_tienda_seleccionado = 0 then

		select sum(CARRITO.cantidad * PRODUCTOS.precio) as Total
        from CARRITO
        inner join INVENTARIO on CARRITO.id_inventario = INVENTARIO.id_inventario
        inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
        where CARRITO.id_cliente = p_id_cliente;
    else

		select sum(CARRITO.cantidad * (PRODUCTOS.precio - 1 )) as Total
        from CARRITO
        inner join INVENTARIO on CARRITO.id_inventario = INVENTARIO.id_inventario
        inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
        where CARRITO.id_cliente = p_id_cliente;

    end if;
end //
DELIMITER ;

-- * 1.2.5 Procedimiento Ver Carrito
DELIMITER //
create procedure Ver_Carrito(
	in p_id_usuario int,
	in p_offset int,
    in p_records_per_page int
)
begin
	declare p_id_cliente int;
    
    select CLIENTES.id_cliente into p_id_cliente
    from CLIENTES
    inner join PERSONAS on CLIENTES.id_persona = PERSONAS.id_persona
    inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
    where USUARIOS.id_usuario = p_id_usuario;
    
    if p_offset is not null and p_records_per_page is not null then
		select CARRITO.id_inventario as Producto, PRODUCTOS.nombre as Nombre, PRODUCTOS.imagen as Imagen, CARRITO.cantidad as Cantidad, INVENTARIO.stock as Disponible, PRODUCTOS.precio as Precio, PRODUCTOS.descripcion as Descripcion, (CARRITO.cantidad * PRODUCTOS.precio) as Total
		from CARRITO
		inner join INVENTARIO on CARRITO.id_inventario = INVENTARIO.id_inventario
		inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
		where CARRITO.id_cliente = p_id_cliente
        limit p_records_per_page
        offset p_offset;
    else
		select CARRITO.id_inventario as Producto, PRODUCTOS.nombre as Nombre, PRODUCTOS.imagen as Imagen, CARRITO.cantidad as Cantidad, INVENTARIO.stock as Disponible, PRODUCTOS.precio as Precio, PRODUCTOS.descripcion as Descripcion, (CARRITO.cantidad * PRODUCTOS.precio) as Total
		from CARRITO
		inner join INVENTARIO on CARRITO.id_inventario = INVENTARIO.id_inventario
		inner join PRODUCTOS on INVENTARIO.id_producto = PRODUCTOS.id_producto
		where CARRITO.id_cliente = p_id_cliente;
    end if;
end //
DELIMITER ;

-- * 1.2.4 Realizar Pedido 
DELIMITER //
create procedure Realizar_Pedido(
	in p_id_usuario int,  
    in p_fecha_requerido date,
    in p_tienda_seleccionado int,
    in p_habilitar_stock boolean,
    out mensaje text
)
begin

declare p_id_pedido int;
declare P_TOTALCARRITO int;

declare p_total_pedidos_del_mismo_dia int;

declare p_stock_actual int;
declare p_id_inventario int;
declare p_cantidad int;
declare p_fecha_limite datetime;
declare p_id_cliente int;
declare done int default 0;
    
declare cur cursor for

	select id_inventario, cantidad from CARRITO where id_cliente = p_id_cliente;

declare continue handler for not found set done = true;
    
set mensaje = '';    
    
select CLIENTES.id_cliente into p_id_cliente
from CLIENTES
inner join PERSONAS on CLIENTES.id_persona = PERSONAS.id_persona
inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
where USUARIOS.id_usuario = p_id_usuario;


select count(id_cliente) as Total into p_total_pedidos_del_mismo_dia
from PEDIDOS
where PEDIDOS.id_cliente = p_id_cliente and f_requerido = p_fecha_requerido;

if p_tienda_seleccionado is null then

	select count(id_cliente) as Total into p_total_pedidos_del_mismo_dia
	from PEDIDOS
	where PEDIDOS.id_cliente = p_id_cliente and id_tiendas is null and f_requerido = p_fecha_requerido;

else 
	select count(id_cliente) as Total into p_total_pedidos_del_mismo_dia
	from PEDIDOS
	where PEDIDOS.id_cliente = p_id_cliente and id_tiendas = p_tienda_seleccionado and f_requerido = p_fecha_requerido;
end if;

select SUM(CARRITO.cantidad) into P_TOTALCARRITO from CARRITO inner join CLIENTES
on CARRITO.id_cliente = CLIENTES.id_cliente inner join INVENTARIO on 
CARRITO.id_inventario = INVENTARIO.id_inventario
where CLIENTES.id_cliente = p_id_cliente;

if(P_TOTALCARRITO<20 or P_TOTALCARRITO >50) or P_TOTALCARRITO is null 
then
 -- if para mostrar un error si la cantidad de los pedidos es menor a 20 y mayor a 50
	set mensaje = 'La Cantidad de productos debe ser entre 20 y 50';

else 
if(p_fecha_requerido > date_add(date(now()),interval 6 month))
then
-- if para evitar que la fecha elegida sea para fechas mayores a 6 medes y anteriores a hoy
set mensaje = 'Fecha Invalida para realzar Pedido 1231231';

else

if (p_fecha_requerido < date(now())) 
then

set mensaje = 'Fecha Invalida para realzar Pedido fecha anterior';
else 
-- if para evitar que los clientes sin tienda realizen un pedido del mismo dia 
if day(p_fecha_requerido) = day(now()) and p_tienda_seleccionado = 0 
then

set mensaje = 'Fecha Invalida para realzar Pedido adsdsadsa';

else
				-- if para evitar que un cliente con  tienda realize un pedido del mismo dia despues de las 11:00 am
if year(p_fecha_requerido) = year(now()) and month(p_fecha_requerido) = month(now()) and day(p_fecha_requerido) = day(now()) and p_tienda_seleccionado != 0 and hour(now()) > hour('10:00:00') 
then

set mensaje = 'Fecha Invalida para realzar Pedido ya pasaron las 11:00 am';

else 
					-- if para evitar realizar un pedido para el mismo dia 
if p_total_pedidos_del_mismo_dia > 0 
then

set mensaje = 'Fecha Invalida para realzar Pedido ya hico un pedido para esta fecha';

else 
if p_habilitar_stock = 1
then
set done = false;

open cur;

							-- Evita que se realize un pedido con un producto que no tiene suficiente stock
read_loop: LOOP 

fetch cur into p_id_inventario, p_cantidad;

if done 
then

leave read_loop;

end if;

								-- Verificar el stock actual
select stock into p_stock_actual
from INVENTARIO
where INVENTARIO.id_inventario = p_id_inventario;

if p_stock_actual < p_cantidad 
then

set mensaje = 'No hay suficiente inventario para uno de los productos.';

call Insertar_Modificar_Carrito(p_id_usuario, p_id_inventario, p_stock_actual,1);

leave read_loop;

end if;

end LOOP;

close cur;
							
if mensaje = '' then

set done = false;

open cur;

								-- Evita que se realize un pedido con un producto que no tiene suficiente stock
read_loop: LOOP   

fetch cur into p_id_inventario, p_cantidad;

if done then

leave read_loop;

end if;

update INVENTARIO
set stock = stock - p_cantidad
where INVENTARIO.id_inventario = p_id_inventario; 

end LOOP;

close cur;

end if;

end if;
						
if mensaje = '' then
							-- Calcula la fecha limite
if p_fecha_requerido < date_add(now(), interval 14 day) then

set p_fecha_limite = date_add(p_fecha_requerido, interval -1 day);
set p_fecha_limite = date_add(date(p_fecha_limite), interval 20 hour);

else
set p_fecha_limite = date_add(p_fecha_requerido, interval -7 day);
set p_fecha_limite = date_add(date(p_fecha_limite), interval 20 hour);

end if;

if p_tienda_seleccionado = 0 then

insert into PEDIDOS value (null, p_id_cliente, null, now(), p_fecha_requerido, p_fecha_limite, '0000-00-00 00:00:00', null, 'pendiente');

else

insert into PEDIDOS value (null, p_id_cliente, p_tienda_seleccionado, now(), p_fecha_requerido, null, '0000-00-00 00:00:00', null, 'pendiente');
end if;

set p_id_pedido = last_insert_id(); -- ultimo pedido insertado

insert into DETALLE_PEDIDO (id_pedido, id_inventario, cantidad)
select p_id_pedido, id_inventario, cantidad 
from CARRITO
where CARRITO.id_cliente = p_id_cliente;
								
delete from CARRITO
where id_cliente = p_id_cliente;
							
set mensaje = 'pedido realizado';

end if;
end if;
end if;
end if;
end if;
end if;
end if;
end //
DELIMITER ;



-- * Verificar Carrito
DELIMITER //
create procedure Verificar_Carrito(
in p_id_usuario int,
out mensaje text
)
begin
declare p_id_cliente int;
declare p_stock_actual int;
declare p_id_inventario int;
declare p_cantidad int;

declare done int default 0;

declare cur cursor for
select id_inventario, cantidad from CARRITO where id_cliente = p_id_cliente;
declare continue handler for not found set done = true;
    
select CLIENTES.id_cliente into p_id_cliente
from CLIENTES
inner join PERSONAS on CLIENTES.id_persona = PERSONAS.id_persona
inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
where USUARIOS.id_usuario = p_id_usuario;
    
set done = false;
open cur;

-- Evita que se realize un pedido con un producto que no tiene suficiente stock
read_loop: LOOP   
fetch cur into p_id_inventario, p_cantidad;
if done then
leave read_loop;
end if;

-- Verificar el stock actual
select stock into p_stock_actual
from INVENTARIO
where id_inventario = p_id_inventario;

if p_stock_actual < p_cantidad then
        
set mensaje = 'El carrito se modifico por falta de inventario.';
call Insertar_Modificar_Carrito(p_id_usuario, p_id_inventario, p_stock_actual, 1);
                                    
leave read_loop;
end if;
end LOOP;

close cur;
end //
DELIMITER ;



-- * 1.2.2 Modificar Carrito
DELIMITER //
create procedure Modificar_Carrito(
in p_id_cliente int, -- Variable para saber cual cliente es el carrito  
in p_id_producto int, -- Variable para saber cual producto del carrito se va a cancelar
in p_cantidad int, -- Variable para saber cual va a ser la modificacion del la cantidad del producto en el carrito 
in p_verificarStock boolean
)
begin
declare stock_disponible int;

select stock into stock_disponible
from INVENTARIO
where id_producto = p_id_producto;

if p_cantidad = 0 then

delete from CARRITO
where CARRITO.id_inventario = p_id_producto;
else 
if p_verificarStock = 1 then

if p_cantidad <= stock_disponible then

update CARRITO
set cantidad = p_cantidad
where CARRITO.id_cliente = p_id_cliente and CARRITO.id_inventario = p_id_producto;
end if;
else 

update CARRITO
set cantidad = p_cantidad
where CARRITO.id_cliente = p_id_cliente and CARRITO.id_inventario = p_id_producto;
end if;
end if;
end //
DELIMITER ;


-- * 1.1.3 Procedimiento. Insertar Producto al Carrito
DELIMITER //
create procedure Insertar_Producto_Carrito(
in p_id_cliente int,
in p_id_producto int,
in p_cantidad int,
in p_verificarStock boolean
)
begin
declare stock_disponible int;

select stock into stock_disponible
from INVENTARIO
where INVENTARIO.id_producto = p_id_producto;

if p_verificarStock = 1 then

if p_cantidad <= stock_disponible then

insert into CARRITO value (null, p_id_cliente, p_id_producto, p_cantidad);
end if;
else

insert into CARRITO value (null, p_id_cliente, p_id_producto, p_cantidad);
end if;
end //
DELIMITER ;




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
where PRODUCTOS.imagen = n_imagen;

select count(PRODUCTOS.nombre) into contador
from PRODUCTOS
where PRODUCTOS.nombre = n_nombre;

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