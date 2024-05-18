drop database if exists scholar_universe;
create database scholar_universe;

/*Una página donde los estudiantes puedan compartir y acceder a apuntes, resúmenes y recursos de estudio para 
 sus clases. Podrían crear perfiles, seguir materias específicas y colaborar.
 Siguiendo esto pues quiero que sea para subir archivos, etc.
 */
use scholar_universe;

/*adicional categorias por ejemplo sistemas que englobe informatica y tics,etc,
 roles apuntes - archivos recursos de estudio - archivos mis materias colaborar en la creación de contenido educativo,
*/
create table usuarios (
    id int not null primary key auto_increment,
    nombre varchar(255) not null,
    apellido_paterno varchar(255) not null,
    apellido_materno varchar(255),
    email varchar(255) not null,
    password varchar(255) not null,
    rol enum('Estudiante', 'Root') not null default ('1'),
    fecha_creacion datetime not null default current_timestamp()
);

create table universidades(
    id int auto_increment not null,
    nombre varchar(200) not null,
    direccion varchar(200),
    pais varchar(200),
    estado varchar(200),
    fecha_creacion datetime not null default current_timestamp(),
    primary key (id)
);

create table categorias (
    id int not null primary key auto_increment,
    nombre varchar(255) not null,
    fecha_creacion datetime not null default current_timestamp()
);

create table materias(
    id int auto_increment not null,
    nombre varchar(100) not null,
    descripcion text not null,
    fecha_creacion datetime not null default current_timestamp(),
    primary key(id)
);

create table categoria_materia(
    categoria_id int not null,
    materia_id int not null,
    primary key(categoria_id, materia_id),
    foreign key(categoria_id) references categorias(id) on delete restrict on update cascade,
    foreign key(materia_id) references materias(id) on delete restrict on update cascade
);

create table univerdad_materia(
    univerdad_id int not null,
    materia_id int not null,
    primary key(univerdad_id, materia_id),
    foreign key(univerdad_id) references universidades(id) on delete restrict on update cascade,
    foreign key(materia_id) references materias(id) on delete restrict on update cascade
);

create table estudiantes(
    id int auto_increment not null,
    matricula varchar(50) not null,
    universidad_id int not null,
    usuario_id int not null,
    primary key(id),
    foreign key(universidad_id) references universidades(id) on delete restrict on update cascade,
    foreign key (usuario_id) references usuarios (id) on delete restrict on update cascade
);

create table estudiante_materia(
    estudiante_id int not null,
    materia_id int not null,
    seguir char not null default '0',
    primary key(estudiante_id, materia_id),
    foreign key(estudiante_id) references estudiantes(id) on delete restrict on update cascade,
    foreign key(materia_id) references materias(id) on delete restrict on update cascade
);

create table archivos(
    id int auto_increment not null,
    nombre varchar(1000) not null,
    peso varchar(1000) not null,
    ruta varchar(500) not null,
    tipo enum('recursos', 'apunte','resumen') not null default ('0'),
    fecha_creacion datetime not null default current_timestamp(),
    primary key(id)
);

create table colaboracion_estudiante_materia(
    estudiante_id int not null,
    materia_id int not null,
    archivo_id int not null,
    visible char not null default '0',
    primary key(estudiante_id, materia_id, archivo_id),
    foreign key(estudiante_id) references estudiantes(id) on delete restrict on update cascade,
    foreign key(materia_id) references materias(id) on delete restrict on update cascade,
    foreign key(archivo_id) references archivos(id) on delete restrict on update cascade
);

create view view_universidades as
select * from universidades;

create view view_categorias as
select * from categorias;


create view view_materias as
select * from materias;

create view view_estudiantes as
select e.id,e.matricula,e.universidad_id,e.usuario_id,u.nombre, u.apellido_paterno,u.apellido_materno,u.email,u.rol,uni.nombre universidad,uni.direccion,uni.pais,uni.estado
from estudiantes e inner join usuarios u on u.id = e.usuario_id inner join universidades uni on uni.id = e.universidad_id;

/*la contraseña es admin*/
INSERT INTO scholar_universe.usuarios
(nombre, apellido_paterno, apellido_materno, email, password, rol, fecha_creacion)
VALUES('Mayant', 'Gorgonio', 'Tolentino', 'mayant.tolentino@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 'Root', '2024-05-15 00:22:25');

/*insert into universidades values(null, 'ITSAL', 'Salina Cruz Oaxaca', 'Mexico');
 insert into universidades values(null, 'Universidad Viscaya', 'Salina Cruz Oaxaca', 'Mexico');
 
 insert into estudiantes values(null, 'Mayant', 'Gorgonio Tolentino', 'mayant.tolentino@gmail.com', 1);
 insert into estudiantes values(null, 'Mariana', 'Angeles Ordoñes', 'marianaangeleso@gmail.com', 2);
 
 insert into materias values(null, 'Programacion Web', 'La programación web sirve para crear páginas y sitios en Internet. 
 Para poder hacerlo, se utilizan distintos lenguajes específicos que permiten desarrollar la creatividad humana. 
 Estos lenguajes se fundamentan en la posibilidad de enlazar a través de hipervínculos distintas páginas web, lo que 
 genera la interconexión que conocemos hoy como Internet.', 1);
 insert into materias values(null, 'Desarrollo de emprendedores', 'El desarrollo emprendedor es el proceso mediante el cual 
 los emprendedores adquieren habilidades, conocimientos y recursos necesarios para iniciar, gestionar y hacer crecer un negocio. 
 Implica identificar oportunidades, establecer objetivos, diseñar estrategias y tomar decisiones efectivas para alcanzar el éxito
 empresarial.', 2);
 
 insert into estudiantes_materias values(null, 1, 1);
 insert into estudiantes_materias values(null, 2, 2);*/