<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of carritoController
 * 
 * Controlem els serveis reservats que te un usuari.
 *
 * @author KEVIN
 */
class carritoController extends Controller{
    
    private $_carrito;
    
    public function __construct() {
        parent::__construct();
        
        $this->_carrito = $this->loadModel('Carrito');//Cargamos el modelo hoteles
    }
    public function index() {
        $this->_view->titulo = 'Carrito';
        
        $this->_view->carrito = $this->_carrito->getCarrito();
        $this->_view->render('index','carrito');
        
        if($this->getInt('pagar') == 1){
            $idreserva = Session::get('id_reserva');
            if($this->_carrito->PagarReserva($idreserva)){
                Session::set('ReservaPagada', true);
                Session::set('ReservaCreada', false);
                Session::set('ReservesPendents', 0);
            }
            else{
                Session::set('ReservaPagada', false);
            }
            echo '<script>parent.window.location.reload(true);</script>';   //RECARGA LA PAGINA
        }
    }

}