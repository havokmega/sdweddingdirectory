<?php
/**
 *  SDWeddingDirectory - Couple Wedding Website Section
 *  -------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Wedding_Website_Couple_Grooms_Man_Section' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Section' ) ){

    /**
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    class SDWeddingDirectory_Couple_Wedding_Website_Couple_Grooms_Man_Section extends SDWeddingDirectory_Couple_Website_Section{

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
             *  Wedding Website - Section 
             *  -------------------------
             */
            add_filter( 'sdweddingdirectory/wedding-website/layout-1', [ $this, 'widget' ], absint( '60' ), absint( '1' ) );
        }

        /**
         *  Wedding Website - Section 
         *  -------------------------
         */
        public static function widget( $args = [] ){

            $args[ 'groomsmen' ]      =     [
                                                'scroll'        =>      '40',                                

                                                'name'          =>      esc_attr__( 'Groomsmen', 'sdweddingdirectory-couple-website' ),

                                                'member'        =>      [ __CLASS__, 'member' ],

                                                'menu'          =>      true
                                            ];
            return      $args;
        }

        /**
         *  Couple About Us
         *  ---------------
         */
        public static function member(){

            ?>
            <div class="wide-tb-120">
                <div class="container">

                    <?php parent:: heading_section( 'couple_groom_heading', 'couple_groom_description' ); ?>

                    <div class="row">
                        <?php

                            $_grooms    =   parent:: get_website_meta( 'couple_groom' );

                            if( parent:: _is_array( $_grooms ) ){

                                foreach( $_grooms as $key => $value ){

                                    /**
                                     *  Extract
                                     *  -------
                                     */
                                    extract( $value );

                                    /**
                                     *  Load Grooms
                                     *  -----------
                                     */
                                    printf( '<div class="col-lg-3 col-md-6">
                                                <div class="friends-members">
                                                    <img src="%1$s" alt="%2$s" />
                                                    <h4>%2$s</h4>
                                                </div>
                                            </div>',

                                            /**
                                             *  1. Image
                                             *  --------
                                             */
                                            parent:: media_id_to_get_src( [

                                                'media_id'  =>  absint( $groom_image ),

                                                'size'      =>  esc_attr( 'sdweddingdirectory_img_200x200' ),

                                            ] ),

                                            /**
                                             *  2. Name
                                             *  -------
                                             */
                                            esc_attr( $groom_name )
                                    );
                                }
                            }

                        ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }

    /**
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    SDWeddingDirectory_Couple_Wedding_Website_Couple_Grooms_Man_Section::get_instance();
}