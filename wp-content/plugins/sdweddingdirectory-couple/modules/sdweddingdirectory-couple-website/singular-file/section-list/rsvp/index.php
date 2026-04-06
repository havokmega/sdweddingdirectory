<?php
/**
 *  SDWeddingDirectory - Couple Wedding Website Section
 *  -------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Wedding_Website_Couple_RSVP_Section' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Section' ) ){

    /**
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    class SDWeddingDirectory_Couple_Wedding_Website_Couple_RSVP_Section extends SDWeddingDirectory_Couple_Website_Section{

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
            add_filter( 'sdweddingdirectory/wedding-website/layout-1', [ $this, 'widget' ], absint( '50' ), absint( '1' ) );
        }

        /**
         *  Wedding Website - Section 
         *  -------------------------
         */
        public static function widget( $args = [] ){

            $args[ 'rsvp' ]             =   [
                                                'scroll'        =>      '30',

                                                'name'          =>      esc_attr__( 'RSVP', 'sdweddingdirectory-couple-website' ),

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
            $img   =   parent:: media_id_to_get_src( array(

                                    'media_id'      =>  absint( parent:: get_website_meta( 'couple_rsvp_image' ) ),

                                    'size'          =>  esc_attr( 'sdweddingdirectory_img_1920x1200' ),

                                    'placeholder'   =>  esc_attr( 'web-layout-1-rsvp' )

                                ) );
            ?>
            <div class="will-you-attend" <?php printf( 'style="background: url(%1$s) no-repeat center center;"', $img ); ?>>
                <div class="container">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="head">
                                <?php
                                    
                                    $_have_heading      =   parent:: get_website_meta( 'couple_rsvp_heading' );

                                    $_have_description  =   parent:: get_website_meta( 'couple_rsvp_description' );

                                    /**
                                     *  Heading + Description
                                     *  ---------------------
                                     */
                                    printf( '%1$s %2$s',

                                            /**
                                             *  1. Heading
                                             *  ----------
                                             */
                                            parent:: _have_data( $_have_heading )   ?  sprintf( '<h1>%1$s</h1>', $_have_heading ) : '',

                                            /**
                                             *  2. Description
                                             *  --------------
                                             */
                                            parent:: _have_data( $_have_description )   ?  sprintf( '<p><span>%1$s</span></p>', $_have_description ) : ''
                                    );
                                ?>
                                <i class="sdweddingdirectory-cake-stand"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-md-12">
                            <div class="rsvp-form">
                                <div class="head pb-4 text-center">
                                    <h5><?php esc_attr_e( 'R.S.V.P.', 'sdweddingdirectory-couple-website' ); ?></h5>
                                    <img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'images/flower_art.png' ); ?>" />
                                </div>
                                <?php

                                    /**
                                     *  RSVP Form Action
                                     *  ----------------
                                     */
                                    do_action( 'sdweddingdirectory/couple/website/layout-1/rsvp' );

                                ?>
                            </div>
                        </div>
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
    SDWeddingDirectory_Couple_Wedding_Website_Couple_RSVP_Section::get_instance();
}