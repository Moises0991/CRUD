<?php
    include 'funciones.php';
    csrf();
    if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        die();
    }
    $error = false;
    $config = include '../database/config.php';
    
    try {

        $dns = 'mysql:host=' . $config['db']['host'] .';dbname=' . $config['db']['name'];
        $conexion = new PDO($dns, $config['db']['user'] ,$config['db']['pass'] ,$config['db']['options']);
        
        if (isset($_POST['surname'])) {
            $consultaSQL = "SELECT * FROM usuarios WHERE apellidos_usuario LIKE '%" . $_POST['surname'] . "%'";
        } else {
            $consultaSQL = "SELECT * FROM usuarios";
        }

        $sentencia = $conexion -> prepare($consultaSQL);
        $sentencia -> execute();
        $usuarios = $sentencia -> fetchAll();
        
        
    } catch (PDOException $error) {

        if(!$conexion)
        {
        include '../database/instalar.php';
        header("Location: ../index.php");
        }
        $error = $error -> getMessage();
    }

    $titulo = isset($_POST['apellido']) ? 'Lista de Alumnos (' . $_POST['surname'] . ')' : 'Lista de Alumnos';
    
?>
<?php include "../templates/header.php"?>

<?php
    // lo que hay dentro de este php es para poder mostrar un mensaje de error en caso de que se genere uno 
    if ($error) {
        ?>
        <div class="container mt-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger" role="alert"><?= $error ?></div> <!--las etiquetas de apertura y cierre en el error son una abreviatura de \<\?php echo $a; ?>-->
                </div>
            </div>
        </div>
        <?php
    }
?>

<!-- <div class="container">
    <div class="row">
        <div class="col-md-12">
            <a href="create.php" class="btn btn-primary mt-4">Crear Alumno</a><hr>
            <form method="post" class="form-inline">
                <div class="form-group mr-3">
                    <input type="text" id="apellido" name="apellido" placeholder="Buscar por Apellido" class="form-control" maxlength="25" required pattern="[A-Za-z ]+" title="Solo se aceptan letras de la [A-Z] o [a-z]">
                </div>
                <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
                <button type="submit" name="submit" class="btn btn-primary">Buscar</button>
            </form>
            <?php
            ?>
        </div>
    </div>
</div> -->   


<?php include '../dist/navegacion.php'?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>inicio</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/perfect-scrollbar/perfect-scrollbar.css">
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	<div class="limiter">
		<div class="container-table100" style="background:#2c3e5000">
			<div class="wrap-table100">
				<div class="table100 ver5 m-b-110">
					<table data-vertable="ver5">
						<thead>
							<tr class="row100 head">
								<th class="column100 column1" data-column="column1">#</th>
								<th class="column100 column2" data-column="column2">Nombre</th>
								<th class="column100 column3" data-column="column3">Apellidos</th>
								<th class="column100 column4" data-column="column4">Edad</th>
								<th class="column100 column5" data-column="column5">Correo</th>
								<th class="column100 column6" data-column="column6">Password</th>
								<th class="column100 column7" data-column="column7" style="text-align: center">Acciones</th>
							</tr>
						</thead>
						<tbody>
                            <?php
                                if ($usuarios && $sentencia -> rowCount()>0) {
                                    foreach ($usuarios as $fila) {
                                        ?>
                                        <tr class="row100">
                                            <td class="column100 column1" data-column="column1"><?php echo escapar($fila["id"]);?></td>
                                            <td class="column100 column2" data-column="column2"><?=escapar($fila["nombre_usuario"]);?></td>
                                            <td class="column100 column3" data-column="column3"><?=escapar($fila["apellidos_usuario"]);?></td>
                                            <td class="column100 column4" data-column="column4"><?=escapar($fila["edad_usuario"]);?></td>
                                            <td class="column100 column5" data-column="column5"><?=escapar($fila["correo_usuario"]);?></td>
                                            <td class="column100 column6" data-column="column6"><?=escapar($fila["password_usuario"]);?></td>
                                            <!-- <td class="column100 column7" data-column="column7"><?=escapar($fila["create_at"]);?></td> -->
                                            
                                            <td style="text-align:center">
                                                <a href="<?= 'borrar.php?id=' . escapar($fila["id"]) ?>">️<button type="button" class="btn btn-danger">❌️️️Borrar</button></a>
                                                <a href="<?= 'editar.php?id=' . escapar($fila["id"]) ?>">️<button type="button" class="btn btn-secondary">📝Editar</button></a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                            ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="vendor/select2/select2.min.js"></script>
	<script src="js/main.js"></script>
</body>
</html>