<?php 
/**
 * Description of vuelosController
 *
 * @author KEVIN
 */
class vuelosController extends Controller{
    
    private $_vuelos;
    
    public function __construct() {
        parent::__construct();
        
        $this->_vuelos = $this->loadModel('Vuelos');//Cargamos el modelo vuelos
    }
    
    public function index() {
        
        $this->_view->titulo = 'Vuelos';
        
        if($this->_vuelos->mostrarVuelos()){    //Existen vuelos en la bbdd
            $iduser = Session::get('id_usuario');
            $mirarReserva = $this->_vuelos->ReservesPendents($iduser);
            if($mirarReserva > 0){
                Session::set('ReservesPendents', 1);
            }
            else{
                Session::set('ReservesPendents', 0);
            }
            $this->_view->numvuelos = $this->_vuelos->numVuelos();
            $this->_view->vuelos = $this->_vuelos->getVuelos();
            $this->_view->render('index','vuelos');
        }
        else{
            $this->_view->render('error','vuelos');
        }
        
        if($this->getInt('EmpezarReserva') == 1){
            if($this->_vuelos->CrearReserva()){
                Session::set('ReservaCreada', true);  //NO LO USO DE MOMENTO, QUITAR AL ACABAR
                echo '<script>parent.window.location.reload(true);</script>';   //RECARGA LA PAGINA
            }
        }
        
        if($this->getInt('reservar')){
            //$idservei = $_POST['reservar'];
	    $idservei = filter_input(INPUT_POST, 'reservar');
            Session::set('idservei', $idservei);
            
            if($this->_vuelos->AfegirServeisReservats()){
                echo 'TRUE';
            }
            else if(!$this->_vuelos->AfegirServeisReservats()){
                echo 'FALSE';
            }
        }
        
    }


}