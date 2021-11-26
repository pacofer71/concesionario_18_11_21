<?php

namespace Concesionario;

use PDOException;
use PDO;
use Faker;
use Concesionario\Marcas;

class Coches extends Conexion
{
    private $id;
    private $modelo;
    private $kms;
    private $tipo;
    private $color;
    private $img;
    private $marca_id;

    public function __construct()
    {
        parent::__construct();
    }
    //__________________________________________ CRUD ______________________________
    public function create()
    {

        $q = "insert into coches(modelo, kms, tipo, color, img, marca_id) values(:m, :k, :t, :c, :i, :mi)";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':m' => $this->modelo,
                ':k' => $this->kms,
                ':t' => $this->tipo,
                ':c' => $this->color,
                ':i' => $this->img,
                ':mi' => $this->marca_id,

            ]);
        } catch (PDOException $ex) {
            die("Error al crear coche: " . $ex->getMessage());
        }
        parent::$conexion = null;
    }
    
    public function read()
    {
        $q = "select * from coches order by modelo";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error al devolver hay coches: " . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt;
    }
    
    public function update($id)
    {
        $q="update coches set modelo=:m, kms=:k, tipo=:t, color=:c, img=:i, marca_id=:mi where id=:id";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':m' => $this->modelo,
                ':k' => $this->kms,
                ':t' => $this->tipo,
                ':c' => $this->color,
                ':i' => $this->img,
                ':mi' => $this->marca_id,
                ':id'=>$id

            ]);
        } catch (PDOException $ex) {
            die("Error al actualizar coche: " . $ex->getMessage());
        }
        parent::$conexion = null;
    }
    
    public function delete($id)
    {
        $q = "delete from coches where id=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':i'=>$id
            ]);
        } catch (PDOException $ex) {
            die("Error al borrar coches: " . $ex->getMessage());
        }
        parent::$conexion = null;
    }

    //__________________________________________OTROS METODOS ______________________
    public function hayCoches(): bool
    {
        $q = "select * from coches";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error al comprobar si hay coches: " . $ex->getMessage());
        }
        parent::$conexion = null;

        return ($stmt->rowCount() != 0);
    }
    //______________________________________________________________________________
    public function crearCoches($cant)
    {
        if (!$this->hayCoches()) {
            //si no hay los crep
            $faker = Faker\Factory::create('es_ES');
            $idMarcas = (new Marcas)->getMarcasId();
            $APP_URL = "http://127.0.0.1/~pacofer71/pdo/concesionario/public/";
            for ($i = 0; $i < $cant; $i++) {
                $m = ucfirst($faker->word());
                $kms = $faker->numberBetween(1000, 99999);
                $tipo = $faker->randomElement(['Electrico', 'Hibrido', 'Gasolina', 'Gasoil', 'Gas']);
                $color = $faker->colorName();
                $img = $APP_URL . "img/coches/default.png";
                $marca_id = $faker->randomElement($idMarcas);
                (new Coches)->setModelo($m)
                    ->setKms($kms)
                    ->setTipo($tipo)
                    ->setColor($color)
                    ->setImg($img)
                    ->setMarca_id($marca_id)
                    ->create();
            }
        }
    }
    //-------------------------------------------------------------------------
    public function filtroCoches($c, $v)
    {
        $q = "select * from coches where $c=:valor order by modelo";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':valor' => $v
            ]);
        } catch (PDOException $ex) {
            die("Error al devolver hay coches: " . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt;
    }
    //-------------------------------------------------------------------
    public function detalleCoche($id)
    {
        $q = "select coches.*, nombre, pais, marcas.img as imagenMarca from coches, marcas where marcas.id=coches.marca_id AND coches.id=:id";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':id' => $id
            ]);
        } catch (PDOException $ex) {
            die("Error al recuperar datos de un coche: " . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    //___________________________________________ SETTERS __________________________
    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of modelo
     *
     * @return  self
     */
    public function setModelo($modelo)
    {
        $this->modelo = $modelo;

        return $this;
    }

    /**
     * Set the value of kms
     *
     * @return  self
     */
    public function setKms($kms)
    {
        $this->kms = $kms;

        return $this;
    }

    /**
     * Set the value of tipo
     *
     * @return  self
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Set the value of color
     *
     * @return  self
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Set the value of img
     *
     * @return  self
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Set the value of marca_id
     *
     * @return  self
     */
    public function setMarca_id($marca_id)
    {
        $this->marca_id = $marca_id;

        return $this;
    }
    //----------------metodo toString
    public function __toString(){
        return "modelo:{$this->modelo}, color:{$this->color}, kms:{$this->kms}, tipo:{$this->tipo}, img:{$this->img}";
    }
}
