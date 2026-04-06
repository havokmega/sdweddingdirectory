<?php
/**
 *  SDWeddingDirectory Request Quote
 *  ------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Request_Quote' ) && class_exists( 'SDWeddingDirectory_Request_Quote_Database' ) ){

    /**
     *  SDWeddingDirectory Request Quote
     *  ------------------------
     */
    class SDWeddingDirectory_Request_Quote extends SDWeddingDirectory_Request_Quote_Database{

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
             *  1. Load Script
             *  --------------
             */
            add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'] );

            /**
             *  2. Request quote Form Setup
             *  ---------------------------
             */
            add_action( 'sdweddingdirectory/venue/singular/right-side/widget', [$this, 'request_quote_form'], absint( '10' ), absint( '1' ) );

            /**
             *  3. Vendor Request quote Form Setup
             *  ----------------------------------
             */
            add_action( 'sdweddingdirectory/vendor/singular/right-side/widget', [$this, 'request_quote_form'], absint( '10' ), absint( '1' ) );
        }

        /**
         *  1. Load Script
         *  --------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Is Couple and not on Dashboard Page
             *  -----------------------------------
             */
            if( parent:: is_couple() && ! parent:: is_dashboard() ){

                /**
                 *  Load Script
                 *  -----------
                 */
                wp_enqueue_script(

                    /**
                     *  1. File Name
                     *  ------------
                     */
                    esc_attr( sanitize_title( __CLASS__ ) ),

                    /**
                     *  2. File Path
                     *  ------------
                     */
                    esc_url(   plugin_dir_url( __FILE__ )   .   'script.js'   ),

                    /**
                     *  3. Load After "JQUERY" Load
                     *  ---------------------------
                     */
                    array( 'jquery', 'toastr' ),

                    /**
                     *  4. Bootstrap - Library Version
                     *  ------------------------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ )   .   'script.js' ) ),

                    /**
                     *  5. Load in Footer
                     *  -----------------
                     */
                    true
                );
            }
        }

        /**
         *  2. Load Request Quote Form
         *  --------------------------
         */
        public static function request_quote_form( $args = [] ){

            /**
             *  Maker sure args is array
             *  ------------------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'       =>      absint( '0' ),

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                ?>
                <!-- Widget Wrap -->
                <div class="widget">

                    <h3 class="widget-title"><?php esc_attr_e( 'Message Supplier', 'sdweddingdirectory-request-quote' ); ?></h3>

                    <form id="<?php echo esc_attr( parent:: _rand() ); ?>" class="sdweddingdirectory_venue_request_quote" data-event-call="0" method="post">

                        <div class="request-quote-form">

                            <?php

                            /**
                             *  Form Fields
                             *  -----------
                             */
                            print   apply_filters( 'sdweddingdirectory/request-quote/form-fields',

                                        /**
                                         *  1. Default
                                         *  ----------
                                         */
                                        '',

                                        /**
                                         *  2. Args
                                         *  -------
                                         */
                                        [
                                            'venue_post_id'       =>      ( is_singular( 'venue' ) || is_singular( 'vendor' ) )

                                                                            ?   absint( get_the_ID() )

                                                                            :   absint( '0' ),

                                            'couple_post_id'        =>      parent:: is_couple() && is_user_logged_in()

                                                                            ?   absint( parent:: post_id() )

                                                                            :   absint( '0' )
                                        ]
                                    );
                            ?>

                        </div>

                    </form>

                </div>
                <!-- Widget Wrap -->
                <?php
            }
        }
    }   

    /**
     *  SDWeddingDirectory - Request Quote OBJ call
     *  -----------------------------------
     */
    SDWeddingDirectory_Request_Quote:: get_instance();
}
