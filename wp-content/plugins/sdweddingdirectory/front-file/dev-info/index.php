<?php
/**
 *  SDWeddingDirectory - Dev Tools
 *  ----------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dev_Tools' ) && class_exists( 'SDWeddingDirectory_Front_End_Modules' ) ){

    /**
     *  SDWeddingDirectory - Dev Tools
     *  ----------------------
     */
    class SDWeddingDirectory_Dev_Tools extends SDWeddingDirectory_Front_End_Modules {

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

        /**
         *  Initiator
         *  ---------
         */
        public static function get_instance() {
          
            if ( ! isset( self::$instance ) ) {

                self::$instance = new self;
            }

            return self::$instance;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  SDWeddingDirectory - Theme / Plugin / ShortCode Information
             *  ---------------------------------------------------
             */
            add_action( 'init', array( $this, 'sdweddingdirectory_information_markup' ) );

            /**
             *  Theme Info Filter
             *  -----------------
             */
            add_filter( 'sdweddingdirectory/theme-info', [ $this, 'sdweddingdirectory_theme_info' ], absint( '10' ), absint( '1' ) );

            /**
             *  Theme Info Filter
             *  -----------------
             */
            add_filter( 'sdweddingdirectory/hosting-info', [ $this, 'sdweddingdirectory_hosting_info' ], absint( '10' ), absint( '1' ) );

            /**
             *  Theme Info Filter
             *  -----------------
             */
            add_filter( 'sdweddingdirectory/domain-info', [ $this, 'sdweddingdirectory_domain_info' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Domain Info Filter
         *  ------------------
         */
        public static function sdweddingdirectory_domain_info( $args = [] ){

            /**
             *  Args
             *  ----
             */
            $_data      =   [   'blog_info'         =>      esc_attr( get_bloginfo( 'name' ) ),

                                'home_url'          =>      esc_url( home_url( '/' ) ),

                                'purchase_code'     =>      get_option( 'SDWeddingDirectory_Theme_Registration' ),
                            ];

            /**
             *  Theme Args
             *  ----------
             */
            return      array_merge( $args, array( $_data ) );
        }

        /**
         *  Hosting Info Filter
         *  -------------------
         */
        public static function sdweddingdirectory_hosting_info( $args = [] ){

            /**
             *  Args
             *  ----
             */
            $_data      =   [   'phpversion'                =>  phpversion(),

                                'upload_max_filesize'       =>  ini_get('upload_max_filesize'),

                                'post_max_size'             =>  ini_get('post_max_size'),

                                'memory_limit'              =>  ini_get('memory_limit'),

                                'max_execution_time'        =>  ini_get('max_execution_time'),

                                'max_input_vars'            =>  ini_get('max_input_vars' ),
                            ];

            /**
             *  Theme Args
             *  ----------
             */
            return      array_merge( $args, array( $_data ) );
        }

        /**
         *  Theme Info Filter
         *  -----------------
         */
        public static function sdweddingdirectory_theme_info( $args = [] ){

            $my_theme                 =   wp_get_theme( 'sdweddingdirectory' );

            $_data                    =   [];

            $_data[ 'Name' ]          =   esc_html( $my_theme->get( 'Name' ) );

            $_data[ 'Version' ]       =   esc_html( $my_theme->get( 'Version' ) );

            $_data[ 'TextDomain' ]    =   esc_html( $my_theme->get( 'TextDomain' ) );

            $_data[ 'AuthorName' ]    =   esc_html( $my_theme->get( 'Author' ) );

            /**
             *  Theme Args
             *  ----------
             */
            return      array_merge( $args, array( $_data ) );
        }

        /**
         *  Developer - information
         *  -----------------------
         */
        public static function sdweddingdirectory_information_markup(){

            /**
             *  SDWeddingDirectory - Theme Information
             *  ------------------------------
             */
            if( isset( $_GET[ 'sdweddingdirectory_rand' ] ) ){

                /**
                 *  Return Data
                 *  -----------
                 */
                for ( $i=0; $i <= absint( '20' ); $i++ ){

                    printf( '<p>%1$s</p>',

                        /**
                         *  Random IDs
                         *  ----------
                         */
                        esc_attr( parent:: _rand() )
                    );
                }

                exit();
            }

            /**
             *  SDWeddingDirectory - Hosting Requirement
             *  --------------------------------
             */
            if( isset( $_GET[ 'sdweddingdirectory-php-info' ] ) ){

                /**
                 *  PHP Information
                 *  ---------------
                 */
                exit( phpinfo() );
            }

            /**
             *  SDWeddingDirectory - Hosting Requirement
             *  --------------------------------
             */
            if( isset( $_GET[ 'sdweddingdirectory-hosting-info' ] ) ){

                /**
                 *  Args
                 *  ----
                 */
                $_data      =   apply_filters( 'sdweddingdirectory/hosting-info', [] );

                /**
                 *  SDWeddingDirectory - Hosting Requirement
                 *  --------------------------------
                 */
                if( $_GET[ 'sdweddingdirectory-hosting-info' ] ==  esc_attr( 'return_data' ) ){

                    exit( json_encode( [ 'data' => $_data  ] ) );
                }

                else{

                    exit( _print_r( $_data ) );
                }
            }

            /**
             *  SDWeddingDirectory - Domain Information
             *  -------------------------------
             */
            if( isset( $_GET[ 'sdweddingdirectory-domain-info' ] ) ){

                /**
                 *  Args
                 *  ----
                 */
                $_data      =   apply_filters( 'sdweddingdirectory/domain-info', [] );

                /**
                 *  SDWeddingDirectory - Hosting Requirement
                 *  --------------------------------
                 */
                if( $_GET[ 'sdweddingdirectory-domain-info' ] ==  esc_attr( 'return_data' ) ){

                    exit( json_encode( [ 'data' => $_data  ] ) );
                }

                else{

                    exit( _print_r( $_data ) );
                }
            }

            /**
             *  SDWeddingDirectory - Plugin Information
             *  -------------------------------
             */
            if( isset( $_GET[ 'sdweddingdirectory-plugin-info' ] ) ){

                /**
                 *  SDWeddingDirectory - Plugin Information
                 *  -------------------------------
                 */
                $_data    =   apply_filters( 'sdweddingdirectory/plugin', [] );

                /** 
                 *  Is Array ?
                 *  ----------
                 */
                if( parent:: _is_array( $_data ) ){

                    /**
                     *  Is Return Data ?
                     *  ----------------
                     */
                    if( $_GET[ 'sdweddingdirectory-plugin-info' ] ==  esc_attr( 'return_data' ) ){

                        exit( json_encode( [ 'data' => $_data  ] ) );
                    }

                    else{

                        exit( _print_r( $_data ) );
                    }
                }
            }

            /**
             *  SDWeddingDirectory - Theme Information
             *  ------------------------------
             */
            if( isset( $_GET[ 'sdweddingdirectory-theme-info' ] ) ){

                /**
                 *  Theme Args
                 *  ----------
                 */
                $_data  =   apply_filters( 'sdweddingdirectory/theme-info', [] );

                /**
                 *  Return Data
                 *  -----------
                 */
                if( $_GET[ 'sdweddingdirectory-theme-info' ] ==  esc_attr( 'return_data' ) ){

                    exit( json_encode( [ 'data' => $_data  ] ) );
                }

                else{

                    exit( _print_r( $_data ) );
                }
            }

            /**
             *  SDWeddingDirectory - ShortCode List
             *  ---------------------------
             */
            if( isset( $_GET[ 'sdweddingdirectory-shortcode-list' ] ) ){

                $_data        =   apply_filters( 'sdweddingdirectory_shortcode_list', [] );

                $_new_array   =   [];

                if( parent:: _is_array( $_data ) ){

                    foreach( $_data as $key => $value ){

                        $_shortcode  =  apply_filters(  'wpautop',  apply_filters(  'sdweddingdirectory_clean_shortcode',  $value ) );

                        $_new_array[ $key ]     =   esc_attr( shortcode_unautop( $_shortcode ) );
                    }
                }

                get_header();

                print '<div class="container"><div class="row"><div class="col-12">';

                if( parent:: _is_array( $_new_array ) ){

                    foreach( $_new_array as $key => $value ){

                        printf( '   <div class="card">

                                        <div class="card-body">
                                            
                                            <h5 class="card-title">%1$s</h5>
                                            
                                            <p class="card-text"> <pre>%2$s</pre></p>
                                      
                                        </div>
                                    
                                    </div>', 

                                    $key,

                                    trim( $value )
                        );
                    }
                }

                print '</div></div></div>';

                get_footer();

                exit();
            }
        }
    }

    /**
     *  SDWeddingDirectory - Dev Tools
     *  ----------------------
     */
    SDWeddingDirectory_Dev_Tools::get_instance();
}