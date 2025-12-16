<?php
//require_once "model/persist/CategoryFileDAO.class.php";
    require_once "model/persist/CategoryDbDAO.class.php";

class CategoryModel {

    private $dataCategory;

    public function __construct() {
        // File
        //$this->dataCategory=CategoryFileDAO::getInstance();
        
        // Database
        $this->dataCategory=CategoryDbDAO::getInstance();
    }

    /**
     * insert a category
     * @param $category Category object to insert
     * @return TRUE or FALSE
     */
    public function add($category):bool {
        $result=$this->dataCategory->add($category);
        
        if ($result==FALSE) {
            $_SESSION['error']=CategoryMessage::ERR_DAO['insert'];
        }
        
        return $result;
    }

    /**
     * update a category
     * @param $category Category object to update
     * @return TRUE or FALSE
     */
    public function modify($category):bool {
        $categories=$this->dataCategory->modify($category);
        
        return $categories;
    }

    /**
     * delete a category
     * @param $id string Category Id to delete
     * @return TRUE or FALSE
     */    
    public function delete($id):bool {
        $categories=$this->dataCategory->delete($id);
        
        return $categories;
    }

    /**
     * select all categories
     * @param void
     * @return array of Category objects or array void
     */    
    public function listAll():array {
        $categories=$this->dataCategory->listAll();
        
        return $categories;
    }

    /**
    * select a category by Id
    * @param $id string Category Id
    * @return Category object or NULL
    */
    public function searchById($id) {
        $category = $this->dataCategory->searchById($id);
        return $category;
    }
    


}