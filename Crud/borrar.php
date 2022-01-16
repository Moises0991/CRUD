<?php 
  include 'funciones.php';
  csrf();
  if (isset($_POST['submit'])&& !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
    die();
  }
  $config = include '../database/config.php';

  $resultado = [
    'error' => false,
    'mensaje' => ''
  ];

  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $id = $_GET['id'];
    $consultaSQL = "DELETE FROM usuarios WHERE id=" . $id;

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();

    // header() es usado para enviar encabezados HTTP sin formato. 
    // header() debe ser llamado antes de mostrar nada por pantalla
    header('Location: inicio.php');
    // con la linea anterior se consiguio redireccionarnos al archivo index

  } catch (PDOException $error) {

    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();

  }
?>
<?php require "templates/header.php";?>

<!-- a continuacion solo se informa si ha sucidido algun error al intentar eliminar el alumno -->
<div class="container mt-2">
  <div class="row">
    <div class="col-md-12">
      <div class="alert alert-danger" role="alert"><?= $resultado['mensaje'] ?></div>
    </div>
  </div>
</div>

<?php require "templates/footer.php";?>