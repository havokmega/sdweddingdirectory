<?php
/**
 *  SDWeddingDirectory - Couple Wedding Website Section
 *  -------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Wedding_Website_Couple_Thankyou_Section' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Section' ) ){

    /**
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    class SDWeddingDirectory_Couple_Wedding_Website_Couple_Thankyou_Section extends SDWeddingDirectory_Couple_Website_Section{

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
            add_filter( 'sdweddingdirectory/wedding-website/layout-1', [ $this, 'widget' ], absint( '80' ), absint( '1' ) );
        }

        /**
         *  Wedding Website - Section 
         *  -------------------------
         */
        public static function widget( $args = [] ){

            $args[ 'thank_you' ]        =   [
                                                'scroll'        =>      '130',                                

                                                'name'          =>      esc_attr__( 'Thank You\'s', 'sdweddingdirectory-couple-website' ),

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

            /**
             *  Testimonials Image
             *  ------------------
             */
            $_image  =  parent:: media_id_to_get_src( array(

                            'media_id'      =>   absint( parent:: get_website_meta( 'couple_testimonial_image' ) ),

                            'size'          =>  esc_attr( 'sdweddingdirectory_img_1400x500' ),

                            'placeholder'   =>  esc_attr( 'web-layout-1-testimonial' )

                        ) );
            ?>
            <div class="what-they-say" style="background: url(<?php echo esc_url( $_image ); ?>) no-repeat center center;">
                <div class="container">

                    <?php

                        /**
                         *  Load Header
                         *  -----------
                         */
                        parent:: heading_section( 'couple_testimonial_heading', 'couple_testimonial_description' );

                        $_testimonial   =   parent:: get_website_meta( 'couple_testimonial' );

                        /**
                         *  Have Testimonials
                         *  -----------------
                         */
                        if( parent:: _is_array( $_testimonial ) ){

                            ?>
                            <div class="row align-items-end">
                                <div class="col-lg-8 col-md-12 mx-auto">
                                    <div class="testimonail-slider">
                                        <div class="owl-carousel owl-theme" id="slider-testimonail"><?php

                            foreach( $_testimonial as $key => $value ){

                                /**
                                 *  Extract
                                 *  -------
                                 */
                                extract( $value );

                                /**
                                 *  Item
                                 *  ----
                                 */
                                printf('<div class="item">
                                            <div class="testimonail-quotes">
                                                <div class="text">%1$s</div>
                                                <div class="name">~ %2$s ~</div>
                                            </div>
                                        </div>',

                                        /**
                                         *  1. Content
                                         *  ----------
                                         */
                                        esc_attr( $content ),
                                        
                                        /**
                                         *  2. Name
                                         *  -------
                                         */
                                        esc_attr( $name )
                                );
                            }


                            ?></div></div></div></div><?php
                        }

                    ?>
                    
                </div>
            </div>
            <?php
        }
    }

    /**
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    SDWeddingDirectory_Couple_Wedding_Website_Couple_Thankyou_Section::get_instance();
}