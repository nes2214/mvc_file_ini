<?php
require_once "model/ModelInterface.class.php";
require_once "model/persist/ConnectFile.class.php";

class ProductFileDAO implements ModelInterface {

    private static $instance=NULL; // instancia de la clase
    private $connect; // conexión actual

    const FILE="model/resource/products.txt";    
    
    public function __construct() {
        $this->connect=new ConnectFile(self::FILE);
    }

    // singleton: patrón de diseño que crea una instancia única
    // para proporcionar un punto global de acceso y controlar
    // el acceso único a los recursos físicos
    public static function getInstance():ProductFileDAO {
        if (is_null(self::$instance)) {
            self::$instance=new self();
        }
        return self::$instance;
    }

    /**
     * insert a product in file
     * @param $product product object to insert
     * @return TRUE or FALSE
     */    
    public function add($product):bool {
        $result=FALSE;

        // abre el fichero en modo append
        if ($this->connect->openFile("a+")) {
            fputs($this->connect->getHandle(), $product->__toString());
            $this->connect->closeFile();
            $result=TRUE;
        }

        return $result;
    }

    /**
     * update a product in file
     * @param $product product object to update
     * @return TRUE or FALSE
     */
public function modify($product): bool {
    $result = false;
    $lines = [];

    // 1. Leer todas las líneas
    if ($this->connect->openFile("r")) {
        while (!feof($this->connect->getHandle())) {
            $line = trim(fgets($this->connect->getHandle()));

            if ($line !== "") {
                $fields = explode(";", $line);

                // Si coincide el ID → reemplazamos
                if ($fields[0] == $product->getId()) {
                    $lines[] = $product->__toString(); 
                    $result = true;
                } else {
                    // Si NO coincide → mantenemos línea original
                    $lines[] = $line;
                }
            }
        }
        $this->connect->closeFile();
    }

    // 2. Reescribir todo el archivo
    if ($this->connect->openFile("w")) {
        foreach ($lines as $line) {
            fwrite($this->connect->getHandle(), $line . PHP_EOL);
        }
        $this->connect->closeFile();
    }

    return $result;
}


    /**
     * delete a product in file
     * @param $id string product Id to delete
     * @return TRUE or FALSE
     */    
public function delete($id): bool {
    $result = false;
    $lines = [];

    // 1. Abrir archivo en modo lectura
    if ($this->connect->openFile("r")) {
        while (!feof($this->connect->getHandle())) {
            $line = trim(fgets($this->connect->getHandle()));

            if ($line !== "") {
                $fields = explode(";", $line);

                
                if ($fields[0] != $id) {
                    $lines[] = $line;
                } else {
                    $result = true;  
                }
            }
        }

        $this->connect->closeFile();
    }

   
    if ($this->connect->openFile("w")) {
        foreach ($lines as $line) {
            fwrite($this->connect->getHandle(), $line . PHP_EOL);
        }
        $this->connect->closeFile();
    }

    return $result;
}

    /**
     * select all categories from file
     * @param void
     * @return array of product objects or array void
     */    
    public function listAll():array {
        $result=array();

        // abre el fichero en modo read
        if ($this->connect->openFile("r")) {
            while(!feof($this->connect->getHandle())) {
                $line=trim(fgets($this->connect->getHandle()));
                if ($line!="") {
                    $fields=explode(";", $line);
                    $product=new product($fields[0], $fields[1] , $fields[2], $fields[3], $fields[4]);
                    array_push($result, $product);
                }
            }
            $this->connect->closeFile();
        }

        return $result;
    }    

    /**
    * select a product by Id from file
    * @param $id string product Id
    * @return product object or NULL
    */
    public function searchById($id) {
        $product=NULL;

        // abre el fichero en modo read
        if ($this->connect->openFile("r")) {
            while(!feof($this->connect->getHandle())) {
                $line=trim(fgets($this->connect->getHandle()));
                if ($line!="") {
                    $fields=explode(";", $line);
                    if($id == $fields[0]){
                        $product=new product($fields[0], $fields[1], $fields[2], $fields[3], $fields[4]);
                        break;
                    }
                    
                }
            }
            $this->connect->closeFile();
        }

        return $product;
    }

}