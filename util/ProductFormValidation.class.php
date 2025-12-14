<?php
require_once "model/Product.class.php";
require_once "util/ProductMessage.class.php";

class ProductFormValidation {

    const ADD_FIELDS = array('id','name','price','description','category');
    const MODIFY_FIELDS = array('id','name','price','description','category');
    const DELETE_FIELDS = array('id');
    const SEARCH_FIELDS = array('id');
    const NUMERIC = "/^[0-9]+$/";
    const ALPHANUMERIC = "/^[a-zA-Z0-9\s]+$/";

    public static function checkData($fields) {
        $id = NULL;
        $name = NULL;
        $price = NULL;
        $description = NULL;
        $category = NULL;

        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['error'])) $_SESSION['error'] = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'id':
                    $id = trim(filter_input(INPUT_POST, 'id'));
                    if (empty($id)) {
                        $_SESSION['error'][] = ProductMessage::ERR_FORM['empty_id'];
                    } else if (!preg_match(self::NUMERIC, $id)) {
                        $_SESSION['error'][] = ProductMessage::ERR_FORM['invalid_id'];
                    }
                    break;

                case 'name':
                    $name = trim(filter_input(INPUT_POST, 'name'));
                    if (empty($name)) {
                        $_SESSION['error'][] = ProductMessage::ERR_FORM['empty_name'];
                    } else if (!preg_match(self::ALPHANUMERIC, $name)) {
                        $_SESSION['error'][] = ProductMessage::ERR_FORM['invalid_name'];
                    }
                    break;

                case 'price':
                    $price = trim(filter_input(INPUT_POST, 'price'));
                    if (empty($price)) {
                        $_SESSION['error'][] = ProductMessage::ERR_FORM['empty_price'];
                    } else if (!is_numeric($price) || $price < 0) {
                        $_SESSION['error'][] = ProductMessage::ERR_FORM['invalid_price'];
                    }
                    break;

                case 'description':
                    $description = trim(filter_input(INPUT_POST, 'description'));
                    break;

                case 'category':
                    $category = trim(filter_input(INPUT_POST, 'category'));
                    if (empty($category)) {
                        $_SESSION['error'][] = "Category must be selected";
                    }
                    break;
            }
        }

        return new Product($id, $name, $price, $description, $category);
    }
}
