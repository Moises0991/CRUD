<?php include '../templates/header.php';?>

<?php
  
  include 'funciones.php';
    csrf();
    if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        die();
    }
    $config = include '../database/config.php';

    $resultado = [
        'error' => false,
        'mensaje' => ''
    ];

    if (!isset($_GET['id'])) {
        $resultado['error'] = true;
        $resultado['mensaje'] = 'El usuario no existe';
    }
    
    // en las siguientes lineas se evalua si ya esta definido en el array post el submit (osea ya se envio el formulario)
    // y en caso de ser true se realiza la modificacion de la informacion del alumno con los nuevos datos enviados en el nuevo form
    if (isset($_POST['submit'])) {

        // try {

            // la conexion que se realiza en este try es para realizar la modificacion de los valores del alumno en la base de datos
            $dns = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name']; 
            $conexion = new PDO($dns, $config['db']['user'] , $config['db']['pass'], $config['db']['options']);

            // la clausla SET(en SQL) establece los nuevos valores para las columnas especificadas (EN MYSQL)
            // update_at sirve para establecer la fecha en la que se actualiza la entrada (EN MYSQL)

            // lo que se realiza a continuacion es la actualizacion de los valores que hay en el array alumno (como si se tratase de un update de mysql)
           
           
            $id = $_GET['id'];
            $nombre_usuario = $_POST['username'];
            $apellidos_usuario = $_POST['surname'];
            $password_usuario = md5($_POST['password']);
            $edad_usuario = $_POST['edad'];
            $correo_usuario = $_POST['email'];
            $profesion_usuario = "2";
       

        $consultaSQL = "UPDATE usuarios SET
                        nombre_usuario = '$nombre_usuario',
                        apellidos_usuario = '$apellidos_usuario',
                        password_usuario = '$password_usuario',
                        edad_usuario = '$edad_usuario',
                        correo_usuario = '$correo_usuario',
                        profesion_usuario = '$profesion_usuario',
                        update_at = NOW()
                        WHERE id ='$id'";


            $sentencia = $conexion -> prepare($consultaSQL);
            // la $sentencia que ya tiene establecida la conexion y la consulta que se realizara se ejecuta con el array alumno
            // que es el que contiene los datos que se van a actualizar
           
            $sentencia -> execute();

        // } catch (PDOException $error) {

            // $resultado['error'] = true;
            // $resultado['mensaje'] = $error -> getMessage();

        // }
    }

    try {

        // la conexion que se realiza en este try es para sacar la informacion actual de la base de datos
        $dns = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name']; 
        $conexion = new PDO($dns, $config['db']['user'] , $config['db']['pass'], $config['db']['options']);

        $id = $_GET['id'];
        $consultaSQL = "SELECT * FROM usuarios WHERE id=" . $id;

        $sentencia = $conexion -> prepare($consultaSQL);
        $sentencia -> execute();
        // FETCH_ASSOC sirve para Obtener una fila de resultado como un array asociativo
        $usuario = $sentencia -> fetch(PDO::FETCH_ASSOC);

        // el siguiente codigo se va a ejecutar si la varible alumno se encuentra vacia
        // cuando se evalua una variable funciona asi: si tiene valor devuelve verdadero en caso contrario devuelve falso
        if (!$usuario) {
            $resultado['error'] = true;
            $resultado['mensaje'] = 'No se ha encontrado el usuario';
        }

    } catch(PDOException $error) {

        $resultado['error'] = true;
        $resultado['mensaje'] = $error->getMessage();  

    }

?>

<!-- a continuacion imprime un mensaje de error si no se encontro el alumno -->
<?php 
    if ($resultado['error']) {
        ?>
        <div class="container mt-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger" role="alert"><?=$resultado['mensaje']?></div>
                </div>
            </div>
        </div>
        <?php
    }
?>

<!-- a continuacion se imprime un mensaje de confirmacion en caso de que no hayan habido errores -->

<!-- a continuacion es donde va el formulario -->

                <?php
    if (isset($usuario) && $usuario) {
        ?>

<html>
<head>
	<meta charset="utf-8">
	<title>Registrarse</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- estilos css -->
	<link rel="stylesheet" href="../fonts/material-design-iconic-font/css/material-design-iconic-font.css">
	<link rel="stylesheet" href="../css/registrar.css">
</head>
<body style="background: linear-gradient(90deg, #49a09d, #5f2c82);">
	<div class="wrapper">
   
		<form method="post">
			<div id="wizard" style="padding:25px 93px 0; ">
             <!-- mensajes de actualizar y error -->
             <!-- mensaje actualizar -->

            <?php
                if (isset($_POST['submit']) && !$resultado['error']) {
                    ?>
                    <div class="container mt-2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-success" role="alert">El usuario ha sido actualizado correctamente</div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            ?>
                <!-- fin mensaje actualizar -->
                 <!-- fin mensajes -->
				<!-- Seccion 1 ----------------------------------------------------->
				<h4></h4>
                <br>
                <section>
                <div class="form-header" >
                    <div class="avartar">
                        <a href="#">
                            <img src="../images/avartar.png" alt="">
                        </a>
                        <div class="avartar-picker">
                            <input type="file" name="file-1[]" id="file-1" class="inputfile" data-multiple-caption="{count} files selected" multiple />
                            <label for="file-1">
                                <i class="zmdi zmdi-camera"></i>
                                <span>Elegir Imagen</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-holder active">
                            <input type="text"  placeholder="Nombre" class="form-control" name="username" id="username" value="<?= escapar($usuario['nombre_usuario']) ?>">
                        </div>
                        <div class="form-holder">
                            <input type="text" placeholder="Apellidos" class="form-control" name="surname" id="surname" value="<?= escapar($usuario['apellidos_usuario']) ?>">
                        </div>
                        <div class="form-holder">
                            <input type="text" placeholder="Edad" class="form-control" name="edad" id="edad"value="<?= escapar($usuario['edad_usuario']) ?>">
                        </div>
                    </div>
                </div>
                <div class="form-holder">
                    <input type="text" placeholder="Correo" class="form-control" name="email" id="email" value="<?= escapar($usuario['correo_usuario']) ?>">
                </div>
                <div class="form-holder">
                    <input type="password" placeholder="Crear Contraseña" class="form-control" name="password" id="password">
                </div>
                <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">




            </section>

             

				<!-- Seccion 2 ----------------------->
				<h4></h4>
				<section>
					<div class="grid">
						<div class="row">
							<a href="#" class="grid-item" style="background-image: url(../images/programming-bg.jpg);">
								<div class="inner">
									<img src="../images/programming.png" alt="">
									<span>Programador</span>
								</div>
							</a>
							<a href="#" class="grid-item" style="background-image: url('../images/research-bg.jpg');">
								<div class="inner">
									<img src="../images/research.png" alt="">
									<span>Investigador</span>
								</div>
							</a>
							<a href="#" class="grid-item" style="background-image: url('../images/teacher-bg.jpg');">
								<div class="inner">
									<img src="../images/teacher.png" alt="">
									<span>Profesor</span>
								</div>
							</a>
						</div>
						<div class="row">
							<a href="#" class="grid-item" style="background-image: url('../images/tour-guide-bg.jpg');">
								<div class="inner">
									<img src="../images/tour-guide.png" alt="">
									<span>Guia Turistico</span>
								</div>
							</a>
							<a href="#" class="grid-item" style="background-image: url('../images/business-bg.jpg');">
								<div class="inner">
									<img src="../images/business.png" alt="">
									<span>Empresario</span>
								</div>
							</a>
							<a href="#" class="grid-item" style="background-image: url('../images/artist-bg.jpg');">
								<div class="inner">
									<img src="../images/artist.png" alt="">
									<span>Artista</span>
								</div>
							</a>
						</div>

				
                        <input type="submit" name="submit" class="btn btn-outline-success" value="Enviar">
					</div>
				</section>
			</div>
		</form>
	</div>
	<!-- js -->
	<script src="../js/jquery-3.3.1.min.js"></script>
	<script src="../js/jquery.steps.js"></script>
	<script src="../js/main.js"></script>
</body>
</html>


        <?php
    }
?>

