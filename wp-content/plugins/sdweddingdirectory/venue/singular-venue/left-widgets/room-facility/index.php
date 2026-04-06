<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Room_Facility' ) && class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Room_Facility extends SDWeddingDirectory_Venue_Singular_Left_Side_Widget {

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
             *  1. Venue Section Left Widget [ Team ]
             *  ---------------------------------------
             */
            add_action( 'sdweddingdirectory/venue/singular/left-side/widget', [ $this, 'widget' ], absint( '110' ), absint( '1' ) );
        }

        /**
         *  1. Venue Section Left Widget [ Team ]
         *  ---------------------------------------
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

                                        'id'                    =>      sanitize_title( 'room_facility' ),

                                        'icon'                  =>      'fa fa-handshake-o',

                                        'heading'               =>      esc_attr__( 'Facilites', 'sdweddingdirectory-venue' ),

                                        'card_info'             =>      true,

                                        'handler'               =>      '',

                                        'counter'               =>      absint( '1' )

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
                 *  Get Content ?
                 *  -------------
                 */
                $_have_rooms  =  get_post_meta( absint( $post_id ), sanitize_key( 'venue_facilities' ), true );

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _is_array( $_have_rooms ) ){

                    /**
                     *  Layout 1
                     *  --------
                     */
                    if( $layout == absint( '1' ) ){


                        $handler    .=      '<div class="card-shadow-body">';

                        foreach( $_have_rooms as $key => $value ){

                            /**
                             *  Extract Args
                             *  ------------
                             */
                            extract( $value );

                            $_have_media    =   parent:: _filter_media_ids( parent:: _coma_to_array( $facilities_gallery ) );

                            $_gallery       =   '';

                            $_counter       =   absint( '1' );

                            /**
                             *  Have Media ?
                             *  ------------
                             */
                            if( parent:: _is_array( $_have_media ) ){

                                foreach( $_have_media as $media_id ){

                                    $_gallery  .=

                                    sprintf(   '<div class="vendor-gallery item">

                                                    <a href="%2$s" title="%3$s %4$s">

                                                        <img src="%1$s" alt="%5$s" />

                                                        <div class="content">
                                                            <i class="fa fa-search" aria-hidden="true"></i>
                                                        </div>

                                                    </a>                                            

                                                </div>',

                                            /**
                                             *  1. Image Source
                                             *  ---------------
                                             */
                                            parent:: _have_media( $media_id )

                                            ?   apply_filters( 'sdweddingdirectory/media-data', [

                                                    'media_id'      =>  absint( $media_id ),

                                                    'image_size'    =>  esc_attr( 'sdweddingdirectory_img_600x385' ),

                                                ] )

                                            :   esc_url( parent:: placeholder( 'real-wedding-gallery' ) ),

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
                                            esc_attr( get_the_title() ),

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

                                                'start_alt' =>  esc_attr( 'Room Facilites' ),

                                                'end_alt'   =>  sprintf( 

                                                                    /**
                                                                     *  1. Translation Ready String
                                                                     *  ---------------------------
                                                                     */
                                                                    esc_attr__( 'Gallery %1$s', 'sdweddingdirectory-venue' ), 

                                                                    /**
                                                                     *  2. Galler Count
                                                                     *  ---------------
                                                                     */
                                                                    absint( $_counter )
                                                                )
                                            ) ) )
                                    );

                                    $_counter++;
                                }
                            }

                            /**
                             *  Print
                             *  -----
                             */
                            $handler    .=      

                            sprintf(    '<div class="facilites-box">
                                            
                                            <div class="row">

                                                <div class="col-md-4 order-md-last">

                                                    <div class="facilites-thumb-gallery vendor-img-gallery">

                                                        <div class="owl-carousel owl-theme venue-singular-facilities">%1$s</div>

                                                    </div>

                                                </div>

                                                <div class="col-md-8">

                                                    <div class="head-wrap">

                                                        <div class="facilities-content-head">

                                                            <div class="facilities-head-left"><span>%7$s</span>%2$s</div>

                                                        </div>

                                                        <div class="facilities-head-right">

                                                            <div class="seating-capacity">

                                                                <small>%9$s</small>
                                                                
                                                                <div class="count">%3$s</div>

                                                            </div>

                                                            <div class="price-per-plate">

                                                                <small>%8$s</small>

                                                                <div class="count">%4$s</div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <p class="show-read-more" data-word="300" data-read-more-string="%6$s" data-class="">%5$s</p>

                                                </div>

                                            </div>

                                        </div>', 

                                        /**
                                         *  1. Load Gallery
                                         *  ---------------
                                         */
                                        $_gallery,

                                        /**
                                         *  2. Name
                                         *  -------
                                         */
                                        esc_attr( $facilities_name ),

                                        /**
                                         *  3. Position
                                         *  -----------
                                         */
                                        esc_attr( $facilities_seating ),

                                        /**
                                         *  4. Bio
                                         *  ------
                                         */
                                        sdweddingdirectory_pricing_possition( esc_attr( $facilities_price ) ),

                                        /**
                                         *  5. Translation Ready String
                                         *  ---------------------------
                                         */
                                        $facilities_desc,

                                        /**
                                         *  6. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'View More', 'sdweddingdirectory-venue' ),

                                        /**
                                         *  7. Facilities Category
                                         *  ----------------------
                                         */
                                        esc_attr( $facilities_cat ),

                                        /**
                                         *  8. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Venue Rental Fees', 'sdweddingdirectory-venue' ),

                                        /**
                                         *  8. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Seating Capacity', 'sdweddingdirectory-venue' )
                            );
                        }

                        $handler        .=          '</div>';


                        /**
                         *  Card Info Enable ?
                         *  ------------------
                         */
                        if(  $card_info  ){

                            /**
                             *  Output
                             *  ------
                             */
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
                         *  Direct Print Data
                         *  -----------------
                         */
                        else{

                            print       $handler;
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
    SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Room_Facility:: get_instance();
}