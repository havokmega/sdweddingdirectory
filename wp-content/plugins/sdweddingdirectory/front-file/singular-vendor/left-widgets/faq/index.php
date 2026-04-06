<?php
/**
 *  SDWeddingDirectory - Vendor Left Widget: FAQ
 *  ------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_FAQ' ) && class_exists( 'SDWeddingDirectory_Vendor_Singular_Left_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Vendor Left Widget: FAQ
     *  ------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_FAQ extends SDWeddingDirectory_Vendor_Singular_Left_Side_Widget{

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
             *  Vendor Section Left Widget [ FAQ ]
             *  ---------------------------------
             */
            add_action( 'sdweddingdirectory/vendor/singular/left-side/widget', [ $this, 'widget' ], absint( '26' ), absint( '1' ) );
        }

        /**
         *  Vendor Section Left Widget [ FAQ ]
         *  ---------------------------------
         */
        public static function widget( $args = [] ){

            if( parent:: _is_array( $args ) ){

                $args       =       wp_parse_args( $args, [

                                        'post_id'               =>      absint( '0' ),

                                        'active_tab'            =>      false,

                                        'id'                    =>      sanitize_title( 'vendor_faq' ),

                                        'icon'                  =>      'fa fa-question-circle',

                                        'heading'               =>      esc_attr__( 'FAQs', 'sdweddingdirectory' ),

                                        'card_info'             =>      true,

                                        'handler'               =>      ''

                                    ] );

                extract( $args );

                if( empty( $post_id ) ){

                    return;
                }

                $_have_content  =   get_post_meta( absint( $post_id ), sanitize_key( 'venue_faq' ), true );

                if( true ){

                    if( $layout == absint( '1' ) ){

                        $faq_items = parent::_is_array( $_have_content ) ? array_values( $_have_content ) : [];
                        $faq_count = count( $faq_items );
                        $collapse_id = sanitize_key( 'sdwd_vendor_faq_more_' . absint( $post_id ) );

                        $handler .= '<div class="card-shadow-body p-0">';

                        foreach ( $faq_items as $index => $value ) {

                            $item_markup = sprintf(
                                '<div class="card border-bottom venue-faq-section border-0">
                                    <div class="card-body">
                                        <h4>%1$s</h4><p class="mb-0 pb-0 txt-orange">%2$s</p>
                                    </div>
                                </div>',
                                esc_attr( isset( $value['faq_title'] ) ? $value['faq_title'] : '' ),
                                isset( $value['faq_description'] ) ? $value['faq_description'] : ''
                            );

                            if( $index < 3 ){
                                $handler .= $item_markup;
                            }else{
                                if( $index === 3 ){
                                    $handler .= sprintf( '<div class="collapse" id="%1$s">', esc_attr( $collapse_id ) );
                                }

                                $handler .= $item_markup;
                            }
                        }

                        if( $faq_count > 3 ){
                            $handler .= '</div>';

                            $handler .= sprintf(
                                '<div class="px-4 py-3 border-top">
                                    <a class="sd-faq-more-toggle" data-bs-toggle="collapse" href="#%1$s" role="button" aria-expanded="false" aria-controls="%1$s">
                                        <span>%2$s</span>
                                        <i class="fa fa-angle-down"></i>
                                    </a>
                                </div>',
                                esc_attr( $collapse_id ),
                                esc_html__( 'Read all FAQ', 'sdweddingdirectory' )
                            );
                        }

                        $service_groups = [
                            [
                                'key'     => sanitize_key( 'vendor_pricing' ),
                                'label'   => esc_html__( 'What is included in the starting price?', 'sdweddingdirectory' ),
                            ],
                            [
                                'key'     => sanitize_key( 'vendor_services' ),
                                'label'   => esc_html__( 'Services offered', 'sdweddingdirectory' ),
                            ],
                            [
                                'key'     => sanitize_key( 'vendor_style' ),
                                'label'   => esc_html__( 'Style options', 'sdweddingdirectory' ),
                            ],
                            [
                                'key'     => sanitize_key( 'vendor_specialties' ),
                                'label'   => esc_html__( 'Specialties', 'sdweddingdirectory' ),
                            ],
                        ];

                        $service_markup = '';

                        foreach( $service_groups as $group ){

                            $values = get_post_meta( absint( $post_id ), sanitize_key( $group['key'] ), true );
                            $values = parent::_is_array( $values ) ? array_values( array_filter( $values ) ) : [];

                            if( empty( $values ) ){
                                continue;
                            }

                            $options = '';

                            foreach( $values as $value ){
                                $options .= sprintf(
                                    '<span><i class="fa fa-check-circle txt-orange"></i> %1$s</span>',
                                    esc_html( $value )
                                );
                            }

                            $service_markup .= sprintf(
                                '<div class="sd-vendor-service-group">
                                    <h4>%1$s</h4>
                                    <div class="sd-vendor-service-options">%2$s</div>
                                </div>',
                                esc_html( $group['label'] ),
                                $options
                            );
                        }

                        if( $service_markup !== '' ){
                            $handler .= sprintf(
                                '<div class="px-4 py-4 border-top sd-vendor-service-summary">%1$s</div>',
                                $service_markup
                            );
                        }

                        $handler .= '</div>';

                        $still_questions_cta = sprintf(
                            '<div class="sd-cta-box d-flex align-items-center my-4 rounded overflow-hidden" style="background-color: #fff3e0;">
                                <div class="sd-cta-box-text p-4">
                                    <h4 class="fw-bold">%1$s</h4>
                                    <p class="text-muted mb-3">%2$s</p>
                                    <a href="javascript:" class="btn text-white %3$s" style="background-color: var(--sdweddingdirectory-color-orange);" %4$s>%5$s</a>
                                </div>
                                <div class="sd-cta-box-image flex-shrink-0"></div>
                            </div>',
                            esc_html__( 'Still have questions?', 'sdweddingdirectory' ),
                            esc_html__( 'Reach out directly and they will quickly send answers to any remaining questions.', 'sdweddingdirectory' ),
                            is_user_logged_in() ? 'sdweddingdirectory-request-quote-popup' : '',
                            ! is_user_logged_in()
                            ? apply_filters( 'sdweddingdirectory/couple-login/attr', '' )
                            : sprintf( 'data-venue-id="%1$s" data-event-call="0" id="%2$s"', absint( $post_id ), esc_attr( wp_unique_id( 'sdwd_faq_' ) ) ),
                            esc_html__( 'Message vendor', 'sdweddingdirectory' )
                        );

                        if(  $card_info  ){

                            printf(    '<div class="card-shadow position-relative">

                                            <a id="section_%1$s" class="anchor-fake"></a>

                                            <div class="card-shadow-header">

                                                <h3><i class="%2$s"></i> %3$s</h3>

                                            </div>

                                            %4$s

                                        </div>',

                                        sanitize_key( $id ),

                                        $icon,

                                        esc_attr( $heading ),

                                        $handler
                                );

                            print $still_questions_cta;
                        }else{

                            print       $handler . $still_questions_cta;
                        }
                    }

                    if( $layout == absint( '2' ) ){

                        printf('<a href="#section_%1$s" class="%2$s"><i class="%3$s"></i> %4$s</a>',

                                esc_attr( $id ),

                                $active_tab ? sanitize_html_class( 'active' ) : '',

                                $icon,

                                $heading
                        );
                    }
                }
            }
        }
    }

    /**
     *  SDWeddingDirectory - Vendor Left Widget: FAQ
     *  ------------------------------------------
     */
    SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_FAQ::get_instance();
}
