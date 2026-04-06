<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Team' ) && class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Team extends SDWeddingDirectory_Venue_Singular_Left_Side_Widget {

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
            add_action( 'sdweddingdirectory/venue/singular/left-side/widget', [ $this, 'widget' ], absint( '90' ), absint( '1' ) );
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

                                        'id'                    =>      sanitize_title( 'vendors_team' ),

                                        'icon'                  =>      'fa fa-user',

                                        'heading'               =>      esc_attr__( 'Team', 'sdweddingdirectory-venue' ),

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
                $_have_team  =  get_post_meta( absint( $post_id ), sanitize_key( 'venue_team' ), true );

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _is_array( $_have_team ) ){

                    /**
                     *  Layout 1
                     *  --------
                     */
                    if( $layout == absint( '1' ) ){

                        /**
                         *  Handler
                         *  -------
                         */
                        $handler    .=      '<div class="card-shadow-body"><div class="singular-team-wrap">';

                        /**
                         *  Team Posts
                         *  ----------
                         */
                        foreach( $_have_team as $key => $value ){

                            /**
                             *  Extract Args
                             *  ------------
                             */
                            extract( $value );

                            /**
                             *  Make sure image not empty!
                             *  --------------------------
                             */
                            if( ! empty( $image ) ){

                                $handler    .=      

                                sprintf(    '<div class="team-item">

                                                <div class="thumb">

                                                    <img src="%1$s" alt="%2$s" />

                                                </div>

                                                <div class="content">

                                                    <h3>%2$s</h3>

                                                    <h5>%3$s</h5>

                                                    <p class="show-read-more" data-word="115" data-read-more-string="%5$s">%4$s</p>

                                                </div>

                                            </div>', 

                                            /**
                                             *  1. Team Image
                                             *  -------------
                                             */
                                            apply_filters( 'sdweddingdirectory/media-data', [

                                                'media_id'      =>      absint( $image ),

                                                'image_size'    =>      esc_attr( 'thumbnail' )
                                            ] ),

                                            /**
                                             *  2. Name
                                             *  -------
                                             */
                                            esc_attr( $first_name ) .' '. esc_attr( $last_name ),

                                            /**
                                             *  3. Position
                                             *  -----------
                                             */
                                            esc_attr( $position ),

                                            /**
                                             *  4. Bio
                                             *  ------
                                             */
                                            $bio,

                                            /**
                                             *  5. Translation Ready String
                                             *  ---------------------------
                                             */
                                            esc_attr__( 'Read More', 'sdweddingdirectory-venue' )
                                );
                            }
                        }

                        $handler    .=      '</div></div>';

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
    SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Team:: get_instance();
}