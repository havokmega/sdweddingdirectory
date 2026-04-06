<?php
/**
 *  ---------------------------
 *  SDWeddingDirectory - Venue Fields
 *  ---------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Fields_Post_FAQ' ) && class_exists( 'SDWeddingDirectory_Dashboard_Venue_Update' ) ){

    /**
     *  ---------------------------
     *  SDWeddingDirectory - Venue Fields
     *  ---------------------------
     */
    class SDWeddingDirectory_Venue_Fields_Post_FAQ extends SDWeddingDirectory_Dashboard_Venue_Update{

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

            return      esc_attr__( 'Faq\'s', 'sdweddingdirectory-venue' );
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
            add_filter( 'sdweddingdirectory/add-update-venue/tabs', [ $this, 'tab_info' ], absint( '90' ), absint( '1' ) );
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

                    'title'       =>   esc_attr__( 'Venue Faq\'s', 'sdweddingdirectory-venue' ),

                    'class'       =>   sanitize_html_class( 'mb-0' )
                )

            ) );

            /**
             *  Add FAQ Button
             *  --------------
             */
            printf( '<div class="card-body %1$s">

                        <div class="row row-cols-lg-1 row-cols-md-1 row-cols-1 %1$s" id="%1$s">%4$s</div>

                        <div class="text-center">

                            <a href="javascript:void(0)" 

                            class="btn btn-sm btn-default sdweddingdirectory_group_accordion" 

                            data-class="SDWeddingDirectory_Vendor_Venue_Database"

                            data-member="get_venue_faqs"

                            data-parent="%1$s"

                            id="%2$s">%3$s</a>

                        </div>

                    </div>',

                    /**
                     *  1. Parent Collapse ID
                     *  ---------------------
                     */
                    esc_attr( 'venue_faqs' ),

                    /**
                     *  2. Section Button ID
                     *  --------------------
                     */
                    esc_attr( parent:: _rand() ),

                    /**
                     *  3. Button Text : Translation Ready
                     *  ----------------------------------
                     */
                    esc_attr__( 'Add New Faq', 'sdweddingdirectory-venue' ),

                    /**
                     *  4. Data
                     *  -------
                     */
                    $post_id

                    ?   parent:: get_venue_faqs( [ 'post_id' =>  absint( $post_id ) ] )

                    :   ''
            );
        }
    }

    /**
     *  ---------------------------
     *  SDWeddingDirectory - Venue Fields
     *  ---------------------------
     */
    SDWeddingDirectory_Venue_Fields_Post_FAQ::get_instance();
}