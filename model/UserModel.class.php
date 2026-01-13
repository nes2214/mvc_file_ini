<?php
require_once "model/persist/UserDbDAO.class.php";

class UserModel {

    private $dataUser;

    public function __construct() {
        $this->dataUser = UserDbDAO::getInstance();        
    }

    public function add($user): bool {
        return $this->dataUser->add($user);
    }

    public function modify($user): bool {
        return $this->dataUser->modify($user);
    }

    public function delete($username): bool {
        return $this->dataUser->delete($username);
    }

    public function searchById($id) {
        return $this->dataUser->searchById($id);
    }

    public function listAll(): array {
        return $this->dataUser->listAll();
    }
}