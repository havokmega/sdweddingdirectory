<?php
/**
 *  ---------------------------------
 *  SDWeddingDirectory - ShortCode - [ Team ]
 *  ---------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Team' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  ---------------------------------
     *  SDWeddingDirectory - ShortCode - [ Team ]
     *  ---------------------------------
     */
    class SDWeddingDirectory_Shortcode_Team extends SDWeddingDirectory_Shortcode {

        /**
         *  A reference to an instance of this class
         *  ----------------------------------------
         */
        private static $instance;

        /**
         *  Returns an instance of this class.
         *  ----------------------------------
         */
        public static function get_instance() {

            if ( null == self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         *  ShortCode Atts
         *  --------------
         */
        public static function default_args(){

            return  [
                        'layout'        =>      absint( '1' ),

                        'post_id'       =>      absint( '0' ),

                        'style'         =>      '',
                    ];
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  1. Slider Section Start
             *  -----------------------
             */
            add_shortcode( 'sdweddingdirectory_team_section', [ $this, 'sdweddingdirectory_team_section' ] );

            /**
             *  SDWeddingDirectory - ShortCode Info
             *  ---------------------------
             */
            add_filter( 'sdweddingdirectory_shortcode_list', function( $args = [] ){

                /**
                 *  Get Plugin Information
                 *  ----------------------
                 */
                return  array_merge( $args, [

                            'sdweddingdirectory_team_section'   =>  sprintf( '[sdweddingdirectory_team_section %1$s]%2$s[/sdweddingdirectory_team_section]',

                                                                /**
                                                                 *  1. Attributes
                                                                 *  -------------
                                                                 */
                                                                parent:: _shortcode_atts( self:: default_args() )
                                                            )
                        ] );
            } );

            /**
             *  Image Size ?
             *  ------------
             */
            add_filter( 'sdweddingdirectory/image-size', function( $args = [] ){

                return  array_merge( $args, array(

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_360x400' ),

                                'name'          =>      esc_attr__( 'Team', 'sdweddingdirectory-shortcodes' ),

                                'width'         =>      absint( '360' ),

                                'height'        =>      absint( '400' )
                            ],

                        ) );
            } );

            /**
             *  Placeholder Filter
             *  ------------------
             */
            add_filter( 'sdweddingdirectory/placeholder/shortcode', function( $args = [] ){

                return  array_merge( $args, array(

                            'team'  =>  esc_url(  plugin_dir_url( __FILE__ ) . 'images/' . 'team.jpg' ),
                        
                        ) );
            } );
        }

        /**
         *  Slider Section Load
         *  -------------------
         */
        public static function sdweddingdirectory_team_section( $atts, $content = '' ){

            /**
             *  Extract Attr Data
             *  -----------------
             */
            extract( shortcode_atts( self:: default_args(), $atts, esc_attr( __FUNCTION__ ) ) );

            /**
             *  Make sure post id not empty !
             *  -----------------------------
             */
            if( empty( $post_id  ) ){

                return;
            }

            /**
             *  Team Post Helper
             *  ----------------
             */
            extract( [

                'collection'            =>      '',

                'team_name'             =>      esc_attr( get_the_title( $post_id ) ),

                'team_image'            =>      apply_filters( 'sdweddingdirectory/media-data', [

                                                    'media_id'         =>  get_post_meta( absint( $post_id ), sanitize_key( '_thumbnail_id' ), true ),

                                                    'image_size'       =>  esc_attr( 'sdweddingdirectory_img_360x400' ),

                                                ] ),

                'team_social_media'     =>      self:: team_social_info( [

                                                    'post_id'       =>      $post_id,

                                                    'layout'        =>      $layout

                                                ] ),

                'team_designation'      =>      esc_attr( get_post_meta( $post_id, sanitize_key( 'designation' ), true ) )

            ] );

            /**
             *  Style
             *  -----
             */
            $collection    .=       self:: _shortcode_style_start( $style );

            /**
             *  Layout 1 ?
             *  ----------
             */
            if( $layout == absint( '1' ) ){

                /**
                 *  Team Layout 1
                 *  -------------
                 */
                $collection    .=       sprintf(   '<div class="team-style-default">

                                                        <div class="social-wrap">

                                                            <img src="%1$s" alt="%2$s">

                                                            %3$s

                                                        </div>

                                                        <div class="content">

                                                            <h3 class="fw-7">%2$s</h3>

                                                            <div>%4$s</div>

                                                        </div>

                                                    </div>',

                                                    /**
                                                     *  1. Team Image
                                                     *  -------------
                                                     */
                                                    esc_url( $team_image ),

                                                    /**
                                                     *  2. Post Title
                                                     *  -------------
                                                     */
                                                    esc_attr( $team_name ),

                                                    /**
                                                     *  3. Social Media
                                                     *  ---------------
                                                     */
                                                    $team_social_media,

                                                    /**
                                                     *  4. Team Designation
                                                     *  -------------------
                                                     */
                                                    $team_designation
                                        );
            }

            /**
             *  Layout 2 ?
             *  ----------
             */
            elseif( $layout == absint( '2' ) ){

                /**
                 *  Team Layout 2
                 *  -------------
                 */
                $collection    .=       sprintf(   '<div class="team-style-alternate">

                                                        <div class="img-wrap">

                                                            %1$s

                                                            <img src="%2$s" alt="%3$s">

                                                        </div>

                                                        <div class="content">

                                                            <h3 class="fw-7">%3$s</h3>

                                                            <div>%4$s</div>

                                                        </div>

                                                    </div>',

                                                    /**
                                                     *  1. Content
                                                     *  ----------
                                                     */
                                                    $team_social_media,

                                                    /**
                                                     *  2. Team Image
                                                     *  -------------
                                                     */
                                                    esc_url( $team_image ),

                                                    /**
                                                     *  3. Image Alt
                                                     *  ------------
                                                     */
                                                    esc_attr( $team_name ),

                                                    /**
                                                     *  4. Team Designation
                                                     *  -------------------
                                                     */
                                                    $team_designation
                                        );
            }

            /**
             *  Style
             *  -----
             */
            $collection    .=       self:: _shortcode_style_end( $style );

            /**
             *  Return Button HTML
             *  ------------------
             */
            return      $collection;
        }

        /**
         *  Team post - Social Media
         *  ------------------------
         */
        public static function team_social_info( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'       =>      absint( '0' ),

                    'layout'        =>      absint( '0' ),

                    'handler'       =>      ''

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) || empty( $layout ) ){

                    return;
                }

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( [

                    'social_media'      =>      get_post_meta( $post_id, 'social_profile', true ),

                ] );

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _is_array( $social_media ) ){

                    /**
                     *  Layout 1
                     *  --------
                     */
                    if( $layout == absint( '1' ) ){

                        $handler        .=          '<div class="social-icons"><ul class="share-menu">';

                        $handler        .=          '<li class="share top"><i class="fa fa-share-alt"></i><ul class="submenu">';
                    }

                    /**
                     *  Layout 2
                     *  --------
                     */
                    if( $layout == absint( '2' ) ){

                        $handler    .=      '<ul class="submenu"> ';
                    }

                    /**
                     *  List of social media
                     *  --------------------
                     */
                    foreach( $social_media as $key => $value ){

                        /**
                         *  Extract Args
                         *  ------------
                         */
                        extract( $value );

                        /**
                         *  List 
                         *  ----
                         */
                        $handler        .=      sprintf( '<li><a href="%1$s"><i class="fa %2$s"></i></a></li>', 

                                                    /**
                                                     *  1. Link
                                                     *  -------
                                                     */
                                                    esc_url( $link ),

                                                    /**
                                                     *  2. Icon
                                                     *  -------
                                                     */
                                                    $platform
                                                );
                    }

                    /**
                     *  Layout 1
                     *  --------
                     */
                    if( $layout == absint( '1' ) ){

                        $handler        .=          '</ul></li>';

                        $handler        .=          '</ul></div>';
                    }

                    /**
                     *  Layout 2
                     *  --------
                     */
                    if( $layout == absint( '2' ) ){

                        $handler    .=      '</ul>';
                    }
                }

                /**
                 *  Social Media Data
                 *  -----------------
                 */
                return          $handler;
            }
        }

        /**
         *  Page Builder *Value* pass here to print features
         *  ------------------------------------------------
         */
        public static function page_builder( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Setting with Merge Empty Fields
                 *  ---------------------------------------
                 */
                extract( wp_parse_args( $args, self:: default_args() ) );

                /**
                 *  Return : Team HTML
                 *  ------------------
                 */
                return  do_shortcode(

                            sprintf(   '[sdweddingdirectory_team_section layout="%1$s" post_id="%2$s" style="%3$s"][/sdweddingdirectory_team_section]',

                                        /**
                                         *  1. Team Layout ?
                                         *  ----------------
                                         */
                                        absint( $layout ),

                                        /**
                                         *  2. Post ID
                                         *  ----------
                                         */
                                        absint( $post_id ),

                                        /**
                                         *  3. Have Style ?
                                         *  ---------------
                                         */
                                        $style
                            )
                        );
            }
        }
    }

    /**
     *  ---------------------------------
     *  SDWeddingDirectory - ShortCode - [ Team ]
     *  ---------------------------------
     */
    SDWeddingDirectory_Shortcode_Team::get_instance();
}