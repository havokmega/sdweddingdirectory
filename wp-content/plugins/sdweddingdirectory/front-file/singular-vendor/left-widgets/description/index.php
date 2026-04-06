<?php
/**
 *  SDWeddingDirectory - Vendor Left Widget: Description
 *  ----------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_Description' ) && class_exists( 'SDWeddingDirectory_Vendor_Singular_Left_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Vendor Left Widget: Description
     *  ----------------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_Description extends SDWeddingDirectory_Vendor_Singular_Left_Side_Widget{

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
             *  Vendor Section Left Widget [ Description ]
             *  -----------------------------------------
             */
            add_action( 'sdweddingdirectory/vendor/singular/left-side/widget', [ $this, 'widget' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Vendor Section Left Widget [ Description ]
         *  -----------------------------------------
         */
        public static function widget( $args = [] ){

            if( parent:: _is_array( $args ) ){

                $args       =       wp_parse_args( $args, [

                                        'post_id'               =>      absint( '0' ),

                                        'active_tab'            =>      true,

                                        'id'                    =>      sanitize_title( 'vendor_about' ),

                                        'icon'                  =>      'fa fa-file-text',

                                        'heading'               =>      esc_attr__( 'About this vendor', 'sdweddingdirectory' ),

                                        'card_info'             =>      true,

                                        'handler'               =>      ''

                                    ] );

                extract( $args );

                if( empty( $post_id ) ){

                    return;
                }

                $company_about  =   get_post_meta( absint( $post_id ), sanitize_key( 'company_about' ), true );

                $content_raw    =   parent:: _have_data( $company_about )

                                    ?   $company_about

                                    :   get_post_field( 'post_content', absint( $post_id ) );

                $the_content    =   apply_filters( 'the_content', $content_raw );

                if( empty( $the_content ) ){

                    $the_content = sprintf( esc_attr__( 'Welcome %1$s', 'sdweddingdirectory' ), esc_attr( get_the_title( absint( $post_id ) ) ) );
                }

                if( $layout == absint( '1' ) ){

                    $ownership_type      = sanitize_text_field( get_post_meta( absint( $post_id ), sanitize_key( 'vendor_ownership_type' ), true ) );
                    $years_in_business   = absint( get_post_meta( absint( $post_id ), sanitize_key( 'vendor_years_in_business' ), true ) );
                    $languages           = sanitize_text_field( get_post_meta( absint( $post_id ), sanitize_key( 'vendor_languages' ), true ) );
                    $team_size           = sanitize_text_field( get_post_meta( absint( $post_id ), sanitize_key( 'vendor_team_size' ), true ) );
                    $instagram_url       = esc_url( get_post_meta( absint( $post_id ), sanitize_key( 'vendor_instagram' ), true ) );
                    $profile_image       = parent:: profile_picture( [
                        'post_id' => absint( $post_id ),
                    ] );

                    $owner_name = sanitize_text_field( get_post_meta( absint( $post_id ), sanitize_key( 'company_contact_name' ), true ) );
                    if( empty( $owner_name ) ){
                        $owner_name = sanitize_text_field( get_the_title( absint( $post_id ) ) );
                    }
                    $owner_title = sanitize_text_field( get_post_meta( absint( $post_id ), sanitize_key( 'vendor_owner_title' ), true ) );
                    if( empty( $owner_title ) ){
                        $owner_title = parent::_have_data( $ownership_type ) ? $ownership_type : '';
                    }

                    $quick_facts = '';

                    if( ! empty( $years_in_business ) ){
                        $quick_facts .= sprintf(
                            '<div class="sd-vendor-fact-item"><i class="fa fa-clock-o"></i><span>%1$s</span></div>',
                            esc_html( sprintf( esc_attr__( '%1$s years in business', 'sdweddingdirectory' ), absint( $years_in_business ) ) )
                        );
                    }

                    if( parent::_have_data( $languages ) ){
                        $quick_facts .= sprintf(
                            '<div class="sd-vendor-fact-item"><i class="fa fa-language"></i><span>%1$s</span></div>',
                            esc_html( sprintf( esc_attr__( 'We speak %1$s', 'sdweddingdirectory' ), $languages ) )
                        );
                    }

                    if( parent::_have_data( $team_size ) ){
                        $quick_facts .= sprintf(
                            '<div class="sd-vendor-fact-item"><i class="fa fa-users"></i><span>%1$s</span></div>',
                            esc_html( $team_size )
                        );
                    }

                    $social = '';

                    if( parent::_have_data( $instagram_url ) ){
                        $social .= sprintf(
                            '<div class="sd-vendor-social mt-2"><span>%1$s</span><a href="%2$s" target="_blank" rel="noopener"><i class="fa fa-instagram"></i></a></div>',
                            esc_html__( 'Follow on:', 'sdweddingdirectory' ),
                            esc_url( $instagram_url )
                        );
                    }

                    $handler .= sprintf(
                        '<div class="card-shadow-body clearfix">
                            <div class="sd-vendor-about-top d-flex align-items-start">
                                <div class="sd-vendor-about-owner text-center">
                                    <div class="sd-vendor-about-profile"><img src="%1$s" alt="%2$s" /></div>
                                    <div class="sd-vendor-owner-name fw-bold mt-2">%6$s</div>
                                    %7$s
                                </div>
                                %4$s
                            </div>
                            <div class="sd-vendor-about-content sd-vendor-about-readmore-wrap">%3$s</div>
                            %5$s
                        </div>',
                        esc_url( $profile_image ),
                        esc_attr( get_the_title( absint( $post_id ) ) ),
                        $the_content,
                        $quick_facts !== '' ? sprintf( '<div class="sd-vendor-quick-facts-col">%1$s</div>', $quick_facts ) : '',
                        $social,
                        esc_html( $owner_name ),
                        ! empty( $owner_title ) ? sprintf( '<div class="sd-vendor-owner-title text-muted small">%1$s</div>', esc_html( $owner_title ) ) : ''
                    );

                    if( $card_info ){

                        printf( '<div class="card-shadow position-relative">
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
                    }else{

                        print $handler;
                    }
                }

                if( $layout == absint( '2' ) ){

                    printf( '<a href="#section_%1$s" class="%2$s"><i class="%3$s"></i> %4$s</a>',

                        esc_attr( $id ),
                        $active_tab ? sanitize_html_class( 'active' ) : '',
                        $icon,
                        $heading
                    );
                }
            }
        }
    }

    /**
     *  SDWeddingDirectory - Vendor Left Widget: Description
     *  ----------------------------------------------------
     */
    SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_Description::get_instance();
}
