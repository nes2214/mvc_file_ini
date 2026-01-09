<?php
// File
require_once "model/persist/UserFileDAO.class.php";


class UserModel {

    private $dataUser;

    public function __construct() {
        // File
        $this->dataUser=UserFileDAO::getInstance();        
    }

    public function add($user):bool {
        /*TODO*/
    }

    public function modify($user):bool {
        /*TODO*/
    }

    public function delete($username) {
        /*TODO*/
    }    
    
    public function searchByUser($user):bool {
         /*TODO*/
    }

    public function searchByUsername($username) {
        $result=$this->dataUser->searchByUsername($username);
        
        return $result;
    }

    public function listAll():array {
        $user=$this->dataUser->listAll();
        
        return $user;
    }
    
}