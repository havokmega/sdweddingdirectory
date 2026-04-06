<?php
/**
 *  Create Pagination
 *  -----------------
 *  @credit - https://jsfiddle.net/yw7y4wez/1739/
 *  ---------------------------------------------
 */
if ( ! function_exists( 'sdweddingdirectory_venue_pagination' ) ){

	function sdweddingdirectory_venue_pagination( $args = [] ) {

		$get_data           =   '';

		$section_class 		=	sanitize_html_class( 'sdweddingdirectory-pagination-post' );

		$section_name 		=	sanitize_html_class( 'sdweddingdirectory-venue-page-section' );

		/**
		 *  Load the Pagination Section with Data
		 *  -------------------------------------
		 */
		if( SDWeddingDirectory_Loader:: _is_array( $args ) ){

			extract( $args );
            
            if( SDWeddingDirectory_Loader:: _is_array( $venue_data ) ){

	            $start_div          =   $per_page;

	            $_total_post        =   count( $venue_data );

	            $end_div  = $page_counter = $_loop = absint( '1' );

	            $_pagination        =   $per_page < $_total_post;

                foreach ( $venue_data as $key => $value) {

                    #=======================#
                    if( $start_div % $per_page == absint( '0' ) && $_pagination ){

                        $get_data   .=

                        sprintf( '<div class="col-12 %1$s %3$s" id="%1$s-%2$s"><div class="row">', 

                        	/**
                        	 *  1. Section Name
                        	 *  ---------------
                        	 */
                        	esc_attr( $section_name ),

                        	/**
                        	 *  2. Count Current Page
                        	 *  ---------------------
                        	 */
                        	absint( $page_counter ),

                        	/**
                        	 *  3. Section Class
                        	 *  ----------------
                        	 */
                        	sanitize_html_class( $section_class )

                       	);

                        $page_counter++;
                    }
                    #=======================#

                    $get_data .= $value;

                    #=======================#
                    if( $end_div % $per_page == absint( '0' ) && $_pagination || ( $_total_post == $_loop )  ){

                        $get_data .= '</div></div>';
                    }

                    $end_div++; $start_div++; $_loop++;
                    #=======================#
                }
            }
		}

		return 	$get_data;
	}
}

/**
 *  Theme Option value function to get method.
 *  -----------------------------------------
 */
if ( ! function_exists( 'sdweddingdirectory_option' ) ) {

    function sdweddingdirectory_option( $key, $default = '' ) {

        return get_option( 'sdwd_' . $key, $default );
    }
}

if( ! function_exists( 'sdweddingdirectory_currenty' ) ){

	function sdweddingdirectory_currenty(){

		$_currency 		=	sdweddingdirectory_option( 'venue_currency_sign' );

		if( $_currency !== '' && ! empty( $_currency ) ){

			return 	esc_attr( $_currency );

		}else{

			return esc_attr__( '$', 'sdweddingdirectory' );
		}
	}
}


if( ! function_exists( 'sdweddingdirectory_currency_possition' ) ){

	function sdweddingdirectory_currency_possition(){

		$_currency 		=	sdweddingdirectory_option( '_currencty_possition_' );

		if( $_currency !== '' && ! empty( $_currency ) ){

			return 	esc_attr( $_currency );

		}else{

			return esc_attr( 'left' );
		}
	}
}

/**
 *  SDWeddingDirectory - Currency Possition Check to update it.
 *  ---------------------------------------------------
 *  Before : Before any div for currency
 *  ------------------------------------
 *  After  : After any div for currency
 *  ------------------------------------
 */
if( ! function_exists( 'sdweddingdirectory_pricing_possition' ) ){

	function sdweddingdirectory_pricing_possition( $price = '', $before = '', $after = '' ){

		/**
		 *  Check possition to set pricing
		 *  ------------------------------
		 */
		$_possition 	=	sdweddingdirectory_currency_possition();

		$_is_left 		=	$_possition != '' && $_possition == esc_attr( 'left' );

		$_is_right 		=	$_possition != '' && $_possition == esc_attr( 'right' );

		$_currency 		=	$before . sdweddingdirectory_currenty() . $after;

		/**
		 *  Is Left Side Currency Setting ?
		 *  -------------------------------
		 */
		if( $_is_left ){

			return 	$_currency . $price;
		}

		/**
		 *  Is Right Side Currency Setting ?
		 *  --------------------------------
		 */
		if( $_is_right ){

			return 	$price . $_currency;
		}
	}
}

if( ! function_exists( '_print_r' ) ){

	function _print_r( $args ){

		print '<pre>'; print_r( $args ); print '</pre>';
	}
}

if( ! function_exists( 'sdweddingdirectory_time_period' ) ){

        function sdweddingdirectory_time_period( $string, $start, $end ){

            $arr = [];

            for( $i = absint( $start ); $i<=absint( $end ); $i++ ){

                $arr[  sprintf( '+%1$s %2$s', $i, $string ) ] =  sprintf( '%1$s %2$s', $i, $string );
            }

            return $arr;
        }
}

/** 
 *  @return Array: Days, Month, Week, Year with argument.
 */

if( ! function_exists( 'sdweddingdirectory_time_period_theme_option' ) ){

		function sdweddingdirectory_time_period_theme_option( $a, $b, $c ){

			$current_code = sdweddingdirectory_time_period( $a, $b, $c );

			$return_array = [];

			if( SDWeddingDirectory_Loader:: _is_array( $current_code ) ){

				foreach ( $current_code as $key) {

			        $return_array[] = array(

			            'value'       => sprintf( '+%1$s', $key ),
			            'label'       => sprintf( '+%1$s', $key ),
			            'src'         => ''
			        );
				}
			}

			return $return_array;
		}	
}

if( ! function_exists( 'sdweddingdirectory_redux_time_period_theme_option' ) ){

		function sdweddingdirectory_redux_time_period_theme_option( $a, $b, $c ){

			$current_code = sdweddingdirectory_time_period( $a, $b, $c );

			$return_array = [];

			if( SDWeddingDirectory_Loader:: _is_array( $current_code ) ){

				foreach ( $current_code as $key) {

			        $return_array[] = sprintf( '+%1$s', $key );
				}
			}

			return $return_array;
		}	
}

/**
 *  @return Paypal Currency Code
 */

if( ! function_exists( 'sdweddingdirectory_paypal_currency' ) ){

		function sdweddingdirectory_paypal_currency(){

			$current_code = sdweddingdirectory_paypal_currency_code();

			$return_array = [];

			if( SDWeddingDirectory_Loader:: _is_array( $current_code ) ){

				foreach ( $current_code as $key) {

			        $return_array[] = array(

			            'value'       => $key,
			            'label'       => esc_html( $key ),
			            'src'         => ''
			        );
				}
			}

			return $return_array;
		}	
}

if( ! function_exists( 'sdweddingdirectory_create_OT_array' ) ){

	function sdweddingdirectory_create_OT_array( $array ){

		$return = [];

		if( SDWeddingDirectory_Loader:: _is_array( $array ) ){

		  	foreach( $array as $key => $value ){

		  		$return[] = array(
	              'value'       => $key,
	              'label'       => esc_html( $value ),
	              'src'         => ''
	            );
		  	}
		}

		return $return;
	}
}

/** 
 *  @return Paypal Currecty Code
 */
if( ! function_exists( 'sdweddingdirectory_paypal_currency_code' ) ){
		
		function sdweddingdirectory_paypal_currency_code(){

			return array(

			    'USD', 'EUR', 'AUD', 'BRL', 'CAD', 'CHF', 'CZK', 'DKK', 'HKD', 'HUF', 
			    'IDR', 'ILS', 'INR', 'JPY', 'KOR', 'KSH', 'MYR', 'MXN', 'NGN', 'NOK', 
			    'NZD', 'PHP', 'PLN', 'GBP', 'SGD', 'SEK', 'TWD', 'THB', 'TRY', 'VND',  'ZAR'
			);	

		}
}

if( ! function_exists( 'sdweddingdirectory_redux_paypal_payment_currency' ) ){

	function sdweddingdirectory_redux_paypal_payment_currency(){

		$_currency 	=	[];

		$_data 		=	sdweddingdirectory_paypal_currency_code();

		if( SDWeddingDirectory_Loader:: _is_array( $_data ) ){

			foreach( $_data as $key => $value ){

				$_currency[]	= 	esc_attr( $value );
			}
		}

		return $_currency;
	}
}

if( ! function_exists( 'php_console' ) ){

	function php_console($data) {

		if( ! empty( $data ) ){

		    $output = $data;

		    if (is_array($output)){

		        $output = implode(',', $output);
		    }

		    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
		}
	}
}
