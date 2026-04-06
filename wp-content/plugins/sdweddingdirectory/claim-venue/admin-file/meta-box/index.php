<?php
/**
 *  SDWeddingDirectory Claim Post Metabox
 *  -----------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Claim_Post_Meta' ) ){

    /**
     *  SDWeddingDirectory Claim Post Metabox
     *  -----------------------------
     */
    class SDWeddingDirectory_Claim_Post_Meta{

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
             *  Venue Post Added One of Claim Available for this venue ?
             *  ------------------------------------------------------------
             */
            add_filter( 'sdweddingdirectory/meta', [ $this, 'sdweddingdirectory_venue_claim_available' ], absint('10') );

            /**
             *  Claim Venue Post Meta
             *  -----------------------
             */
            add_filter( 'sdweddingdirectory/meta', [ $this, 'sdweddingdirectory_venue_claim_meta_list' ], absint('10') );
        }

        /**
         *  Venue Post Added One of Claim Available for this venue ?
         *  ------------------------------------------------------------
         */
        public static function sdweddingdirectory_venue_claim_available( $args = [] ) {

            $new_args = array(

                'id'        =>  esc_attr('sdweddingdirectory_venue_claim_available_section'),

                'title'     =>  esc_attr__('Claim Available ?', 'sdweddingdirectory-claim-venue'),

                'pages'     =>  array( 'venue' ),

                'context'   =>  esc_attr( 'side' ),

                'priority'  =>  esc_attr( 'low' ),

                'fields'    =>  array(

                    array(

                        'id'    =>  sanitize_key( "claim_available_for_venue" ),

                        'std'   =>  esc_attr( 'off' ),

                        'type'  =>  esc_attr( 'on-off' ),
                    ),
                ),
            );

            return array_merge($args, array($new_args));
        }

        /**
         *  Claim Venue Post Meta
         *  -----------------------
         */
        public static function sdweddingdirectory_venue_claim_meta_list( $args = [] ) {

            $new_args  = array(

                'id'        =>  esc_attr( 'sdweddingdirectory_venue_claim_more_info' ),

                'title'     =>  esc_attr__( 'Claim Info', 'sdweddingdirectory-claim-venue' ),

                'pages'     =>  array( 'claim-venue' ),

                'context'   =>  esc_attr( 'normal' ),

                'priority'  =>  esc_attr( 'high' ),

                'fields'    =>  array(

                    array(

                        'id'      =>    sanitize_key( 'claimant_name' ),

                        'label'   =>    esc_attr__('Claimant Name', 'sdweddingdirectory-claim-venue'),

                        'type'    =>    esc_attr( 'text' ),
                    ),

                    array(

                        'id'      =>    sanitize_key( 'claimant_phone' ),

                        'label'   =>    esc_attr__('Claimant Phone', 'sdweddingdirectory-claim-venue'),

                        'type'    =>    esc_attr( 'text' ),
                    ),

                    array(

                        'id'      =>    sanitize_key( 'claimant_email' ),

                        'label'   =>    esc_attr__( 'Claimant Email', 'sdweddingdirectory-claim-venue'),

                        'type'    =>    esc_attr( 'text' ),
                    ),

                    array(

                        'id'      =>    sanitize_key( 'target_post_id' ),

                        'label'   =>    esc_attr__('Target Post ID', 'sdweddingdirectory-claim-venue'),

                        'type'    =>    esc_attr( 'text' ),
                    ),

                    array(

                        'id'      =>    sanitize_key( 'target_post_type' ),

                        'label'   =>    esc_attr__('Target Post Type', 'sdweddingdirectory-claim-venue'),

                        'type'    =>    esc_attr( 'text' ),
                    ),

                    array(

                        'id'      =>    sanitize_key( 'target_slug' ),

                        'label'   =>    esc_attr__('Target Slug', 'sdweddingdirectory-claim-venue'),

                        'type'    =>    esc_attr( 'text' ),
                    ),
                ),
            );

            return      array_merge($args, array($new_args));
        }
    }   

    /**
     *  SDWeddingDirectory - Claim Lising Post Meta Object
     *  ------------------------------------------
     */
    SDWeddingDirectory_Claim_Post_Meta:: get_instance();
}
