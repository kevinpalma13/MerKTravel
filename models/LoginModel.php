<?php

class loginModel extends Model
{
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * getUsuario Mirara si l'usuari i el password son correctes.
     * 
     * @param string $usuario   Usuari que fiquem al formulari de logueix
     * @param string $password  Password que fiquem al formulari de logueix
     * @return type
     */
    public function getUsuario($usuario, $password)
    {
        try{
            $datos = $this->_db->query(
                    "select * from usuaris " .
                    "where usuario = '$usuario' " .
                    "and password = '" . md5($password) ."'"
                    );

            return $datos->fetch();
            
        } catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
        }
    }
    
    /**
     * ReservesPendents mira si per a 'usuari conectat queda alguna reserva pendent.
     * 
     * @param int $idusuario
     * @return int Retorna el numero de reserves Pendents (Quet hauria de ser 1 o 0).
     */
    public function ReservesPendents($idusuario){
        try{
            $query = $this->_db->prepare("SELECT id FROM reserves WHERE idusuari = ? && status = 'Pendiente'");
            $query->bindParam(1, $idusuario);
            $query->execute();
            
            if($query){
                $row = $query->fetch();
                Session::set('id_reserva', $row[0]);
                //echo ' Reserva->'.Session::get('id_reserva');
            }
            return $query->rowCount();
        }
        catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return -1;
        }
    }
}