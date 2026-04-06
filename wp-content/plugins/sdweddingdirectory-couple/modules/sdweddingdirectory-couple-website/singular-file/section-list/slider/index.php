<?php
/**
 *  SDWeddingDirectory - Couple Wedding Website Section
 *  -------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Wedding_Website_Couple_Slider_Section' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Section' ) ){

    /**
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    class SDWeddingDirectory_Couple_Wedding_Website_Couple_Slider_Section extends SDWeddingDirectory_Couple_Website_Section{

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
            add_filter( 'sdweddingdirectory/wedding-website/layout-1', [ $this, 'widget' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Wedding Website - Section 
         *  -------------------------
         */
        public static function widget( $args = [] ){

            $args[ 'slider' ]   =           [
                                                'scroll'        =>      '40',                                

                                                'name'          =>      esc_attr__( 'Slider', 'sdweddingdirectory-couple-website' ),

                                                'member'        =>      [ __CLASS__, 'member' ],

                                                'menu'          =>      false
                                            ];
            return      $args;
        }

        /**
         *  Couple About Us
         *  ---------------
         */
        public static function member(){

            /**
             *  Header Image
             *  ------------
             */
            $_image  =  parent:: media_id_to_get_src( array(

                            'media_id'      =>   absint( parent:: get_website_meta( 'header_image' ) ),

                            'size'          =>  esc_attr( 'full' ),

                            'placeholder'   =>  esc_attr( 'web-layout-1-slider' )

                        ) );
            ?>
            <!--  Home Banner Start -->
            <section class="home-background" style="background: url(<?php print esc_url( $_image ); ?>) no-repeat top center;">
                <div class="home-content">
                    <div class="container">
                        <div class="name">
                            <?php

                                printf( '<h1>%1$s <i class="sdweddingdirectory-heart-ring"></i> %2$s</h1><em>%3$s</em>',

                                    /**
                                     *  1. Groom Name
                                     *  -------------
                                     */
                                    parent:: get_website_meta( 'bride_first_name' ),

                                    /**
                                     *  2. Bride Name
                                     *  -------------
                                     */
                                    parent:: get_website_meta( 'groom_first_name' ),

                                    /**
                                     *  3. Translation Ready String
                                     *  ---------------------------
                                     */
                                    esc_attr__( 'Are getting Married!', 'sdweddingdirectory-couple-website' )
                                );

                                printf( '<div class="date"><h3>%1$s</h3></div>',

                                    /**
                                     *  1. Wedding Date
                                     *  ---------------
                                     */
                                    esc_attr(  date( 'l - d F Y', strtotime(  parent:: get_website_meta( 'wedding_date' ) ) )  )
                                );
                            ?>
                        </div>
                    </div>
                </div>
            </section>
            <!--  Home Banner End -->
            <?php
        }
    }

    /**
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    SDWeddingDirectory_Couple_Wedding_Website_Couple_Slider_Section::get_instance();
}