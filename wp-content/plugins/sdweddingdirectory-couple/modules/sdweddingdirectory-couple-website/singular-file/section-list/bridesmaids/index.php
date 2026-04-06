<?php
/**
 *  SDWeddingDirectory - Couple Wedding Website Section
 *  -------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Wedding_Website_Couple_Bridesmaid_Section' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Section' ) ){

    /**
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    class SDWeddingDirectory_Couple_Wedding_Website_Couple_Bridesmaid_Section extends SDWeddingDirectory_Couple_Website_Section{

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
            add_filter( 'sdweddingdirectory/wedding-website/layout-1', [ $this, 'widget' ], absint( '70' ), absint( '1' ) );
        }

        /**
         *  Wedding Website - Section 
         *  -------------------------
         */
        public static function widget( $args = [] ){

            $args[ 'bridesmaids' ]      =   [
                                                'scroll'        =>      '130',                                

                                                'name'          =>      esc_attr__( 'Bridesmaids', 'sdweddingdirectory-couple-website' ),

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
            <div class="wide-tb-120 pt-0">
                <div class="container">

                    <?php parent:: heading_section( 'couple_bride_heading', 'couple_bride_description' ); ?>

                    <div class="row">
                        <?php

                            $_bride    =   parent:: get_website_meta( 'couple_bride' );

                            if( parent:: _is_array( $_bride ) ){

                                foreach( $_bride as $key => $value ){

                                    /**
                                     *  Extract
                                     *  -------
                                     */
                                    extract( $value );

                                    /**
                                     *  Load Bride
                                     *  ----------
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

                                                'media_id'  =>  absint( $bride_image ),

                                                'size'      =>  esc_attr( 'sdweddingdirectory_img_200x200' ),

                                            ] ),

                                            /**
                                             *  2. Name
                                             *  -------
                                             */
                                            esc_attr( $bride_name )
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
    SDWeddingDirectory_Couple_Wedding_Website_Couple_Bridesmaid_Section::get_instance();
}