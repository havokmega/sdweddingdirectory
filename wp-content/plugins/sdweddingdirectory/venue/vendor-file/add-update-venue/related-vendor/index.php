<?php
/**
 *  ---------------------------
 *  SDWeddingDirectory - Venue Fields
 *  ---------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Fields_Post_Related_Vendors' ) && class_exists( 'SDWeddingDirectory_Dashboard_Venue_Update' ) ){

    /**
     *  ---------------------------
     *  SDWeddingDirectory - Venue Fields
     *  ---------------------------
     */
    class SDWeddingDirectory_Venue_Fields_Post_Related_Vendors extends SDWeddingDirectory_Dashboard_Venue_Update{

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
         *  Enable Section ?
         *  ----------------
         */
        public static function _enable( $post_id = 0 ){

            $_display_none    =   sanitize_html_class( 'd-none' );

            if( empty( $post_id ) ){

                return  $_display_none;

            }else{

                $_enable    =

                sdwd_get_term_field( 'cuisine_offer',

                    apply_filters( 'sdweddingdirectory/post/term-parent', [

                        'post_id'       =>      absint( $post_id ),

                        'taxonomy'      =>      esc_attr( 'venue-type' )

                    ] )
                );

                if( ! $_enable ){

                    return  $_display_none;
                }
            }
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

            return      esc_attr__( 'Preferred Vendors', 'sdweddingdirectory-venue' );
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
            add_filter( 'sdweddingdirectory/add-update-venue/tabs', [ $this, 'tab_info' ], absint( '120' ), absint( '1' ) );
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

                    'title'       =>   esc_attr__( 'Preferred Vendors', 'sdweddingdirectory-venue' ),

                    'class'       =>   sanitize_html_class( 'mb-0' )
                )

            ) );

            /**
             *  Multiple Select Fields
             *  ----------------------
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
                'field'                     =>      array(

                    'field_type'            =>      esc_attr( 'multiple_select' ),

                    'id'                    =>      esc_attr( 'preferred_venues' ),

                    'name'                  =>      esc_attr( 'preferred_venues' ),

                    'placeholder'           =>      esc_attr__( 'Select Your Favorite Venues', 'sdweddingdirectory-venue' ),

                    'options'               =>      apply_filters( 'sdweddingdirectory/post/data', [

                                                        'post_type'     =>      esc_attr( 'venue' )

                                                    ] ),

                    'selected'              =>      !   empty( $post_id )

                                                    ?   preg_split ("/\,/",
                                                        
                                                            get_post_meta(

                                                                /**
                                                                 *  1. Get the Venue ID
                                                                 *  ---------------------
                                                                 */
                                                                absint( $post_id ),

                                                                /**
                                                                 *  2. Meta Key 
                                                                 *  -----------
                                                                 */
                                                                sanitize_key( 'preferred_venues' ),

                                                                /**
                                                                 *  3. True
                                                                 *  -------
                                                                 */
                                                                true
                                                            )
                                                        )

                                                    :   []
                )

            ) );
        }
    }

    /**
     *  ---------------------------
     *  SDWeddingDirectory - Venue Fields
     *  ---------------------------
     */
    SDWeddingDirectory_Venue_Fields_Post_Related_Vendors::get_instance();
}