<?php
if (!isset($_GET['id'])) {
    header("Location:index.php");
    die();
}
$id = $_GET['id'];
require dirname(__DIR__, 2) . "/vendor/autoload.php";

use Concesionario\Coches;

$coche = (new Coches)->detalleCoche($id);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- BootStrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- FONTAWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Detalle Coche</title>





</head>

<body style="background-color:#ff9800">
    <h5 class="text-center mt-2">Detalle Coche (cod='<?php echo $id ?>')</h5>
    <div class="container mt-2">
        <div class="my-2 p-4 mx-auto" style="background-color:#c0ca33; width:40rem">
            <div class="d-flex justify-content-center mb-1">
                <img src="<?php echo $coche->img; ?>" width='100rem' height='100rem' class='img-thumbnail d-block' />
            </div>
            <div class="mt-2">
                <h4 class="text-center"><?php echo $coche->modelo; ?></h4>
            </div>
            <div class="d-flex flex-row mt-4 justify-content-between">
                <div><b>Marca:&nbsp;</b>
                    <a href="fcoche.php?campo=marca_id&valor=<?php echo $coche->marca_id; ?>" class="p-1 rounded-pill bg-warning text-light" style="text-decoration:none">
                        <?php echo $coche->nombre; ?></a>
                </div>
                <div><b>Pais:&nbsp;</b><?php echo $coche->pais; ?></div>
            </div>
           
            <div class="mt-2">
                <b>Color:&nbsp;</b>
                <a href="fcoche.php?campo=color&valor=<?php echo $coche->color; ?>" class="p-1 rounded-pill bg-danger text-light" style="text-decoration:none">
                    <?php echo $coche->color; ?> </a>
            </div>
            <div class="mt-2">
                <b>Kilometros:&nbsp;</b><?php echo $coche->kms; ?>
            </div>
            <div class="mt-2">
                <b>Tipo:</b>&nbsp;
                <a href="fcoche.php?campo=tipo&valor=<?php echo $coche->tipo; ?>" class="p-1 rounded-pill bg-success text-light" style="text-decoration:none">
                    <?php echo $coche->tipo; ?> </a>
            </div>
            <div class='mt-4'>
                <a href="index.php" class='btn btn-primary'><i class='fas fa-backward'></i> Volver</a>
            </div>
        </div>

    </div>
</body>

</html>