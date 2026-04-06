<?php
/**
 *  SDWeddingDirectory - Vendor Left Widget: Services
 *  -------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_Services' ) && class_exists( 'SDWeddingDirectory_Vendor_Singular_Left_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Vendor Left Widget: Services
     *  -------------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_Services extends SDWeddingDirectory_Vendor_Singular_Left_Side_Widget{

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
             *  Vendor Section Left Widget [ Services ]
             *  --------------------------------------
             */
            // Disabled — services section removed from vendor profiles.
            // add_action( 'sdweddingdirectory/vendor/singular/left-side/widget', [ $this, 'widget' ], absint( '12' ), absint( '1' ) );
        }

        /**
         *  Vendor Section Left Widget [ Services ]
         *  --------------------------------------
         */
        public static function widget( $args = [] ){

            if( parent:: _is_array( $args ) ){

                $args       =       wp_parse_args( $args, [

                                        'post_id'               =>      absint( '0' ),

                                        'active_tab'            =>      false,

                                        'id'                    =>      sanitize_title( 'vendor_services' ),

                                        'icon'                  =>      'fa fa-list',

                                        'heading'               =>      esc_attr__( 'Services', 'sdweddingdirectory' ),

                                        'card_info'             =>      true,

                                        'handler'               =>      ''

                                    ] );

                extract( $args );

                if( empty( $post_id ) ){

                    return;
                }

                $services = get_post_meta( absint( $post_id ), sanitize_key( 'vendor_services' ), true );

                if( empty( $services ) ){

                    return;
                }

                if( parent:: _is_array( $services ) ){

                    $service_list = $services;

                }else{

                    $service_list = parent:: _coma_to_array( sanitize_text_field( $services ) );
                }

                if( ! parent:: _is_array( $service_list ) ){

                    return;
                }

                if( $layout == absint( '1' ) ){

                    $items = '';

                    foreach( $service_list as $service ){

                        if( ! empty( $service ) ){

                            $items .= sprintf( '<li>%1$s</li>', esc_attr( $service ) );
                        }
                    }

                    if( empty( $items ) ){

                        return;
                    }

                    $handler .= sprintf( '<div class="card-shadow-body"><ul class="list-unstyled mb-0">%1$s</ul></div>', $items );

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
     *  SDWeddingDirectory - Vendor Left Widget: Services
     *  -------------------------------------------------
     */
    SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_Services::get_instance();
}
