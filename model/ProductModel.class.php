<?php
//require_once "model/persist/ProductFileDAO.class.php";

require_once "model/persist/ProductDbDAO.class.php";

class ProductModel {

    private $dataProduct;

    public function __construct() {
        // File
        //$this->dataProduct=ProductFileDAO::getInstance();
        
        // Database
        $this->dataProduct=ProductDbDAO::getInstance();
    }

    /**
     * insert a Product
     * @param $product Product object to insert
     * @return TRUE or FALSE
     */
    public function add($product):bool {
        $result=$this->dataProduct->add($product);
        
        if ($result==FALSE) {
            $_SESSION['error']=ProductMessage::ERR_DAO['insert'];
        }
        
        return $result;
    }

    /**
     * update a Product
     * @param $product Product object to update
     * @return TRUE or FALSE
     */
    public function modify($product):bool {
        $categories=$this->dataProduct->modify($product);
        
        return $categories;
    }

    /**
     * delete a Product
     * @param $id string Product Id to delete
     * @return TRUE or FALSE
     */    
    public function delete($id):bool {
        $categories=$this->dataProduct->delete($id);
        
        return $categories;
    }

    /**
     * select all categories
     * @param void
     * @return array of Product objects or array void
     */    
    public function listAll():array {
        $categories=$this->dataProduct->listAll();
        
        return $categories;
    }

    /**
    * select a Product by Id
    * @param $id string Product Id
    * @return Product object or NULL
    */
    public function searchById($id) {
        $product = $this->dataProduct->searchById($id);
        return $product;
    }
    


}