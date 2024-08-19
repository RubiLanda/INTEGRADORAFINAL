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

 ----- * 4.2.1 VER INFO REPARTIDOR -----------------
DELIMITER //
create procedure Ver_Informacion_Repartidor(
	in p_id_usuario int
)
begin
    select USUARIOS.id_usuario as id_user, USUARIOS.username as Usuario, CONCAT(PERSONAS.nombre, ' ', PERSONAS.a_p, ' ', PERSONAS.a_m) AS Nombre, 
    PERSONAS.f_nac as Fecha_nacimiento, PERSONAS.genero as Genero, REPARTIDORES.fol_liconducir as Licencia_conducir,PERSONAS.telefono as Telefono,
     REPARTIDORES.f_ingreso as Fecha_Ingreso, REPARTIDORES.ine as INE
    from REPARTIDORES
    inner join PERSONAS on REPARTIDORES.id_persona = PERSONAS.id_persona
    inner join USUARIOS on PERSONAS.id_usuario = USUARIOS.id_usuario
    where USUARIOS.id_usuario = p_id_usuario;
end //
DELIMITER ;

----- * 4.2.2 ACTUALIZAR INFORMACIÓN REPARTIDOR-------------------------------
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


/*PROCEDIMIENTO PARA CAMBIARLE LA INE AL REAPRTIDOR*/
DELIMITER //
create procedure actualizar_ine(
in N_id_repartidor int,
in n_ine varchar(2083),
out mensaje text
)
begin

declare ine_actual varchar(2083);
declare ine_existente int;

select  ine into ine_actual 
from REPARTIDORES
where  REPARTIDORES.id_repartidor = N_id_repartidor ;

select COUNT(*) into ine_existente
from REPARTIDORES
where REPARTIDORES.ine = n_ine and REPARTIDORES.id_repartidor != N_id_repartidor;

if ine_existente > 0 
then
set mensaje = 'La imagen de la ine ya esta asignada en otro repartidor';
else
if ine_actual = n_ine 
then
set mensaje = 'Error Imagen Repetida';
else
update REPARTIDORES
set ine = n_ine
where REPARTIDORES.id_repartidor = N_id_repartidor;

set mensaje = 'INE Actualizada Correctamente';
end if;
end if;
end //
DELIMITER ;