<?php
/**
 *  SDWeddingDirectory - Vendor Left Widget: Video
 *  --------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_Video' ) && class_exists( 'SDWeddingDirectory_Vendor_Singular_Left_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Vendor Left Widget: Video
     *  --------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_Video extends SDWeddingDirectory_Vendor_Singular_Left_Side_Widget{

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
             *  Vendor Section Left Widget [ Video ]
             *  -----------------------------------
             */
            add_action( 'sdweddingdirectory/vendor/singular/left-side/widget', [ $this, 'widget' ], absint( '40' ), absint( '1' ) );
        }

        /**
         *  Vendor Section Left Widget [ Video ]
         *  -----------------------------------
         */
        public static function widget( $args = [] ){

            if( parent:: _is_array( $args ) ){

                $args       =       wp_parse_args( $args, [

                                        'post_id'               =>      absint( '0' ),

                                        'active_tab'            =>      false,

                                        'id'                    =>      sanitize_title( 'vendor_video' ),

                                        'icon'                  =>      'fa fa-video-camera',

                                        'heading'               =>      esc_attr__( 'Video', 'sdweddingdirectory' ),

                                        'card_info'             =>      true,

                                        'handler'               =>      ''

                                    ] );

                extract( $args );

                if( empty( $post_id ) ){

                    return;
                }

                $_have_content  =   get_post_meta( absint( $post_id ), sanitize_key( 'venue_video' ), true );

                if( parent:: _have_data( $_have_content ) ){

                    if( $layout == absint( '1' ) ){

                        $handler    .= sprintf( '<div class="card-shadow-body">

                                        <div class="sdweddingdirectory-post-formate ratio ratio-16x9">%1$s</div>

                                    </div>',

                                    parent:: sdweddingdirectory_video_embed( wp_oembed_get( $_have_content ) )
                        );

                        if( $card_info  ){

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
     *  SDWeddingDirectory - Vendor Left Widget: Video
     *  --------------------------------------------
     */
    SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_Video::get_instance();
}
