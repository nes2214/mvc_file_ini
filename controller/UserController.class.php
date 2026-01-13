<?php
require_once "controller/ControllerInterface.php";
require_once "view/UserView.class.php";
require_once "model/UserModel.class.php";
require_once "model/User.class.php";
require_once "util/UserMessage.class.php";
require_once "util/UserFormValidation.class.php";

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
                case "add":
                    $this->add();                
                    break;
                case "search":
                    $this->searchById();
                    break;
                case "delete":
                    $this->delete();
                    break;
                case "modify":
                    $this->modify();
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

        $user=$this->model->searchById($userValid->getUsername());
        
        if (!is_null($user) && ($userValid->getPassword() == $user->getPassword())) {
            if($user->isActive()==false){
                $_SESSION['error'][] = "User is not active. Contact the administrator.";
                header("Location: index.php");
                return;
            }
            session_start();
            $_SESSION['username']=$user->getUsername();
            $_SESSION['user']=$user; // Guardar el objeto user completo para acceder al rol
        }
        header("Location: index.php");
    }

    public function logout() {
        session_destroy();
        header("Location: index.php");
    }  
    
    
    public function add() {
        // Verificar si el usuario tiene rol advanced
        if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'advanced') {
            $_SESSION['error'][] = "No tienes permisos para realizar esta acción";
            header("Location: index.php");
            return;
        }

        $userValid = UserFormValidation::checkData(UserFormValidation::ADD_FIELDS);

        if (!empty($_SESSION['error'])) {
            $this->view->display("view/form/UserFormAdd.php", $userValid);
            return;
        }

        $user = $this->model->searchById($userValid->getUsername());

        if ($user !== null) {
            $_SESSION['error'][] = UserMessage::ERR_FORM['exists_user'];
            $this->view->display("view/form/UserFormAdd.php", $userValid);
            return;
        }

        $result = $this->model->add($userValid);

        if ($result === true) {
            $_SESSION['info'][] = UserMessage::INF_FORM['insert'];
            $this->view->display("view/form/UserFormAdd.php");
        } else {
            $_SESSION['error'][] = UserMessage::ERR_DAO['insert'];
            $this->view->display("view/form/UserFormAdd.php", $userValid);
        }
    }


    public function delete() {
        // Verificar si el usuario tiene rol advanced
        if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'advanced') {
            $_SESSION['error'][] = "No tienes permisos para realizar esta acción";
            header("Location: index.php");
            return;
        }

        $userValid = UserFormValidation::checkData(UserFormValidation::DELETE_FIELDS);
        if (empty($_SESSION['error'])) {
            $user = $this->model->searchById($userValid->getUsername());

            if (!is_null($user)) {
                $result = $this->model->delete($userValid->getUsername());

                if ($result === TRUE) {
                    $_SESSION['info'][] = UserMessage::INF_FORM['delete'];

                } else {
                    $_SESSION['error'][] = UserMessage::ERR_DAO['delete'];
                }
            } else {
                $_SESSION['error'][] = UserMessage::ERR_FORM['not_found'];
            }
        }
        $this->view->display("view/form/UserFormSModDel.php", $userValid);
    }

    public function listAll() {
        // Verificar si el usuario tiene rol advanced
        if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'advanced') {
            $_SESSION['error'][] = "No tienes permisos para realizar esta acción";
            header("Location: index.php");
            return;
        }

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
        // Verificar si el usuario tiene rol advanced
        if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'advanced') {
            $_SESSION['error'][] = "No tienes permisos para realizar esta acción";
            header("Location: index.php");
            return;
        }

        $userValid = UserFormValidation::checkData(UserFormValidation::MODIFY_FIELDS);
        if (empty($_SESSION['error'])) {
            $user = $this->model->searchById($userValid->getUsername());

            if (!is_null($user)) {
                $result = $this->model->modify($userValid);

                if ($result === TRUE) {
                    $_SESSION['info'][] = UserMessage::INF_FORM['update'];
                } else {
                    $_SESSION['error'][] = UserMessage::ERR_DAO['update'];
                }
            } else {
                $_SESSION['error'][] = UserMessage::ERR_FORM['not_found'];
            }
        }
        $this->view->display("view/form/UserFormSModDel.php", $userValid);
    }

    public function searchById() {
        // Verificar si el usuario tiene rol advanced
        if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'advanced') {
            $_SESSION['error'][] = "No tienes permisos para realizar esta acción";
            header("Location: index.php");
            return;
        }

        $userValid=UserFormValidation::checkData(UserFormValidation::SEARCH_FIELDS);
        
        if (empty($_SESSION['error'])) {
            $user=$this->model->searchById($userValid->getUsername());

            if (!is_null($user)) { // is NULL or Category object?
                $_SESSION['info']=UserMessage::INF_FORM['found'];
                $userValid=$user;
            }
            else {
                $_SESSION['error']=UserMessage::ERR_FORM['not_found'];
            }
        }

        $this->view->display("view/form/UserFormSModDel.php", $userValid);
    }
    
    public function formAdd() {
        // Verificar si el usuario tiene rol advanced
        if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'advanced') {
            $_SESSION['error'][] = "No tienes permisos para realizar esta acción";
            header("Location: index.php");
            return;
        }

        $this->view->display("view/form/UserFormAdd.php");
    }

    public function FormSModDel() {
        // Verificar si el usuario tiene rol advanced
        if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'advanced') {
            $_SESSION['error'][] = "No tienes permisos para realizar esta acción";
            header("Location: index.php");
            return;
        }

        $this->view->display("view/form/UserFormSModDel.php");
    }
    

}