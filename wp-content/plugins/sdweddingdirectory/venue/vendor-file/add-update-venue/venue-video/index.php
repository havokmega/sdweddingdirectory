<?php
/**
 *  ---------------------------
 *  SDWeddingDirectory - Venue Fields
 *  ---------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Fields_Post_Video' ) && class_exists( 'SDWeddingDirectory_Dashboard_Venue_Update' ) ){

    /**
     *  ---------------------------
     *  SDWeddingDirectory - Venue Fields
     *  ---------------------------
     */
    class SDWeddingDirectory_Venue_Fields_Post_Video extends SDWeddingDirectory_Dashboard_Venue_Update{

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

            return      esc_attr__( 'Venue Video', 'sdweddingdirectory-venue' );
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
            add_filter( 'sdweddingdirectory/add-update-venue/tabs', [ $this, 'tab_info' ], absint( '70' ), absint( '1' ) );
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
            $post_id        =       parent:: venue_post_ID();

            /**
             *   Section Information
             *   -------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>   esc_attr( 'info' ),

                    'title'       =>   esc_attr__( 'Venue Video', 'sdweddingdirectory-venue' ),

                    'class'       =>   sanitize_html_class( 'mb-0' )
                )

            ) );

            /**
             *    Venue Categories
             *    ------------------
             */
            parent:: create_section( array(

                /**
                 *  DIV Managment
                 *  -------------
                 */
                'div'        =>  array(

                    'id'          =>   '',

                    'class'       =>   sanitize_html_class( 'card-body' ),

                    'start'       =>   true,

                    'end'         =>   true,
                ),

                /**
                 *  Row Managment
                 *  -------------
                 */
                'row'        =>  array(

                    'id'          =>   '',

                    'class'       =>   '',

                    'start'       =>   true,

                    'end'         =>   true,
                ),

                /**
                 *  Column Managment
                 *  ----------------
                 */
                'column'     =>   array(

                    'grid'        =>   absint( '12' ),

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

                    'formgroup'         =>  false,

                    'field_type'        =>  esc_attr( 'input' ),

                    'type'              =>  esc_attr( 'url' ),

                    'placeholder'       =>  esc_attr__( 'Ex : https://www.youtube.com/watch?v=VQWgez89JlM', 'sdweddingdirectory-venue' ),

                    'name'              =>  sanitize_key( 'venue_video' ),

                    'id'                =>  sanitize_key( 'venue_video' ),

                    'value'             =>  self:: _have_value( $post_id )
                )

            ) );
        }

        /**
         *  Have Post ID ?
         *  --------------
         */
        public static function _have_value( $post_id = 0 ){

            /**
             *  Post ID
             *  -------
             */
            if( ! empty( $post_id ) ){

                return      esc_url( get_post_meta(

                                /**
                                 *  1. Get the Venue ID
                                 *  ---------------------
                                 */
                                absint( $post_id ),

                                /**
                                 *  2. Meta Key 
                                 *  -----------
                                 */
                                sanitize_key( 'venue_video' ),

                                /**
                                 *  3. True
                                 *  -------
                                 */
                                true
                            ) );
            }
        }
    }

    /**
     *  ---------------------------
     *  SDWeddingDirectory - Venue Fields
     *  ---------------------------
     */
    SDWeddingDirectory_Venue_Fields_Post_Video::get_instance();
}