<?php
/**
 *  SDWeddingDirectory Couple Guest List RSVP
 *  ---------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dashboard_Guest_List_Invitation' ) && class_exists( 'SDWeddingDirectory_Dashboard_Guest_List' ) ){

    /**
     *  SDWeddingDirectory Couple Guest List RSVP
     *  ---------------------------------
     */
    class SDWeddingDirectory_Dashboard_Guest_List_Invitation extends SDWeddingDirectory_Dashboard_Guest_List{

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
        public function __construct(){

            /**
             *  1. Dashboard Scripts
             *  --------------------
             */
            add_action( 'wp_enqueue_scripts', [ $this, 'sdweddingdirectory_script' ], absint( '51' ) );

            /**
             *  2. Dashboard Page
             *  -----------------
             */
            add_action( 'sdweddingdirectory/couple-dashboard', [ $this, 'dashboard_page' ], absint( '51' ), absint( '1' ) );
        }

        /**
         *  1. Dashboard Scripts
         *  --------------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  If is couple guest list
             *  -----------------------
             */
            if( parent:: dashboard_page_set( esc_attr( 'guest-management-invitation' ) ) ) {

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
                    [ 'jquery' ],

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
         *  2. Dashboard Page
         *  -----------------
         */
        public static function dashboard_page( $args = [] ){

            /**
             *  Have Args
             *  ---------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args,[

                    'layout'        =>      absint( '1' ),

                    'post_id'       =>      '',

                    'page'          =>      ''

                ] ) );

                /**
                 *  Make sure this page is couple dashboard page
                 *  --------------------------------------------
                 */
                if( ! empty( $page ) && $page == esc_attr( 'guest-management-invitation' ) ){

                    ?><div class="container"><?php

                        /**
                         *  2.1 Load Title
                         *  --------------
                         */
                        printf('<div class="section-title">

                                    <div class="row">

                                        <div class="col"><h2>%1$s</h2></div>

                                    </div>

                                </div>',

                                /**
                                 *  1. Title
                                 *  --------
                                 */
                                esc_attr__( 'Send your invitations online', 'sdweddingdirectory-guest-list' )
                        );

                        ?>
                        <div class="row">

                            <div class="col-md-3"><div class="row"><?php self:: invitation_sidebar(); ?></div></div>

                            <div class="col-md-7"><div class="row"><?php self:: invitation_email_body(); ?></div></div>

                        </div>

                    </div><?php
                }
            }
        }

        /**
         *  Sidebar
         *  -------
         */
        public static function invitation_sidebar(){

            /**
             *  Get Couple Events
             *  -----------------
             */
            $_events    =   parent:: event_list();

            /**
             *  Make sure event have data
             *  -------------------------
             */
            if( parent:: _is_array( $_events ) ){

                ?>
                <div class="col-12">

                    <div class="card">
                            
                        <div class="card-body">
                            
                            <p><?php esc_attr_e( 'CHOOSE AN EVENT:', 'sdweddingdirectory-guest-list' ); ?></p>

                            <select id="select_event" name="select_event" class="form-control">

                            <?php

                                /**
                                 *  Select Options
                                 *  --------------
                                 */
                                foreach( array_column( $_events, 'event_list', 'event_unique_id' ) as $key => $value ){

                                    /**
                                     *  Options
                                     *  -------
                                     */
                                    printf( '<option value="%1$s">%2$s</option>', 

                                        /**
                                         *  1. Key
                                         *  ------
                                         */
                                        esc_attr( $key ),

                                        /**
                                         *  2. Value
                                         *  --------
                                         */
                                        esc_attr( $value )
                                    );
                                }

                            ?>

                            </select>

                        </div>

                    </div>

                </div>
                <?php
            }

            ?>
            <div class="col-12">

                <div class="card">
                        
                    

                </div>

            </div>            

            <?php
        }

        /**
         *  Body Content
         *  ------------
         */
        public static function invitation_email_body(){
         

        }
    }

    /**
     *  SDWeddingDirectory Couple Guest List RSVP
     *  ---------------------------------
     */
    SDWeddingDirectory_Dashboard_Guest_List_Invitation:: get_instance();
}