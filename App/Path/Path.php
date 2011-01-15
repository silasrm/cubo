<?php

    namespace App\Path;
    
    /**
     * resolve o carregamento de arquivos dinâmicos
     * @author Silas Ribas
    */
    class Path
    {
	/**
	 * inicia e resolve o carregamento
	*/
        public static function init()
        {
	    $infos = explode('/', $_SERVER['QUERY_STRING']);
	    $folder = null;
	    $file = null;
	    
	    // se o primeiro nível não for passado, seta null
	    if( trim($infos[0])  == "" )
		$folder = null;
	    else
		$folder = $infos[0] . '/';
	    
	    // se não existe informações sobre o segundo nível, carrega o index
	    if( count( $infos ) == 1 )
		$file = 'index.php';
	    else
		$file = $infos[1] . '.php';
	    
	    $pathPages = realpath( dirname( __FILE__ ) ) . '/../../pages/';
            
	    // se existir o arquivo solicitado, carrega
            if( file_exists( $pathPages . $folder . $file ) )
                require_once $pathPages . $folder . $file;
            else
	    // se não carrega o 404 erro
                require_once '404.php';
        }
    }

?>