<?php
/**
 *  SDWeddingDirectory Couple Guest List RSVP
 *  ---------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dashboard_Guest_List_RSVP' ) && class_exists( 'SDWeddingDirectory_Dashboard_Guest_List' ) ){

    /**
     *  SDWeddingDirectory Couple Guest List RSVP
     *  ---------------------------------
     */
    class SDWeddingDirectory_Dashboard_Guest_List_RSVP extends SDWeddingDirectory_Dashboard_Guest_List{

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
            add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'], absint( '52' ) );

            /**
             *  2. Dashboard Page
             *  -----------------
             */
            add_action( 'sdweddingdirectory/couple-dashboard', [$this, 'dashboard_page'], absint( '52' ), absint( '1' ) );
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
            if( parent:: dashboard_page_set( esc_attr( 'guest-management-rsvp' ) ) ) {

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

                /**
                 *  Add Localize string
                 *  -------------------
                 */
                add_filter( 'sdweddingdirectory/localize_script', function( $args = [] ){

                    /**
                     *  Add Args
                     *  --------
                     */
                    return      array_merge( $args, [

                                    'guest_list'        =>      [

                                        'invalid_email'             =>      esc_attr__( 'Invalid Email', 'sdweddingdirectory-guest-list' ),

                                        'empty_email'               =>      esc_attr__( 'Empty Email', 'sdweddingdirectory-guest-list' )
                                    ]

                                ] );
                } );
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
                if( ! empty( $page ) && $page == esc_attr( 'guest-management-rsvp' ) ){

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
                                esc_attr__( 'Send your requests for RSVPs', 'sdweddingdirectory-guest-list' )
                        );

                        ?>
                        <form id="submit_rsvp_to_guest" method="post">

                            <div class="row">

                                <div class="col-12 pb-5">
                                    
                                    <div class="row">
                                        
                                        <div class="col-md-6 col-12"><?php self:: rsvp_email_content(); ?></div>

                                        <div class="col-md-6 col-12"><?php self:: guest_list_details(); ?></div>

                                    </div>

                                </div>

                            
                                <div class="col-12 text-center border-top pt-5">
                                <?php 

                                    printf( '<button class="btn btn-primary btn-rounded" type="submit" id="couple_send_rsvp">

                                                <span class="me-1">%1$s</span>

                                                <i class="fa fa-paper-plane-o" aria-hidden="true"></i>

                                            </button>

                                            %2$s',

                                        /**
                                         *  1. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Send', 'sdweddingdirectory-guest-list' ),

                                        /**
                                         *  2. Security
                                         *  -----------
                                         */
                                        wp_nonce_field( 'sdweddingdirectory_rsvp_email_security', 'sdweddingdirectory_rsvp_email_security', true, false )
                                    );

                                ?>
                                </div>

                            </div>

                        </form>

                    </div><?php
                }
            }
        }

        /**
         *  RSVP Email
         *  ----------
         */
        public static function rsvp_email_content(){

            ?>
            <div class="row">
                
                <div class="col-12 mb-3">
                <?php 

                    /**
                     *  Heading
                     *  -------
                     */
                    printf( '<h3>%1$s</h3>',

                        /**
                         *  Translation Ready String
                         *  ------------------------
                         */
                        esc_attr__( '1. Personalize your message', 'sdweddingdirectory-guest-list' )

                    );
                ?>
                </div>

                <div class="col-12">
                    
                    <div class="card">

                        <div class="card-body text-center">
                        <?php

                            printf( '<img src="%1$s" id="rsvp_image" class="w-50 py-4" /><p class="lead">%2$s</p>',

                                /**
                                 *  1. RSVP
                                 *  -------
                                 */
                                esc_url( sdweddingdirectory_option( 'email-image-' . 'couple-rsvps' ) ),

                                /**
                                 *  2. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Confirm your attendance', 'sdweddingdirectory-guest-list' )
                            );
                        ?>
                        </div>

                        <div class="card-body">
                        <?php

                            printf( '<textarea class="form-control" id="rsvp-message" rows="5">%1$s</textarea>', 

                                /**
                                 *  1. Message Default
                                 *  ------------------
                                 */
                                esc_attr__( 'You’re invited! Please RSVP to let us know if you’re able to attend.', 'sdweddingdirectory-guest-list' )
                            );
                        ?>
                        </div>


                        <div class="card-body text-center">
                        <?php

                            printf( '<button type="button" class="btn btn-primary mb-3" disabled>%1$s</button>

                                    <p>%2$s</p>

                                    <p>%3$s %4$s & %5$s %6$s</p>', 

                                /**
                                 *  1. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'RSVP', 'sdweddingdirectory-guest-list' ),

                                /**
                                 *  2. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Thank you!', 'sdweddingdirectory-guest-list' ),

                                /**
                                 *  3. First Name
                                 *  -------------
                                 */
                                get_post_meta( parent:: post_id(), sanitize_key( 'groom_first_name' ), true ),

                                /**
                                 *  4. Last Name
                                 *  ------------
                                 */
                                get_post_meta( parent:: post_id(), sanitize_key( 'groom_last_name' ), true ),

                                /**
                                 *  5. First Name
                                 *  -------------
                                 */
                                get_post_meta( parent:: post_id(), sanitize_key( 'bride_first_name' ), true ),

                                /**
                                 *  6. Last Name
                                 *  ------------
                                 */
                                get_post_meta( parent:: post_id(), sanitize_key( 'bride_last_name' ), true )
                            );
                        ?>
                        </div>

                        <div class="border-top collapse show" id="enable_website_in_footer">

                            <div class="card-body">

                                <div class="d-flex justify-content-center">

                                    <div class="icon me-4">
                                        
                                        <i class="fa-2x sdweddingdirectory-website"></i>

                                    </div>

                                    <div class="content">
                                    <?php

                                        /**
                                         *  Content
                                         *  -------
                                         */
                                        printf( '<p class="mb-0">%1$s</p><p class="mb-0"><a href="%2$s">%2$s</a></p>',

                                            /**
                                             *  1. Translation Ready String
                                             *  ---------------------------
                                             */
                                            esc_attr__( 'Visit our website', 'sdweddingdirectory-guest-list' ),

                                            /**
                                             *  2. Website Link
                                             *  ---------------
                                             */
                                            esc_url( get_the_permalink( absint( parent:: website_post_id() ) ) )
                                        );

                                    ?>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
            <?php
        }

        /**
         *  Guest list page content
         *  -----------------------
         */
        public static function guest_list_details(){

            /**
             *  Collect All Guest
             *  -----------------
             */
            $guest_list     =   parent:: guest_list();

            /**
             *  Make sure guest not empty!
             *  --------------------------
             */
            if( ! parent:: _is_array( $guest_list ) ){

                return;
            }

            ?>
            <div class="row">
                    
                <div class="col-12 mb-3">
                <?php 

                    /**
                     *  Heading
                     *  -------
                     */
                    printf( '<h3>%1$s</h3>',

                        /**
                         *  Translation Ready String
                         *  ------------------------
                         */
                        esc_attr__( '2. Send to your guests', 'sdweddingdirectory-guest-list' )

                    );
                ?>
                </div>

                <div class="col-12">
                    
                    <div class="card">
                        
                        <div class="card-body">
                        <?php

                            printf( '<p>%1$s</p>', 

                                /**
                                 *  1. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'To:', 'sdweddingdirectory-guest-list' )
                            );


                            printf( '<ul class="guest-rsvp-list list-unstyled">%1$s</ul>', 

                                /**
                                 *  1. First List
                                 *  -------------
                                 */
                                isset( $_GET['guest-id'] ) && ! empty( $_GET['guest-id'] )

                                ?   self:: guest_tag( $_GET['guest-id'] )

                                :   ''
                            );

                        ?>
                        </div>

                        <div class="find-guest-input">
                            
                            <div class="input-group">

                                <span class="input-group-text border-0" id="basic-addon1"><i class="fa fa-search"></i></span>

                                <?php

                                    printf( '<input type="text" class="form-control search-guest" placeholder="%1$s" aria-label="%1$s">', 

                                        /**
                                         *  1. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Search Guests...', 'sdweddingdirectory-guest-list' )
                                    );

                                ?>
                            </div>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-12 card-body">

                                    <div class="custom-control custom-checkbox form-light">
                                    <?php

                                        printf( '<input type="checkbox" class="form-check-input" id="%1$s" />

                                                <label class="form-check-label" for="%1$s">%2$s</label>',

                                                /**
                                                 *  1. ID / Name
                                                 *  ------------
                                                 */
                                                esc_attr( 'select-all' ),

                                                /**
                                                 *  2. Translation Ready String
                                                 *  ---------------------------
                                                 */
                                                esc_attr__( 'Select All', 'sdweddingdirectory-guest-list' )
                                        );

                                    ?>
                                    </div>

                                </div>

                            </div>

                            <?php self:: guest_list_with_checkbox(); ?>

                        </div>

                        <div class="card-body border-top">

                            <div class="row">
                            <?php

                                printf( '<div class="col-md-10 col-12">

                                            <h4 class="text-uppercase">%1$s</h4>

                                            <p class="mb-0">%2$s</p>

                                        </div>

                                        <div class="col-md-2">

                                            <div class="form-check form-switch d-flex justify-content-center align-items-center">

                                                <input class="form-check-input" id="enable_website" type="checkbox" role="switch" checked

                                                data-bs-toggle="collapse" href="#%3$s" role="button" aria-expanded="false" aria-controls="%3$s" />

                                            </div>

                                        </div>',

                                        /**
                                         *  1. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Wedding Website', 'sdweddingdirectory-guest-list' ),

                                        /**
                                         *  2. Website Link
                                         *  ---------------
                                         */
                                        esc_url( get_the_permalink( absint( parent:: website_post_id() ) ) ),

                                        /**
                                         *  3. Input ID
                                         *  -----------
                                         */
                                        esc_attr( 'enable_website_in_footer' )
                                );

                            ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
            <?php
        }

        /**
         *  Have Guest ID ?
         *  ---------------
         */
        public static function guest_tag( $guest_id = '' ){

            /**
             *  Make sure guest id not emtpy!
             *  -----------------------------
             */
            if( empty( $guest_id ) ){

                return;
            }

            /**
             *  Collect All Guest
             *  -----------------
             */
            $guest_list     =   parent:: guest_list();

            /**
             *  Make sure guest not empty!
             *  --------------------------
             */
            if( parent:: _is_array( $guest_list ) ){

                /**
                 *  Loop
                 *  ----
                 */
                foreach( $guest_list as $key => $value ){

                    /**
                     *  Extract
                     *  -------
                     */
                    extract( $value );

                    /**
                     *  Guest Found ?
                     *  -------------
                     */
                    if( $guest_unique_id == $guest_id ){

                        return 

                        sprintf(   '<li class="tag">

                                        <span>%1$s %2$s</span>

                                        <a href="javascript:" class="del-guest text-dark" data-id="%3$s">

                                            <i class="fa fa-close"></i>

                                        </a>

                                    </li>',

                                    /** 
                                     *  1. First Name
                                     *  -------------
                                     */
                                    esc_attr( $first_name ),

                                    /**
                                     *  2. Last Name
                                     *  ------------
                                     */
                                    esc_attr( $last_name ),

                                    /**
                                     *  3. Guest ID
                                     *  -----------
                                     */
                                    esc_attr( $guest_unique_id )
                        );
                    }
                }
            }
        }

        /**
         *  Have Guest ID ?
         *  ---------------
         */
        public static function guest_list_with_checkbox(){

            /**
             *  Collect All Guest
             *  -----------------
             */
            $guest_list     =   parent:: guest_list();

            $guest_id       =   isset( $_GET['guest-id'] ) &&  ! empty( $_GET['guest-id'] )

                            ?   esc_attr( $_GET['guest-id'] )

                            :   '';

            /**
             *  Make sure guest not empty!
             *  --------------------------
             */
            if( parent:: _is_array( $guest_list ) ){

                ?>

                <div class="row guest_rsvp_list">

                    <?php

                    /**
                     *  Loop
                     *  ----
                     */
                    foreach( $guest_list as $key => $value ){

                            /**
                             *  Extract
                             *  -------
                             */
                            extract( $value );

                            /**
                             *  Checkbox
                             *  --------
                             */
                            printf( '<div class="col-12 card-body pb-0 guest-info" id="section_%5$s">

                                        <div class="row">

                                            <div class="col-md-1">

                                                <input type="checkbox" class="form-check-input %8$s" name="guest[]" 

                                                    id="guest_%1$s" value="%1$s" %2$s data-name="%3$s %4$s" %8$s />

                                            </div>

                                            <div class="col-md-5 d-flex align-items-center">

                                                <i class="fa fa-2x fa-user-circle-o me-2 text-muted" aria-hidden="true"></i>

                                                <span>%3$s %4$s</span>

                                            </div>

                                            <div class="col-md-6 text-end">

                                                <a  href="javascript:" class="" 

                                                    data-bs-toggle="collapse" data-bs-target="#target_%5$s" 

                                                    aria-expanded="false" aria-controls="target_%5$s"

                                                    id="toggle_%5$s">%6$s</a>

                                            </div>

                                            <div class="col-11 ms-auto mt-3 collapse" id="target_%5$s">

                                                <div class="sdweddingdirectory_guest_email_update" id="%5$s">

                                                    <div class="input-group mb-3">

                                                        <input type="email" id="email_%5$s" class="form-control form-dark" value="%10$s" placeholder="%9$s" />

                                                        <button type="button" id="submit_%5$s" data-guest-id="%1$s"

                                                            class="loader update_guest_email_id btn btn-primary btn-sm d-flex justify-content-center">

                                                            <i class="fa fa-check" aria-hidden="true"></i>

                                                        </button>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>',

                                    /**
                                     *  1. Guest ID
                                     *  -----------
                                     */
                                    esc_attr( $guest_unique_id ),

                                    /**
                                     *  2. Is Checked ?
                                     *  ---------------
                                     */
                                    $guest_unique_id == $guest_id

                                    ?   esc_attr( 'checked' )

                                    :   '',

                                    /** 
                                     *  3. First Name
                                     *  -------------
                                     */
                                    esc_attr( $first_name ),

                                    /**
                                     *  4. Last Name
                                     *  ------------
                                     */
                                    esc_attr( $last_name ),

                                    /**
                                     *  5. Unique ID
                                     *  ------------
                                     */
                                    esc_attr( parent:: _rand() ),

                                    /**
                                     *  6. Have Email ?
                                     *  ---------------
                                     */
                                    empty( $guest_email )

                                    ?   esc_attr__( 'Add Email', 'sdweddingdirectory-guest-list' )

                                    :   sanitize_email( $guest_email ),

                                    /**
                                     *  7. Button Class
                                     *  ---------------
                                     */
                                    '',

                                    /**
                                     *  8. Checkbox Condition
                                     *  ---------------------
                                     */
                                    empty( $guest_email )

                                    ?   esc_attr( 'disabled' )

                                    :   '',

                                    /**
                                     *  9. Translation Ready String
                                     *  ---------------------------
                                     */
                                    esc_attr__( 'Enter Guest Email ID', 'sdweddingdirectory-guest-list' ),

                                    /**
                                     *  10. Guest Email
                                     *  ---------------
                                     */
                                    !   empty( $guest_email )

                                    ?   sanitize_email( $guest_email )

                                    :   ''
                            );
                    }

                    ?>
                    
                </div>

                <?php
            }
        }
    }

    /**
     *  SDWeddingDirectory Couple Guest List RSVP
     *  ---------------------------------
     */
    SDWeddingDirectory_Dashboard_Guest_List_RSVP:: get_instance();
}