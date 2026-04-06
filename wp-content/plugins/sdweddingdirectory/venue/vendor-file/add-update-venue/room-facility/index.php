<?php
/**
 *  ---------------------------
 *  SDWeddingDirectory - Venue Fields
 *  ---------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Fields_Post_Room_Facility' ) && class_exists( 'SDWeddingDirectory_Dashboard_Venue_Update' ) ){

    /**
     *  ---------------------------
     *  SDWeddingDirectory - Venue Fields
     *  ---------------------------
     */
    class SDWeddingDirectory_Venue_Fields_Post_Room_Facility extends SDWeddingDirectory_Dashboard_Venue_Update{

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

            return      esc_attr__( 'Room Facilities', 'sdweddingdirectory-venue' );
        }

        /**
         *  Is Enable ?
         *  -----------
         */
        public static function _enable( $post_id = 0 ){

            $_display_none  =   sanitize_html_class( 'd-none' );

            /**
             *  Is Empty ?
             *  ----------
             */
            if( empty( $post_id ) ){

                return  $_display_none;

            }else{

                $_enable    =

                sdwd_get_term_field( 'room_facilities',

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
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Add Filter
             *  ----------
             */
            add_filter( 'sdweddingdirectory/add-update-venue/tabs', [ $this, 'tab_info' ], absint( '110' ), absint( '1' ) );
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

                    'title'       =>   esc_attr__( 'Room Facilities', 'sdweddingdirectory-venue' ),

                    'class'       =>   sanitize_html_class( 'mb-0' )
                )

            ) );

            $_section_class         =   sanitize_html_class( 'venue_facilities' );

            $_class                 =   self:: _enable( $post_id );

            /**
             *  Add FAQ Button
             *  --------------
             */
            printf( '<div class="card-body %5$s %1$s-section %1$s">

                        <div class="row row-cols-1 %1$s" id="%1$s">%4$s</div>

                        <div class="text-center">

                            <a href="javascript:void(0)" 

                            class="btn btn-sm btn-default sdweddingdirectory_group_accordion %1$s" 

                            data-class="SDWeddingDirectory_Vendor_Venue_Database"

                            data-member="get_venue_facilities"

                            data-parent="%1$s"

                            id="%2$s">%3$s</a>

                        </div>

                    </div>',

                    /**
                     *  1. Parent Collapse ID
                     *  ---------------------
                     */
                    esc_attr( $_section_class ),

                    /**
                     *  2. Section Button ID
                     *  --------------------
                     */
                    esc_attr( parent:: _rand() ),

                    /**
                     *  3. Button Text : Translation Ready
                     *  ----------------------------------
                     */
                    esc_attr__( 'Add New Facilities', 'sdweddingdirectory-venue' ),

                    /**
                     *  4. Data
                     *  -------
                     */
                    !   empty( $post_id )

                    ?   parent:: get_venue_facilities( [ 'post_id'  =>  absint( $post_id ) ] )

                    :   '',

                    /**
                     *  5. Category term have permission for this fields ?
                     *  --------------------------------------------------
                     */
                    !   empty( $_class )

                    ?   sanitize_html_class( $_class )

                    :   ''
            );
        }
    }

    /**
     *  ---------------------------
     *  SDWeddingDirectory - Venue Fields
     *  ---------------------------
     */
    SDWeddingDirectory_Venue_Fields_Post_Room_Facility::get_instance();
}