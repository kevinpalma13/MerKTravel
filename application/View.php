<?php
/**
 * Description View class
 * 
 * Establirem les vistes per a els diferents controladors
 * 
 * @param $_controlador Fiquem el controlador per a el cual volem la vista
 */
class View
{
    private $_controlador;
    //private $_js;
    
    public function __construct(Request $peticion) {
        $this->_controlador = $peticion->getControlador();
        //$this->_js = array();
    }
    
    /**
     * Description of funtion render
     * 
     * Ens mostrara la vista del controlador del que venim
     * 
     * @param type $vista
     * @throws Exception No existeix la vista
     */
    
    public function render($vista, $item = false)
    {
        $menu = array(
            array(
                'id' => 'inicio',
                'titulo' => 'Inicio',
                'enlace' => BASE_URL
                ),
            array(
                'id' => 'vuelos',
                'titulo' => 'Vuelos',
                'enlace' => BASE_URL . 'vuelos'
                ),
            array(
                'id' => 'hoteles',
                'titulo' => 'Hoteles',
                'enlace' => BASE_URL . 'hoteles'
                ),
            array(
                'id' => 'planes',
                'titulo' => 'Planes',
                'enlace' => BASE_URL . 'planes'
                )
        );
        
        if(Session::get('autenticado')){
            
            $menu[] = array(
                'id' => 'perfil',
                'titulo' => 'Mi Perfil',
                'enlace' => BASE_URL . 'perfil'
                );
            $menu[] = array(
                'id' => 'carrito',
                'titulo' => 'Carrito',
                'enlace' => BASE_URL . 'carrito'
                );
            $menu[] = array(
                'id' => 'login',
                'titulo' => 'Cerrar',
                'enlace' => BASE_URL . 'login/cerrar'
                );
        }
        else{
            $menu[] = array(
                'id' => 'login',
                'titulo' => 'Iniciar Sesión',
                'enlace' => BASE_URL . 'login'
                );
            $menu[] = array(
                'id' => 'registro',
                'titulo' => 'Regístrate',
                'enlace' => BASE_URL . 'registro'
                );
        }
        
        $_layoutParams = array(
            'ruta_css' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/css/',
            'ruta_img' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/img/',
            'ruta_js' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/js/',
            'ruta_rss' => BASE_URL . 'views/rss/',
            'menu' => $menu
        );
        
        $rutaView = ROOT . 'views' . DS . $this->_controlador . DS . $vista . '.phtml';
        
        if(is_readable($rutaView)){
            include_once ROOT . 'views'. DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'header.php';
            include_once $rutaView;
            include_once ROOT . 'views'. DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'footer.php';
        } 
        else {
            throw new Exception('Error de vista');
        }
    }
}

?>
