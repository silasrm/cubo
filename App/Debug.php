<?php

    namespace App;
    
    /**
     * imprime dados para debug
     * @author Silas Ribas
    */
    class Debug
    {
        /**
         * @example App\Debug::dump($_GET, 'GET')
         * @example App\Debug::dump($_GET)
         * @var array|object $var
         * @var string|null $label
         * @return void
         */
        public static function dump( $var, $label = null )
        {
            if( !is_null( $label ) )
                echo '<br />--->' . $label . ' {{ <br />';

            echo '<pre>';
            print_r( $var );
            echo '</pre>';

            if( !is_null( $label ) )
                echo '}}<br />';
        }
    }

?>