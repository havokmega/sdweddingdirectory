<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Gallery' ) && class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Gallery extends SDWeddingDirectory_Venue_Singular_Left_Side_Widget {

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
             *  1. Venue Section Left Widget [ Description ]
             *  ----------------------------------------------
             */
            add_action( 'sdweddingdirectory/venue/singular/left-side/widget', [ $this, 'widget' ], absint( '30' ), absint( '1' ) );
        }

        /**
         *  12. Venue Section Left Widget [ Gallery ]
         *  -------------------------------------------
         */
        public static function widget( $args = [] ){

            /**
             *  Is array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Merge Args
                 *  ----------
                 */
                $args       =       wp_parse_args( $args, [

                                        'post_id'               =>      absint( '0' ),

                                        'active_tab'            =>      false,

                                        'id'                    =>      sanitize_title( 'venue_gallery' ),

                                        'icon'                  =>      'fa fa-th-large',

                                        'heading'               =>      esc_attr__( 'Gallery', 'sdweddingdirectory-venue' ),

                                        'card_info'             =>      true,

                                        'handler'               =>      ''

                                    ] );

                /**
                 *  Extract the data
                 *  ----------------
                 */
                extract( $args );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Gallery Data
                 *  ------------
                 */
                $_have_gallery      =   get_post_meta( absint( $post_id ), sanitize_key( 'venue_gallery' ), true );

                $_condition_1       =   parent:: _have_data( $_have_gallery );

                $_condition_2       =   $_condition_1 && parent:: _is_array( array_filter( parent:: _coma_to_array( $_have_gallery ) ) );

                /**
                 *  Make sure have data ?
                 *  ---------------------
                 */
                if( $_condition_2 ){

                    /**
                     *  Have Data ?
                     *  -----------
                     */
                    $_gallery_media     =   parent:: _filter_media_ids( array_filter( parent:: _coma_to_array( $_have_gallery ) ) );

                    /**
                     *  Layout 1
                     *  --------
                     */
                    if( $layout == absint( '1' ) ){

                        /**
                         *  Body Class
                         *  ----------
                         */
                        $_body_class    =   parent:: _is_array( $_gallery_media ) && count( $_gallery_media ) >= absint( '6' ) 

                                            ?  ''   

                                            :  sanitize_html_class( 'pb-1' );

                        /**
                         *  Card Body
                         *  ---------
                         */
                        $handler    .=  sprintf(    '<div class="card-shadow-body %1$s">', sanitize_html_class( $_body_class ) );

                        /**
                         *  Have Data ?
                         *  -----------
                         */
                        if( parent:: _is_array( $_gallery_media ) ){

                            /**
                             *  Gallery Counter
                             *  ---------------
                             */
                            $_counter               =   absint( '0' );

                            $_collapse_id           =   esc_attr( 'sdweddingdirectory-venue-image-gallery' );

                            $_count_images          =   absint( count( $_gallery_media ) );

                            $_show_more             =   absint( '6' );

                            $_condition_show_more   =   absint( $_show_more + absint( '1' ) );

                            $_gallery_column        =   'row row-cols-md-3 row-cols-sm-2 row-cols-1 venue-gallery';

                            /**
                             *  Have Data ?
                             *  -----------
                             */
                            foreach ( $_gallery_media as $_key => $media_id ) {

                                /**
                                 *  Make sure counter value 
                                 *  -----------------------
                                 */
                                if( $_counter == absint( '0' ) ){

                                    $handler    .=

                                    sprintf( '<div class="%1$s">', $_gallery_column );
                                }
                                
                                elseif( $_counter == absint( $_show_more ) && $_count_images >= absint( $_condition_show_more ) ){

                                    $handler    .=

                                    sprintf( '</div><div class="collapse" id="%2$s"><div class="%1$s %1$s">',

                                        /**
                                         *  1. Gallery Column
                                         *  -----------------
                                         */
                                        $_gallery_column,

                                        /**
                                         *  2. Collapse ID
                                         *  --------------
                                         */
                                        esc_attr( $_collapse_id )
                                    );
                                }

                                /**
                                 *  Show Gallery Image
                                 *  ------------------
                                 */
                                $handler    .=

                                sprintf('<div class="col">

                                            <a href="%2$s" title="%3$s %4$s">

                                                <img src="%1$s" class="rounded" alt="%5$s" />

                                            </a>

                                        </div>',

                                        /**
                                         *  1. Image Source
                                         *  ---------------
                                         */
                                        parent:: _have_data( $media_id )

                                        ?   apply_filters( 'sdweddingdirectory/media-data', [

                                                'media_id'      =>  absint( $media_id ),

                                                'image_size'    =>  esc_attr( 'sdweddingdirectory_img_600x385' ),

                                            ] )

                                        :   '',

                                        /**
                                         *  2. Image Source
                                         *  ---------------
                                         */
                                        apply_filters( 'sdweddingdirectory/media-data', [

                                            'media_id'      =>  absint( $media_id ),

                                            'image_size'    =>  esc_attr( 'full' ),

                                        ] ),

                                        /**
                                         *  3. Venue Name
                                         *  ---------------
                                         */
                                        esc_attr( get_the_title( absint( $post_id ) ) ),

                                        /**
                                         *  4. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Gallery', 'sdweddingdirectory-venue' ),

                                        /**
                                         *  5. Image ALT
                                         *  ------------
                                         */
                                        esc_attr( parent:: _alt( array(

                                            'media_id'  =>  absint( $media_id ),

                                            'post_id'   =>  absint( $post_id ),

                                            'end_alt'   =>  sprintf(  esc_attr__( 'Gallery %1$s', 'sdweddingdirectory-venue' ),

                                                                /**
                                                                 *  1. Galler Count
                                                                 *  ---------------
                                                                 */
                                                                $_counter
                                                            )
                                        ) ) )
                                );                        

                                /**
                                 *  Counter Value
                                 *  -------------
                                 */
                                $_counter++;
                            }

                            /**
                             *  Make sure gallery images more then 6 + this is end of last image
                             *  ----------------------------------------------------------------
                             */
                            if( $_counter >= absint( $_condition_show_more ) && $_count_images >= absint( $_condition_show_more ) ){

                                $handler    .=

                                sprintf( '</div></div>

                                        <div class="gallery-btn">
                                            <a href="javascript:" data-bs-toggle="collapse" data-bs-target="#%1$s" aria-expanded="false" aria-controls="%1$s"><i class="fa fa-angle-down"></i> <span>%2$s %3$s</span></a>
                                        </div>',

                                        /**
                                         *  1. Collapse ID
                                         *  --------------
                                         */
                                        esc_attr( $_collapse_id ),

                                        /**
                                         *  6. Total Pending View Image
                                         *  ---------------------------
                                         */
                                        absint( count( $_gallery_media ) - absint( $_show_more ) ),

                                        /**
                                         *  3. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'More', 'sdweddingdirectory-venue' )
                                );

                            }else{

                                $handler    .=      '</div>';
                            }
                        }

                        $handler    .=      '</div>';


                        /**
                         *  Card Info Enable ?
                         *  ------------------
                         */
                        if( $card_info ){

                            printf(    '<div class="card-shadow position-relative">

                                            <a id="section_%1$s" class="anchor-fake"></a>

                                            <div class="card-shadow-header">

                                                <h3><i class="%2$s"></i> %3$s</h3>

                                            </div>

                                            %4$s

                                        </div>',

                                        /**
                                         *  1. Tab name
                                         *  -----------
                                         */
                                        sanitize_key( $id ),

                                        /**
                                         *  2. Tab Icon
                                         *  -----------
                                         */
                                        $icon,

                                        /**
                                         *  3. Heading
                                         *  ----------
                                         */
                                        esc_attr( $heading ),

                                        /**
                                         *  4. Output
                                         *  ---------
                                         */
                                        $handler
                                );
                        }

                        /**
                         *  Direct Output
                         *  -------------
                         */
                        else{

                            print   $handler;
                        }
                    }

                    /**
                     *  Layout 2
                     *  --------
                     */
                    if( $layout == absint( '2' ) ){

                        /**
                         *  Tab Overview
                         *  ------------
                         */
                        printf( '<a href="#section_%1$s" class="%2$s"><i class="%3$s"></i> %4$s</a>',

                                /**
                                 *  Tab name
                                 *  --------
                                 */
                                esc_attr( $id ),

                                /**
                                 *  Default Active
                                 *  --------------
                                 */
                                $active_tab   ?   sanitize_html_class( 'active' )  :  '',

                                /**
                                 *  Tab Icon
                                 *  --------
                                 */
                                $icon,

                                /**
                                 *  Tab Title
                                 *  ---------
                                 */
                                $heading
                        );
                    }

                    /**
                     *  Layout 3 - Tabing Style
                     *  -----------------------
                     */
                    if( $layout == absint( '3' ) ){

                        ob_start();

                        /**
                         *  List of slider tab icon
                         *  -----------------------
                         */
                        call_user_func( [ __CLASS__, __FUNCTION__ ], [

                            'post_id'       =>      absint( $post_id ),

                            'layout'        =>      absint( '1' ),

                            'card_info'     =>      false

                        ] );

                        $data   =   ob_get_contents();

                        ob_end_clean();

                        /**
                         *  Tab layout
                         *  ----------
                         */
                        printf( '[sdweddingdirectory_tab icon="%1$s" title="%2$s"]%3$s[/sdweddingdirectory_tab]', 

                            /**
                             *  Tab Icon
                             *  --------
                             */
                            $icon,

                            /**
                             *  Tab Title
                             *  ---------
                             */
                            $heading,

                            /**
                             *  Card Body
                             *  ---------
                             */
                            $data
                        );
                    }
                }
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Gallery:: get_instance();
}