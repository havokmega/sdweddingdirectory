<?php
/**
 *  SDWeddingDirectory - Icon Handaler ( Manager )
 *  --------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Icon_Manager' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory - Icon Handaler ( Manager )
     *  --------------------------------------
     */
    class SDWeddingDirectory_Icon_Manager extends SDWeddingDirectory_Config{

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
             *  Load Fonts File
             *  ---------------
             */
            self:: insert_icons_files();

            /**
             *  Get Font Icon with OT filter
             *  ----------------------------
             */
            add_filter( 'sdweddingdirectory_icons_set_for_ot', [ $this, 'sdweddingdirectory_icons_list' ] );

            /**
             *  SVG icon list
             *  -------------
             */
            add_filter( 'sdweddingdirectory_marker_icons_set', [ $this, 'sdweddingdirectory_marker_icons_list' ] );

            /**
             *  Get Option
             *  ----------
             */
            add_filter( 'sdweddingdirectory/icons', [ $this, 'sdweddingdirectory_icons' ], absint( '10' ), absint( '1' ) );

            /**
             *  Get list of normal array of icons
             *  ---------------------------------
             */
            // add_action( 'init', [ $this, 'sdweddingdirectory_icon_list' ] );

            /**
             *  List Of Icon On Front Page When you are switch this option.
             *  -----------------------------------------------------------
             */
            // add_action( 'init', [ $this, 'show_list_of_icon' ] );
        }

        /**
         *  Load Icons
         *  ----------
         */
        public static function insert_icons_files(){

            /**
             *  Required Files
             *  --------------
             */
            foreach ( glob(  plugin_dir_path( __FILE__ ) . '*/*.php' ) as $_sdweddingdirectory_file ) {
           
                require_once $_sdweddingdirectory_file;
            }
        }

        /**
         *  This function convert icon array to option tree select option array
         *  -------------------------------------------------------------------
         */
        public static function sdweddingdirectory_icons_list(){

            $args = [];

            $_sdweddingdirectory_icon_set = apply_filters( 'sdweddingdirectory_icons_set', [] );

            if( parent:: _is_array( $_sdweddingdirectory_icon_set ) ){

                foreach ( $_sdweddingdirectory_icon_set as $key => $value) {
                    
                    $args[] = 

                    array(

                        'value' => $key,
                        'label' => $value,
                        'src'   => '',
                    );
                }

                return $args;
            }
        }

        /**
         *  Get List of Marker SVG Icon
         *  ---------------------------
         */
        public static function sdweddingdirectory_marker_icons_list(){

            $svg = [];

            $svg[ '0' ]     =   esc_attr__( 'Select Icon', 'wedddingdir' );

            $_have_svg      =   glob( plugin_dir_path( __FILE__ ) . 'marker-icon/' . '*.svg' );

            if( parent:: _is_array( $_have_svg ) ){

                foreach ( $_have_svg as $file ) {

                    $svg[ esc_html( sanitize_title( basename( $file, '.svg' ) . PHP_EOL ) ) ] =  esc_html( sanitize_title( basename( $file, '.svg' ) . PHP_EOL ) );
                }

            }

           return $svg;
        }

        public static function sdweddingdirectory_icon_list(){

            _print_r( apply_filters( 'sdweddingdirectory_icons_set', [] ) );

            exit();
        }

        /**
         *  Showing All Icon after Exit. Admin can easier to get icon.
         *  ----------------------------------------------------------
         */
        public static function show_list_of_icon(){

            $_icon_list = apply_filters( 'sdweddingdirectory_icons_set_for_ot', [] );

            if( parent:: _is_array( $_icon_list ) ){

                /**
                 *  1. Count Font Array
                 *  -------------------
                 */
                printf( 'Number of Font : %1$s',

                        absint( count(  $_icon_list  ) )
                );

                foreach ( $_icon_list as $key => $value) {
                    
                    printf( '<h1>%1$s : <i class="fa %1$s" style="color:black;"></i></h1>',

                        /**
                         *  1. $key
                         *  -------
                         */
                        $value[ 'value' ]

                    );
                }

                // exit();
            }
        }

        /**
         *  Icon Picker
         *  -----------
         */
        public static function sdweddingdirectory_icons( $args = [] ){

            /**
             *  Extract
             *  -------
             */
            extract( wp_parse_args( $args, [ 

                'selected'      =>      '',

                'icons'         =>      apply_filters( 'sdweddingdirectory_icons_set', [] ),

                'collection'    =>      '',

                'placeholder'   =>      esc_attr__( 'Select Icon', 'wedddingdir' )

            ] ) );

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $icons ) ){

                /**
                 *  Default
                 *  -------
                 */
                $collection         .=    sprintf( '<option>%1$s</option>', $placeholder );

                /**
                 *  Loop
                 *  ----
                 */
                foreach( $icons as $key => $value ){
                    
                    /**
                     *  Options
                     *  -------
                     */
                    $collection     .=      sprintf( '<option value="%1$s" %2$s>%1$s</option>',

                                                /**
                                                 *  1. Key
                                                 *  ------
                                                 */
                                                esc_attr( $value ),

                                                /**
                                                 *  2. Is Selected ?
                                                 *  ----------------
                                                 */
                                                $selected == $key

                                                ?   esc_attr( 'selected' ) 

                                                :   '',

                                                /**
                                                 *  3. Value
                                                 *  --------
                                                 */
                                                esc_attr( $value )
                                            );
                }
            }

            /**
             *  Return : Options
             *  ----------------
             */
            return      $collection;
        }
    }

    /**
     *  Icon Configuration
     *  ------------------
     */
    SDWeddingDirectory_Icon_Manager::get_instance();
}