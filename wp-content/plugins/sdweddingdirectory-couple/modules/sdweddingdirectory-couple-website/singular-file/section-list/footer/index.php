<?php
/**
 *  SDWeddingDirectory - Couple Wedding Website Section
 *  -------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Wedding_Website_Couple_Footer_Section' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Section' ) ){

    /**
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    class SDWeddingDirectory_Couple_Wedding_Website_Couple_Footer_Section extends SDWeddingDirectory_Couple_Website_Section{

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
            add_filter( 'sdweddingdirectory/wedding-website/layout-1', [ $this, 'widget' ], absint( '100' ), absint( '1' ) );
        }

        /**
         *  Wedding Website - Section 
         *  -------------------------
         */
        public static function widget( $args = [] ){

            $args[ 'footer' ]      =   [
                                                'scroll'        =>      '40',

                                                'name'          =>      esc_attr__( 'Footer', 'sdweddingdirectory-couple-website' ),

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

                            'media_id'      =>   absint( parent:: get_website_meta( 'footer_image' ) ),

                            'size'          =>  esc_attr( 'sdweddingdirectory_img_1920x888' ),

                            'placeholder'   =>  esc_attr( 'web-layout-1-footer' )

                        ) );
            ?>
            <footer class="main-footer" style="background: url(<?php print esc_url( $_image ); ?>) no-repeat bottom center;">
                <div class="container">
                    <div class="text">
                        <?php

                            printf( '<h3>%1$s</h3><h1>%2$s <i class="sdweddingdirectory-heart-ring"></i> %3$s</h1>', 

                                /**
                                 *  1. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Just Get Married', 'sdweddingdirectory-couple-website' ),

                                /**
                                 *  2. Groom Name
                                 *  -------------
                                 */
                                parent:: get_website_meta( 'bride_first_name' ),

                                /**
                                 *  3. Bride Name
                                 *  -------------
                                 */
                                parent:: get_website_meta( 'groom_first_name' )
                            );

                            /**
                             *  Flower
                             *  ------
                             */
                            printf( '<img src="%1$s" alt="%2$s">',

                                /**
                                 *  1. Image Src
                                 *  ------------
                                 */
                                esc_url( plugin_dir_url( __FILE__ ) . 'images/' . 'flower.png' ),

                                /**
                                 *  2. Image Alt
                                 *  ------------
                                 */
                                parent:: _alt( array(

                                    'site_name' =>  esc_attr( get_bloginfo( 'name' ) ),

                                ) )
                            );

                        ?>

                    </div>

                    <?php echo '<div class="copyrights">Copyright &copy; ' . date('Y') . ' - Designed by SDWeddingDirectory</div>'; ?>

                </div>
            </footer>
            <?php
        }
    }

    /**
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    SDWeddingDirectory_Couple_Wedding_Website_Couple_Footer_Section::get_instance();
}
