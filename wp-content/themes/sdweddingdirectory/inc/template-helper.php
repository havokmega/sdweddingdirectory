<?php
/**
 *  SDWeddingDirectory - Template Helper
 *  ----------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Template_Helper' ) && class_exists( 'SDWeddingDirectory' ) ){

    /**
     *  SDWeddingDirectory - Template Helper
     *  ----------------------------
     */
    class SDWeddingDirectory_Template_Helper extends SDWeddingDirectory{

    	/**
    	 *  Var
    	 *  ---
    	 */
        private static $instance;

        /**
         *  Call Self
         *  ---------
         */
        public static function get_instance() {

            if ( null == self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

        	/**
        	 *  Add Filter
        	 *  ----------
        	 */
        	add_filter( 'sdweddingdirectory/pagination', [ $this, 'sdweddingdirectory_pagination' ], absint( '10' ), absint( '1' ) );
        }

		/**
		 *   @credit - https://developer.wordpress.org/reference/functions/paginate_links/#user-contributed-notes
		 *   ----------------------------------------------------------------------------------------------------
		 *   SDWeddingDirectory - Pagination
		 *   -----------------------
		 */
		public static function sdweddingdirectory_pagination( $args = [] ){

			/**
			 *  Have Data ?
			 *  -----------
			 */
			if( parent:: _is_array( $args ) ){

				/**
				 *  Have Args ?
				 *  -----------
				 */
				extract( wp_parse_args( $args, [

					'big' 			=> 		absint( '999999999' ),

					'paged'			=>		absint( '1' ),

					'numpages'		=>		absint( '1' ),

					'add_args'		=>		[],

				] ) );

				/**
				 *  Pagination
				 *  ----------
				 */
				return
				
				sprintf(    '<div class="col-12 text-center">

								<div class="theme-pagination mb-xl-0 mb-lg-0">

									<ul class="d-block pagination"> %1$s </ul>

					  			</div>

					  		</div>',

					  		/**
					  		 *  1. Page
					  		 *  -------
					  		 */
					  		paginate_links( [

								'base' 			=> 		str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),

								'format' 		=> 		'?paged=%#%',

								'current' 		=> 		$paged,

								'total' 		=> 		$numpages,

								'prev_text'     => 		'&laquo;',

								'next_text'     => 		'&raquo;',

								'add_args'      =>      parent:: _is_array( $add_args ) ? $add_args : [],
							] )
				);
			}
		}
    }  

    /**
     *  SDWeddingDirectory - Body Markup Object
     *  -------------------------------
     */
    SDWeddingDirectory_Template_Helper:: get_instance();
}

/**
 *  1. Get Setting Option values
 *  ----------------------------
 */
if( ! function_exists( 'sdweddingdirectory_option' ) ){

    function sdweddingdirectory_option( $key, $default = '' ){

        return get_option( 'sdwd_' . $key, $default );
    }
}

if( ! function_exists( 'sdweddingdirectory_logo_uri' ) ){

    function sdweddingdirectory_logo_uri(){

        $logo_path = defined( 'SDW_LOGO_PATH' ) ? SDW_LOGO_PATH : 'assets/images/logo/logo_dark.svg';

        return esc_url( get_theme_file_uri( $logo_path ) );
    }
}

/**
 *  --------------------
 *  1. wp_body_open hook
 *  --------------------
 *  @credit - https://make.wordpress.org/themes/2019/03/29/addition-of-new-wp_body_open-hook/
 *  -----------------------------------------------------------------------------------------
 */
if( ! function_exists( 'wp_body_open' ) ){

    function wp_body_open( $key = '' ){

      	/**
      	 *  Backwards Compatibility
      	 *  -----------------------
      	 */
		do_action( 'wp_body_open' );
    }
}

?>
