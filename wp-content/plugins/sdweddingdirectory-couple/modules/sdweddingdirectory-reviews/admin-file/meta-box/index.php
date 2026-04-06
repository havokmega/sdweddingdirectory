<?php
/**
 *  SDWeddingDirectory Review Metabox
 *  -------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Review_Meta' ) ){

    /**
     *  SDWeddingDirectory Review Metabox
     *  -------------------------
     */
    class SDWeddingDirectory_Review_Meta{

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

            add_filter( 'sdweddingdirectory/meta', [ $this, 'sdweddingdirectory_review_meta_box' ], absint('10') );

            add_filter( 'sdweddingdirectory/meta', [ $this, 'sdweddingdirectory_venue_review_for' ], absint('10') );
        }

        public static function get_reviews_list(){

            return  array(

                array(
                    'value'   => '',
                    'label'   => esc_attr__('-- Rating --', 'sdweddingdirectory-reviews'),
                    'src'     => '',
                ),
                array(
                    'value'   => absint( '1' ),
                    'label'   => esc_attr__('1 Star Rating', 'sdweddingdirectory-reviews'),
                    'src'     => '',
                ),
                array(
                    'value'   => absint( '2' ),
                    'label'   => esc_attr__('2 Star Rating', 'sdweddingdirectory-reviews'),
                    'src'     => '',
                ),
                array(
                    'value'   => absint( '3' ),
                    'label'   => esc_attr__('3 Star Rating', 'sdweddingdirectory-reviews'),
                    'src'     => '',
                ),
                array(
                    'value'   => absint( '4' ),
                    'label'   => esc_attr__('4 Star Rating', 'sdweddingdirectory-reviews'),
                    'src'     => '',
                ),
                array(
                    'value'   => absint( '5' ),
                    'label'   => esc_attr__('5 Star Rating', 'sdweddingdirectory-reviews'),
                    'src'     => '',
                ),
            );
        }

        /**
         *  Rating - Metabox - Rating For
         *  -----------------------------
         */
        public static function sdweddingdirectory_venue_review_for( $args = [] ) {

            $new_args       =   array(

                'id'        =>  esc_attr( 'sdweddingdirectory_venue_review_for' ),

                'title'     =>  esc_attr__( 'Review For', 'sdweddingdirectory-reviews' ),

                'pages'     =>  array( 'venue-review' ),

                'context'   =>  esc_attr( 'side' ),

                'priority'  =>  esc_attr( 'low' ),

                'fields'    =>  array(

                    array(

                        'id'      =>    sanitize_key( 'couple_id' ),

                        'label'   =>    esc_attr__('Couple ID', 'sdweddingdirectory-reviews'),

                        'type'    =>    esc_attr( 'text' ),

                        // 'type'          =>    esc_attr( 'custom-post-type-select' ),

                        // 'post_type'     =>      esc_attr( 'couple' )
                    ),

                    array(

                        'id'      =>    sanitize_key( 'vendor_id' ),

                        'label'   =>    esc_attr__('Vendor ID', 'sdweddingdirectory-reviews'),

                        'type'    =>    esc_attr( 'text' ),

                        // 'type'          =>    esc_attr( 'custom-post-type-select' ),

                        // 'post_type'     =>      esc_attr( 'vendor' )
                    ),

                    array(

                        'id'      =>    sanitize_key( 'venue_id' ),

                        'label'   =>    esc_attr__('Venue ID', 'sdweddingdirectory-reviews'),

                        'type'    =>    esc_attr( 'text' ),

                        // 'type'          =>    esc_attr( 'custom-post-type-select' ),

                        // 'post_type'     =>      esc_attr( 'venue' )
                    ),
                ),
            );

            return  array_merge( $args, array( $new_args ) );
        }

        /**
         *  Review - Metabox - Rating Info
         *  ------------------------------
         */
        public static function sdweddingdirectory_review_meta_box( $args = [] ) {

            $new_args  = array(

                'id'        =>  esc_attr( 'SDWeddingDirectory_Couple_Give_Reviews' ),

                'title'     =>  esc_attr__( 'Venue Reviews', 'sdweddingdirectory-reviews' ),

                'pages'     =>  array( 'venue-review' ),

                'context'   =>  esc_attr( 'normal' ),

                'priority'  =>  esc_attr( 'high' ),

                'fields'    =>  array(

                    /**
                     *  Rating Tab
                     *  ----------
                     */
                    array(

                        'label'     =>  esc_attr__( 'Rating Info', 'sdweddingdirectory-reviews' ),

                        'id'        =>  esc_attr( 'sdweddingdirectory_rating_information' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),
                    array(

                        'id'        =>  sanitize_key( 'quality_service' ),

                        'label'     =>  esc_attr__( 'Quality of Service', 'sdweddingdirectory-reviews'),

                        'type'      =>  esc_attr( 'select' ),

                        'choices'   =>  self:: get_reviews_list(),
                    ),
                    array(

                        'id'        =>  sanitize_key( 'facilities' ),

                        'label'     =>  esc_attr__( 'Responsiveness', 'sdweddingdirectory-reviews'),

                        'type'      =>  esc_attr( 'select' ),

                        'choices'   =>  self:: get_reviews_list(),
                    ),
                    array(

                        'id'        =>  sanitize_key( 'staff' ),

                        'label'     =>  esc_attr__( 'Professionalism', 'sdweddingdirectory-reviews'),

                        'type'      =>  esc_attr( 'select' ),

                        'choices'   =>  self:: get_reviews_list(),
                    ),
                    array(

                        'id'        =>  sanitize_key( 'flexibility' ),

                        'label'     =>  esc_attr__( 'Flexibility', 'sdweddingdirectory-reviews'),

                        'type'      =>  esc_attr( 'select' ),

                        'choices'   =>  self:: get_reviews_list(),
                    ),
                    array(

                        'id'        =>  sanitize_key( 'value_of_money' ),

                        'label'     =>  esc_attr__( 'Value for Money', 'sdweddingdirectory-reviews'),

                        'type'      =>  esc_attr( 'select' ),

                        'choices'   =>  self:: get_reviews_list(),
                    ),

                    /**
                     *  Rating Comment Tab
                     *  ------------------
                     */
                    array(

                        'label'     =>  esc_attr__( 'Comment Info', 'sdweddingdirectory-reviews' ),

                        'id'        =>  esc_attr( 'sdweddingdirectory_rating_comment' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                    array(

                        'id'        =>  sanitize_key( 'couple_comment' ),

                        'label'     =>  esc_attr__('Couple Comment', 'sdweddingdirectory-reviews'),

                        'type'      =>  esc_attr( 'textarea-simple' ),

                        'rows'      =>  absint( '10' ),
                    ),

                    array(

                        'id'        =>  sanitize_key( 'vendor_comment' ),

                        'label'     =>  esc_attr__('Vendor Response', 'sdweddingdirectory-reviews'),

                        'type'      =>  esc_attr( 'textarea-simple' ),

                        'rows'      =>  absint( '10' ),
                    ),

                    array(

                        'id'        =>  sanitize_key( 'couple_comment_time' ),

                        'label'     =>  esc_attr__('Couple Comment Time', 'sdweddingdirectory-reviews'),

                        'type'      =>  esc_attr( 'text' ),
                    ),

                    array(

                        'id'        =>  sanitize_key( 'vendor_comment_time' ),

                        'label'     =>  esc_attr__('Vendor Comment Time', 'sdweddingdirectory-reviews'),

                        'type'      =>  esc_attr( 'text' ),
                    ),
                ),
            );

            return  array_merge( $args, array( $new_args ) );
        }
    }

    /**
     *  SDWeddingDirectory - Review Post Meta Object
     *  ------------------------------------
     */
    SDWeddingDirectory_Review_Meta:: get_instance();
}