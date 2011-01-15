<?php

    namespace App\Debug;
    
    /**
     * imprime dados para debug
     * @author Silas Ribas
    */
    class Core
    {
        public static function dump( $var )
        {
            echo '<pre>';
            print_r( $var );
            echo '</pre>';
        }
    }

?>