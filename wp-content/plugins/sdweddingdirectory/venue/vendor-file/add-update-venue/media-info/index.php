<?php
/**
 *  ---------------------------
 *  SDWeddingDirectory - Venue Fields
 *  ---------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Fields_Post_Media' ) && class_exists( 'SDWeddingDirectory_Dashboard_Venue_Update' ) ){

    /**
     *  ---------------------------
     *  SDWeddingDirectory - Venue Fields
     *  ---------------------------
     */
    class SDWeddingDirectory_Venue_Fields_Post_Media extends SDWeddingDirectory_Dashboard_Venue_Update{

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

            return      esc_attr__( 'Upload Images', 'sdweddingdirectory-venue' );
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
            add_filter( 'sdweddingdirectory/add-update-venue/tabs', [ $this, 'tab_info' ], absint( '40' ), absint( '1' ) );
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

                    'title'       =>   esc_attr__( 'Featured Image', 'sdweddingdirectory-venue' ),

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

                    'field_type'        =>      esc_attr( 'single_img_upload' ),

                    'database_key'      =>      esc_attr( '_thumbnail_id' ),

                    'post_id'           =>      absint( $post_id ),

                    'image_size'        =>      esc_attr( 'sdweddingdirectory_img_1920x700' ),

                    'class'             =>      esc_attr( 'btn-default text-center' ),

                    'default_img'       =>      esc_url( parent:: placeholder( 'venue-banner' ) ),

                    'input_class'       =>      sanitize_html_class( 'venue_featured_image' )
                )

            ) );


            /**
             *   Section Information
             *   -------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>   esc_attr( 'info' ),

                    'title'       =>   esc_attr__( 'Venue Gallery', 'sdweddingdirectory-venue' ),

                    'class'       =>   sanitize_html_class( 'mb-0' )
                )

            ) );

                /**
                 *   Upload Gallery Images
                 *  ----------------------
                 */
                parent:: create_section( array(

                    /**
                     *  DIV Managment
                     *  -------------
                     */
                    'div'        =>  array(

                        'id'          =>   '',

                        'class'       =>   'card-body text-center',

                        'start'       =>   true,

                        'end'         =>   true,
                    ),

                    /**
                     *  Row Managment
                     *  -------------
                     */
                    'row'     =>   array(

                        'start'       =>   true,

                        'end'         =>   true,
                    ),

                    /**
                     *  Column Managment
                     *  ----------------
                     */
                    'column'     =>   array(

                        'grid'        =>   absint( '12' ),

                        'start'       =>   true,

                        'end'         =>   true,

                        'class'       =>   sanitize_html_class( 'text-center' )
                    ),

                    /**
                     *  Fields Arguments
                     *  ----------------
                     */
                    'field'    =>  array(

                        'field_type'        =>      esc_attr( 'gallery_img_upload' ),

                        'database_key'      =>      esc_attr( 'venue_gallery' ),

                        'image_size'        =>      esc_attr( 'thumbnail' ),

                        'post_id'           =>      absint( $post_id ),

                        'row_class'         =>      'row-cols-xxl-6 row-cols-xl-6 row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-2',

                        'input_class'       =>      sanitize_html_class( 'venue_gallery_image' ),

                        'button_class'      =>      'btn-primary btn-sm'
                    )

                ) );
        }
    }

    /**
     *  ---------------------------
     *  SDWeddingDirectory - Venue Fields
     *  ---------------------------
     */
    SDWeddingDirectory_Venue_Fields_Post_Media::get_instance();
}