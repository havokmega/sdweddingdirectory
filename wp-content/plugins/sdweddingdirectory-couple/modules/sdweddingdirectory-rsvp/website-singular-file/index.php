<?php
/**
 *  SDWeddingDirectory - Couple RSVP
 *  ------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_RSVP' ) && class_exists( 'SDWeddingDirectory_Couple_RSVP_Database' ) ){

    /**
     *  SDWeddingDirectory - Couple RSVP
     *  ------------------------
     */
    class SDWeddingDirectory_RSVP extends SDWeddingDirectory_Couple_RSVP_Database{

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
            add_action( 'wp_enqueue_scripts', [ $this, 'sdweddingdirectory_script' ] );

            /**
             *  2. RSVP Form - Website Layout 1
             *  -------------------------------
             */
            add_action( 'sdweddingdirectory/couple/website/layout-1/rsvp', [ $this, 'website_layout_1_rsvp' ] );
        }

        /**
         *  SDWeddingDirectory - Script
         *  -------------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Is Website Page ?
             *  -----------------
             */
            if( is_singular( 'website' ) ){

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
         *  RSVP Form - Website Layout 1
         *  ----------------------------
         */
        public static function website_layout_1_rsvp(){

            /**
             *  Load Form
             *  ---------
             */
            printf( '<div id="sdweddingdirectory-rsvp-process" data-post-id="%2$s">%1$s</div>',

                /**
                 *  Call Form
                 *  ---------
                 */
                self:: _find_name_in_rsvp_form(),

                /**
                 *  2. Website Post ID
                 *  ------------------
                 */
                absint( get_the_ID() )
            );
        }

        /**
         *  RSVP - Find Name Form
         *  ---------------------
         */
        public static function _find_name_in_rsvp_form(){

            $_collection        =   '';

            $form_fields        =   [

                'first_name'        =>  esc_attr__( 'First Name', 'sdweddingdirectory-rsvp' ),

                'last_name'         =>  esc_attr__( 'Last Name', 'sdweddingdirectory-rsvp' ),
            ];

            /**
             *  Make sure it's array ?
             *  ----------------------
             */
            if( parent:: _is_array( $form_fields ) ){

                foreach( $form_fields as $key => $value ){

                    $_collection    .=

                    sprintf( '<div class="col-md-4 col-12 mb-xs-20">

                                <input autocomplete="off" type="text" id="%1$s" placeholder="%2$s" class="%1$s form-control">

                            </div>',

                            /**
                             *  1. ID
                             *  -----
                             */
                            esc_attr( $key ),

                            /**
                             *  2. Placeholder
                             *  --------------
                             */
                            $value
                    );
                }
            }

            return  

            sprintf(   '<form id="sdweddingdirectory-rsvp-website-template-one" method="post">

                            <div class="row">

                                %1$s

                                <div class="col-md-4 col-12 mb-xs-20">

                                    <div class="d-grid">

                                        <button type="submit" id="couple_rsvp_submit_layout_1" class="btn btn-primary btn-block">%3$s</button>

                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-12 mt-3">

                                    <div id="_message"></div>

                                    <input autocomplete="off" type="hidden" id="_first_name_or_last_name" value="%2$s" />

                                    <input autocomplete="off" type="text" id="sdweddingdirectory_rsvp_honeypot" value="" tabindex="-1" style="position:absolute;left:-9999px;opacity:0;" />

                                </div>

                            </div>

                        </form>',

                        /**
                         *  1. Form Fields
                         *  --------------
                         */
                        $_collection,

                        /**
                         *  2. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr__( 'Enter your first name or last name.', 'sdweddingdirectory-rsvp' ),

                        /**
                         *  3. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr__( 'Search', 'sdweddingdirectory-rsvp' )
            );
        }
    }

    /**
     *  SDWeddingDirectory - Couple RSVP
     *  ------------------------
     */
    SDWeddingDirectory_RSVP::get_instance();
}
