<?php
/**
 *  SDWeddingDirectory Claim Venue Database
 *  ---------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Claim_Venue_Button_Widget' ) && class_exists( 'SDWeddingDirectory_Claim_Venue_Filter_Hook' ) ){

    /**
     *  SDWeddingDirectory Claim Venue Database
     *  ---------------------------------
     */
    class SDWeddingDirectory_Claim_Venue_Button_Widget extends SDWeddingDirectory_Claim_Venue_Filter_Hook{

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
             *  Claim Button Load
             *  -----------------
             */
            add_action( 'sdweddingdirectory/venue/singular/header-button/bottom', [ $this, 'button_widget' ], absint( '20' ), absint( '1' ) );

            /**
             *  Vendor Claim Button Load
             *  ------------------------
             */
            add_action( 'sdweddingdirectory/vendor/singular/header-button/bottom', [ $this, 'button_widget' ], absint( '20' ), absint( '1' ) );

            /**
             *  Register Popup ID
             *  -----------------
             */
            add_filter( 'sdweddingdirectory/model-popup', [ $this, 'register_modal' ], absint( '50' ), absint( '1' ) );

            /**
             *  Load Script
             *  -----------
             */
            add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'] );

            /**
             *  Claim Model
             *  -----------
             */
            add_action( 'wp_footer', [$this, 'sdweddingdirectory_claim_popup'] );

            /**
             *  SDWeddingDirectory - Claim Button Attr
             *  ------------------------------
             */
            add_filter( 'sdweddingdirectory/claim-button/attr', [$this, 'claim_button_attr' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Claim Button Load
         *  -----------------
         */
        public static function button_widget( $args = [] ){

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                extract( wp_parse_args( $args, array(

                    'post_id'   =>  absint( '0' )

                ) ) );
            }

            /** 
             *  Is Empty!
             *  ---------
             */
            if( empty( $post_id ) ){

                return;
            }

            /**
             *  Claim Available ?
             *  -----------------
             */
            $claim_available        =   get_post_meta(  $post_id,  sanitize_key( 'claim_available_for_venue' ),  true  );

            /**
             *  Make sure claim is off
             *  ----------------------
             */
            if(  $claim_available == esc_attr( 'off' )  ){

                return;
            }

            /**
             *  Claim Available ?
             *  -----------------
             */
            if( ! is_user_logged_in() ){

                printf( '<a class="btn btn-sm btn-dark" %1$s>

                            <i class="fa fa-bullhorn"></i> <span>%2$s</span>

                        </a>',

                        /**
                         *  1. Vendor Login Model ID
                         *  ------------------------
                         */
                        apply_filters( 'sdweddingdirectory/vendor-login/attr', '' ),

                        /**
                         *  2. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr__( 'Claim Now', 'sdweddingdirectory-claim-venue' )
                );
            }

            elseif( parent:: claim_condition() ){

                printf( '<a class="btn btn-sm btn-dark" %1$s>

                            <i class="fa fa-bullhorn"></i>

                            <span>%2$s</span>

                        </a>',

                    /**
                     *  1. Claim  Model ID
                     *  ------------------
                     */
                    apply_filters( 'sdweddingdirectory/claim-button/attr', '' ),

                    /**
                     *  2. Translation Ready String
                     *  ---------------------------
                     */
                    esc_attr__( 'Claim Now', 'sdweddingdirectory-claim-venue' )
                );
            }

            else{

                printf( '<button type="button" class="btn btn-sm btn-dark" disabled> 

                            <i class="fa fa-bullhorn"></i>

                                <span>%1$s</span>

                        </button>',

                        /**
                         *  2. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr__( 'Claim Now', 'sdweddingdirectory-claim-venue' )
                );
            }
        }

        /**
         *  Register Modal
         *  --------------
         */
        public static function register_modal( $args = [] ){

            return      array_merge( $args, [

                            [
                                'slug'              =>      esc_attr( 'claim_venue' ),

                                'modal_id'          =>      esc_attr( 'sdweddingdirectory_claim_venue' ),

                                'redirect_link'     =>      '',

                                'name'              =>      esc_attr__( 'Claim Venue Modal Popup', 'sdweddingdirectory-claim-venue' )
                            ],

                        ] );
        }

        /**
         *  1. Load Script
         *  --------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Check Claim Condition
             *  ---------------------
             */
            if( parent:: claim_condition() ){

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
         *  2. SDWeddingDirectory - Claim Model Popup
         *  ---------------------------------
         */
        public static function sdweddingdirectory_claim_popup(){

            if( parent:: claim_condition() ){

                /**
                 *  Claim Venue Model Popup ID
                 *  ----------------------------
                 */
                printf( '<!-- Claim Venue Model Popup ID -->
                        <div class="modal fade" id="%1$s" tabindex="-1" aria-labelledby="%1$s" aria-hidden="true">',

                    /**
                     *  1. Claim Venue Model ID
                     *  -------------------------
                     */
                    esc_attr( parent:: popup_id( 'claim_venue' ) )
                );

                ?>
                    <div class="modal-dialog modal-dialog-centered modal-md">
                        <div class="modal-content">

                            <div class="modal-header">
                                <?php

                                    /**
                                     *  Heading
                                     *  -------
                                     */
                                    printf( '<h3 class="modal-title">%1$s</h3>',

                                        /**
                                         *  1. Claim Model Heading
                                         *  ----------------------
                                         */
                                        esc_attr__( 'Claim This Venue', 'sdweddingdirectory-claim-venue' )
                                    );
                                ?>
                                
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>

                            </div>

                            <div class="modal-body">
                               
                                <form id="sdweddingdirectory_claim_venue_form" method="post">
                                    <div class="row">

                                        <?php

                                            $required   =   esc_attr( 'required' );

                                            /**
                                             *  First Name
                                             *  ----------
                                             */
                                            printf( '<div class="col-12"><div class="mb-3">

                                                        <label class="control-label sr-only" for="%1$s"></label>

                                                        <input autocomplete="off" id="%1$s" type="text" name="%1$s" placeholder="%2$s" value="%3$s" class="form-control" %4$s />

                                                    </div></div>',

                                                    /**
                                                     *  1. Name
                                                     *  -------
                                                     */
                                                    esc_attr( 'sdweddingdirectory_claim_venue_first_name' ),

                                                    /**
                                                     *  2. Placeholder
                                                     *  --------------
                                                     */
                                                    esc_attr__('First Name','sdweddingdirectory-claim-venue'),

                                                    /**
                                                     *  3. Value
                                                     *  --------
                                                     */
                                                    parent:: _have_data( parent:: get_data( esc_attr( 'first_name' ) ) )

                                                    ?   parent:: get_data( esc_attr( 'first_name' ) )

                                                    :   '',

                                                    /**
                                                     *  4. Required Fields
                                                     *  ------------------
                                                     */
                                                    $required
                                            );

                                            /**
                                             *  Last Name
                                             *  ----------
                                             */
                                            printf( '<div class="col-12"><div class="mb-3">

                                                        <label class="control-label sr-only" for="%1$s"></label>

                                                        <input autocomplete="off" id="%1$s" type="text" name="%1$s" placeholder="%2$s" value="%3$s" class="form-control" %4$s />

                                                    </div></div>',

                                                    /**
                                                     *  1. Name
                                                     *  -------
                                                     */
                                                    esc_attr( 'sdweddingdirectory_claim_venue_last_name' ),

                                                    /**
                                                     *  2. Placeholder
                                                     *  --------------
                                                     */
                                                    esc_attr__('Last Name','sdweddingdirectory-claim-venue'),

                                                    /**
                                                     *  3. Value
                                                     *  --------
                                                     */
                                                    parent:: _have_data( parent:: get_data( esc_attr( 'last_name' ) ) )

                                                    ?   parent:: get_data( esc_attr( 'last_name' ) )

                                                    :   '',

                                                    /**
                                                     *  4. Required Fields
                                                     *  ------------------
                                                     */
                                                    $required
                                            );

                                            /**
                                             *  Contact Number
                                             *  --------------
                                             */
                                            printf( '<div class="col-12"><div class="mb-3">

                                                        <label class="control-label sr-only" for="%1$s"></label>

                                                        <input autocomplete="off" id="%1$s" type="tel" name="%1$s" placeholder="%2$s" value="%3$s" class="form-control" %4$s />

                                                    </div></div>',

                                                    /**
                                                     *  1. Name
                                                     *  -------
                                                     */
                                                    esc_attr( 'sdweddingdirectory_claim_venue_contact_number' ),

                                                    /**
                                                     *  2. Placeholder
                                                     *  --------------
                                                     */
                                                    esc_attr__('Contact Number','sdweddingdirectory-claim-venue'),

                                                    /**
                                                     *  3. Value
                                                     *  --------
                                                     */
                                                    parent:: _have_data( parent:: get_data( esc_attr( 'user_contact' ) ) )

                                                    ?   parent:: get_data( esc_attr( 'user_contact' ) )

                                                    :   '',

                                                    /**
                                                     *  4. Required Fields
                                                     *  ------------------
                                                     */
                                                    $required
                                            );

                                            /**
                                             *  Message
                                             *  -------
                                             */
                                            printf( '<div class="col-12"><div class="mb-3">

                                                        <label class="control-label sr-only" for="%1$s"></label>

                                                        <textarea id="%1$s" name="%1$s" placeholder="%2$s" class="form-control" %3$s></textarea>

                                                    </div></div>',

                                                    /**
                                                     *  1. Name
                                                     *  -------
                                                     */
                                                    esc_attr( 'sdweddingdirectory_claim_venue_message' ),

                                                    /**
                                                     *  2. Placeholder
                                                     *  --------------
                                                     */
                                                    esc_attr__('Message','sdweddingdirectory-claim-venue'),

                                                    /**
                                                     *  3. Required Fields
                                                     *  ------------------
                                                     */
                                                    $required
                                            );

                                            /**
                                             *  Submit Claim
                                             *  ------------
                                             */
                                            printf( '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-end">

                                                        <div class="mb-3">

                                                            <input autocomplete="off" type="hidden" id="%4$s" name="%4$s" value="%5$s" />

                                                            <input autocomplete="off" type="hidden" id="%6$s" name="%6$s" value="%7$s" />

                                                            <button type="submit" id="%1$s" class="btn btn-default ">%2$s</button> %3$s

                                                        </div>

                                                    </div>', 

                                                    /**
                                                     *  1. Claim Submit ID
                                                     *  ------------------
                                                     */
                                                    esc_attr( 'sdweddingdirectory_claim_venue_submit_button' ),

                                                    /**
                                                     *  2. Translation Ready String
                                                     *  ---------------------------
                                                     */
                                                    esc_html__( 'Submit Claim', 'sdweddingdirectory-claim-venue' ),
                                                    
                                                    /**
                                                     *  3. Claim Security
                                                     *  -----------------
                                                     */
                                                    wp_nonce_field( 'sdweddingdirectory_claim_venue_security', 'sdweddingdirectory_claim_venue_security', true, false ),

                                                    /**
                                                     *  4. Claim Venue Post ID
                                                     *  ------------------------
                                                     */
                                                    esc_attr( 'sdweddingdirectory_claim_venue_id' ),

                                                    /**
                                                     *  5. Claim Venue ID
                                                     *  -------------------
                                                     */
                                                    absint( get_the_ID() ),

                                                    /**
                                                     *  6. Author ID
                                                     *  ------------
                                                     */
                                                    esc_attr( 'sdweddingdirectory_claim_vendor_user_id' ),

                                                    /**
                                                     *  7. Author ID Value
                                                     *  ------------------
                                                     */
                                                    absint( parent:: author_id() )
                                            );

                                        ?>

                                    </div>
                                </form>
                                   
                            </div>
                        </div>
                    </div>
                <?php

                print '</div><!-- Claim Venue Model Popup ID -->';
            }
        }

        /**
         *  SDWeddingDirectory - Claim Button Attr
         *  ------------------------------
         */
        public static function claim_button_attr(){

            /**
             *  Claim Venue Button - Attributes
             *  ---------------------------------
             */
            return      sprintf( 'href="javascript:" role="button" data-bs-toggle="modal" data-bs-target="#%1$s" data-bs-dismiss="modal"', 

                            /**
                             *  1. Popup ID - Claim Venue
                             *  ---------------------------
                             */
                            esc_attr( parent:: popup_id( 'claim_venue' ) )
                        );
        }

    } /* class end **/

    /**
     *  SDWeddingDirectory Claim Venue Database
     *  ---------------------------------
     */
    SDWeddingDirectory_Claim_Venue_Button_Widget:: get_instance();
}
