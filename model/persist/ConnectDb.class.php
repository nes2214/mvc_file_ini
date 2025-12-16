<?php
class ConnectDb {
    
    public function __construct() {  
        
    }
    
    // connexió a la BD
    //TODO - canvieu host,BD, usuari, password
    public function getConnection() {
        $hostname='localhost';
        $username='proven';
        $password='1234';
        $dbname='categprod';

        $conn=NULL;
        
        try {
            $conn=new PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
//            printf("<p>Error code:</p><p>%s</p>", $e->getCode());
//            printf("<p>Error message:</p><p>%s</p>", $e->getMessage());
//            printf("<p>Stack trace:</p><p>%s</p>", nl2br($e->getTraceAsString()));
        }
        
        return $conn;
    }
    
}