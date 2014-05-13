<?php

/**
 * Description indexController
 * 
 * Ens mostrara la pagina d'inici de l'aplicació on ens dona la benvinguda
 */
class indexController extends Controller
{
    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
        $this->_view->titulo = 'Portada';
        $this->_view->render('index', 'inicio');
    }
}

?>