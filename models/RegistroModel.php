<?php

/**
 * Aqui registrem als nous usuaris
 */
class RegistroModel extends Model{
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Verifica si l'usuari ya existeix a la base de dades
     * 
     * @param type $usuario
     * @return boolean Si es true es que ya existeix i te que escollir un altre nom.
     */
    public function verificarUsuario($usuario){//Comprueba si el nuevo usuario ya existe en la BBDD
        $id = $this->_db->query(
                "select id from usuaris where usuario ='$usuario'"
                );
        
        if($id->fetch()){//Si lo hace es que ya existe el usuario
            return true;
        }
        
        return false;//No existe ese usuario en la BBDD
    }
    
    /**
     * 
     * @param type $email
     * @return boolean Si es true es que ya existeix aquest email a la bbdd.
     */
    public function verificarEmail($email){//Comprueba si el email del nuevo usuario ya existe en la BBDD
        $id = $this->_db->query(
                "select id from usuaris where email ='$email'"
                );
        
        if($id->fetch()){//Si lo hace es que ya existe el email en la BBDDD
            return true;
        }
        
        return false;//No existe ese email en la BBDD
    }
    
    /**
     * Aquesta funcio registra a l'usuari en la nostre aplicació
     * 
     * @param type $usuario     Nom d'usuari que fiquem al formulari
     * @param type $nombre      Nom que fiquem al formulari
     * @param type $apellidos   Cognoms que fiquem al formulari
     * @param type $email       Email que fiquem al formulari
     * @param type $pass        Password que fiquem al formulari
     */
    public function registrarUsuario($usuario, $nombre, $apellidos, $email, $pass){
        try{
            $query = $this->_db->prepare(
                "insert into usuaris values" .
                "(null, :usuario, :nom, :cognoms, :email, :pass, 3)"
                );
            $query->execute(array(
                    ':usuario' => $usuario,
                    ':nom' => $nombre,
                    ':cognoms' => $apellidos,
                    ':email' => $email,
                    ':pass' => md5($pass),
                    ));
        }
        catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
        }
    }
}
