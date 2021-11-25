<?php
if (!isset($_GET['id'])) {
    header("Location:index.php");
    die();
}
$id = $_GET['id'];

session_start();
require dirname(__DIR__, 2) . "/vendor/autoload.php";

use Concesionario\{Marcas, Coches, Imagen};

$marcas = (new Marcas)->misMarcas();
$tipos = ['Electrico', 'Hibrido', 'Gasolina', 'Gasoil', 'Gas'];
$errorGeneral = false;
$esteCoche = (new Coches)->detalleCoche($id);

function validaCampos($n, $v)
{
    global $errorGeneral;
    if (strlen($v) == 0) {
        $_SESSION[$n] = "***Rellene el campo $n";
        $errorGeneral = true;
    }
}

if (isset($_POST['enviar'])) {
    //Procesamos el formulario
    $modelo = trim(ucwords($_POST['modelo']));
    $color = trim(ucwords($_POST['color']));
    $kms = $_POST['kms'];
    $tipo = $_POST['tipo'];
    $marca_id = $_POST['marca'];

    validaCampos('modelo', $modelo);
    validaCampos('color', $color);
    //procesamos la imagen
    if (is_uploaded_file($_FILES['img']['tmp_name'])) {
        //he subido un fichero, comprobemos que realmente sea una imagen
        if ((new Imagen)->isImagen($_FILES['img']['type'])) {
            //Realmente subimos una imagen
            $imagen = new Imagen;
            $imagen->setAppUrl('http://127.0.0.1/~pacofer71/pdo/concesionario/public/');
            $imagen->setDirStorage(dirname(__DIR__) . "/img/coches/");
            $imagen->setNombreF($_FILES['img']['name']);
            //La guardamos en el ordenador
            if (!$imagen->guardarImagen($_FILES['img']['tmp_name'])) {
                //NO se pudo guardar la imagen
                $errorGeneral = true;
                $_SESSION['img'] = "***Error al intentar guardar imagen";
            } else {
                //ya tenemos la imagen guardad en el disco duro
                //generamos la url y seteamos el campo coche con esa url 
                //para guardarlo en la base de datos
                $coche = new Coches;
                $coche->setImg($imagen->getUrlImagen('coches'));
            }
        } else {
            //lo que he subido NO es una imagen
            $errorGeneral = true;
            $_SESSION['img'] = "***El archivo debe ser de tipo IMAGEN";
        }
    } else {
        //NO he subido ningún archivo pondre por defecto el default.png
        $coche = new Coches;
        $imagen = new Imagen;
        $imagen->setAppUrl('http://127.0.0.1/~pacofer71/pdo/concesionario/public/');
        $coche->setImg($imagen->guardardefault('coches'));
    }

    if (!$errorGeneral) {
        //guardamos todo, la imaen ya está seteada seteamos el resto de los campos
        $coche->setModelo($modelo)
            ->setColor($color)
            ->setTipo($tipo)
            ->setKms($kms)
            ->setMarca_id($marca_id)
            ->create();
        $_SESSION['mensaje'] = 'Coche Editado.';
        header("Location:index.php");
    } else {
        header("Location:{$_SERVER['PHP_SELF']}?id=$id");
    }
} else {

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

        <title>Editar Coche</title>
    </head>

    <body style="background-color:#ff9800">
        <h4 class="text-center">Editar Coche (<?php echo $esteCoche->id; ?>)</h4>
        <div class="container mt-2">
            <div class="my-2 p-4 mx-auto" style="background-color:#c0ca33; width:40rem">
                <div class="d-flex justify-content-center mb-1">
                    <img src="<?php echo $esteCoche->img; ?>" width='100rem' height='100rem' class='img-thumbnail d-block' />
                </div>
                <form name="s" action='<?php echo $_SERVER['PHP_SELF'] . "?id=$id"; ?>' method='POST' enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="n" class="form-label">Nombre Modelo</label>
                        <input type="text" class="form-control" id="n" placeholder="Nombre Modelo" name="modelo" value="<?php echo $esteCoche->modelo; ?>">
                        <?php
                        if (isset($_SESSION['modelo'])) {
                            echo "<p class='my-2 p-2 text-danger'>{$_SESSION['modelo']}</p>";
                            unset($_SESSION['modelo']);
                        }
                        ?>
                    </div>
                    <div class="mb-3">
                        <label for="p" class="form-label">Color</label>
                        <input type="text" class="form-control" id="p" placeholder="Color" name="color" value="<?php echo $esteCoche->color; ?>">
                        <?php
                        if (isset($_SESSION['color'])) {
                            echo "<p class='my-2 p-2 text-danger'>{$_SESSION['color']}</p>";
                            unset($_SESSION['color']);
                        }
                        ?>
                    </div>
                    <div class="mb-3">
                        <label for="k" class="form-label">Kilometros</label>
                        <input type="number" class="form-control" id="k" required placeholder="Kilometros" name="kms" step='1' min='0' value="<?php echo $esteCoche->kms; ?>">

                    </div>
                    <div class="mb-3">
                        <label for="t">Tipo</label>
                        <select class="form-control" id="t" name="tipo">
                            <?php
                            foreach ($tipos as $item) {
                                if ($item == $esteCoche->tipo)
                                    echo "<option selected>$item</option>";
                                else
                                    echo "<option>$item</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="m">Marca</label>
                        <select class="form-control" id="m" name="marca">
                            <?php
                            foreach ($marcas as $item) {
                                if ($item->id == $esteCoche->marca_id)
                                    echo "<option value='{$item->id}' selected>{$item->nombre}</option>";
                                else
                                    echo "<option value='{$item->id}'>{$item->nombre}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="i" class="form-label">Imagen Coche</label>
                        <input class="form-control" type="file" id="i" name="img">
                        <?php
                        if (isset($_SESSION['img'])) {
                            echo "<p class='my-2 p-2 text-danger'>{$_SESSION['img']}</p>";
                            unset($_SESSION['img']);
                        }
                        ?>
                    </div>
                    <div class="mb-3">
                        <button type="submit" name="editar" class="btn btn-success"><i class="fas fa-edit"></i> Editar</button>

                        <a href="index.php" class="btn btn-primary"><i class="fas fa-home"></i> Inicio</a>
                    </div>
                </form>
            </div>

        </div>
    </body>

    </html>
<?php } ?>