CREATE DATABASE LAESPIGAFINAL CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE LAESPIGAFINAL;

CREATE TABLE ROLES(
id_rol int(11)auto_increment primary key NOT NULL,
rol varchar(20) not null
);

create table USUARIOS(
id_usuario int(11)auto_increment primary key NOT NULL,
username varchar(150)  CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
contraseña varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, 
f_registro datetime NOT NULL,
constraint UQ_USERNAME unique (username)
)CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

create table ROL_USUARIO(
id_rolusua int(11)auto_increment primary key NOT NULL,
id_rol int(11) NOT NULL,
id_usuario int(11) NOT NULL,
constraint FK_ROL foreign key (id_rol) REFERENCES ROLES (id_rol),
constraint FK_USUARIO foreign key (id_usuario) REFERENCES USUARIOS (id_usuario)
);

CREATE TABLE PERSONAS(
id_persona int(11)auto_increment primary key NOT NULL,
nombre VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
a_p varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
a_m varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
f_nac date NOT NULL,
genero enum('M', 'F', 'O') NOT NULL,
telefono char(10),
id_usuario int(11),
constraint FK_USER foreign key (id_usuario) references USUARIOS (id_usuario)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci; 


create table ADMINISTRADORES(
id_admin int(11) auto_increment primary key NOT NULL,
id_persona int(11)NOT NULL,
fecha_cargo date NOT NULL,
estatus boolean ,
constraint FK_PERADMIN foreign key (id_persona) references PERSONAS (id_persona)
);

create table TIENDAS(
id_tienda int(11) auto_increment primary key NOT NULL,
nombre_tienda varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
direccion varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
estatus boolean,
constraint UQ_TIENDA unique (nombre_tienda)
);

create table CLIENTES(
id_cliente int(11)auto_increment primary key NOT NULL,
id_persona int(11) NOT NULL,
constraint FK_PERCLI foreign key (id_persona) references PERSONAS (id_persona)
);

create table CLIENTE_TIENDA(
id_clientien  int(11)auto_increment primary key NOT NULL,
id_cliente int (11) not null,
id_tienda int (11) not null,
constraint FK_CLIENTE foreign key (id_cliente) references CLIENTES (id_cliente),
constraint FK_TIENDA foreign key (id_tienda) references TIENDAS (id_tienda)
);
create table REPARTIDORES(
id_repartidor int(11)auto_increment primary key NOT NULL,
f_ingreso date NOT NULL,
fol_liconducir char(11) NOT NULL,
id_persona int(11) NOT NULL,
estatus boolean NOT NULL,
constraint FK_PERREP foreign key (id_persona) references personas(id_persona)
);
alter table repartidores
ADD constraint UQ_LICENCIA unique (fol_liconducir);

create table CATEGORIAS(
id_categoria int(11) auto_increment primary key NOT NULL,
nombre varchar(40) NOT NULL,
estado boolean default true
);

create table PRODUCTOS(
id_producto int(11)auto_increment primary key NOT NULL,
nombre varchar (40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
id_categoria int(11) NOT NULL,
imagen varchar(2083) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci , -- preguntar si subirlas al servidor 
precio decimal(10,2) NOT NULL,
estado boolean default true,
descripcion varchar (255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ,
constraint FK_CATEGORIA foreign key (id_categoria) references CATEGORIAS (id_categoria)
);

create table INVENTARIO(
id_inventario int(11) auto_increment primary key NOT NULL,
id_producto int(11) NOT NULL,
stock int(11) NOT NULL,
constraint FK_PRODUCTO foreign key(id_producto) references PRODUCTOS (id_producto)
);

create table PEDIDOS(
id_pedido int(11)auto_increment primary key NOT NULL,
id_cliente int(11) NOT NULL,
id_tiendas int (11) NULL,
f_pedido datetime NOT NULL,
f_requerido date NOT NULL,
f_limitepago datetime NULL,
f_entrega datetime, -- quitarle el null
id_repartidor int(11),
estado_pedido enum('pendiente','pendiente a pagar', 'aceptado', 'cancelado', 'entregado'),
constraint FK_CLIENTEPEDIDO foreign key (id_cliente) references CLIENTES (id_cliente),
constraint FK_REPARTIDOR foreign key (id_repartidor) references REPARTIDORES (id_repartidor),
constraint FK_PEDIDOTIENDA foreign key(id_tiendas) references TIENDAS(id_tienda)
);

create table DETALLE_PEDIDO(
id_detalle int(11) auto_increment primary key NOT NULL,
id_pedido int(11) NOT NULL,
id_inventario int(11) NOT NULL,
cantidad int(11) NOT NULL,
constraint FK_PEDIDO foreign key (id_pedido)references PEDIDOS (id_pedido),
constraint FK_INVEN foreign key (id_inventario) references INVENTARIO (id_inventario)
);

create table Carrito(
	id_carrito int auto_increment primary key,
    id_cliente int,
    id_inventario int,
    cantidad int,
    constraint FK_clientes foreign key (id_cliente) references clientes(id_cliente),
    constraint FK_inventarios foreign key (id_inventario) references inventario(id_inventario)
);

ALTER TABLE personas MODIFY a_m varchar(40) NULL;
