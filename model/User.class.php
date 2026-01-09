<?php
class User {
    
    private $username;
    private $password;
    private $age;
    private $role;
    private $active;

    public function __construct($username=NULL, $password=NULL, $age=NULL, $role=NULL, $active=NULL) {
        $this->username=$username;
        $this->password=$password;
        $this->age=$age;
        $this->role=$role;
        $this->active=$active;
    }
    
    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username=$username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password=$password;
    }

    public function getAge() {
        return $this->age;
    }

    public function getRole() {
        return $this->role;
    }

    public function setAge($age) {
        $this->age = $age;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function getActive() {
        return $this->active;
    }

    public function setActive($active) {
        $this->active = $active;
    }
    
    public function __toString() {
        return sprintf("%s;%s;%s;%s;%s\n", 
            $this->username, 
            $this->password, 
            $this->age,
            $this->role,
            $this->active);
    }
    
}
