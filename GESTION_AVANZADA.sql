-- * 1.1.4 Procedimiento. Ver Productos filtrado por Categoria y por Nombre de Producto
DELIMITER //
create procedure Ver_Productos_Fltros(
	in p_categoria int,
    in p_nombre_producto varchar(50),
    in p_offset int,
    in p_records_per_page int
)
begin
	if p_offset is null and p_records_per_page is null then
		if p_categoria != 0 and p_nombre_producto is null then
			select id_producto as ID, nombre as Nombre, imagen as Imagen, precio as Precio, descripcion as Descripcion
			from productos
			where estado = 1 and id_categoria = p_categoria;
		end if;
		
		if p_categoria = 0 and p_nombre_producto is not null then
			select id_producto as ID, nombre as Nombre, imagen as Imagen, precio as Precio, descripcion as Descripcion
			from productos
			where estado = 1 and nombre = p_nombre_producto;
		end if;
		
		if p_categoria = 0 and p_nombre_producto is null then
			select id_producto as ID, nombre as Nombre, imagen as Imagen, precio as Precio, descripcion as Descripcion
			from productos
			where estado = 1;
		end if;
    else 
		if p_categoria != 0 and p_nombre_producto is null then
			select id_producto as ID, nombre as Nombre, imagen as Imagen, precio as Precio, descripcion as Descripcion
			from productos
			where estado = 1 and id_categoria = p_categoria
			limit p_records_per_page
			offset p_offset;
		end if;
		
		if p_categoria = 0 and p_nombre_producto is not null then
			select id_producto as ID, nombre as Nombre, imagen as Imagen, precio as Precio, descripcion as Descripcion
			from productos
			where estado = 1 and nombre = p_nombre_producto
			limit p_records_per_page
			offset p_offset;
		end if;
		
		if p_categoria = 0 and p_nombre_producto is null then
			select id_producto as ID, nombre as Nombre, imagen as Imagen, precio as Precio, descripcion as Descripcion
			from productos
			where estado = 1
			limit p_records_per_page
			offset p_offset;
		end if;
    end if;
end //
DELIMITER ;