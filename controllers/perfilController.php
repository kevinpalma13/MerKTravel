<?php

/**
 * perfilController s'encarrega de mostrar l'informacio de l'usuari a la pestanya Mi Perfil.
 */
class perfilController extends Controller
{
    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
        $this->_view->titulo = 'Mi Perfil';
        $this->_view->render('index', 'perfil');
    }
}

?>