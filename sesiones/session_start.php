<?php
    session_start();

    // a continuacion se evalua que haya una sesion iniciada y con valor que no sea nulo
    if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'])) {
        echo "Es necesario iniciar sesion para poder acceder a esta pagina<br>";
        echo "<br><a href='../index.php'>Iniciar Sesion</a>";
        exit;
    } 

    $now = time();

    if($now > $_SESSION['expire']) {

        session_destroy();
        echo "Se ha excedido el tiempo de sesion, <a href='../index.php'>Iniciar Sesion</a>";
        exit;
    }
?>