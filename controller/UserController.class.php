<?php
require_once "controller/ControllerInterface.php";
require_once "view/UserView.class.php";
require_once "model/UserModel.class.php";
require_once "model/User.class.php";
require_once "util/UserMessage.class.php";

class UserController implements ControllerInterface{

    private $view;
    private $model;

    public function __construct() {
        $this->view=new UserView();            
        
        $this->model=new UserModel();
    }

    public function processRequest() {
        
        $request=NULL;
        $_SESSION['info']=array();
        $_SESSION['error']=array();
        
        if (filter_has_var(INPUT_POST, 'action')) {
            $request=filter_has_var(INPUT_POST, 'action')?filter_input(INPUT_POST, 'action'):NULL;
        }
        else {
            $request=filter_has_var(INPUT_GET, 'option')?filter_input(INPUT_GET, 'option'):NULL;
        }        
        
        if (isset($_SESSION['username'])) {
            switch ($request) {
                case "logout":
                    $this->logout();
                    break;
                case "form_add":
                    $this->formAdd();
                    break;
                case "list_all":
                    $this->listAll();
                    break;
                case "form_smoddel":
                    $this->FormSModDel();
                    break;
                $this->listAll();
                break;
                default:
                    $this->view->display();
            }
        }
        else {
            switch ($request) {
                case "login":
                    $this->login();
                    break;
                
                default:
                    $this->view->display("view/form/LoginForm.php");
            }            
        }
        
    }

    public function login() {
        $userValid=new User(trim(filter_input(INPUT_POST, 'username')), 
                       trim(filter_input(INPUT_POST, 'password')));

        $user=$this->model->searchByUsername($userValid->getUsername());
        
        if (!is_null($user) && ($userValid->getPassword() == $user->getPassword())) {
            session_start();
            $_SESSION['username']=$user->getUsername();
        }
        header("Location: index.php");
    }

    public function logout() {
        session_destroy();
        header("Location: index.php");
    }  
    
    
    public function add() {
        $userValid = UserFormValidation::checkData(UserFormValidation::ADD_FIELDS);

    if (empty($_SESSION['error'])) {

        $user = $this->model->searchById($userValid->getId());

        if (is_null($product)) {
            $result = $this->model->add($productValid);

            if ($result === TRUE) {
                $_SESSION['info'][] = UserMessage::INF_FORM['insert'];
                $userValid = null;
            } else {
                $_SESSION['error'][] = UserMessage::ERR_DAO['insert'];
            }
        } else {
            $_SESSION['error'][] = UserMessage::ERR_FORM['exists_id'];
        }
    }

   
    $user = UserDbDAO::getInstance()->listAll();

    $this->view->display("view/form/UserFormAdd.php", $userValid);
    }

    public function delete() {
        /*TODO */
    }

    public function listAll() {
        $user=$this->model->listAll();
        
        if (!empty($user)) { // array void or array of Category objects?
            $_SESSION['info']=UserMessage::INF_FORM['found'];
        }
        else {
            $_SESSION['error']=UserMessage::ERR_FORM['not_found'];
        }
        
        $this->view->display("view/form/UserList.php", $user);
    }

    public function modify() {
        /*TODO */
    }

    public function searchById() {
        /*TODO */
    }
    public function formAdd() {
        $this->view->display("view/form/UserFormAdd.php");
    }

    public function FormSModDel() {
        $this->view->display("view/form/UserFormSModDel.php");
    }
    

}