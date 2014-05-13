<?php

/**
 * Description of Model
 * 
 * Agafem la conexió ya establerta per utilitzarla als models
 * 
 * @param $_db La conexio es fica aquí.
 */

class Model
{
    protected $_db;
    
    public function __construct() {
        //$this->_db = new Database();
        $this->_db = Database::singleton();
    }
}

?>
