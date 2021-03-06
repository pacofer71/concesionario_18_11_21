<?php
namespace Concesionario;

use PDO;
use PDOException;
use Faker;

class Marcas extends Conexion{
    private $id;
    private $nombre;
    private $img;
    private $pais;
    public function __construct()
    {
        parent::__construct();
    }
    //------------ CRUD __________________
    public function create(){
        $q="insert into marcas(nombre, img, pais) values(:n, :i, :p)";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':n'=>$this->nombre,
                ':i'=>$this->img,
                ':p'=>$this->pais
            ]);
        }catch(PDOException $ex){
            die("Error al crear marca: ".$ex->getMessage());
        }
        parent::$conexion=null;
    }
    
    public function read(){
        $q="select * from marcas order by('nombre')";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("error al devolver marcas: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt;
    }

    public function update($id){
        $q="update marcas set nombre=:n, pais=:p, img=:i where id=:id";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':n'=>$this->nombre,
                ':i'=>$this->img,
                ':p'=>$this->pais,
                ':id'=>$id
            ]);
        }catch(PDOException $ex){
            die("Error al actualizar marca: ".$ex->getMessage());
        }
        parent::$conexion=null;

    }
    public function delete($id){
        $q="delete from marcas where id=:id";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':id'=>$id
            ]);
        }catch(PDOException $ex){
            die("error al borrar marca: ".$ex->getMessage());
        }
        parent::$conexion=null;

    }
    //_______________  OTROS METODOS _______________________
    public function crearMarcas($cant){
        $URL_APP="http://127.0.0.1/~pacofer71/pdo/concesionario/public/";
        if(!$this->hayMarcas()){
            $faker=Faker\Factory::create('es_ES');

            for($i=0; $i<$cant; $i++){
                $n=ucfirst($faker->word());
                $p=$faker->country();
                (new Marcas)
                ->setNombre($n)
                ->setPais($p)
                ->setImg($URL_APP."img/marcas/default.png")
                ->create();
            }
        }

    }
    //____________________________________________________________________
    public function hayMarcas(){
        $q="select * from marcas";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("error al comprobar si hay marcas: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return ($stmt->rowCount()!=0);
    }
    //______________________________________________________________________
    public function datosMarca($id){
        $q="select * from marcas where id=:id";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':id'=>$id
            ]);
        }catch(PDOException $ex){
            die("error al editar marca: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    //___________________________________________________________________
    public function getMarcasId(){
        $q="select id from marcas";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("error al devolver ids marca: ".$ex->getMessage());
        }
        parent::$conexion=null;
        $ids=[];
        while($fila=$stmt->fetch(PDO::FETCH_OBJ)){
            $ids[]=$fila->id;
        }
        return $ids;

    }
    //-------------------------------- devolver nombre e id de marca
    public function misMarcas(){
        $q="select id, nombre from marcas order by nombre";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("error al devolver ids marca: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetchAll(PDO::FETCH_OBJ);

    }

    //____________________________ SETTERS ________________________________

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
     * Set the value of nombre
     *
     * @return  self
     */ 
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

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
     * Set the value of pais
     *
     * @return  self
     */ 
    public function setPais($pais)
    {
        $this->pais = $pais;

        return $this;
    }
}