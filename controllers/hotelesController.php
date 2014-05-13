<?php
/**
 * Description of hotelesController
 *
 * @author KEVIN
 */
class hotelesController extends Controller{
    
    private $_hoteles;
    
    
    public function __construct() {
        parent::__construct();
        
        $this->_hoteles = $this->loadModel('Hoteles');//Cargamos el modelo hoteles
    }
    
    public function index() {
        $this->_view->titulo = 'Hoteles';
        
        if($this->_hoteles->mostrarHoteles()){    //Existen hoteles en la bbdd
            $iduser = Session::get('id_usuario');
            $mirarReserva = $this->_hoteles->ReservesPendents($iduser);
            if($mirarReserva > 0){
                Session::set('ReservesPendents', 1);
            }
            else{
                Session::set('ReservesPendents', 0);
            }
            $this->_view->numhoteles = $this->_hoteles->numHoteles();
            $this->_view->hoteles = $this->_hoteles->getHoteles();
            $this->_view->render('index','hoteles');
        }
        else{
            $this->_view->render('error','hoteles');
        }
        
        if($this->getInt('EmpezarReserva') == 1){
            if($this->_hoteles->CrearReserva()){
                Session::set('ReservaCreada', true);  //NO LO USO DE MOMENTO, QUITAR AL ACABAR
                echo '<script>parent.window.location.reload(true);</script>';   //RECARGA LA PAGINA
            }
        }
        
        if($this->getInt('reservar')){
            $idservei = $_POST['reservar'];
            Session::set('idservei', $idservei);
            
            if($this->_hoteles->AfegirServeisReservats()){
                echo 'TRUE';
            }
            else if(!$this->_hoteles->AfegirServeisReservats()){
                echo 'FALSE';
            }
        }
    }

}