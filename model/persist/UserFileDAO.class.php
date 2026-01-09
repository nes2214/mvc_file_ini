<?php
require_once "model/persist/ConnectFile.class.php";

class UserFileDAO {

    private static $instance=NULL; // instancia de la clase
    private $connect; // conexión actual

    const FILE="model/resource/users.txt";    
    
    public function __construct() {
        $this->connect=new ConnectFile(self::FILE);
    }

    // singleton: patrón de diseño que crea una instancia única
    // para proporcionar un punto global de acceso y controlar
    // el acceso único a los recursos físicos
    public static function getInstance():UserFileDAO {
        if (is_null(self::$instance)) {
            self::$instance=new self();
        }
        return self::$instance;
    }
    
    public function searchByUsername($username) {
        $user=NULL;

        // abre el fichero en modo read
        if ($this->connect->openFile("r")) {
            while(!feof($this->connect->getHandle())) {
                $line=trim(fgets($this->connect->getHandle()));
                if ($line!="") {
                    $fields=explode(";", $line);
                    
                    if (is_numeric(strpos(strtolower($fields[0]), strtolower($username)))) {
                        $user=new User($fields[0], $fields[1], $fields[2], $fields[3], $fields[4]);
                        break;
                    }      
                }
            }
            $this->connect->closeFile();
        }

        return $user;
    }    

    public function listAll():array {
        $users=array();

        // abre el fichero en modo read
        if ($this->connect->openFile("r")) {
            while(!feof($this->connect->getHandle())) {
                $line=trim(fgets($this->connect->getHandle()));
                if ($line!="") {
                    $fields=explode(";", $line);
                    
                    $users[]=new User($fields[0], $fields[1], $fields[2], $fields[3], $fields[4]);
                }
            }
            $this->connect->closeFile();
        }

        return $users;
    }

    public function add($user):bool {
        $success=false;

        // abre el fichero en modo append
        if ($this->connect->openFile("a")) {
            fwrite($this->connect->getHandle(), $user->__toString());
            $this->connect->closeFile();
            $success=true;
        }

        return $success;
    }
        
}