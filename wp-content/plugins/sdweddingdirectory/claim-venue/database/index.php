<?php
/**
 *  SDWeddingDirectory Claim Venue Database
 *  ---------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Claim_Venue_Database' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory Claim Venue Database
     *  ---------------------------------
     */
    class SDWeddingDirectory_Claim_Venue_Database extends SDWeddingDirectory_Config{

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

        }

        /**
         *  Claim Condition
         *  ---------------
         */
        public static function claim_condition(){

            $_condition_1   =   is_singular( 'venue' ) || is_singular( 'vendor' );

            $_condition_2   =   parent:: is_vendor() && parent:: post_id() !== parent:: venue_author_id( get_the_ID() );

            $_condition_3   =   self:: venue_claim_available( absint( get_the_ID() ) );

            /**
             *  1. Make sure it's venue singular page
             *  ---------------------------------------
             *  2. Make sure is vendor
             *  ----------------------
             *  3. Make sure it's vendor and it's not owner of venue
             *  ------------------------------------------------------
             */
            return  $_condition_1 && $_condition_2 && $_condition_3;
        }

        /**
         *  Check Venue Claim Available ?
         *  -------------------------------
         */
        public static function venue_claim_available( $venue_id = '0' ){

            if( empty( $venue_id ) ){

                return;
            }

            /**
             *  Have Claim ?
             *  ------------
             */
            $_claim_available   =   get_post_meta( 

                                        /**
                                         *  1. Venue Post ID
                                         *  ------------------
                                         */
                                        absint( $venue_id ),

                                        /**
                                         *  2. Meta Key
                                         *  -----------
                                         */
                                        sanitize_key( 'claim_available_for_venue' ), 

                                        /**
                                         *  3. TRUE
                                         *  -------
                                         */
                                        true
                                    );
            /**
             *  Check Condition
             *  ---------------
             */
            if( parent:: _have_data( $_claim_available ) && $_claim_available == esc_attr( 'off' ) ){

                return  false;

            }else{

                return  true;
            }
        }

    } /* class end **/

    /**
     *  SDWeddingDirectory Claim Venue Database
     *  ---------------------------------
     */
    SDWeddingDirectory_Claim_Venue_Database:: get_instance();
}
