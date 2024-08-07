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
    where id_cliente= p_id_cliente;
    
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
			elseif contadorError >1 then 
				set mensajeError = concat(mensajeError, ', apellido paterno');
			end if;
			set contadorError = contadorError + 1;
		end if;
        
        if telefononuev = ''  then
			if contadorError = 0 then 
				set mensajeError = 'No puedes dejar el numero de telefono vacio';
			elseif contadorError = 1 then 
				set mensajeError = concat(mensajeError, ' ni tampoco el numero de telefono');
			elseif contadorError >1 then 
				set mensajeError = concat(mensajeError
                , ', numero de telefono');
			end if;
			set contadorError = contadorError + 1;
		end if;
        
        if length(telefononuev)<10 then
        		if contadorError = 0 then 
				set mensajeError = 'El numero de telefono no puede tener menos de 10 numeros';
			elseif contadorError = 1 then 
				set mensajeError = concat(mensajeError, ' número de teléfono inválido');
			elseif contadorError >1 then 
				set mensajeError = concat(mensajeError
                , ', número de teléfono inválido');
			end if;
			set contadorError = contadorError + 1;
		end if;
        
        
		if ModificacionNombre = 0 then
			if nombrenuev != '' then
				update PERSONAS
				inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
				set PERSONAS.nombre= nombrenuev
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
				set PERSONAS.a_p=a_pnuev
				where USUARIOS.id_usuario=id_u;
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
			set PERSONAS.a_m=a_mnuev
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
			if telefononuev != ''  and length(telefononuev)=10 then
				update PERSONAS
				inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
				set PERSONAS.telefono=telefononuev
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
set mensajeError='Máximo 100 caracteres ';
elseif contadorError=1 then
set mensajeError= concat(mensajeError, ' ni tampoco puedes poner mas de 100 caracteres en la dirección');
elseif contadorError>1 then
set mensajeError= concat(mensajeError, ', máximo 100 caracteres');
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
	in p_id_usuario int
)
begin
	declare p_id_cliente int;

	select CLIENTES.id_cliente into p_id_cliente
	from CLIENTES
	inner join PERSONAS on CLIENTES.id_persona = PERSONAS.id_persona
	inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
	where USUARIOS.id_usuario = p_id_usuario;
    
    select TIENDAS.id_tienda as IDTI, TIENDAS.nombre_tienda as Nombre, TIENDAS.direccion as Direccion, TIENDAS.estatus as Estatus
    from TIENDAS
    inner join CLIENTE_TIENDA on TIENDAS.id_tienda = CLIENTE_TIENDA.id_tienda
    where CLIENTE_TIENDA.id_cliente = p_id_cliente;
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
