<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CarritoModel
 * 
 * Farem les consultes necesaries per que el controlador pugui establir una bona vista
 *
 * @author KEVIN
 */
class CarritoModel extends Model{
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * @param int $reserva Tindra el id de la reserva del usuari conectat
     * @return type
     */
    public function getCarrito(){
        $reserva = Session::get('id_reserva');
        $carrito = $this->_db->query("SELECT * FROM serveis_reservats sr INNER JOIN serveis s ON sr.idservei = s.id WHERE idreserva = $reserva");
        
        return $carrito->fetchall();
    }
    
    /**
     * 
     * @param int $idreserva Tindra el id de la reserva del usuari conectat
     * @return boolean
     */
    public function PagarReserva($idreserva){
        try{
            $creareserva = $this->_db->prepare("UPDATE reserves SET status = 'Pagada' WHERE id = ?");
            $creareserva->bindParam(1, $idreserva);
            $creareserva->execute();
        }
        catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return false;
        }
        return true;
    }
    
}
