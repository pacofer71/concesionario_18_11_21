<?php
namespace Concesionario;

use PDOException;
use Faker;
use Concesionario\Marcas;

class Coches extends Conexion{
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
    public function create(){
        
        $q="insert into coches(modelo, kms, tipo, color, img, marca_id) values(:m, :k, :t, :c, :i, :mi)";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':m'=>$this->modelo,
                ':k'=>$this->kms,
                ':t'=>$this->tipo,
                ':c'=>$this->color,
                ':i'=>$this->img,
                ':mi'=>$this->marca_id,

            ]);
        }catch(PDOException $ex){
            die("Error al crear coche: ".$ex->getMessage());
        }
        parent::$conexion=null;
    }
    public function read(){
        $q="select * from coches order by marca_id, modelo";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Error al devolver hay coches: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt;


    }
    public function update(){

    }
    public function delete(){

    }

    //__________________________________________OTROS METODOS ______________________
    public function hayCoches(): bool{
        $q="select * from coches";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Error al comprobar si hay coches: ".$ex->getMessage());
        }
        parent::$conexion=null;

        return ($stmt->rowCount()!=0);

    }
    public function crearCoches($cant){
        if(!$this->hayCoches()){
            //si no hay los crep
            $faker=Faker\Factory::create('es_ES');
            $idMarcas=(new Marcas)->getMarcasId();
            $APP_URL="http://127.0.0.1/~pacofer71/pdo/concesionario/public/";
            for($i=0; $i<$cant; $i++){
                $m = ucfirst($faker->word());
                $kms=$faker->numberBetween(1000, 99999);
                $tipo=$faker->randomElement(['Electrico', 'Hibrido', 'Gasolina', 'Gasoil', 'Gas']);
                $color=$faker->colorName();
                $img=$APP_URL."img/coches/default.png";
                $marca_id=$faker->randomElement($idMarcas);
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
}