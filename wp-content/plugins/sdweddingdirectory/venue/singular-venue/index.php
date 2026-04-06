<?php
/**
 *  SDWeddingDirectory - Venue Singular
 *  -----------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Singular_Venue' ) && class_exists( 'SDWeddingDirectory_Vendor_Venue_Database' ) ){

    /**
     *  SDWeddingDirectory - Venue Singular
     *  -----------------------------
     */
    class SDWeddingDirectory_Singular_Venue extends SDWeddingDirectory_Vendor_Venue_Database{

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
             *  Load one by one shortcode file
             *  ------------------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {

                require_once $file;
            }

            /**
             *  Load Script for venue singular
             *  ------------------------------
             */
            add_action( 'wp_enqueue_scripts', array( $this, 'sdweddingdirectory_script' ) );

            /**
             *  Setup Venue Single Page
             *  -----------------------
             */
            add_action( 'sdweddingdirectory/venue/detail-page', [ $this, 'venue_details_page' ] );
        }

        /**
         *  Load script
         *  -----------
         */
        public static function sdweddingdirectory_script(){

            if( is_singular( 'venue' ) || is_page_template( 'user-template/search-venue.php' ) ){

                wp_enqueue_script(
                    esc_attr( sanitize_title( __CLASS__ ) ),
                    esc_url( plugin_dir_url( __FILE__ ) . 'script.js' ),
                    array( 'jquery', 'toastr' ),
                    esc_attr( parent::_file_version( plugin_dir_path( __FILE__ ) . 'script.js' ) ),
                    true
                );

                add_filter( 'sdweddingdirectory/enable-script/magnific-popup', function( $args = [] ){

                    return array_merge( $args, [ 'venue_singular' => true ] );

                } );

                add_filter( 'sdweddingdirectory/enable-script/map', function( $args = [] ){

                    return array_merge( $args, [ 'venue_singular' => true ] );

                } );
            }

            if( is_archive( 'venue' ) || is_singular( 'venue' ) ){

                add_filter( 'sdweddingdirectory/enable-script/review', function( $args = [] ){

                    return array_merge( $args, [ 'venue_singular' => true ] );

                } );
            }
        }

        /**
         *  Venue Singular Page Markup
         *  --------------------------
         */
        public static function venue_details_page(){

            get_header();

            if ( have_posts() ){

                while ( have_posts() ){ the_post();

                    $post_id = absint( get_the_ID() );
                    $profile_action_nonce = wp_create_nonce( 'sdwd_profile_actions' );
                    $is_profile_saved = function_exists( 'sdwd_profile_user_has_item' ) ? sdwd_profile_user_has_item( 'sdwd_saved_profiles', $post_id ) : false;
                    $is_profile_hired = function_exists( 'sdwd_profile_user_has_item' ) ? sdwd_profile_user_has_item( 'sdwd_hired_profiles', $post_id ) : false;

                    update_post_meta(
                        absint( $post_id ),
                        sanitize_key( 'venue_page_view' ),
                        absint( get_post_meta( absint( $post_id ), sanitize_key( 'venue_page_view' ), true ) ) + absint( '1' )
                    );

                    do_action( 'sdweddingdirectory/venue/singular/start', [

                        'post_id' => absint( $post_id ),

                    ] );

                    include __DIR__ . '/sections/breadcrumbs.php';
                    include __DIR__ . '/sections/photo-collage.php';
                    ?>

                    <div class="vendormenu-anim">
                        <div class="container">
                            <div class="vendor-nav-sticky">
                                <div class="sd-profile-section-nav">
                                    <nav class="vendor-nav d-flex gap-3">
                                        <a href="#section_vendor_about" class="vendor-nav-link active"><?php esc_html_e( 'About', 'sdweddingdirectory' ); ?></a>
                                        <a href="#section_vendor_pricing" class="vendor-nav-link"><?php esc_html_e( 'Pricing', 'sdweddingdirectory' ); ?></a>
                                        <a href="#section_vendor_availability" class="vendor-nav-link"><?php esc_html_e( 'Availability', 'sdweddingdirectory' ); ?></a>
                                        <a href="#section_vendor_faq" class="vendor-nav-link"><?php esc_html_e( 'FAQ', 'sdweddingdirectory' ); ?></a>
                                        <a href="#section_vendor_reviews" class="vendor-nav-link"><?php esc_html_e( 'Reviews', 'sdweddingdirectory' ); ?></a>
                                        <a href="#section_vendor_map" class="vendor-nav-link"><?php esc_html_e( 'Map', 'sdweddingdirectory' ); ?></a>
                                    </nav>

                                    <div class="sd-profile-nav-actions">
                                        <?php if( ! is_user_logged_in() ){ ?>
                                            <a class="sd-profile-action-btn sd-profile-hired-btn" <?php echo apply_filters( 'sdweddingdirectory/couple-login/attr', '' ); ?>>
                                                <i class="fa fa-handshake-o" aria-hidden="true"></i>
                                                <span><?php esc_html_e( 'Hired?', 'sdweddingdirectory' ); ?></span>
                                            </a>
                                            <a class="sd-profile-action-btn sd-profile-save-btn" <?php echo apply_filters( 'sdweddingdirectory/couple-login/attr', '' ); ?>>
                                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                                                <span><?php esc_html_e( 'Save', 'sdweddingdirectory' ); ?></span>
                                            </a>
                                        <?php }else{ ?>
                                            <a href="javascript:" class="sd-profile-action-btn sd-profile-hired-btn <?php echo $is_profile_hired ? 'is-active' : ''; ?>" data-action-type="hired" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( $profile_action_nonce ); ?>" data-label-default="<?php esc_attr_e( 'Hired?', 'sdweddingdirectory' ); ?>" data-label-active="<?php esc_attr_e( 'Hired', 'sdweddingdirectory' ); ?>">
                                                <i class="fa fa-handshake-o" aria-hidden="true"></i>
                                                <span><?php echo esc_html( $is_profile_hired ? esc_html__( 'Hired', 'sdweddingdirectory' ) : esc_html__( 'Hired?', 'sdweddingdirectory' ) ); ?></span>
                                            </a>
                                            <a href="javascript:" class="sd-profile-action-btn sd-profile-save-btn <?php echo $is_profile_saved ? 'is-active' : ''; ?>" data-action-type="saved" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( $profile_action_nonce ); ?>" data-label-default="<?php esc_attr_e( 'Save', 'sdweddingdirectory' ); ?>" data-label-active="<?php esc_attr_e( 'Saved', 'sdweddingdirectory' ); ?>">
                                                <i class="fa <?php echo $is_profile_saved ? 'fa-heart' : 'fa-heart-o'; ?>" aria-hidden="true"></i>
                                                <span><?php echo esc_html( $is_profile_saved ? esc_html__( 'Saved', 'sdweddingdirectory' ) : esc_html__( 'Save', 'sdweddingdirectory' ) ); ?></span>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <section class="wide-tb-90 pt-0">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-8 col-md-12 sdweddingdirectory-venue-singular-sections">
                                    <?php
                                    do_action( 'sdweddingdirectory/venue/singular/left-side/widget', [

                                        'layout'  => absint( '1' ),
                                        'post_id' => absint( $post_id ),

                                    ] );
                                    ?>

                                    <div class="sd-cta-interest d-flex align-items-center justify-content-between p-4 my-4 border rounded">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/planning/vendor-manager/1_contact-bubble.svg' ); ?>" alt="" width="36" height="36" style="flex-shrink:0;">
                                            <h4 class="mb-0 fw-bold"><?php esc_html_e( 'Are you interested?', 'sdweddingdirectory' ); ?></h4>
                                        </div>
                                        <a href="javascript:" class="btn text-white <?php echo ! is_user_logged_in() ? '' : 'sdweddingdirectory-request-quote-popup'; ?>" style="background-color: var(--sdweddingdirectory-color-orange);" <?php echo ! is_user_logged_in() ? apply_filters( 'sdweddingdirectory/couple-login/attr', '' ) : sprintf( 'data-venue-id="%1$s" data-event-call="0" id="%2$s"', absint( $post_id ), esc_attr( wp_unique_id( 'sdwd_venue_interest_' ) ) ); ?>>
                                            <?php esc_html_e( 'Message venue', 'sdweddingdirectory' ); ?>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-12 mt-3 mt-lg-0">
                                </div>
                            </div>
                        </div>
                    </section>

                    <?php get_template_part( 'template-parts/why-use-sdwd' ); ?>
                    <?php include __DIR__ . '/sections/footer-links.php'; ?>

                    <?php
                    do_action( 'sdweddingdirectory/venue/singular/end', [

                        'post_id' => absint( $post_id )

                    ] );
                }

                wp_reset_postdata();
            }

            get_footer();
        }
    }

    /**
     *  SDWeddingDirectory - Venue Singular
     *  -----------------------------
     */
    SDWeddingDirectory_Singular_Venue::get_instance();
}
