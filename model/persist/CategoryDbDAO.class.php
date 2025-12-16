<?php

require_once "model/ModelInterface.class.php";
require_once "model/persist/ConnectDb.class.php";

class CategoryDbDAO implements ModelInterface {

    private static $instance = NULL; // instancia de la clase
    private $connect; // conexión actual

    public function __construct() {
        $this->connect = (new ConnectDb())->getConnection();
    }

    // singleton: patrón de diseño que crea una instancia única
    // para proporcionar un punto global de acceso y controlar
    // el acceso único a los recursos físicos
    public static function getInstance(): CategoryDbDAO {
        if (self::$instance == NULL) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function add($category): bool {
        if ($this->connect == NULL) {
            $_SESSION['error'] = "Unable to connect to database";
            return FALSE;
        };

        try {
            $sql = <<<SQL
                INSERT INTO category (id,name) VALUES (:id,:name);
SQL;
            $stmt = $this->connect->prepare($sql);
            $stmt->bindValue(":id", $category->getId(), PDO::PARAM_INT);
            $stmt->bindValue(":name", $category->getName(), PDO::PARAM_STR);

            $stmt->execute(); 

            if ($stmt->rowCount()) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (PDOException $e) {
            return FALSE;
        }
    }

    public function modify($category): bool {
        if ($this->connect == NULL) {
            $_SESSION['error'] = "Unable to connect to database";
            return FALSE;
        };

        try {
            $sql = <<<SQL
                UPDATE category SET name=:name WHERE id=:id;
SQL;
        $stmt = $this->connect->prepare($sql);
        $stmt->bindValue(":id", $category->getId(), PDO::PARAM_INT);
        $stmt->bindValue(":name", $category->getName(), PDO::PARAM_STR);

        $stmt->execute();

        return ($stmt->rowCount() > 0);

    } catch (PDOException $e) {
        return FALSE;
    }
}

    public function delete($id): bool {
         if ($this->connect == NULL) {
            $_SESSION['error'] = "Unable to connect to database";
            return NULL;
        };

        try {
            $sql = <<<SQL
                DELETE FROM category WHERE id=:id;
SQL;
            $stmt = $this->connect->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            $stmt->execute(); 

            $result = ($stmt->rowCount() > 0);

            return $result;
        }catch (PDOException $e) {
            return $result;
        }
    }

    public function listAll(): array {
        $result = array();

        if ($this->connect == NULL) {
            $_SESSION['error'] = "Unable to connect to database";
            return $result;
        };

        try {
            $sql = <<<SQL
                SELECT id,name FROM category;
SQL;

            $result = $this->connect->query($sql); // devuelve los datos

            $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Category');

            return $result->fetchAll();
        } catch (PDOException $e) {
            return $result;
        }

        return $result;
    }

    public function searchById($id) {
        if ($this->connect == NULL) {
            $_SESSION['error'] = "Unable to connect to database";
            return NULL;
        };

        try {
            $sql = <<<SQL
                SELECT id,name FROM category WHERE id=:id;
SQL;

            $stmt = $this->connect->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            $stmt->execute(); // devuelve TRUE o FALSE

            if ($stmt->rowCount()) {
                $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Category');
                return $stmt->fetch();
            } else {
                return NULL;
            }
        } catch (PDOException $e) {
            return NULL;
        }
    }

}