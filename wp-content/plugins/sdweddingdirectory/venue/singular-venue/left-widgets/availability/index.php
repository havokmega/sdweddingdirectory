<?php
/**
 *  SDWeddingDirectory - Venue Left Widget: Availability
 *  ----------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Availability' ) && class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget' ) ){

    class SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Availability extends SDWeddingDirectory_Venue_Singular_Left_Side_Widget{

        private static $instance;

        public static function get_instance() {

            if ( ! isset( self::$instance ) ) {

                self::$instance = new self;
            }

            return self::$instance;
        }

        public function __construct() {

            if( ! function_exists( 'sd_get_vendor_availability' ) ){

                add_action( 'wp_ajax_sd_get_vendor_availability', function(){

                    $vendor_id = absint( isset( $_GET['vendor_id'] ) ? $_GET['vendor_id'] : 0 );

                    if( ! $vendor_id ){
                        wp_send_json_error();
                    }

                    $booked = get_post_meta( $vendor_id, sanitize_key( 'vendor_booked_dates' ), true );

                    if( ! is_array( $booked ) ){
                        $booked = get_post_meta( $vendor_id, sanitize_key( 'venue_booked_dates' ), true );
                    }

                    if( ! is_array( $booked ) ){
                        $booked = [];
                    }

                    $booked = array_values( array_unique( array_filter( array_map( function( $date ){
                        $date = sanitize_text_field( $date );
                        return preg_match( '/^\\d{4}-\\d{2}-\\d{2}$/', $date ) ? $date : '';
                    }, $booked ) ) ) );

                    wp_send_json_success([
                        'booked_dates'    => $booked,
                        'available_dates' => [],
                    ]);
                } );

                add_action( 'wp_ajax_nopriv_sd_get_vendor_availability', function(){
                    do_action( 'wp_ajax_sd_get_vendor_availability' );
                } );
            }

            add_action( 'sdweddingdirectory/venue/singular/left-side/widget', [ $this, 'widget' ], absint( '25' ), absint( '1' ) );
        }

        public static function widget( $args = [] ){

            if( ! parent::_is_array( $args ) ){
                return;
            }

            $args = wp_parse_args( $args, [

                'post_id'       => absint( '0' ),

                'active_tab'    => false,

                'id'            => sanitize_title( 'vendor_availability' ),

                'icon'          => 'fa fa-calendar',

                'heading'       => esc_attr__( 'Availability', 'sdweddingdirectory' ),

                'card_info'     => true,

                'handler'       => '',

            ] );

            extract( $args );

            if( empty( $post_id ) ){
                return;
            }

            if( $layout == absint( '1' ) ){

                ob_start();
                ?>
                <div class="card-shadow position-relative">
                    <a id="section_<?php echo esc_attr( sanitize_key( $id ) ); ?>" class="anchor-fake"></a>
                    <div class="card-shadow-header">
                        <h3><i class="<?php echo esc_attr( $icon ); ?>"></i> <?php echo esc_html( $heading ); ?></h3>
                    </div>
                    <div class="card-shadow-body">
                        <div class="sd-availability-calendar" data-vendor-id="<?php echo absint( $post_id ); ?>">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <button type="button" class="btn btn-sm btn-outline-dark sd-cal-prev"><i class="fa fa-chevron-left"></i></button>
                                <div class="sd-cal-year-labels d-flex gap-3"></div>
                                <button type="button" class="btn btn-sm btn-outline-dark sd-cal-next"><i class="fa fa-chevron-right"></i></button>
                            </div>
                            <div class="sd-cal-viewport">
                                <div class="sd-cal-slider">
                                    <div class="sd-cal-slide sd-cal-slide-prev">
                                        <div class="row">
                                            <div class="col-md-6 sd-cal-month" data-month="prev-left"></div>
                                            <div class="col-md-6 sd-cal-month" data-month="prev-right"></div>
                                        </div>
                                    </div>
                                    <div class="sd-cal-slide sd-cal-slide-active">
                                        <div class="row">
                                            <div class="col-md-6 sd-cal-month" data-month="current"></div>
                                            <div class="col-md-6 sd-cal-month" data-month="next"></div>
                                        </div>
                                    </div>
                                    <div class="sd-cal-slide sd-cal-slide-next">
                                        <div class="row">
                                            <div class="col-md-6 sd-cal-month" data-month="next-left"></div>
                                            <div class="col-md-6 sd-cal-month" data-month="next-right"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="sd-cal-legend d-flex gap-4 mt-3 pt-2 border-top">
                                <span class="d-flex align-items-center gap-2"><span class="sd-cal-dot sd-cal-dot-available"></span> <?php esc_html_e( 'Full availability', 'sdweddingdirectory' ); ?></span>
                                <span class="d-flex align-items-center gap-2"><span class="sd-cal-dot sd-cal-dot-booked"></span> <?php esc_html_e( 'Limited availability', 'sdweddingdirectory' ); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sd-cta-box d-flex align-items-center my-4 rounded overflow-hidden" style="background-color: #fff3e0;">
                    <div class="sd-cta-box-text p-4">
                        <h4 class="fw-bold"><?php esc_html_e( 'Confirm availability', 'sdweddingdirectory' ); ?></h4>
                        <p class="text-muted mb-3"><?php esc_html_e( 'Calendars fill up quickly, so confirm your preferred date as soon as possible.', 'sdweddingdirectory' ); ?></p>
                        <a href="javascript:" class="btn text-white <?php echo ! is_user_logged_in() ? '' : 'sdweddingdirectory-request-quote-popup'; ?>" style="background-color: var(--sdweddingdirectory-color-orange);" <?php echo ! is_user_logged_in() ? apply_filters( 'sdweddingdirectory/couple-login/attr', '' ) : sprintf( 'data-venue-id="%1$s" data-event-call="0" id="%2$s"', absint( $post_id ), esc_attr( wp_unique_id( 'sdwd_venue_msg_' ) ) ); ?>>
                            <?php esc_html_e( 'Message venue', 'sdweddingdirectory' ); ?>
                        </a>
                    </div>
                    <div class="sd-cta-box-image flex-shrink-0"></div>
                </div>
                <?php
                $handler .= ob_get_clean();

                if( $card_info ){
                    print $handler;
                }
            }

            if( $layout == absint( '2' ) ){

                printf(
                    '<a href="#section_%1$s" class="%2$s"><i class="%3$s"></i> %4$s</a>',
                    esc_attr( $id ),
                    $active_tab ? sanitize_html_class( 'active' ) : '',
                    esc_attr( $icon ),
                    esc_html( $heading )
                );
            }
        }
    }

    SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Availability::get_instance();
}
