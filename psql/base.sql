DROP TABLE IF EXISTS notas CASCADE;
DROP TABLE IF EXISTS categorias CASCADE;
DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE categorias
(
        id      bigserial       PRIMARY KEY
    ,   nombre  VARCHAR(255)    UNIQUE NOT NULL
);

CREATE TABLE notas
(
        id      bigserial       PRIMARY KEY
    ,   titulo  VARCHAR(255)    UNIQUE NOT NULL
    ,   header  text            UNIQUE NOT NULL
    ,   cat_id  bigint          NOT NULL REFERENCES categorias (id)
                                ON DELETE NO ACTION ON UPDATE CASCADE
    ,   fecha   varchar(20)     NOT NULL  
);

CREATE TABLE usuarios
(
        id      bigserial       PRIMARY KEY
    ,   nombre  VARCHAR(255)    UNIQUE NOT NULL
    ,   passwd  VARCHAR(255)    NOT NULL
    ,   mail  VARCHAR(255)      NOT NULL
);

INSERT INTO categorias (nombre)
VALUES  ('arte')
    ,   ('juegos')
    ,   ('actualidad')
    ,   ('ocio')
    ,   ('peliculas')
;

INSERT INTO notas(titulo, header, cat_id, fecha)
VALUES  ('Trilogía trajano', 'Este post es sobre la trilogía de trajano escrito por Santiago Posteguillo', 4, '20 January 2019')
    ,   ('Las obras de mi madre', 'Este post va sobre obras creadas por mi madre', 1, '20 February 2018')
    ,   ('Saga de Star Wars', 'La saga acaba con la novena parte de star wars', 5, '5 January 2020')
;

INSERT INTO usuarios (nombre, passwd, mail)
VALUES ('diego', crypt('diego', gen_salt('bf', 12)), 'diegohilderink@gmail.com');


