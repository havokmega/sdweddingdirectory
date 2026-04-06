<?php

/** 
 *  @return Days, Month, Week, Year with argument.
 */

if( ! function_exists( 'sdweddingdirectory_time_period' ) ){

        function sdweddingdirectory_time_period( $string, $start, $end ){

            $arr = [];

            for( $i = absint( $start ); $i<=absint( $end ); $i++ ){

                $arr[  sprintf( '+%1$s %2$s', $i, $string ) ] =  sprintf( '%1$s %2$s', $i, $string );
            }

            return $arr;
        }
}

if( ! function_exists( 'sdweddingdirectory_checklist_time_period_theme_option' ) ){

        function sdweddingdirectory_checklist_time_period_theme_option( $a, $b, $c ){

            $current_code = sdweddingdirectory_time_period( $a, $b, $c );

            $return_array = [];

            foreach ( $current_code as $key) {

                $return_array[] = array(

                    'value'       => sprintf( '-%1$s', $key ),
                    'label'       => sprintf( '-%1$s', $key ),
                    'src'         => ''
                );
            }

            foreach ( $current_code as $key) {

                $return_array[] = array(

                    'value'       => sprintf( '+%1$s', $key ),
                    'label'       => sprintf( '+%1$s', $key ),
                    'src'         => ''
                );
            }

            return $return_array;
        }   
}