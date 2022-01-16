<!-- a continuacion se evalua si se mostrara el menu de navegacion -->
<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
	<meta charset="UTF-8">
	<title>Nice Code</title>
	<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Cherry+Swash'>
	<link rel="stylesheet" href="../dist/style.css">
	</head>
	<body class="body_nav" style="background: linear-gradient(90deg, #49a09d, #5f2c82);">
    <h1 class="h1_nav" style="background: black; margin: 0px 0px; padding: 18px 0px 5px 0px">Nice Code</h1>
	<div style="background: #34495e; height:50px; width: auto;">
		<nav class="nav_nav" style="margin-top:0px">
			<?php include '../templates/nav.php' ?>
			<div class="animation start-about"></div>
		</nav>
	</div>
	</body>
	</html>
<?php
}
?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
<!-- fin de la evaluacion php -->
<?php
    include 'funciones.php';
	
    csrf();
    if (isset($_POST['submit'])&& !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        die();
    }

    if (isset($_POST['submit'])) { //el isset es una funcion que determina si una variable esta definida o no en el php
        $resultado = [
           'error' => false,
           'mensaje' => 'El usuario ' . escapar($_POST['username']) . ' ha sido agregado con éxito'
        ];
        $config = include '../database/config.php';

        try {

            $dns = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
            // a continuacion se crea la variable con el pdo que servira para la conexion a la base de datos
            $conexion = new PDO ($dns, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

            $contraseña = md5($_POST['password']);
			// en el siguiente array se guardan en las claves los valores que se recibieron en el submit de acuerdo al name del form
            $usuario = [
                "nombre_usuario" => $_POST['username'],
                "apellidos_usuario" => $_POST['surname'],
                "password_usuario" => $contraseña,
                "edad_usuario" => $_POST['edad'],
                "correo_usuario" => $_POST['email'],
                "profesion_usuario" => "1"
            ];

            // a continuacion se se crea la varible consulta sql; que sera la que realizara la consulta en la base de datos
            $consultaSQL = "INSERT INTO usuarios (nombre_usuario, apellidos_usuario, password_usuario, edad_usuario, correo_usuario, profesion_usuario)";
            // implode sirve para convertir un array en una cadena de texto
            // array_keys — Devuelve todas las claves de un array o un subconjunto de claves de un array
            $consultaSQL .= "values (:" .implode(", :", array_keys($usuario)).")";
            // hasta el punto anterior no se han especificado que los datos de la consulta provengan de el array usuarios
            // en la varible sentencia se almacena toda la informacion necesaria para almacenar los datos que ingrese el usuario
            //prepare sirve para preparar una sentencia para su ejecución y devuelve un objeto sentencia
            // Prepara una sentencia SQL para ser ejecutada por el método PDOStatement::execute().
            $sentencia = $conexion->prepare($consultaSQL);
            // en la siguiente linea se ejecuta la sentencia con los valores del array
            //Execute permite ejecutar un script o una función PHP
            $sentencia->execute($usuario);

        } catch (PDOException $error) {

            $resultado['error'] = true;
            $resultado['mensaje'] = $error -> getMessage();
            
        }
    }
?>
<!-- a continuacion solo se crea el formulario(ya con el css) y se le da el name a los elementos para su posterior uso en el $_POST -->
<!DOCTYPE html>
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
	<!-- mensajes de actualizar y error -->
	<!-- mensaje actualizar -->
	<?php
		if (isset($_POST['submit']) && !$resultado['error']) {
			?>
			<div class="container mt-2" style="width: 65%">
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-success" role="alert" style="margin-bottom:0px">El usuario ha sido creado correctamente</div>
					</div>
				</div>
			</div>
			<?php
		}
	?>
	<!-- fin mensaje actualizar -->
	<!-- fin mensajes -->
	<div class="wrapper">
		<form method="post">
			<div id="wizard" style="padding: 25px 93px 0;">
				<!-- Seccion 1 -->
				<h4></h4>
				<br>
				<section>
					<div class="form-header">
						<div class="avartar">
							<a href="#" style="display:inline">
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
								<input type="text"  placeholder="Nombre" class="form-control" name="username" id="username" >
							</div>
							<div class="form-holder">
								<input type="text" placeholder="Apellidos" class="form-control" name="surname" id="surname">
							</div>
							<div class="form-holder">
								<input type="text" placeholder="Edad" class="form-control" name="edad" id="edad">
							</div>
						</div>
					</div>
					<div class="form-holder">
						<input type="text" placeholder="Correo" class="form-control" name="email" id="email">
					</div>
					<div class="form-holder">
						<input type="password" placeholder="Crear Contraseña" class="form-control" name="password" id="password">
					</div>
                    <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
					<h4></h4>
					<div id="submit-wrap">
						<input type="submit" name="submit" id="submit" value="Guardar">
					</div>
				</section>
				<section></section>
			</div>
		</form>
	</div>
	<!-- js -->
	<script src="../js/jquery-3.3.1.min.js"></script>
	<script src="../js/jquery.steps.js"></script>
	<script src="../js/main.js"></script>
</body>
</html>