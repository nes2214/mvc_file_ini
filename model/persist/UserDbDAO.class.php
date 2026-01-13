<?php

require_once "model/ModelInterface.class.php";
require_once "model/persist/ConnectDb.class.php";
require_once "model/User.class.php";

class UserDbDAO implements ModelInterface {

    private static $instance = NULL;
    private $connect;

    public function __construct() {
        $this->connect = (new ConnectDb())->getConnection();
    }

    public static function getInstance(): UserDbDAO {
        if (self::$instance == NULL) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Agregar un usuario
    public function add($user): bool {
        if ($this->connect == NULL) {
            $_SESSION['error'] = "Unable to connect to database";
            return FALSE;
        }

        try {
            $sql = <<<SQL
                INSERT INTO users (username, password, age, role, active)
                VALUES (:username, :password, :age, :role, :active);
SQL;

            $stmt = $this->connect->prepare($sql);
            $stmt->bindValue(":username", $user->getUsername(), PDO::PARAM_STR);
            $stmt->bindValue(":password", $user->getPassword(), PDO::PARAM_STR);
            $stmt->bindValue(":age", $user->getAge(), PDO::PARAM_INT);
            $stmt->bindValue(":role", $user->getRole(), PDO::PARAM_STR);
            $stmt->bindValue(":active", $user->isActive(), PDO::PARAM_INT);

            $stmt->execute();
            return ($stmt->rowCount() > 0);

        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            return FALSE;
        }
    }

    // Modificar un usuario
    public function modify($user): bool {
        if ($this->connect == NULL) {
            $_SESSION['error'] = "Unable to connect to database";
            return FALSE;
        }

        try {
            $sql = <<<SQL
                UPDATE users
                SET password = :password,
                    age = :age,
                    role = :role,
                    active = :active
                WHERE username = :username;
SQL;

            $stmt = $this->connect->prepare($sql);
            $stmt->bindValue(":username", $user->getUsername(), PDO::PARAM_STR);
            $stmt->bindValue(":password", $user->getPassword(), PDO::PARAM_STR);
            $stmt->bindValue(":age", $user->getAge(), PDO::PARAM_INT);
            $stmt->bindValue(":role", $user->getRole(), PDO::PARAM_STR);
            $stmt->bindValue(":active", $user->isActive(), PDO::PARAM_INT);

            $stmt->execute();
            return ($stmt->rowCount() > 0);

        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            return FALSE;
        }
    }

    // Eliminar un usuario
    public function delete($username): bool {
        if ($this->connect == NULL) {
            $_SESSION['error'] = "Unable to connect to database";
            return FALSE;
        }

        try {
            $sql = "DELETE FROM users WHERE username = :username;";
            $stmt = $this->connect->prepare($sql);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->execute();

            return ($stmt->rowCount() > 0);

        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            return FALSE;
        }
    }

    // Listar todos los usuarios
    public function listAll(): array {
        $result = array();

        if ($this->connect == NULL) {
            $_SESSION['error'] = "Unable to connect to database";
            return $result;
        }

        try {
            $sql = "SELECT username, password, age, role, active FROM users;";
            $stmt = $this->connect->query($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');

            return $stmt->fetchAll();

        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            return $result;
        }
    }

    // Buscar un usuario por ID (username)
    public function searchById($username) {
        if ($this->connect == NULL) {
            $_SESSION['error'] = "Unable to connect to database";
            return NULL;
        }

        try {
            $sql = "SELECT username, password, age, role, active FROM users WHERE username = :username;";
            $stmt = $this->connect->prepare($sql);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount()) {
                $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');
                return $stmt->fetch();
            }

            return NULL;

        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            return NULL;
        }
    }

    // Método vacío
    public function searchByUsername($username) {
        
    }
}