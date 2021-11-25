<?php
if (!isset($_POST['obj'])) {
    header("Location:index.php");
    die();
}
session_start();
$coche = unserialize($_POST['obj']);
require dirname(__DIR__, 2) . "/vendor/autoload.php";

use Concesionario\{Coches, Imagen};
//borramos de la base de datos
(new Coches)->delete($coche->id);
//Borro el Fichero SI no es el default.png
if (basename($coche->img) != 'default.png') {
    //borramos
    $imagen = (new Imagen)->setDirStorage(dirname(__DIR__) . "/img/coches/");
    $imagen->borrarFichero(basename($coche->img));
}
$_SESSION['mensaje'] = "Coche Borrado con éxito.";
header("Location:index.php");
