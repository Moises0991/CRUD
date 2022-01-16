<?php
    session_start();

    // a continuacion se evalua que haya una sesion iniciada y con valor que no sea nulo
    if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'])) {
        echo "Es necesario iniciar sesion para poder acceder a esta pagina<br>";
        echo "<br><a href='../login.html'>Iniciar Sesion</a>";
        exit;
    } 

    $now = time();

    if($now > $_SESSION['expire']) {

        session_destroy();
        echo "Se ha excedido el tiempo de sesion, <a href='../login.html'>Iniciar Sesion</a>";
        exit;
    }
?>

<!-- a continuacion lo que se pretende es regresar un html con un menu de opciones -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <?= "Bienvenido " . $_SESSION['username'] . "!";?>
</head>
<body>
    <a href="inicio.php" class="btn btn-default">Inicio</a>
    <a href="somos.php" class="btn btn-default">¿Quienes Somos?</a>
    <a href="contacto.php" class="btn btn-default">Contacto</a>
    <a href="logout.php" class="btn btn-default">Cerrar Sesion</a>
</body>
</html>