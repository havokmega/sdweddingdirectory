<?php
/**
 *  SDWeddingDirectory - Couple Wedding Website Section
 *  -------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Wedding_Website_Couple_Info_Section' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Section' ) ){

    /**
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    class SDWeddingDirectory_Couple_Wedding_Website_Couple_Info_Section extends SDWeddingDirectory_Couple_Website_Section{

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
            add_filter( 'sdweddingdirectory/wedding-website/layout-1', [ $this, 'widget' ], absint( '20' ), absint( '1' ) );
        }

        /**
         *  Wedding Website - Section 
         *  -------------------------
         */
        public static function widget( $args = [] ){

            $args[ 'about_couple' ]     =   [
                                                'scroll'        =>      '40',

                                                'name'          =>      esc_attr__( 'Couple', 'sdweddingdirectory-couple-website' ),

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
             *  Header Image
             *  ------------
             */
            $_image  =  parent:: media_id_to_get_src( array(

                            'media_id'      =>   absint( parent:: get_website_meta( '_thumbnail_id' ) ),

                            'size'          =>  esc_attr( 'sdweddingdirectory_img_600x600' ),

                            'placeholder'   =>  esc_attr( 'web-layout-1-couple-frame' )

                        ) );
            ?>
            <div class="wide-tb-120 bg-light">
                <div class="container">

                    <?php parent:: heading_section( 'couple_info_heading', 'couple_info_description' ); ?>

                    <div class="row align-items-center">

                        <div class="col-lg-6 order-lg-2">
                            <div class="text-center">

                                <div class="image-stack">
                                  <div class="image-stack__item image-stack__item--bottom">
                                    <img src="<?php echo esc_url( $_image ); ?>" alt="<?php the_title(); ?>">
                                  </div>
                                  <div class="image-stack__item image-stack__item--top">
                                    <img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'images/couple-frame.png' ); ?>" />
                                  </div>
                                </div>
                                
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 order-lg-2">
                            <?php

                                /**
                                 *  Groom Info
                                 *  ----------
                                 */
                                printf( '<div class="couple-info"><span>%1$s</span> <h3>%2$s</h3> <p>%3$s</p> %4$s </div>',

                                        /**
                                         *  1. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Groom', 'sdweddingdirectory-couple-website' ),

                                        /**
                                         *  2. Groom Name
                                         *  -------------
                                         */
                                        parent:: get_website_meta( 'groom_first_name' ),

                                        /**
                                         *  3. Get Website Data
                                         *  -------------------
                                         */
                                        parent:: get_website_meta( 'about_groom' ),

                                        /**
                                         *  4. Facebook
                                         *  -----------
                                         */
                                        self:: get_social_media( 'groom' )
                                );

                            ?>
                        </div>

                        <div class="col-lg-3 col-md-6 order-lg-13">
                            <?php

                                /**
                                 *  Bride Info
                                 *  ----------
                                 */
                                printf( '<div class="couple-info"> <span>%1$s</span> <h3>%2$s</h3> <p>%3$s</p> %4$s </div>',

                                        /**
                                         *  1. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Bride', 'sdweddingdirectory-couple-website' ),

                                        /**
                                         *  2. Groom Name
                                         *  -------------
                                         */
                                        parent:: get_website_meta( 'bride_first_name' ),

                                        /**
                                         *  3. Get Website Data
                                         *  -------------------
                                         */
                                        parent:: get_website_meta( 'about_bride' ),

                                        /**
                                         *  4. Facebook
                                         *  -----------
                                         */
                                        self:: get_social_media( 'bride' )
                                );

                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php
        }

        /**
         *  Get Social 
         *  ----------
         */
        public static function get_social_media( $prefix = '' ){

            /**
             *  Make sure it's not empty!
             *  -------------------------
             */
            if( empty( $prefix ) ){

                return;
            }

            /**
             *  Collected Groom Social
             *  ----------------------
             */
            $_get_social  =   '';

            /**
             *  Groom Social
             *  ------------
             */
            $_social     =  array(

                $prefix . '_instagram'     =>  '<i class="fa fa-instagram"></i>',

                $prefix . '_facebook'      =>  '<i class="fa fa-facebook-f"></i>',

                $prefix . '_twitter'       =>  '<i class="fa fa-twitter"></i>',
            );

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $_social ) ){

                $_get_social  .=   '<div class="social-icons"><ul class="list-unstyled">';

                foreach( $_social as $key => $value ){

                    $_have_link          =   parent:: get_website_meta( $key );

                    if( parent:: _have_data( $_have_link ) ){

                        $_get_social  .=

                        sprintf( '<li><a href="%1$s">%2$s</a></li>', 

                            /**
                             *  1. Get Data
                             *  -----------
                             */
                            esc_attr( $_have_link ),

                            /**
                             *  2. Icon
                             *  -------
                             */
                            $value
                        );
                    }
                }

                $_get_social  .=   '</ul></div>';
            }

            /**
             *  Return Data
             *  -----------
             */
            return  $_get_social;
        }
    }

    /**
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    SDWeddingDirectory_Couple_Wedding_Website_Couple_Info_Section::get_instance();
}