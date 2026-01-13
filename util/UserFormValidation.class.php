<?php
require_once "model/User.class.php";
require_once "util/UserMessage.class.php";

class UserFormValidation {

    const ADD_FIELDS = array('username','password','age','role','active');
    const MODIFY_FIELDS = array('username','password','age','role','active');
    const DELETE_FIELDS = array('username');
    const SEARCH_FIELDS = array('username');

    const USERNAME = "/^[a-zA-Z0-9_]+$/";
    const PASSWORD = "/^.{4,}$/";
    const NUMERIC = "/^[0-9]+$/";

    public static function checkData($fields) {
        $username = null;
        $password = null;
        $age = null;
        $role = null;
        $active = 0;

        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['error'])) $_SESSION['error'] = [];

        foreach ($fields as $field) {
            switch ($field) {

                case 'username':
                    $username = trim(filter_input(INPUT_POST, 'username'));
                    if (empty($username)) {
                        $_SESSION['error'][] = UserMessage::ERR_FORM['empty_username'];
                    } else if (!preg_match(self::USERNAME, $username)) {
                        $_SESSION['error'][] = UserMessage::ERR_FORM['invalid_username'];
                    }
                    break;

                case 'password':
                    $password = trim(filter_input(INPUT_POST, 'password'));
                    if (empty($password)) {
                        $_SESSION['error'][] = UserMessage::ERR_FORM['empty_password'];
                    } else if (!preg_match(self::PASSWORD, $password)) {
                        $_SESSION['error'][] = UserMessage::ERR_FORM['invalid_password'];
                    }
                    break;

                case 'age':
                    $age = trim(filter_input(INPUT_POST, 'age'));
                    if (empty($age)) {
                        $_SESSION['error'][] = UserMessage::ERR_FORM['empty_age'];
                    } else if (!preg_match(self::NUMERIC, $age) || $age < 0) {
                        $_SESSION['error'][] = UserMessage::ERR_FORM['invalid_age'];
                    }
                    break;

                case 'role':
                    $role = trim(filter_input(INPUT_POST, 'role'));
                    if (empty($role)) {
                        $_SESSION['error'][] = UserMessage::ERR_FORM['empty_role'];
                    }
                    break;

                case 'active':
                    $active = filter_has_var(INPUT_POST, 'active') ? 1 : 0;
                    break;
            }
        }

        return new User($username, $password, $age, $role, $active);
    }
}