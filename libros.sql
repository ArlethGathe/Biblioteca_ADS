-- 1) Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario     VARCHAR(50)    NOT NULL UNIQUE,
  correo      VARCHAR(50)    NOT NULL,
  clave       VARCHAR(255)   NOT NULL,
  rol         ENUM('bibliotecario', 'lector') NOT NULL DEFAULT 'lector',
  creado_at   TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

INSERT INTO `usuarios` (`id`, `usuario`, `correo`, `clave`, `rol`) VALUES
(1, 'Arleth', 'arleth@gmail.com', 'arleth123', 'bibliotecario'),
(2, 'Niall', 'niallhoran@gmail.com', 'nial13', 'lector'),
(3, 'Axel', 'a@a.com', 'a', 'bibliotecario');

-- 2) Tabla de géneros
CREATE TABLE IF NOT EXISTS generos (
  id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100)   NOT NULL
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- 3) Tabla de clasificaciones
CREATE TABLE IF NOT EXISTS clasificaciones (
  id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100)   NOT NULL
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- 4) Tabla de libros
CREATE TABLE IF NOT EXISTS libros (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titulo            VARCHAR(255) NOT NULL,
  autor             VARCHAR(255) NOT NULL,
  editorial         VARCHAR(255) NOT NULL,
  edicion           VARCHAR(50)  DEFAULT NULL,
  genero_id         INT UNSIGNED DEFAULT NULL,
  clasificacion_id  INT UNSIGNED DEFAULT NULL,
  cantidad          INT UNSIGNED NOT NULL DEFAULT 1,
  estado            ENUM('disponible', 'prestado', 'reservado', 'apartado') 
                    NOT NULL DEFAULT 'disponible',
  portada           VARCHAR(255) DEFAULT NULL,
  fecha_apartado    DATETIME DEFAULT NULL,  -- Nueva columna para registrar la fecha de apartado
  creado_at         TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_libros_genero 
    FOREIGN KEY (genero_id)       REFERENCES generos(id)
      ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT fk_libros_clasif 
    FOREIGN KEY (clasificacion_id) REFERENCES clasificaciones(id)
      ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- 5) Tabla de préstamos
CREATE TABLE IF NOT EXISTS prestamos (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  libro_id          INT UNSIGNED NOT NULL,
  lector_id         INT UNSIGNED NOT NULL,
  fecha_solicitud   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  fecha_inicio      DATE         DEFAULT NULL,
  fecha_vencimiento DATE         DEFAULT NULL,
  renovado          TINYINT(1)   NOT NULL DEFAULT 0,
  estado            ENUM('pendiente', 'activo', 'finalizado', 'renovado')
                    NOT NULL DEFAULT 'pendiente',
  CONSTRAINT fk_prestamos_libro 
    FOREIGN KEY (libro_id)  REFERENCES libros(id)
      ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_prestamos_lector 
    FOREIGN KEY (lector_id) REFERENCES usuarios(id)
      ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- 6) Tabla de apartados
CREATE TABLE IF NOT EXISTS apartados (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  libro_id          INT UNSIGNED NOT NULL,
  lector_id         INT UNSIGNED NOT NULL,
  fecha_apartado    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  estado            ENUM('apartado', 'cancelado') 
                    NOT NULL DEFAULT 'apartado',
  CONSTRAINT fk_apartados_libro 
    FOREIGN KEY (libro_id)  REFERENCES libros(id)
      ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_apartados_lector 
    FOREIGN KEY (lector_id) REFERENCES usuarios(id)
      ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

