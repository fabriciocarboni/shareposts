<?php
    //load config
    require_once 'config/config.php';
    
    //Autoload core Libraries
    spl_autoload_register(function($className){
        require_once 'libraries/' . $className . '.php';
    });