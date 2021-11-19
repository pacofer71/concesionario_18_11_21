drop table if exists coches;
drop table if exists marcas;

-- Tabla marcas
create table marcas(
    id int AUTO_INCREMENT primary key,
    nombre varchar(80) not null,
    img varchar(120) not null,
    pais varchar(80) not null
);

-- Tabla coches
create table coches(
    id int AUTO_INCREMENT primary key,
    modelo varchar(100) not null,
    kms int,
    tipo enum('Electrico', 'Hibrido', 'Gasolina', 'Gasoil', 'Gas'),
    color varchar(100),
    img varchar(120) not null,
    marca_id int,
    constraint fk_coche_marca foreign key(marca_id) references marcas(id)
     on delete cascade on update cascade

);
