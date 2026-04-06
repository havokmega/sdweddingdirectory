<?php
/**
 *  SDWeddingDirectory - Vendor Left Widget: Pricing
 *  ------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_Pricing' ) && class_exists( 'SDWeddingDirectory_Vendor_Singular_Left_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Vendor Left Widget: Pricing
     *  ------------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_Pricing extends SDWeddingDirectory_Vendor_Singular_Left_Side_Widget{

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

            add_action( 'sdweddingdirectory/vendor/singular/left-side/widget', [ $this, 'widget' ], absint( '15' ), absint( '1' ) );
        }

        /**
         *  Build Pricing Tiers
         *  -------------------
         */
        public static function pricing_tiers( $post_id = 0 ){

            $post_id = absint( $post_id );

            if( $post_id <= 0 ){
                return [];
            }

            $saved_data = get_post_meta( $post_id, sanitize_key( 'sdwd_vendor_pricing_tiers' ), true );

            if( ! parent::_is_array( $saved_data ) ){
                return [];
            }

            $tier_count = isset( $saved_data['tier_count'] ) ? absint( $saved_data['tier_count'] ) : absint( '0' );

            if( $tier_count < 1 || $tier_count > 3 ){
                $tier_count = 3;
            }

            $saved_tiers = isset( $saved_data['tiers'] ) && parent::_is_array( $saved_data['tiers'] ) ? $saved_data['tiers'] : [];

            $tiers = [];

            for( $tier = 1; $tier <= $tier_count; $tier++ ){

                $tier_data = isset( $saved_tiers[ $tier ] ) && parent::_is_array( $saved_tiers[ $tier ] ) ? $saved_tiers[ $tier ] : [];

                $title = isset( $tier_data['title'] ) ? sanitize_text_field( $tier_data['title'] ) : '';

                if( $title === '' ){
                    $title = sprintf( esc_attr__( 'Package %1$s', 'sdweddingdirectory' ), absint( $tier ) );
                }

                $price = isset( $tier_data['price'] ) ? sanitize_text_field( $tier_data['price'] ) : '';

                $raw_items = isset( $tier_data['items'] ) && parent::_is_array( $tier_data['items'] ) ? $tier_data['items'] : [];

                $items = [];

                $has_content = $price !== '';

                foreach( $raw_items as $row ){

                    if( ! parent::_is_array( $row ) ){
                        continue;
                    }

                    $text = isset( $row['text'] ) ? sanitize_text_field( $row['text'] ) : '';

                    if( $text === '' ){
                        continue;
                    }

                    $has_content = true;

                    $items[] = [

                        'text'      => $text,

                        'included'  => isset( $row['included'] ) ? absint( $row['included'] ) : absint( '0' ),
                    ];
                }

                if( ! $has_content ){
                    continue;
                }

                $hours = isset( $tier_data['hours'] ) ? sanitize_text_field( $tier_data['hours'] ) : '';

                $tiers[] = [

                    'title' => $title,

                    'price' => $price,

                    'hours' => $hours,

                    'items' => $items,
                ];
            }

            return $tiers;
        }

        /**
         *  Vendor Section Left Widget [ Pricing ]
         *  -------------------------------------
         */
        public static function widget( $args = [] ){

            if( ! parent::_is_array( $args ) ){
                return;
            }

            $args = wp_parse_args( $args, [

                'post_id'       => absint( '0' ),

                'active_tab'    => false,

                'id'            => sanitize_title( 'vendor_pricing' ),

                'icon'          => 'fa fa-tags',

                'heading'       => esc_attr__( 'Pricing Options', 'sdweddingdirectory' ),

                'card_info'     => true,

                'handler'       => '',

            ] );

            extract( $args );

            if( empty( $post_id ) ){
                return;
            }

            $tiers = self:: pricing_tiers( absint( $post_id ) );

            if( ! is_array( $tiers ) || empty( $tiers ) ){
                $tiers = [
                    [
                        'title' => esc_attr__( 'Essential Package', 'sdweddingdirectory' ),
                        'price' => '1000',
                        'items' => [
                            [ 'text' => esc_attr__( 'Feature 1', 'sdweddingdirectory' ), 'included' => 1 ],
                            [ 'text' => esc_attr__( 'Feature 2', 'sdweddingdirectory' ), 'included' => 1 ],
                            [ 'text' => esc_attr__( 'Feature 3', 'sdweddingdirectory' ), 'included' => 1 ],
                            [ 'text' => esc_attr__( 'Feature 4', 'sdweddingdirectory' ), 'included' => 0 ],
                            [ 'text' => esc_attr__( 'Feature 5', 'sdweddingdirectory' ), 'included' => 0 ],
                        ],
                    ],
                    [
                        'title' => esc_attr__( 'Signature Package', 'sdweddingdirectory' ),
                        'price' => '1500',
                        'items' => [
                            [ 'text' => esc_attr__( 'Feature 1', 'sdweddingdirectory' ), 'included' => 1 ],
                            [ 'text' => esc_attr__( 'Feature 2', 'sdweddingdirectory' ), 'included' => 1 ],
                            [ 'text' => esc_attr__( 'Feature 3', 'sdweddingdirectory' ), 'included' => 1 ],
                            [ 'text' => esc_attr__( 'Feature 4', 'sdweddingdirectory' ), 'included' => 1 ],
                            [ 'text' => esc_attr__( 'Feature 5', 'sdweddingdirectory' ), 'included' => 0 ],
                        ],
                    ],
                    [
                        'title' => esc_attr__( 'Premium Package', 'sdweddingdirectory' ),
                        'price' => '2000',
                        'items' => [
                            [ 'text' => esc_attr__( 'Feature 1', 'sdweddingdirectory' ), 'included' => 1 ],
                            [ 'text' => esc_attr__( 'Feature 2', 'sdweddingdirectory' ), 'included' => 1 ],
                            [ 'text' => esc_attr__( 'Feature 3', 'sdweddingdirectory' ), 'included' => 1 ],
                            [ 'text' => esc_attr__( 'Feature 4', 'sdweddingdirectory' ), 'included' => 1 ],
                            [ 'text' => esc_attr__( 'Feature 5', 'sdweddingdirectory' ), 'included' => 1 ],
                        ],
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

                    $title = esc_html( $tier['title'] );

                    $tier_icon = '';

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
                    $feature_index = 0;

                    if( parent::_is_array( $tier['items'] ) ){

                        foreach( $tier['items'] as $item ){

                            $is_included = isset( $item['included'] ) && absint( $item['included'] ) === 1;

                            $row_class = $is_included ? 'sdwd-pricing-feature' : 'sdwd-pricing-feature sdwd-pricing-feature-excluded';

                            if( $feature_index % 2 === 1 ){
                                $row_class .= ' sdwd-pricing-feature-striped';
                            }

                            $icon_class = $is_included ? 'fa-check' : 'fa-times';

                            $rows .= sprintf(
                                '<li class="%1$s"><i class="fa %2$s"></i> <span>%3$s</span></li>',
                                esc_attr( $row_class ),
                                esc_attr( $icon_class ),
                                esc_html( $item['text'] )
                            );

                            $feature_index++;
                        }
                    }

                    if( $rows === '' ){
                        $rows = sprintf(
                            '<li class="sdwd-pricing-feature"><i class="fa fa-check"></i> <span>%1$s</span></li>',
                            esc_html__( 'Contact for package details', 'sdweddingdirectory' )
                        );
                    }

                    $quote_btn = sprintf(
                        '<a href="javascript:" class="btn btn-outline-dark btn-sm w-100 mt-3 %1$s" %2$s>%3$s</a>',
                        is_user_logged_in() ? 'sdweddingdirectory-request-quote-popup' : '',
                        is_user_logged_in()
                        ? sprintf( 'data-venue-id="%1$s" data-event-call="0" id="%2$s"', absint( $post_id ), esc_attr( wp_unique_id( 'sdwd_quote_' ) ) )
                        : apply_filters( 'sdweddingdirectory/couple-login/attr', '' ),
                        esc_html__( 'Get a personalized quote', 'sdweddingdirectory' )
                    );

                    $card_class = 'pricing-table-wrap sdwd-pricing-package-card';
                    if( $total_tiers >= 2 && $tier_index === 1 ){
                        $card_class .= ' sdwd-pricing-popular';
                    }

                    $handler .= sprintf(
                        '<div class="%1$s">
                            %9$s
                            <div class="%8$s">
                                <h3>%2$s</h3>
                                %3$s
                                <hr class="sdwd-pricing-divider">
                                %11$s
                                <ul class="list-unstyled mb-0">%4$s</ul>
                                %5$s
                            </div>
                        </div>',
                        esc_attr( $column_class ),
                        $title,
                        $price,
                        $rows,
                        $quote_btn,
                        '', // unused
                        '', // unused
                        esc_attr( $card_class ),
                        $popular_badge,
                        $tier_icon,
                        $hours_html
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

                }else{

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

    /**
     *  SDWeddingDirectory - Vendor Left Widget: Pricing
     *  ------------------------------------------------
     */
    SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_Pricing::get_instance();
}
