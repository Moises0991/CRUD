<?php 
    session_start();
?>
<?php
    // las siguientes variables almacenan los valores de la base de datos para posteriormente hacer la conexion
    $host_db = "localhost";
    $user_db = "root";
    $pass_db = "";
    $db_name = "data_usuarios";
    $tbl_name = "usuarios";

    // a continuacion se crea una conexion mediante el objeto mysqli el cual solo sirve para conexiones a bases de datos de mysql
    $conexion = new mysqli($host_db, $user_db, $pass_db, $db_name);

    if ($conexion -> connect_error) {
        include '../database/instalar.php';
        include '../database/instalar2.php';
        die("La conexion fallo: " . $conexion -> connect_error);
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM $tbl_name WHERE  nombre_usuario = '$username'";
    $result = $conexion -> query($sql);

    // a continuacion se evalua que se haya encontrado una concidencia con el nombre de usuario ingresado
    if ($result -> num_rows > 0) {
        // fetch_array guarda la informacion en los indices numericos del array resultante, tambien puede guardar la info en indices asociativos
        $row = $result -> fetch_array(MYSQLI_ASSOC);

        // la funcion md5 sirve para obtener el hash encriptado de la cadena de texto
        // $password = md5($password);
        
        // el array row almacena la relacion de el nombre_usuario con el password_usuario por lo que en el if ya se sabe que contraseña es la que se esta evaluando
        $password = md5($password);
        if ($password == $row['password_usuario']) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['start'] = time();
            // a continuacion se establece el tiempo de expirado de una sesion 
            $_SESSION['expire'] = $_SESSION['start'] + (10 * 60);
            // una vez realizada la autentificacion se redirige al dashboard
            header('Location: ../Crud/inicio.php');
            
        } else {

            echo "username o password son incorrectos.";
            echo "<br><a href ='../index.php'> Volver a intentarlo</a>";
        }

    } else {

        echo "username o password son incorrectos.";
        echo "<br><a href ='../index.php'> Volver a intentarlo</a>";
    }
    mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contraseña erronea</title>
</head>
<body style="background: linear-gradient(90deg, #49a09d, #5f2c82);">
    
</body>
</html>