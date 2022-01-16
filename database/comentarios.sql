use data_usuarios;

CREATE TABLE comentario (
     -- UNSIGNED sirve para permitir solo valores positivos (sin signo)
    id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    nombre_usuario VARCHAR(30) NOT NULL,
    telefono VARCHAR(40) NOT NULL,
    correo_electronico VARCHAR(50) NOT NULL,
    mensajes varchar(100) NOT NULL, 
    -- TIMESTAMP es un tipo de dato que contiene fecha y hora
    -- La función CURRENT_TIMESTAMP devuelve la fecha y la hora local actual 
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);