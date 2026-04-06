<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget_FAQ' ) && class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Venue_Singular_Left_Side_Widget_FAQ extends SDWeddingDirectory_Venue_Singular_Left_Side_Widget {

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
            add_action( 'sdweddingdirectory/venue/singular/left-side/widget', [ $this, 'widget' ], absint( '80' ), absint( '1' ) );
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

                                        'id'                    =>      sanitize_title( 'vendor_faq' ),

                                        'icon'                  =>      'fa fa-question-circle',

                                        'heading'               =>      esc_attr__( 'FAQ', 'sdweddingdirectory-venue' ),

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
                 *  Get Content ?
                 *  -------------
                 */
                $_have_content  =   get_post_meta( absint( $post_id ), sanitize_key( 'venue_faq' ), true );

                /**
                 *  Make sure have data ?
                 *  ---------------------
                 */
                if( true ){

                    /**
                     *  Layout 1
                     *  --------
                     */
                    if( $layout == absint( '1' ) ){


                        $handler    .=      '<div class="card-shadow-body p-0">';

                        $faq_rows = parent::_is_array( $_have_content ) ? $_have_content : [];

                        if( ! empty( $faq_rows ) ){

                            foreach ( $faq_rows as $key => $value) {

                                $handler    .=

                                sprintf('<div class="card border-bottom venue-faq-section border-0">

                                            <div class="card-body">

                                                <h4>%1$s</h4><p class="mb-0 pb-0 txt-orange">%2$s</p>

                                            </div>

                                        </div>',

                                        /**
                                         *  1. FAQ's Title
                                         *  --------------
                                         */
                                        esc_attr( isset( $value[ 'faq_title' ] ) ? $value[ 'faq_title' ] : '' ),

                                        /**
                                         *  2. FAQ's Description
                                         *  --------------------
                                         */
                                        isset( $value[ 'faq_description' ] ) ? $value[ 'faq_description' ] : ''
                                );
                            }
                        }else{

                            $handler .= sprintf(
                                '<div class="card border-bottom venue-faq-section border-0">
                                    <div class="card-body">
                                        <h4>%1$s</h4><p class="mb-0 pb-0 txt-orange">%2$s</p>
                                    </div>
                                </div>',
                                esc_html__( 'Questions & Answers', 'sdweddingdirectory-venue' ),
                                esc_html__( 'Reach out directly for custom details about this venue.', 'sdweddingdirectory-venue' )
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

                            printf(
                                '<div class="sd-cta-box text-center p-4 my-4 rounded" style="background-color: #fff3e0;">
                                    <h4 class="fw-bold">%1$s</h4>
                                    <p class="text-muted">%2$s</p>
                                    <a href="javascript:" class="btn text-white %3$s" style="background-color: var(--sdweddingdirectory-color-orange);" %4$s>%5$s</a>
                                </div>',
                                esc_html__( 'Still have questions?', 'sdweddingdirectory' ),
                                esc_html__( 'Reach out directly and the venue team will quickly respond with details.', 'sdweddingdirectory' ),
                                is_user_logged_in() ? 'sdweddingdirectory-request-quote-popup' : '',
                                ! is_user_logged_in()
                                ? apply_filters( 'sdweddingdirectory/couple-login/attr', '' )
                                : sprintf( 'data-venue-id="%1$s" data-event-call="0" id="%2$s"', absint( $post_id ), esc_attr( wp_unique_id( 'sdwd_venue_faq_' ) ) ),
                                esc_html__( 'Message venue', 'sdweddingdirectory' )
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
    SDWeddingDirectory_Venue_Singular_Left_Side_Widget_FAQ:: get_instance();
}
