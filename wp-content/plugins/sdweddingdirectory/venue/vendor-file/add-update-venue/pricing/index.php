<?php
/**
 *  ---------------------------
 *  SDWeddingDirectory - Venue Fields
 *  ---------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Fields_Post_Pricing' ) && class_exists( 'SDWeddingDirectory_Dashboard_Venue_Update' ) ){

    /**
     *  ---------------------------
     *  SDWeddingDirectory - Venue Fields
     *  ---------------------------
     */
    class SDWeddingDirectory_Venue_Fields_Post_Pricing extends SDWeddingDirectory_Dashboard_Venue_Update{

        /**
         *  A reference to an instance of this class
         *  ----------------------------------------
         */
        private static $instance;

        /**
         *  Returns an instance of this class.
         *  ----------------------------------
         */
        public static function get_instance() {

            if ( null == self::$instance ) {

                self::$instance = new self();
            }

            return  self::$instance;
        }

        /**
         *  Tab ID
         *  ------
         */
        public static function tab_id(){

            return      sanitize_title( self:: tab_name() );
        }

        /**
         *  Tab Name
         *  --------
         */
        public static function tab_name(){

            return      esc_attr__( 'Pricing', 'sdweddingdirectory-venue' );
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
            add_filter( 'sdweddingdirectory/add-update-venue/tabs', [ $this, 'tab_info' ], absint( '50' ), absint( '1' ) );
        }

        /**
         *  Tab Info
         *  --------
         */
        public static function tab_info( $args = [] ){

            /**
             *  Merge Tabs
             *  ----------
             */
            return      array_merge( $args, [

                            self:: tab_id()         =>      [

                                'active'            =>      false,

                                'id'                =>      esc_attr( parent:: _rand() ),

                                'name'              =>      esc_attr( self:: tab_name() ),

                                'callback'          =>      [ 'class' => __CLASS__, 'member' => 'tab_content' ],
                            ]

                        ] );
        }

        /**
         *  Select Category Field
         *  ---------------------
         */
        public static function tab_content(){

            /**
             *  Post ID
             *  -------
             */
            $post_id            =       parent:: venue_post_ID();

            /**
             *   Section Information
             *   -------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>   esc_attr( 'info' ),

                    'title'       =>   esc_attr__( 'Pricing', 'sdweddingdirectory-venue' ),

                    'class'       =>   sanitize_html_class( 'mb-0' )
                )

            ) );

            /**
             *  Venue Min Price
             *  -----------------
             */
            parent:: create_section( array(

                /**
                 *  DIV Managment
                 *  -------------
                 */
                'div'        =>  array(

                    'id'          =>   '',

                    'class'       =>   esc_attr( 'card-body' ),

                    'start'       =>   true,

                    'end'         =>   false,
                ),

                /**
                 *  Row Managment
                 *  -------------
                 */
                'row'        =>  array(

                    'id'          =>   '',

                    'class'       =>   'row',

                    'start'       =>   true,

                    'end'         =>   false,
                ),

                /**
                 *  Column Managment
                 *  ----------------
                 */
                'column'     =>   array(

                    'grid'        =>   absint( '6' ),

                    'id'          =>   '',

                    'class'       =>   '',

                    'start'       =>   true,

                    'end'         =>   true,
                ),

                /**
                 *  Fields Arguments
                 *  ----------------
                 */
                'field'    =>  array(

                    'field_type'        =>  esc_attr( 'input' ),

                    'type'              =>  esc_attr( 'number' ),

                    'placeholder'       =>  esc_attr__( 'Min Price', 'sdweddingdirectory-venue' ),

                    'name'              =>  esc_attr( 'venue_min_price' ),

                    'id'                =>  esc_attr( 'venue_min_price' ),

                    'require'           =>  false,

                    'value'             =>  self:: _have_min_price( $post_id )
                )

            ) );

            /**
             *  Venue Max Price
             *  -----------------
             */
            parent:: create_section( array(

                /**
                 *  DIV Managment
                 *  -------------
                 */
                'div'        =>  array(

                    'start'       =>   false,

                    'end'         =>   true,
                ),

                /**
                 *  Row Managment
                 *  -------------
                 */
                'row'        =>  array(

                    'start'       =>   false,

                    'end'         =>   true,
                ),

                /**
                 *  Column Managment
                 *  ----------------
                 */
                'column'     =>   array(

                    'grid'        =>   absint( '6' ),

                    'id'          =>   '',

                    'class'       =>   '',

                    'start'       =>   true,

                    'end'         =>   true,
                ),

                /**
                 *  Fields Arguments
                 *  ----------------
                 */
                'field'    =>  array(

                    'field_type'        =>  esc_attr( 'input' ),

                    'type'              =>  esc_attr( 'number' ),

                    'placeholder'       =>  esc_attr__( 'Max Price', 'sdweddingdirectory-venue' ),

                    'name'              =>  esc_attr( 'venue_max_price' ),

                    'id'                =>  esc_attr( 'venue_max_price' ),

                    'require'           =>  false,

                    'value'             =>  self:: _have_max_price( $post_id )
                )

            ) );
        }

        /**
         *  Have Post ID ?
         *  --------------
         */
        public static function _have_min_price( $post_id = 0 ){

            /**
             *  Post ID
             *  -------
             */
            if( ! empty( $post_id ) ){

                return  get_post_meta(

                            /**
                             *  1. Get the ID
                             *  -------------
                             */
                            absint( $post_id ),

                            /**
                             *  2. Key
                             *  ------
                             */
                            sanitize_key( 'venue_min_price' ),

                            /**
                             *  3. True
                             *  -------
                             */
                            true
                        );
            }
        }

        /**
         *  Have Post ID ?
         *  --------------
         */
        public static function _have_max_price( $post_id = 0 ){

            /**
             *  Post ID
             *  -------
             */
            if( ! empty( $post_id ) ){

                return  get_post_meta(

                            /**
                             *  1. Get the ID
                             *  -------------
                             */
                            absint( $post_id ),

                            /**
                             *  2. Key
                             *  ------
                             */
                            sanitize_key( 'venue_max_price' ),

                            /**
                             *  3. True
                             *  -------
                             */
                            true
                        );
            }
        }
    }

    /**
     *  ---------------------------
     *  SDWeddingDirectory - Venue Fields
     *  ---------------------------
     */
    SDWeddingDirectory_Venue_Fields_Post_Pricing::get_instance();
}