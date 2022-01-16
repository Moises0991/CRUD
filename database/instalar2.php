<?php 
    $config = include 'config.php';
    try {
        // para poder crear un pdo es necesario pasarle:host de conexion, nombre de usuario sql, contraseña y opciones de conexion
        $conexion = new PDO ('mysql:host=' . $config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['options']);
        $sql = file_get_contents("../database/comentarios.sql");     //file_get_contens es un metodo que asigna una consulta sql a una varible
        $conexion -> exec($sql);        //exec es un metodo para ejercutar la consulta
        echo "La base de datos y la tabla de alumnos se han creado con exito";

    } catch (PDOException $error) {
        echo $error -> getMessage();
    }
    
?>