<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Map' ) && class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Map extends SDWeddingDirectory_Venue_Singular_Left_Side_Widget {

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
            add_action( 'sdweddingdirectory/venue/singular/left-side/widget', [ $this, 'widget' ], absint( '120' ), absint( '1' ) );
        }

        /**
         *  1. Venue Section Left Widget [ Description ]
         *  ----------------------------------------------
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

                                        'id'                    =>      sanitize_title( 'vendor_map' ),

                                        'icon'                  =>      'fa fa-map-marker',

                                        'heading'               =>      esc_attr__( 'Map', 'sdweddingdirectory-venue' ),

                                        'card_info'             =>      true,

                                        'handler'               =>      '',

                                        'hidden_map'            =>      false

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
                $venue_latitude       =       get_post_meta( absint( $post_id ), sanitize_key( 'venue_latitude' ), true );

                $venue_longitude      =       get_post_meta( absint( $post_id ), sanitize_key( 'venue_longitude' ), true );

                $venue_address        =       get_post_meta( absint( $post_id ), sanitize_key( 'venue_address' ), true );

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _have_data( $venue_latitude ) && parent:: _have_data( $venue_longitude ) ){

                    /**
                     *  Layout 1
                     *  --------
                     */
                    if( $layout == absint( '1' ) ){

                        $handler    .=      sprintf(    '<div class="card-shadow-body">

                                                            %6$s

                                                            <div id="%1$s" 

                                                            class="marker_show_on_map %7$s" 

                                                            data-latitude="%2$s" 

                                                            data-longitude="%3$s" 

                                                            data-marker="%4$s" 

                                                            data-zoom="9"></div>

                                                        </div>',

                                                        /**
                                                         *  1. Random ID
                                                         *  ------------
                                                         */
                                                        esc_attr( parent:: _rand() ),

                                                        /**
                                                         *  2. Latitude
                                                         *  -----------
                                                         */
                                                        $venue_latitude,

                                                        /**
                                                         *  3. Longitude
                                                         *  ------------
                                                         */
                                                        $venue_longitude,

                                                        /**
                                                         *  4. Map Icon
                                                         *  ------------
                                                         */
                                                        apply_filters( 'sdweddingdirectory/term/marker', [

                                                            'post_id'   =>   absint( $post_id ),

                                                        ] ),

                                                        /**
                                                         *  5. Zoom Level
                                                         *  -------------
                                                         */
                                                        absint( '5' ),

                                                        /**
                                                         *  6. Marker Address
                                                         *  -----------------
                                                         */
                                                        !   empty( $venue_address )

                                                        ?   sprintf( '<p> <i class="fa fa-map-marker me-2"></i> %1$s</p>', $venue_address )

                                                        :   '',

                                                        /**
                                                         *  7. Map is Hidden ?
                                                         *  ------------------
                                                         */
                                                        $hidden_map     

                                                        ?   sanitize_html_class(  'is-hidden-map'  )

                                                        :   ''
                                            );

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
                        printf('<a href="#section_%1$s" class="%2$s"><i class="%3$s"></i> %4$s</a>',

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

                            'card_info'     =>      false,

                            'hidden_map'    =>      true

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
                }else{

                    if( $layout == absint( '1' ) ){

                        $handler .= sprintf(
                            '<div class="card-shadow-body"><p class="mb-0">%1$s</p></div>',
                            esc_html__( 'Map details will be available soon.', 'sdweddingdirectory-venue' )
                        );

                        if( $card_info ){

                            printf(
                                '<div class="card-shadow position-relative">
                                    <a id="section_%1$s" class="anchor-fake"></a>
                                    <div class="card-shadow-header">
                                        <h3><i class="%2$s"></i> %3$s</h3>
                                    </div>
                                    %4$s
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
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Map:: get_instance();
}
