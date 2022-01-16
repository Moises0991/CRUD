 <?php include '../templates/header.php';?> 

<?php
  
  include '../Crud/funciones.php';
    csrf();
    if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        die();
    }
   
     $resultado = [
         'error' => false,
         'mensaje' => ''
        ];

    // en las siguientes lineas se evalua si ya esta definido en el array post el submit (osea ya se envio el formulario)
    // y en caso de ser true se realiza la modificacion de la informacion del alumno con los nuevos datos enviados en el nuevo form
    if (isset($_POST['submit'])) {
        try {
            $config = include '../database/config.php';

             
            // la conexion que se realiza en este try es para realizar la modificacion de los valores del alumno en la base de datos
            $dns = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name']; 
            $conexion = new PDO($dns, $config['db']['user'] , $config['db']['pass'], $config['db']['options']);

            // la clausla SET(en SQL) establece los nuevos valores para las columnas especificadas (EN MYSQL)
            // update_at sirve para establecer la fecha en la que se actualiza la entrada (EN MYSQL)

            // lo que se realiza a continuacion es la actualizacion de los valores que hay en el array alumno (como si se tratase de un update de mysql)
           
           
            
            $nombre_usuario = $_POST['nombre_usuario'];
            $telefono = $_POST['telefono'];
            $correo_electronico = $_POST['correo_electronico'];
            $mensajes = $_POST['mensajes'];
       
            $consultaSQL = "INSERT INTO comentario (nombre_usuario, telefono, correo_electronico, mensajes)values ('$nombre_usuario','$telefono','$correo_electronico','$mensajes')";
            $sentencia = $conexion -> prepare($consultaSQL);
            $sentencia -> execute();
            $resultado['error'] = true;
        } catch (PDOException $error) {

            $resultado['error'] = true;
            $resultado['mensaje'] = $error -> getMessage();

        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contacto</title>
    <!-- estilos de contacto -->
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/font-awesome.css">
    <!-- estilos de barra de navegacion -->
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Cherry+Swash'>
    <link rel="stylesheet" href="../dist/style.css">
    <!-- js de contacto -->
    <script src="js/jquery-3.2.1.js"></script>
    <script src="js/script.js"></script>
</head>
<body class="body_nav" style="background: linear-gradient(90deg, #49a09d, #5f2c82);">
    <h1 class="h1_nav" style="background: black; margin: 0px 0px; padding: 18px 0px 5px 0px; font-weight: 100">Nice Code</h1>
	<div style="background: #34495e; height:50px; width: auto;">
		<nav class="nav_nav" style="margin-top:0px">
		<?php include '../templates/nav.php' ?>
			<div class="animation start-blog"></div>
		</nav>
		<!-- <p class="p_nav">
			By <span class="span_nav">Nice Code</span>
		</p> -->
	</div>
    <!-- a continuacion imprime un mensaje de error si no se encontro el alumno -->
    <?php
    if (isset($_POST['submit']) && $resultado['error']==true) {
            ?>
            <div class="container mt-2" style="width:65%">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success" role="alert" style="margin: 0px">El Mensaje se ha enviado correctamente</div>
                    </div>
                </div>
            </div>
    <?php 
    }
    ?>
    <section class="form_wrap" style="margin:35px auto">
        <section class="cantact_info">
            <section class="info_title">
                <span class="fa fa-user-circle"></span>
                <h2>INFORMACION<br>DE CONTACTO</h2>
            </section>
            <section class="info_items">
                <p><span class="fa fa-envelope"></span> niceCode@gmail.com</p>
                <p><span class="fa fa-mobile"></span> (998) 39 89 646</p>
            </section>
        </section>
        <form class="form_contact" method="Post" >
            <h2>Enviar un mensaje</h2>
            <div class="user_info">
                <label for="names">Nombre *</label>
                <input type="text" id="names" name="nombre_usuario" maxlength="30" required pattern="[A-Za-z0-9 ]+" title="Solo se aceptan letras de la [A-Z] o [a-z] y numeros del 0 al 9">

                <label for="phone">Telefono / Celular</label>
                <input type="text" id="phone" name="telefono" maxlength="10" required pattern="[0-9]+" title="Solo se aceptan numeros del 0 al 9">

                <label for="email">Correo electronico *</label>
                <input type="text" id="email" name="correo_electronico" maxlength="50" required pattern="[A-Za-z0-9@.]+" title="Solo se aceptan letras de la [A-Z] o [a-z] y numeros del 0 al 9">

                <label for="mensaje">Mensaje *</label>
                <textarea id="mensaje" name="mensajes" maxlength="100" required pattern="[A-Za-z0-9 ]+" title="Solo se aceptan letras de la [A-Z] o [a-z] y numeros del 0 al 9"></textarea>

                <input type="submit" name="submit" id="btnSend" class="btn btn-outline-success">
                <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
            </div>
        </form>
    </section>
</body>
</html>
