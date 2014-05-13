<?php
/**
 * Description of HotelesModel
 * 
 * Mostrarem els hotels de la base de dades
 *
 * @author KEVIN
 */
class HotelesModel extends Model{
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Mostrem els hotels
     * @return type
     */
    public function getHoteles(){
        $hoteles = $this->_db->query("SELECT h.id, h.nom, h.ciutat, h.categoria, h.latitud, h.longitud, s.preu FROM hotels h INNER JOIN serveis s ON h.id = s.id;");
        
        return $hoteles->fetchall();
    }
    
    /**
     * Mostrem el numero d'hotels que hi han
     * @return type
     */
    public function numHoteles(){
        $query = $this->_db->query('SELECT COUNT(*) FROM hotels');
        $num_rows = $query->fetchColumn();
        $html = "<h2>Actualmente disponemos de $num_rows ofertas destacadas en hoteles</h2>";
        return $html;
    }
    
    /**
     * Per saber si existeixen hotels a la base de dades
     * @return boolean
     */
    public function mostrarHoteles(){
        $hoteles = $this->_db->query("select * from hotels");
        
        if($hoteles->fetch()){//Si lo hace es que existen vuelos en la BBDDD
            return true;
        }
        
        return false;//No existe ese email en la BBDD
    }
    
    /**
     * Aquesta funcio crea la reserva a traves d'un procediment
     * @return boolean
     */
    public function CrearReserva(){
        $idusuari = Session::get('id_usuario');
        try{
            //$creareserva = $this->_db->prepare("CALL sp_crea_reserva (CURDATE(), ? ,'Pendiente', @ex);");
            $creareserva = $this->_db->prepare("INSERT INTO reserves (data, idusuari, status) VALUES (CURDATE(), ?, 'Pendiente')");
            $creareserva->bindParam(1, $idusuari);
            $creareserva->execute();
        }
        catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return false;
        }
        return true;
    }
    
    /**
     * Aquesta funcio afegeix serveis a una reserva (Afegim al carrito)
     * @return boolean
     */
    public function AfegirServeisReservats(){
        $idusuari = Session::get('id_usuario');
        $idservei = Session::get('idservei');
        //echo 'Servei->'.$idservei;
        
        $idreserva = $this->_db->query("SELECT id FROM reserves WHERE idusuari = $idusuari && status = 'Pendiente';");
        if($idreserva){
            $row = $idreserva->fetch();
            Session::set('id_reserva', $row[0]);
            $reserva = $row[0];
            //echo ' Reserva->'.$reserva;
        }
        
        $SelectIDyReserva = $this->_db->query("SELECT places FROM serveis_reservats WHERE idservei = $idservei && idreserva = $reserva");
        if($SelectIDyReserva){
            $row2 = $SelectIDyReserva->fetch();
            $placesNew = $row2[0]+1;
            //echo ' Places->'.$placesNew;
        }
        
        try{
            if($placesNew > 1){
                $query = $this->_db->prepare("UPDATE serveis_reservats SET places = ? WHERE idservei = $idservei && idreserva = $reserva");
                $query->bindParam(1, $placesNew);
                $query->execute();
            }
            else{
                $query = $this->_db->prepare("INSERT INTO serveis_reservats VALUES (?, ?, NULL, 1, NULL)");
                $query->bindParam(1, $idservei);
                $query->bindParam(2, $reserva);
                $query->execute();
            }
        } 
        catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * Funcio que busca si l'usuari que inicia sessio ya te iniciada una reserva i no cal crearne una de nova
     * 
     * @param int $idusuario Usuari conectat
     * @return type
     */
    public function ReservesPendents($idusuario){
        try{
            $query = $this->_db->prepare("SELECT status FROM reserves WHERE idusuari = ?");
            $query->bindParam(1, $idusuario);
            $query->execute();
            return $query->rowCount();
        }
        catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return -1;
        }
    }
    
}