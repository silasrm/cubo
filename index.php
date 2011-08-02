<?php

    define('ROOT_PATH', dirname(__FILE__) );
    
    require_once "config/conf.php";
    
    function __autoload($class) {  
        $class = str_replace('\\', '/', $class). '.php';  
        
        require_once($class);  
    }
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" lang="pt-BR" xml:lang="pt-BR">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo TITLE; ?></title>
        <link rel="stylesheet" href="css/style.css" type="text/css" charset="utf-8"  />
    </head>
    <body>
    <?php App\Path\Path::init(); ?>
    </body>
</html>
