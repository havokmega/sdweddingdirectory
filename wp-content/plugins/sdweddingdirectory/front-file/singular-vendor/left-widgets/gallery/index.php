<?php
/**
 *  SDWeddingDirectory - Vendor Left Widget: Gallery
 *  ----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_Gallery' ) && class_exists( 'SDWeddingDirectory_Vendor_Singular_Left_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Vendor Left Widget: Gallery
     *  ----------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_Gallery extends SDWeddingDirectory_Vendor_Singular_Left_Side_Widget{

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
             *  Vendor Section Left Widget [ Gallery ]
             *  -------------------------------------
             */
            // Disabled — gallery section removed from vendor profiles (photos shown in hero collage).
            // add_action( 'sdweddingdirectory/vendor/singular/left-side/widget', [ $this, 'widget' ], absint( '30' ), absint( '1' ) );
        }

        /**
         *  Vendor Section Left Widget [ Gallery ]
         *  -------------------------------------
         */
        public static function widget( $args = [] ){

            if( parent:: _is_array( $args ) ){

                $args       =       wp_parse_args( $args, [

                                        'post_id'               =>      absint( '0' ),

                                        'active_tab'            =>      false,

                                        'id'                    =>      sanitize_title( 'vendor_gallery' ),

                                        'icon'                  =>      'fa fa-th-large',

                                        'heading'               =>      esc_attr__( 'Gallery', 'sdweddingdirectory' ),

                                        'card_info'             =>      true,

                                        'handler'               =>      ''

                                    ] );

                extract( $args );

                if( empty( $post_id ) ){

                    return;
                }

                $_have_gallery      =   get_post_meta( absint( $post_id ), sanitize_key( 'venue_gallery' ), true );

                $_condition_1       =   parent:: _have_data( $_have_gallery );

                $_condition_2       =   $_condition_1 && parent:: _is_array( array_filter( parent:: _coma_to_array( $_have_gallery ) ) );

                if( $_condition_2 ){

                    $_gallery_media     =   parent:: _filter_media_ids( array_filter( parent:: _coma_to_array( $_have_gallery ) ) );

                    if( $layout == absint( '1' ) ){

                        $_body_class    =   parent:: _is_array( $_gallery_media ) && count( $_gallery_media ) >= absint( '6' ) 

                                            ?  ''   

                                            :  sanitize_html_class( 'pb-1' );

                        $handler    .=  sprintf(    '<div class="card-shadow-body %1$s">', sanitize_html_class( $_body_class ) );

                        if( parent:: _is_array( $_gallery_media ) ){

                            $_counter               =   absint( '0' );

                            $_collapse_id           =   esc_attr( 'sdweddingdirectory-vendor-image-gallery' );

                            $_count_images          =   absint( count( $_gallery_media ) );

                            $_show_more             =   absint( '6' );

                            $_condition_show_more   =   absint( $_show_more + absint( '1' ) );

                            $_gallery_column        =   'row row-cols-md-3 row-cols-sm-2 row-cols-1 venue-gallery';

                            foreach ( $_gallery_media as $_key => $media_id ) {

                                if( $_counter == absint( '0' ) ){

                                    $handler    .= sprintf( '<div class="%1$s">', $_gallery_column );
                                }
                                
                                elseif( $_counter == absint( $_show_more ) && $_count_images >= absint( $_condition_show_more ) ){

                                    $handler    .= sprintf( '</div><div class="collapse" id="%2$s"><div class="%1$s %1$s">',

                                        $_gallery_column,

                                        esc_attr( $_collapse_id )
                                    );
                                }

                                $handler    .= sprintf('<div class="col">

                                            <a href="%2$s" title="%3$s %4$s">

                                                <img src="%1$s" class="rounded" alt="%5$s" />

                                            </a>

                                        </div>',

                                        parent:: _have_data( $media_id )

                                        ?   apply_filters( 'sdweddingdirectory/media-data', [

                                                'media_id'      =>  absint( $media_id ),

                                                'image_size'    =>  esc_attr( 'sdweddingdirectory_img_600x385' ),

                                            ] )

                                        :   '',

                                        apply_filters( 'sdweddingdirectory/media-data', [

                                            'media_id'      =>  absint( $media_id ),

                                            'image_size'    =>  esc_attr( 'full' ),

                                        ] ),

                                        esc_attr( get_the_title( absint( $post_id ) ) ),

                                        esc_attr__( 'Gallery', 'sdweddingdirectory' ),

                                        esc_attr( parent:: _alt( array(

                                            'media_id'  =>  absint( $media_id ),

                                            'post_id'   =>  absint( $post_id ),

                                            'end_alt'   =>  sprintf(  esc_attr__( 'Gallery %1$s', 'sdweddingdirectory' ),

                                                                $_counter
                                                            )
                                        ) ) )
                                );                        

                                $_counter++;
                            }

                            if( $_counter >= absint( $_condition_show_more ) && $_count_images >= absint( $_condition_show_more ) ){

                                $handler    .= sprintf( '</div></div>

                                        <div class="gallery-btn">
                                            <a href="javascript:" data-bs-toggle="collapse" data-bs-target="#%1$s" aria-expanded="false" aria-controls="%1$s"><i class="fa fa-angle-down"></i> <span>%2$s %3$s</span></a>
                                        </div>',

                                        esc_attr( $_collapse_id ),

                                        absint( count( $_gallery_media ) - absint( $_show_more ) ),

                                        esc_attr__( 'More', 'sdweddingdirectory' )
                                );

                            }else{

                                $handler    .=      '</div>';
                            }
                        }

                        $handler    .= '</div>';

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
    }

    /**
     *  SDWeddingDirectory - Vendor Left Widget: Gallery
     *  ----------------------------------------------
     */
    SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_Gallery::get_instance();
}
