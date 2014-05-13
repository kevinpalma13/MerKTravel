<?php

/**
 * El que farà el fitxer Bootstrap.php és definir una sèrie de constants 
 * i variables globals que utilitzarem en el motor de la nostra aplicació 
 * i iniciar l'aplicació cap al controlador per defecte.
 * 
 * @param string $controller Sera el nom del controlador al que accedim. Exemple: userController.
 * @param string $rutaControlador Sera la ruta del controlador al que volem accedir.
 */

class Bootstrap
{
    public static function run(Request $peticion)
    {
        $controller = $peticion->getControlador() . 'Controller';
        $rutaControlador = ROOT . 'controllers' . DS . $controller . '.php';
        $metodo = $peticion->getMetodo();
        $args = $peticion->getArgs();
        
        if(is_readable($rutaControlador)){
            require_once $rutaControlador;
            $controller = new $controller;
            
            if(is_callable(array($controller, $metodo))){
                $metodo = $peticion->getMetodo();
            }
            else{
                $metodo = 'index';
            }
            
            if(isset($args)){
                call_user_func_array(array($controller, $metodo), $args);
            }
            else{
                call_user_func(array($controller, $metodo));
            }
            
        } else {
            throw new Exception('no encontrado');
        }
    }
}

?>