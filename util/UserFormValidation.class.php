<?php
require_once "model/User.class.php";
require_once "util/UserMessage.class.php";

class UserFormValidation {

    const ADD_FIELDS = array('id','username','password','email','role');
    const MODIFY_FIELDS = array('id','username','email','role');
    const DELETE_FIELDS = array('id');
    const SEARCH_FIELDS = array('id');
    const NUMERIC = "/^[0-9]+$/";
    const ALPHANUMERIC = "/^[a-zA-Z0-9_]+$/";
    const EMAIL = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

    public static function checkData($fields) {
        $id = NULL;
        $username = NULL;
        $password = NULL;
        $email = NULL;
        $role = NULL;

        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['error'])) $_SESSION['error'] = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'id':
                    $id = trim(filter_input(INPUT_POST, 'id'));
                    if (empty($id)) {
                        $_SESSION['error'][] = UserMessage::ERR_FORM['empty_id'];
                    } else if (!preg_match(self::NUMERIC, $id)) {
                        $_SESSION['error'][] = UserMessage::ERR_FORM['invalid_id'];
                    }
                    break;

                case 'username':
                    $username = trim(filter_input(INPUT_POST, 'username'));
                    if (empty($username)) {
                        $_SESSION['error'][] = UserMessage::ERR_FORM['empty_username'];
                    } else if (!preg_match(self::ALPHANUMERIC, $username)) {
                        $_SESSION['error'][] = UserMessage::ERR_FORM['invalid_username'];
                    }
                    break;

                case 'password':
                    $password = trim(filter_input(INPUT_POST, 'password'));
                    if (empty($password)) {
                        $_SESSION['error'][] = UserMessage::ERR_FORM['empty_password'];
                    }
                    break;

                case 'email':
                    $email = trim(filter_input(INPUT_POST, 'email'));
                    if (empty($email)) {
                        $_SESSION['error'][] = UserMessage::ERR_FORM['empty_email'];
                    } else if (!preg_match(self::EMAIL, $email)) {
                        $_SESSION['error'][] = UserMessage::ERR_FORM['invalid_email'];
                    }
                    break;

                case 'role':
                    $role = trim(filter_input(INPUT_POST, 'role'));
                    if (empty($role)) {
                        $_SESSION['error'][] = "Role must be selected";
                    }
                    break;
            }
        }

        return new User($id, $username, $password, $email, $role);
    }
}