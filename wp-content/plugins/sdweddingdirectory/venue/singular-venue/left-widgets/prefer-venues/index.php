<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Preferred_Vendors' ) && class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Preferred_Vendors extends SDWeddingDirectory_Venue_Singular_Left_Side_Widget {

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
            add_action( 'sdweddingdirectory/venue/singular/left-side/widget', [ $this, 'widget' ], absint( '120' ), absint( '1' ) );
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

                                        'id'                    =>      sanitize_title( 'preferred_venues' ),

                                        'icon'                  =>      'fa fa-male',

                                        'heading'               =>      esc_attr__( 'Preferred Vendors', 'sdweddingdirectory-venue' ),

                                        'card_info'             =>      true,

                                        'handler'               =>      '',

                                        'carousel'              =>      false

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


                $_have_data     =   get_post_meta( absint( $post_id ), sanitize_key( 'preferred_venues' ), true );

                /**
                 *  Have List ?
                 *  -----------
                 */
                $preferred_venues  =   parent:: _coma_to_array( $_have_data );

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _have_data( $_have_data ) && parent:: _is_array( $preferred_venues ) ){

                    /**
                     *  Layout 1
                     *  --------
                     */
                    if( $layout == absint( '1' ) ){

                        /**
                         *  Check post selected
                         *  -------------------
                         */
                        if( count( $preferred_venues ) >= absint( '3' ) ){

                            $carousel  =   true;
                        }

                        $handler    .=      '<div class="card-shadow-body">';
                            
                        if( $carousel ){

                            $handler    .=      '<div class="owl-carousel owl-theme sdweddingdirectory-preferred-vendors-carousel">';
                        }

                        else{

                            $handler    .=      '<div class="row">';
                        }

                        foreach( $preferred_venues as $key => $value ){

                            /**
                             *  Have Carousel ?
                             *  ---------------
                             */
                            if( $carousel ){

                                $handler    .=      '<div class="item">';
 
                                $handler    .=      apply_filters( 'sdweddingdirectory/venue/post', [   

                                                        'post_id'           =>      absint( $value ),

                                                        'layout'            =>      absint( '1' ),

                                                        'target_class'      =>      ''

                                                    ] );

                                $handler    .=    '</div>';
                            }

                            /**
                             *  Normal Column
                             *  -------------
                             */
                            else{
 
                                $handler    .=      apply_filters( 'sdweddingdirectory/venue/post', [   

                                                        'post_id'           =>      absint( $value ),

                                                        'layout'            =>      absint( '1' ),

                                                        'target_class'      =>      [ 'col-sm-6', 'col-12' ]

                                                    ] );
                            }
                        }

                        /**
                         *  Row / Carousel + Card Body
                         *  --------------------------
                         */
                        $handler    .=    '</div></div>';

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
                    if( $layout == absint( '2' ) && false ){

                        /**
                         *  Tab Overview
                         *  ------------
                         */
                        printf('<a href="#section_%1$s" class="%2$s">%3$s %4$s</a>',

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
    SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Preferred_Vendors:: get_instance();
}