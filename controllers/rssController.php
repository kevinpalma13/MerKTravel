<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 * Description of rssController
 * 
 * Aquest controlador ens mostrarà la vista del RSS que es demanava a la practica mitjnçant una serie de funcions
 */

class rssController extends Controller{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $this->_view->titulo = 'RSS';
        $this->_view->render('index','rss');
    }

}