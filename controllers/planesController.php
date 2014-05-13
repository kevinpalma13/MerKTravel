<?php
/**
 * Description of planesController
 *
 * @author KEVIN
 */
class planesController extends Controller{
    
    private $_planes;


    public function __construct() {
        parent::__construct();
        
        $this->_planes = $this->loadModel('Planes');//Cargamos el modelo planes
    }
    
    public function index() {
        $this->_view->titulo = 'Planes';
        
        if($this->_planes->mostrarPlanes()){    //Existen planes en la bbdd
            $iduser = Session::get('id_usuario');
            $mirarReserva = $this->_planes->ReservesPendents($iduser);
            if($mirarReserva > 0){
                Session::set('ReservesPendents', 1);
            }
            else{
                Session::set('ReservesPendents', 0);
            }
            
            $this->_view->numplanes = $this->_planes->numPlanes();
            $this->_view->planes = $this->_planes->getPlanes();
            $this->_view->render('index','planes');
        }
        else{
            $this->_view->render('error','planes');
        }
        
        if($this->getInt('EmpezarReserva') == 1){
            if($this->_planes->CrearReserva()){
                Session::set('ReservaCreada', true);  //NO LO USO DE MOMENTO, QUITAR AL ACABAR
                echo '<script>parent.window.location.reload(true);</script>';   //RECARGA LA PAGINA
            }
        }
        
        if($this->getInt('reservar')){
            $idservei = $_POST['reservar'];
            Session::set('idservei', $idservei);
            
            if($this->_planes->AfegirServeisReservats()){
                echo 'TRUE';
            }
            else if(!$this->_planes->AfegirServeisReservats()){
                echo 'FALSE';
            }
        }
        /*if($this->getInt('reservar')){
            $idservei = $_POST['reservar'];
            Session::set('idservei', $idservei);
            
            if($this->_planes->CrearReserva()){
                if($this->_planes->AfegirServeisReservats()){
                    $this->_view->render('index','planes');
                }
                else{
                    $this->_view->render('error','planes');
                }
            }
            else{
                $this->_view->render('error','planes');
            }
        }*/
    }

}