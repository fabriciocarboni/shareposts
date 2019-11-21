<?php
    //load config
    require_once 'config/config.php';
    require_once 'helpers/session_helper.php';
    require_once 'helpers/redirect_url.php';

    /* Autoload core Libraries and helpers if they exist.
    If by any chance you decide to create another directory
    with classes inside, you should add dir name in this array
    and the class should be instatiated in __contruct inside the class
    where the function is about to be used.
    i.e: private $helper;
    inside the __construct:
    $this->helper = new RedirectUrl;
    */
    spl_autoload_register( function ($class_name) {
        $dirs = array('libraries');

        foreach ($dirs as $dir) {
            $CLASSES_DIR = __DIR__ . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR;  // or whatever your directory is
            $file = $CLASSES_DIR . $class_name . '.php';
            if(file_exists($file)) require_once $file;  // only include if file exists, otherwise we might enter some conflicts with other pieces of code which are also using the spl_autoload_register function
        }
    });

    