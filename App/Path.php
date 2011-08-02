<?php

    namespace App;
    
    /**
     * resolve o carregamento de arquivos dinâmicos
     * @author Silas Ribas
    */
    class Path
    {
    	protected $_projectFolders = array();

    	public function __construct()
    	{
        	$this->_projectFolders[] = 'App';
        	$this->_projectFolders[] = 'config';
        	$this->_projectFolders[] = 'pages';
        	$this->_build();
    	}

		/**
		 * inicia e resolve o carregamento
		 */
        public static function init()
        {
        	$i = new self;
        }

        protected function _build()
        {
        	#if( !$this->_checkIsAProjectFolder( $_SERVER['REQUEST_URI'] ) )
        		#die;

        	if( strstr( $_SERVER['REQUEST_URI'], BASEURL ) !==  false )
        	{
        		$infos = str_replace( BASEURL, '', $_SERVER['REQUEST_URI'] );
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

        protected function _checkIsAProjectFolder( $path )
        {
        	$infos = explode( '/', str_replace( BASEURL, '', $path ) );
        	
        	if( !in_array( $infos[ 0 ], $this->_projectFolders ) )
        	{
        		return false;
        	}

        	return true;
        }
    }