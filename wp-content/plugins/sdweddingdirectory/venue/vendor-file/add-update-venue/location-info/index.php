<?php
/**
 *  ---------------------------
 *  SDWeddingDirectory - Venue Fields
 *  ---------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Fields_Post_Location' ) && class_exists( 'SDWeddingDirectory_Dashboard_Venue_Update' ) ){

    /**
     *  ---------------------------
     *  SDWeddingDirectory - Venue Fields
     *  ---------------------------
     */
    class SDWeddingDirectory_Venue_Fields_Post_Location extends SDWeddingDirectory_Dashboard_Venue_Update{

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

            return      esc_attr__( 'Venue Location', 'sdweddingdirectory-venue' );
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
            add_filter( 'sdweddingdirectory/add-update-venue/tabs', [ $this, 'tab_info' ], absint( '30' ), absint( '1' ) );
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
             *  Extract Args
             *  ------------
             */
            extract( [

                'post_id'       =>      parent:: venue_post_ID(),

                'parent_id'     =>      esc_attr( parent:: _rand() )

            ] );

            /**
             *   Section Information
             *   -------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>   esc_attr( 'info' ),

                    'title'       =>   esc_attr__( 'Venue Location', 'sdweddingdirectory-venue' ),

                    'class'       =>   sanitize_html_class( 'mb-0' )
                )

            ) );

            /**
             *  Venue Address
             *  ---------------
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

                    'field_type'            =>      esc_attr( 'select-location' ),

                    'location_value_name'   =>      apply_filters( 'sdweddingdirectory/find-post-location/name', [

                                                        'taxonomy'      =>      esc_attr( 'venue-location' ),

                                                        'post_id'       =>      $post_id

                                                    ] ),

                    'location_value_id'     =>      apply_filters( 'sdweddingdirectory/find-post-location/id', [

                                                        'taxonomy'      =>      esc_attr( 'venue-location' ),

                                                        'post_id'       =>      $post_id

                                                    ] ),

                    'hide_empty'            =>      false,

                    'ajax_load'             =>      true,

                    'post_type'             =>      esc_attr( 'venue' ),

                    'before_input'          =>      sprintf( '<div class="row sdweddingdirectory-dropdown-parent create-input-border" id="%1$s">', 

                                                        $parent_id 
                                                    ),

                    'after_input'           =>      '</div>',

                    'parent_id'             =>      esc_attr( $parent_id ),
                )

            ) );

            /**
             *   Section Information
             *   -------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>   esc_attr( 'info' ),

                    'title'       =>   esc_attr__( 'Address and Pincode', 'sdweddingdirectory-venue' ),

                    'class'       =>   sanitize_html_class( 'mb-0' )
                )

            ) );

            /**
             *  Venue Address
             *  ---------------
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

                    'end'         =>   false,
                ),

                /**
                 *  Row Managment
                 *  -------------
                 */
                'row'        =>  array(

                    'id'          =>   '',

                    'class'       =>   '',

                    'start'       =>   true,

                    'end'         =>   false,
                ),

                /**
                 *  Column Managment
                 *  ----------------
                 */
                'column'     =>   array(

                    'grid'        =>   absint( '8' ),

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

                    'type'              =>  esc_attr( 'text' ),

                    'placeholder'       =>  esc_attr__( 'Venue Address', 'sdweddingdirectory-venue' ),

                    'name'              =>  esc_attr( 'venue_address' ),

                    'id'                =>  esc_attr( 'venue_address' ),

                    'formgroup'         =>  false,

                    'value'             =>  !   empty( $post_id ) 

                                            ?   get_post_meta(  absint( $post_id ), sanitize_key( 'venue_address' ), true )

                                            :   ''
                )

            ) );

            /**
             *  Venue Address
             *  ---------------
             */
            parent:: create_section( array(

                /**
                 *  DIV Managment
                 *  -------------
                 */
                'div'        =>  array(

                    'id'          =>   '',

                    'class'       =>   sanitize_html_class( 'card-body' ),

                    'start'       =>   false,

                    'end'         =>   true,
                ),

                /**
                 *  Row Managment
                 *  -------------
                 */
                'row'        =>  array(

                    'id'          =>   '',

                    'class'       =>   '',

                    'start'       =>   false,

                    'end'         =>   true,
                ),

                /**
                 *  Column Managment
                 *  ----------------
                 */
                'column'     =>   array(

                    'grid'        =>   absint( '4' ),

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

                    'type'              =>  esc_attr( 'text' ),

                    'placeholder'       =>  esc_attr__( 'Pincode / Zip Code', 'sdweddingdirectory-venue' ),

                    'name'              =>  esc_attr( 'venue_pincode' ),

                    'id'                =>  esc_attr( 'venue_pincode' ),

                    'formgroup'         =>  false,

                    'value'             =>  !   empty( $post_id ) 

                                            ?   get_post_meta(  absint( $post_id ), sanitize_key( 'venue_pincode' ), true )

                                            :   ''
                )

            ) );


            /**
             *  Have Map Permission ?
             *  ---------------------
             */
            if( parent:: sdweddingdirectory_have_map() ){

                /**
                 *   Section Information
                 *   -------------------
                 */
                parent:: create_section( array(

                    'field'     =>   array(

                        'field_type'  =>    esc_attr( 'info' ),

                        'class'       =>   sanitize_html_class( 'mb-0' ),

                        'title'       =>    esc_attr__( 'Map Location', 'sdweddingdirectory-venue' ),
                    )

                ) );

                /**
                 *  Venue Address
                 *  ---------------
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
                    'row'       =>  array(

                        'start'     =>  true,

                        'end'       =>  true,

                        'class'     =>  ''
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

                        'field_type'                    =>      esc_attr( 'find_map_with_address' ),

                        'map_hidden_in_div'             =>      true,

                        'map_address_placeholder'       =>      esc_attr__( 'Map Marker Address', 'sdweddingdirectory-venue' ),

                        'map_latitude_value'            =>      self:: _venue_latitude_value( $post_id ),

                        'map_longitude_value'           =>      self:: _venue_longitude_value( $post_id ),

                        'map_address_value'             =>      self:: _venue_map_address_value( $post_id )
                    )

                ) );
            }
        }

        /**
         *  Have Post ID ?
         *  --------------
         */
        public static function _venue_latitude_value( $post_id = 0 ){

            /**
             *  Post ID
             *  -------
             */
            if( ! empty( $post_id ) ){

                return      get_post_meta( 

                                /**
                                 *  1. Get the Venue ID
                                 *  ---------------------
                                 */
                                absint( $post_id ),

                                /**
                                 *  2. Meta Key 
                                 *  -----------
                                 */
                                sanitize_key( 'venue_latitude' ),

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
        public static function _venue_longitude_value( $post_id = 0 ){

            /**
             *  Post ID
             *  -------
             */
            if( ! empty( $post_id ) ){

                return      get_post_meta( 

                                /**
                                 *  1. Get the Venue ID
                                 *  ---------------------
                                 */
                                absint( $post_id ),

                                /**
                                 *  2. Meta Key 
                                 *  -----------
                                 */
                                sanitize_key( 'venue_longitude' ),

                                /**
                                 *  3. True
                                 *  -------
                                 */
                                true
                            );
            }
        }

        /**
         *  Have Map Address ?
         *  ------------------
         */
        public static function _venue_map_address_value( $post_id = 0 ){

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
                            sanitize_key( 'map_address' ),

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
    SDWeddingDirectory_Venue_Fields_Post_Location::get_instance();
}