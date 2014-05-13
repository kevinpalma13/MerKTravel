<?php

/**
 * Description errorController
 * 
 * Es el controlador que ens mostrara els errors a les pagines
 */

class errorController extends Controller
{
    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
        $this->_view->titulo = 'Error';
        $this->_view->mensaje = $this->_getError();
        $this->_view->render('index');
    }
    
    public function access($codigo)
    {
        $this->_view->titulo = 'Error';
        $this->_view->mensaje = $this->_getError($codigo);
        $this->_view->render('access');
    }
    
    private function _getError($codigo = false)
    {
        if($codigo){
            $codigo = $this->filtrarInt($codigo);
            if(is_int($codigo))
                $codigo = $codigo;
        }
        else{
            $codigo = 'default';
        }        
        
        $error['default'] = 'Ha ocurrido un error y la página no puede mostrarse';
        $error['5050'] = 'Acceso restringido!';
        $error['8080'] = 'Tiempo de la sesion agotado';
        
        if(array_key_exists($codigo, $error)){
            return $error[$codigo];
        }
        else{
            return $error['default'];
        }
    }
}

?>