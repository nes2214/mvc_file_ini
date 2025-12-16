<?php

require_once "model/ModelInterface.class.php";
require_once "model/persist/ConnectDb.class.php";

class ProductDbDAO implements ModelInterface {

    private static $instance = NULL;
    private $connect;

    public function __construct() {
        $this->connect = (new ConnectDb())->getConnection();
    }

    public static function getInstance(): ProductDbDAO {
        if (self::$instance == NULL) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function add($product): bool {
        if ($this->connect == NULL) {
            $_SESSION['error'] = "Unable to connect to database";
            return FALSE;
        }

        try {
            $sql = <<<SQL
                INSERT INTO product (id, name, price, description, category)
                VALUES (:id, :name, :price, :description, :category);
SQL;

            $stmt = $this->connect->prepare($sql);
            $stmt->bindValue(":id", $product->getId(), PDO::PARAM_INT);
            $stmt->bindValue(":name", $product->getName(), PDO::PARAM_STR);
            $stmt->bindValue(":price", $product->getPrice());
            $stmt->bindValue(":description", $product->getDescription(), PDO::PARAM_STR);
            $stmt->bindValue(":category", $product->getCategory(), PDO::PARAM_INT);

            $stmt->execute();

            return ($stmt->rowCount() > 0);

        } catch (PDOException $e) {
            return FALSE;
        }
    }

    public function modify($product): bool {
        if ($this->connect == NULL) {
            $_SESSION['error'] = "Unable to connect to database";
            return FALSE;
        }

        try {
            $sql = <<<SQL
                UPDATE product
                SET name = :name,
                    price = :price,
                    description = :description,
                    category = :category
                WHERE id = :id;
SQL;

            $stmt = $this->connect->prepare($sql);
            $stmt->bindValue(":id", $product->getId(), PDO::PARAM_INT);
            $stmt->bindValue(":name", $product->getName(), PDO::PARAM_STR);
            $stmt->bindValue(":price", $product->getPrice());
            $stmt->bindValue(":description", $product->getDescription(), PDO::PARAM_STR);
            $stmt->bindValue(":category", $product->getCategory(), PDO::PARAM_INT);

            $stmt->execute();

            return ($stmt->rowCount() > 0);

        } catch (PDOException $e) {
            return FALSE;
        }
    }

    public function delete($id): bool {
        if ($this->connect == NULL) {
            $_SESSION['error'] = "Unable to connect to database";
            return FALSE;
        }

        try {
            $sql = <<<SQL
                DELETE FROM product WHERE id = :id;
SQL;

            $stmt = $this->connect->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            return ($stmt->rowCount() > 0);

        } catch (PDOException $e) {
            return FALSE;
        }
    }

    public function listAll(): array {
        $result = array();

        if ($this->connect == NULL) {
            $_SESSION['error'] = "Unable to connect to database";
            return $result;
        }

        try {
            $sql = <<<SQL
                SELECT id, name, price, description, category FROM product;
SQL;

            $stmt = $this->connect->query($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Product');

            return $stmt->fetchAll();

        } catch (PDOException $e) {
            return $result;
        }
    }

    public function searchById($id) {
        if ($this->connect == NULL) {
            $_SESSION['error'] = "Unable to connect to database";
            return NULL;
        }

        try {
            $sql = <<<SQL
                SELECT id, name, price, description, category
                FROM product
                WHERE id = :id;
SQL;

            $stmt = $this->connect->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount()) {
                $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Product');
                return $stmt->fetch();
            }

            return NULL;

        } catch (PDOException $e) {
            return NULL;
        }
    }
}