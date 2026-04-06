<?php
/**
 *  SDWeddingDirectory - Venue Left Widget: Pricing
 *  -----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Pricing' ) && class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget' ) ){

    class SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Pricing extends SDWeddingDirectory_Venue_Singular_Left_Side_Widget{

        private static $instance;

        public static function get_instance() {

            if ( ! isset( self::$instance ) ) {

                self::$instance = new self;
            }

            return self::$instance;
        }

        public function __construct() {

            add_action( 'sdweddingdirectory/venue/singular/left-side/widget', [ $this, 'widget' ], absint( '15' ), absint( '1' ) );
        }

        public static function pricing_tiers( $post_id = 0 ){

            $post_id = absint( $post_id );

            if( $post_id <= 0 ){
                return [];
            }

            $saved_data = get_post_meta( $post_id, sanitize_key( 'sdwd_vendor_pricing_tiers' ), true );

            if( ! is_array( $saved_data ) || ! isset( $saved_data['tiers'] ) || ! is_array( $saved_data['tiers'] ) ){
                return [];
            }

            $tiers = [];

            foreach( $saved_data['tiers'] as $index => $tier ){
                if( ! is_array( $tier ) ){
                    continue;
                }

                $title = isset( $tier['title'] ) ? sanitize_text_field( $tier['title'] ) : '';
                $price = isset( $tier['price'] ) ? sanitize_text_field( $tier['price'] ) : '';
                $items = isset( $tier['items'] ) && is_array( $tier['items'] ) ? $tier['items'] : [];

                if( $title === '' ){
                    $title = sprintf( esc_html__( 'Package %1$s', 'sdweddingdirectory' ), absint( $index ) + 1 );
                }

                if( $price === '' && empty( $items ) ){
                    continue;
                }

                $hours = isset( $tier['hours'] ) ? sanitize_text_field( $tier['hours'] ) : '';

                $tiers[] = [
                    'title' => $title,
                    'price' => $price,
                    'hours' => $hours,
                    'items' => $items,
                ];
            }

            return $tiers;
        }

        public static function widget( $args = [] ){

            if( ! parent::_is_array( $args ) ){
                return;
            }

            $args = wp_parse_args( $args, [

                'post_id'       => absint( '0' ),

                'active_tab'    => false,

                'id'            => sanitize_title( 'vendor_pricing' ),

                'icon'          => 'fa fa-tags',

                'heading'       => esc_attr__( 'Pricing', 'sdweddingdirectory' ),

                'card_info'     => true,

                'handler'       => '',

            ] );

            extract( $args );

            if( empty( $post_id ) ){
                return;
            }

            $tiers = self::pricing_tiers( absint( $post_id ) );

            if( ! is_array( $tiers ) || empty( $tiers ) ){
                $tiers = [
                    [
                        'title' => esc_attr__( 'Pricing details', 'sdweddingdirectory' ),
                        'price' => '',
                        'items' => [],
                    ],
                ];
            }

            if( $layout == absint( '1' ) ){

                $column_class = count( $tiers ) === 1
                    ? 'col-12'
                    : ( count( $tiers ) === 2 ? 'col-md-6' : 'col-md-4' );

                $total_tiers = count( $tiers );

                $handler .= '<div class="row g-3 sdwd-vendor-pricing-packages">';

                foreach( $tiers as $tier_index => $tier ){

                    $tier_icon = '';
                    if( $tier_index === 0 ){
                        $tier_icon = '<div class="sdwd-pricing-tier-icon"><i class="fa fa-tag"></i></div>';
                    }elseif( $tier_index === 1 ){
                        $tier_icon = '<div class="sdwd-pricing-tier-icon"><i class="fa fa-balance-scale"></i></div>';
                    }

                    $popular_badge = '';
                    if( $total_tiers >= 2 && $tier_index === 1 ){
                        $popular_badge = sprintf( '<div class="sdwd-pricing-popular-badge">%s</div>', esc_html__( 'Most popular', 'sdweddingdirectory' ) );
                    }

                    $price = $tier['price'] !== ''
                        ? sprintf( '<div class="plan-price"><sup>%1$s</sup>%2$s</div>', esc_html( sdweddingdirectory_currenty() ), esc_html( $tier['price'] ) )
                        : sprintf( '<div class="plan-price sdwd-price-empty">%1$s</div>', esc_html__( 'Custom Pricing', 'sdweddingdirectory' ) );

                    $hours_html = '';
                    $hours_value = isset( $tier['hours'] ) ? sanitize_text_field( $tier['hours'] ) : '';
                    if( $hours_value !== '' ){
                        $hours_html = sprintf(
                            '<div class="sdwd-pricing-hours"><i class="fa fa-clock-o"></i> %s</div>',
                            esc_html( sprintf( __( '%s hours included', 'sdweddingdirectory' ), $hours_value ) )
                        );
                    }

                    $rows = '';

                    foreach( (array) $tier['items'] as $item ){
                        if( ! is_array( $item ) || empty( $item['text'] ) ){
                            continue;
                        }

                        $included = isset( $item['included'] ) && absint( $item['included'] ) === 1;

                        $rows .= sprintf(
                            '<li class="%1$s"><i class="fa %2$s"></i> <span>%3$s</span></li>',
                            esc_attr( $included ? 'sdwd-pricing-feature' : 'sdwd-pricing-feature sdwd-pricing-feature-excluded' ),
                            esc_attr( $included ? 'fa-check' : 'fa-times' ),
                            esc_html( $item['text'] )
                        );
                    }

                    if( $rows === '' ){
                        $rows = sprintf( '<li class="sdwd-pricing-feature"><i class="fa fa-check"></i> <span>%1$s</span></li>', esc_html__( 'Contact for package details', 'sdweddingdirectory' ) );
                    }

                    $quote_btn = sprintf(
                        '<a href="javascript:" class="btn btn-outline-dark btn-sm w-100 mt-3 %1$s" %2$s>%3$s</a>',
                        ! is_user_logged_in() ? '' : 'sdweddingdirectory-request-quote-popup',
                        ! is_user_logged_in()
                            ? apply_filters( 'sdweddingdirectory/couple-login/attr', '' )
                            : sprintf( 'data-venue-id="%1$s" data-event-call="0" id="%2$s"', absint( $post_id ), esc_attr( wp_unique_id( 'sdwd_venue_quote_' ) ) ),
                        esc_html__( 'Get a personalized quote', 'sdweddingdirectory' )
                    );

                    $card_class = 'pricing-table-wrap sdwd-pricing-package-card';
                    if( $total_tiers >= 2 && $tier_index === 1 ){
                        $card_class .= ' sdwd-pricing-popular';
                    }

                    $handler .= sprintf(
                        '<div class="%1$s">
                            <div class="%2$s">
                                %3$s
                                %4$s
                                <h3>%5$s</h3>
                                %6$s
                                %7$s
                                <hr class="sdwd-pricing-divider">
                                <ul class="list-unstyled mb-0">%8$s</ul>
                                %9$s
                            </div>
                        </div>',
                        esc_attr( $column_class ),
                        esc_attr( $card_class ),
                        $popular_badge,
                        $tier_icon,
                        esc_html( $tier['title'] ),
                        $price,
                        $hours_html,
                        $rows,
                        $quote_btn
                    );
                }

                $handler .= '</div>';

                if( $card_info ){
                    printf(
                        '<div class="card-shadow position-relative">
                            <a id="section_%1$s" class="anchor-fake"></a>
                            <div class="card-shadow-header">
                                <h3><i class="%2$s"></i> %3$s</h3>
                            </div>
                            <div class="card-shadow-body">%4$s</div>
                        </div>',
                        sanitize_key( $id ),
                        esc_attr( $icon ),
                        esc_attr( $heading ),
                        $handler
                    );
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

    SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Pricing::get_instance();
}
