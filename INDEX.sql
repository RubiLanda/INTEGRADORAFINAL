-- 2.2.1 Registrar un cliente nuevo
DELIMITER //
CREATE  PROCEDURE INSERTAR_CLIENTES(
IN N_username VARCHAR(150),    -- varibales de entrada para el registro 
IN N_contraseña VARCHAR(255),
IN N_nombre VARCHAR(40),
IN N_a_p VARCHAR(40),
IN N_a_m VARCHAR(40),
IN N_f_nac DATE,
IN N_genero ENUM('M','F','O'),
IN N_telefono CHAR(10),
out mensaje text
)
BEGIN
declare ultimaid_usuario int; -- declaramos algunas variables para guardar la id de algunas cosas
declare ultimaid_persona int;
declare ultimaid_cliente int;
declare userepe int;
IF N_username='' or N_contraseña=''  or N_nombre=''  or N_a_p='' or N_f_nac=''  or 
N_genero='' or N_telefono=''   -- si cualquiera de los campos son nulos entonces mandara un mensaje de error
then 
 set mensaje = 'NO PUEDES DEJAR ALGUN CAMPO VACIO';
else
select count(USUARIOS.username) into userepe from USUARIOS where username=N_username;
if userepe>0 then
set mensaje='Ya Existe un usuario con ese nombre';
else
if length(N_telefono)<10 then
set mensaje='Numero invalido';
else
IF N_f_nac <= DATE_ADD(CURDATE(), INTERVAL -18 YEAR)
AND N_f_nac >= DATE_ADD(CURDATE(), INTERVAL -100 YEAR)
 -- si la fecha de nacimineto es menor que la fecha que se genere de hoy menos 18 años
then   
insert into USUARIOS (username, contrasena, f_registro)  -- insertar en usuarios los datos
values (N_username, N_contraseña,now());
set ultimaid_usuario = last_insert_id(); -- guardar la ultima id que se genero con el auto-increment
INSERT INTO ROL_USUARIO (id_rol,id_usuario) -- inmediatamente 
values(2,ultimaid_usuario);
INSERT INTO PERSONAS(nombre, a_p, a_m, f_nac, genero, telefono,id_usuario)
VALUES (N_nombre, N_a_p, N_a_m, N_f_nac, N_genero, N_telefono,ultimaid_usuario);
set ultimaid_persona = last_insert_id();
INSERT INTO CLIENTES(id_persona) values(ultimaid_persona);
set ultimaid_cliente = last_insert_id();
set mensaje='REGISTRO EXITOSO';
ELSE
SET mensaje = 'DEBES SER MAYOR DE EDAD PARA REGISTRARTE O LA EDAD ES EXCESIVA';
END IF;
end if;
end if;
end if;
END //
DELIMITER ;

-- * 1.1.4 Procedimiento. Ver Productos filtrado por Categoria y por Nombre de Producto
DELIMITER //
create procedure Ver_Productos_Filtros(
	in p_categoria int,
    in p_nombre_producto varchar(50),
    in p_offset int,
    in p_records_per_page int
)
begin
	if p_offset is null and p_records_per_page is null then
		if p_categoria != 0 and p_nombre_producto is null then
			select id_producto as ID, nombre as Nombre, imagen as Imagen, precio as Precio, descripcion as Descripcion
			from PRODUCTOS
			where PRODUCTOS.estado = 1 and PRODUCTOS.id_categoria = p_categoria;
		end if;
		
		if p_categoria = 0 and p_nombre_producto is not null then
			select id_producto as ID, nombre as Nombre, imagen as Imagen, precio as Precio, descripcion as Descripcion
			from PRODUCTOS
			where PRODUCTOS.estado = 1 and PRODUCTOS.nombre = p_nombre_producto;
		end if;
		
		if p_categoria = 0 and p_nombre_producto is null then
			select id_producto as ID, nombre as Nombre, imagen as Imagen, precio as Precio, descripcion as Descripcion
			from PRODUCTOS
			where PRODUCTOS.estado = 1;
		end if;
    else 
		if p_categoria != 0 and p_nombre_producto is null then
			select id_producto as ID, nombre as Nombre, imagen as Imagen, precio as Precio, descripcion as Descripcion
			from PRODUCTOS
			where PRODUCTOS.estado = 1 and PRODUCTOS.id_categoria = p_categoria
			limit p_records_per_page
			offset p_offset;
		end if;
		
		if p_categoria = 0 and p_nombre_producto is not null then
			select id_producto as ID, nombre as Nombre, imagen as Imagen, precio as Precio, descripcion as Descripcion
			from PRODUCTOS
			where PRODUCTOS.estado = 1 and PRODUCTOS.nombre = p_nombre_producto
			limit p_records_per_page
			offset p_offset;
		end if;
		
		if p_categoria = 0 and p_nombre_producto is null then
			select id_producto as ID, nombre as Nombre, imagen as Imagen, precio as Precio, descripcion as Descripcion
			from PRODUCTOS
			where PRODUCTOS.estado = 1
			limit p_records_per_page
			offset p_offset;
		end if;
    end if;
end //
DELIMITER ;
