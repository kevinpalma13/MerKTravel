<?php

/**
 * Aquest es el controlador que es pondra en contacte amb el model per verificar que
 * l'usuari no existeixi a la base de dades, que l'email tampoc existeixi ya a la base de dades
 * i finalment, si tots es compleix, enregistri a l'usuari mitjançant tots els camps que
 * entrem al formulari. 
 */
class registroController extends Controller{
    
    private $_registro;
    
    public function __construct() {
        parent::__construct();
        
        $this->_registro = $this->loadModel('Registro');//Cargamos el modelo registro
    }
    
    public function index() {
        if(Session::get('autenticado')){//Si el usuario esta logeado no puede entrar en el registro de usuarios
            $this->redireccionar();
        }
        
        $this->_view->titulo = 'Registro';
        
        if($this->getInt('enviar') == 1){ 
            $this->_view->datos = $_POST;
            if(!$this->getAlphaNum('usuario')){//Si no hemos escrito en el campo
                $this->_view->_error = "Debe introducir su nombre de usuario";
                $this->_view->render('index','registro');
                exit;
            }
            if($this->_registro->verificarUsuario($this->getAlphaNum('usuario'))){//Si el usuario ya existe en la bbdd
                $this->_view->_error = "El usuario ".$this->getAlphaNum('usuario')." ya existe.";
                $this->_view->render('index','registro');
                exit;
            }
            if($this->_registro->verificarEmail($this->getTexto('email'))){//Si el email ya existe en la bbdd
                $this->_view->_error = "El email ".$this->getTexto('email')." ya existe.";
                $this->_view->render('index','registro');
                exit;
            }
            if($this->getSql('pass') != $this->getSql('confirmar')){//Si los passwords no son iguales
                $this->_view->_error = "El password de confirmación no es igual.";
                $this->_view->render('index','registro');
                exit;
            }
            
            $this->_registro->registrarUsuario(
                    $this->getAlphaNum('usuario'),
                    $this->getTexto('nombre'),
                    $this->getTexto('apellidos'),
                    $this->getTexto('email'),
                    $this->getSql('pass')
                    );
            
             if(!$this->_registro->verificarUsuario($this->getAlphaNum('usuario'))){
                $this->_view->_error = 'Error al registrar el usuario!';
                $this->_view->render('index', 'registro');
                exit;
             }
             
            $this->_view->datos = false;
            $this->_view->_mensaje = 'Registro completado con éxito!';
        } 
        
        $this->_view->render('index','registro');
    }
    
}