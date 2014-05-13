<?php
/**
 * Description loginController
 * 
 * Establirem una conexio d'usuari
 */
class loginController extends Controller
{
    private $_login;
    
    public function __construct(){
        parent::__construct();
        $this->_login = $this->loadModel('Login');//cargo el modelo
    }
    
    public function index()
    {
        $this->_view->titulo = 'Iniciar Sesion';
        
        if($this->getInt('enviar') == 1){
            $this->_view->datos = $_POST;
            
            if(!$this->getAlphaNum('usuario')){
                $this->_view->_error = 'Debe introducir su nombre de usuario';
                $this->_view->render('index','login');
                exit;
            }
            
            if(!$this->getSql('pass')){
                $this->_view->_error = 'Debe introducir su password';
                $this->_view->render('index','login');
                exit;
            }
            
            $row = $this->_login->getUsuario(
                    $this->getAlphaNum('usuario'),
                    $this->getSql('pass')
                    );
            
            if(!$row){ //Si no devuelve ningun resultado...
                $this->_view->_error = 'Usuario y/o password incorrectos';
                $this->_view->render('index','login');
                exit;
            }
            else{
                Session::set('autenticado', true);
                //Session::set('level', $row['role']);
                Session::set('usuario', $row['usuario']);
                Session::set('id_usuario', $row['id']);
                Session::set('nombre', $row['nom']);
                Session::set('apellidos', $row['cognoms']);
                Session::set('email', $row['email']);
                $iduser = Session::get('id_usuario');
                
                $mirarReserva = $this->_login->ReservesPendents($iduser);
                if($mirarReserva > 0){
                    Session::set('ReservesPendents', 1);
                }
                else{
                    Session::set('ReservesPendents', 0);
                }
            }
            
            $this->redireccionar('perfil');
        }
        //print_r($_SESSION);
        $this->_view->render('index','login');
        
    }
    
    /**
     * Funcio que tanca la sessió y finalitza totes les variables de sessio
     */
    public function cerrar()
    {
        Session::destroy();
        $this->redireccionar();
    }
}