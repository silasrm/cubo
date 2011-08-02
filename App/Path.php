<?php

    namespace App;
    
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
		    self::_build();
        }

        protected function _build()
        {
        	if( strstr( $_SERVER['REQUEST_URI'], BASEURL ) !==  false )
        	{
        		$infos = str_replace( BASEURL, '', $_SERVER['REQUEST_URI'] );

        		$fullPath = array();
			    $path = realpath( './pages/' );
			    
		    	if( is_dir( $path . '/' . $infos ) )
		    	{
		    		if( file_exists( $path . '/' . $infos . 'index.php' ) )
		    		{
		    			require_once $path . '/' . $infos . 'index.php';
		    		}
		    		else if( file_exists( $path . '/' . $infos . '/index.php' ) )
		    		{
		    			require_once $path . '/' . $infos . '/index.php';
		    		}
		    		else
		    			require_once '404.php';
		    	}
		    	else if( file_exists( $path . '/' . $infos . '.php' ) )
		    	{
		    		require_once $path . '/' . $infos . '.php';
		    	}
		    	else
		    		require_once '404.php';
        	}
        	else
        		require_once '404.php';
        }
    }