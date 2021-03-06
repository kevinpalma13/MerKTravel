<?php
/**
 * Description of VuelosModel
 *
 * Mostrarem els vols de la bbdd
 * 
 * @author KEVIN
 */
class VuelosModel extends Model{
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * getVuelos Ens dona tots els vols y el seu preu per mostrarlos posteriorment a la seva vista.
     * 
     * @return consulta
     */
    public function getVuelos(){
        $vuelos = $this->_db->query("SELECT v.id, v.dest, v.aeroport, s.preu FROM vols v INNER JOIN serveis s ON v.id = s.id;");
        
        return $vuelos->fetchall();
    }
    
    /**
     * numPlanes Ens mostra el numero de vols que hi ha a la bbdd.
     * 
     * @param String $html Retornara una cadena amb el numero de vols que hi ha per escollir
     * @return String
     */
    public function numVuelos(){
        $query = $this->_db->query('SELECT COUNT(*) FROM vols');
        $num_rows = $query->fetchColumn();
        $html = "<h2>Actualmente disponemos de $num_rows ofertas destacadas en vuelos</h2>";
        return $html;
    }

    /**
     * Mira si existeixen vols a la bbdd.
     * 
     * @return boolean  True si hi ha vols a la bbdd. False al contrari.
     */
    public function mostrarVuelos(){
        $vuelos = $this->_db->query("select * from vols");
        
        if($vuelos->fetch()){//Si lo hace es que existen vuelos en la BBDDD
            return true;
        }
        
        return false;//No existe ese email en la BBDD
    }
    
    /**
     * Creem una reserva si l'usuari no te cap Pendent
     * 
     * @return boolean True si tot ha anat bé. False al contrari.
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
     * Afegirem serveis al carrito de la reserva que estem realitzant.
     * 
     * @return boolean True si tot ha anat bé. False al contrari.
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
     * Mirem si l'usuariconectat te alguna reseerva Pendent
     * 
     * @param int $idusuario Es el id de l'usuari que esta conectat.
     * @return int Retorna cuantes reserves Pendents té l'usuari conectat
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