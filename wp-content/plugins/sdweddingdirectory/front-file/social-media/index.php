<?php
/**
 *   Social Media
 *   ------------
 *   @link - https://bbbootstrap.com/snippets/share-social-media-modal-link-copy-31507288
 *   ------------------------------------------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Social_Media' ) && class_exists( 'SDWeddingDirectory_Front_End_Modules' ) ){

    /**
     *   Social Media Share Options
     *   --------------------------
     */
    class SDWeddingDirectory_Social_Media extends SDWeddingDirectory_Front_End_Modules{

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
             *  1. Load Script for couple registration
             *  --------------------------------------
             */
            add_action( 'wp_enqueue_scripts', array( $this, 'sdweddingdirectory_script' ) );

            /**
             *  2. Add Filter
             *  -------------
             */
            add_filter( 'sdweddingdirectory/social-media', [ $this, 'social_profile_list' ], absint( '10' ), absint( '1' ) );

            /**
             *  3. Add Filter for Post Link Convert to Share link
             *  -------------------------------------------------
             */
            add_filter( 'sdweddingdirectory/post-share', [ $this, 'post_share_with_social' ], absint( '10' ), absint( '1' ) );            
        }

        /**
         *  1. Load the couple registration script
         *  --------------------------------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Load Script
             *  -----------
             */
            wp_enqueue_script( 

                /**
                 *  1. File Name
                 *  ------------
                 */
                esc_attr( sanitize_title( __CLASS__ ) ),

                /**
                 *  2. File Path
                 *  ------------
                 */
                esc_url(   plugin_dir_url( __FILE__ )   .   'script.js'   ),

                /**
                 *  3. Load After "JQUERY" Load
                 *  ---------------------------
                 */
                array( 'jquery', 'clipboard' ),

                /**
                 *  4. Bootstrap - Library Version
                 *  ------------------------------
                 */
                esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ )   .   'script.js' ) ),

                /**
                 *  5. Load in Footer
                 *  -----------------
                 */
                true
            );
        }

        /**
         *  Social Media List
         *  -----------------
         */
        public static function social_profile_list( $args = [] ){

            /**
             *  Social Media
             *  ------------
             */
            return      array_merge( $args, [

                            esc_attr( 'fa-facebook' )      => [

                                'name'      =>  esc_attr__( 'Facebook', 'sdweddingdirectory' ),

                                'icon'      =>  esc_attr( 'fa-facebook' ),

                                'color'     =>  esc_attr( 'sdweddingdirectory-social-facebook' ),
                            ],

                            esc_attr( 'fa-twitter' )       => [

                                'name'      =>  esc_attr__( 'Twitter', 'sdweddingdirectory' ),

                                'icon'      =>  esc_attr( 'fa-twitter' ),

                                'color'     =>  esc_attr( 'sdweddingdirectory-social-twitter' ),
                            ],

                            esc_attr( 'fa-instagram' )     => [

                                'name'      =>  esc_attr__( 'Instagram', 'sdweddingdirectory' ),

                                'icon'      =>  esc_attr( 'fa-instagram' ),

                                'color'     =>  esc_attr( 'sdweddingdirectory-social-instagrammagenta' ),
                            ],

                            esc_attr( 'fa-youtube-play' )       => [

                                'name'      =>  esc_attr__( 'Youtube', 'sdweddingdirectory' ),

                                'icon'      =>  esc_attr( 'fa-youtube-play' ),

                                'color'     =>  esc_attr( 'sdweddingdirectory-social-youtube' ),
                            ],

                            esc_attr( 'fa-pinterest-p' )     => [

                                'name'      =>  esc_attr__( 'Pintrest', 'sdweddingdirectory' ),

                                'icon'      =>  esc_attr( 'fa-pinterest-p' ),

                                'color'     =>  esc_attr( 'sdweddingdirectory-social-pinterest' ),
                            ],

                            esc_attr( 'fa-linkedin' )      => [

                                'name'      =>  esc_attr__( 'Linkedin', 'sdweddingdirectory' ),

                                'icon'      =>  esc_attr( 'fa-linkedin' ),

                                'color'     =>  esc_attr( 'sdweddingdirectory-social-linkedin' ),
                            ],

                            esc_attr( 'fa-google-plus' )       => [

                                'name'      =>  esc_attr__( 'Google', 'sdweddingdirectory' ),

                                'icon'      =>  esc_attr( 'fa-google-plus' ),

                                'color'     =>  esc_attr( 'sdweddingdirectory-social-googlered' ),
                            ],

                            esc_attr( 'fa-rss' )           => [

                                'name'      =>  esc_attr__( 'RSS', 'sdweddingdirectory' ),

                                'icon'      =>  esc_attr( 'fa-rss' ),

                                'color'     =>  esc_attr( 'sdweddingdirectory-social-rss' ),
                            ],

                            esc_attr( 'fa-tumblr' )        => [

                                'name'      =>  esc_attr__( 'Tumblr', 'sdweddingdirectory' ),

                                'icon'      =>  esc_attr( 'fa-tumblr' ),

                                'color'     =>  esc_attr( 'sdweddingdirectory-social-tumblr' ),
                            ],

                            esc_attr( 'fa-vimeo' )         => [

                                'name'      =>  esc_attr__( 'Vimeo', 'sdweddingdirectory' ),

                                'icon'      =>  esc_attr( 'fa-vimeo' ),

                                'color'     =>  esc_attr( 'sdweddingdirectory-social-vimeoblue' ),
                            ],

                            esc_attr( 'fa-behance' )       => [

                                'name'      =>  esc_attr__( 'Behance', 'sdweddingdirectory' ),

                                'icon'      =>  esc_attr( 'fa-behance' ),

                                'color'     =>  esc_attr( 'sdweddingdirectory-social-behance' ),
                            ],

                            esc_attr( 'fa-dribbble' )      => [

                                'name'      =>  esc_attr__( 'Dribbble', 'sdweddingdirectory' ),

                                'icon'      =>  esc_attr( 'fa-dribbble' ),

                                'color'     =>  esc_attr( 'sdweddingdirectory-social-dribbble' ),
                            ],

                            esc_attr( 'fa-flickr' )        => [

                                'name'      =>  esc_attr__( 'Flickr', 'sdweddingdirectory' ),

                                'icon'      =>  esc_attr( 'fa-flickr' ),

                                'color'     =>  esc_attr( 'sdweddingdirectory-social-flickrpink' ),
                            ],

                            esc_attr( 'fa-git' )           => [

                                'name'      =>  esc_attr__( 'Git', 'sdweddingdirectory' ),

                                'icon'      =>  esc_attr( 'fa-git' ),

                                'color'     =>  esc_attr( 'sdweddingdirectory-social-git' ),
                            ],

                            esc_attr( 'fa-skype' )         => [

                                'name'      =>  esc_attr__( 'Skype', 'sdweddingdirectory' ),

                                'icon'      =>  esc_attr( 'fa-skype' ),

                                'color'     =>  esc_attr( 'sdweddingdirectory-social-skypeblue' ),
                            ],

                            esc_attr( 'fa-weibo' )         => [

                                'name'      =>  esc_attr__( 'Weibo', 'sdweddingdirectory' ),

                                'icon'      =>  esc_attr( 'fa-weibo' ),

                                'color'     =>  esc_attr( 'sdweddingdirectory-social-weibo' ),
                            ],

                            esc_attr( 'fa-foursquare' )    => [

                                'name'      =>  esc_attr__( 'Foursquare', 'sdweddingdirectory' ),

                                'icon'      =>  esc_attr( 'fa-foursquare' ),

                                'color'     =>  esc_attr( 'sdweddingdirectory-social-foursquarepink' ),
                            ],

                            esc_attr( 'fa-soundcloud' )    => [

                                'name'      =>  esc_attr__( 'Soundcloud', 'sdweddingdirectory' ),

                                'icon'      =>  esc_attr( 'fa-soundcloud' ),

                                'color'     =>  esc_attr( 'sdweddingdirectory-social-soundcloud' ),
                            ],

                            esc_attr( 'fa-telegram' )    => [

                                'name'      =>  esc_attr__( 'Telegram', 'sdweddingdirectory' ),

                                'icon'      =>  esc_attr( 'fa fa-telegram' ),

                                'color'     =>  esc_attr( 'sdweddingdirectory-social-telegram' ),
                            ],

                            esc_attr( 'fa-vk' )    => [

                                'name'      =>  esc_attr__( 'VK', 'sdweddingdirectory' ),

                                'icon'      =>  esc_attr( 'fa fa-vk' ),

                                'color'     =>  esc_attr( 'sdweddingdirectory-social-vk' ),
                            ],

                            esc_attr( 'fa-whatsapp' )      => [

                                'name'      =>  esc_attr__( 'Whatsapp', 'sdweddingdirectory' ),

                                'icon'      =>  esc_attr( 'fa-whatsapp' ),

                                'color'     =>  esc_attr( 'sdweddingdirectory-social-whatsappgreen' ),
                            ]

                        ] );
        }

        /**
         *  3. Add Filter for Post Link Convert to Share link
         *  -------------------------------------------------
         */
        public static function post_share_link( $post_id =  '0' ){

            /**
             *  Make sure post id not empty
             *  ---------------------------
             */
            if( empty( $post_id ) ){

                return;
            }

            // OptionTree setting removed - keep sharing enabled with the current full network list.
            $_enable_social     =   [
                'facebook',
                'twitter',
                'linkedIn',
                'pinterest',
                'digg',
                'reddit',
                'stumbleupon',
                'tumblr',
                'vk',
                'whatsapp',
                'telegram',
                'email',
                'skype',
            ];

            /**
             *  Collection
             *  ----------
             */
            $_collection        =   [];

            /**
             *  Enabled Social Media
             *  --------------------
             */
            if( parent:: _is_array( $_enable_social ) ){

                /**
                 *  1. Get the post permalink
                 *  -------------------------
                 */
                $_post_link     =   esc_url( get_the_permalink( absint( $post_id ) ) );

                /**
                 *  2. Get The Title
                 *  ----------------
                 */
                $_post_title    =   esc_attr( get_the_title( absint( $post_id ) ) );

                /**
                 *  3. Get Blog Info
                 *  ----------------
                 */
                $_blog_info     =   esc_attr( get_bloginfo( 'name' ) );
                
                /**
                 *  Have Collection ?
                 *  -----------------
                 */
                foreach( $_enable_social as $key => $value ){

                    /**
                     *  Is Facebook ?
                     *  -------------
                     */
                    if( $value  ==  'facebook' ){

                        $_collection[ sanitize_key( $value ) ]   =    array(

                            'name'      =>  esc_attr( 'Facebook' ),

                            'icon'      =>  '<i class="fa fa-facebook-f" aria-hidden="true"></i>',

                            'class'     =>  'sdweddingdirectory-social-facebook',

                            'link'      =>  esc_url( sprintf( 'http://www.facebook.com/sharer.php?u=%1$s',

                                                /**
                                                 *  1. Get the post permalink
                                                 *  -------------------------
                                                 */
                                                esc_url( $_post_link )

                                            ) )
                        );
                    }

                    /**
                     *  Is Twitter ?
                     *  ------------
                     */
                    elseif( $value == 'twitter' ){

                        $_collection[ sanitize_key( $value ) ]   =    array(

                            'name'      =>  esc_attr( 'Twitter' ),

                            'icon'      =>  '<i class="fa fa-twitter" aria-hidden="true"></i>',

                            'class'     =>  'sdweddingdirectory-social-twitter',

                            'link'      =>  esc_url( sprintf( 'https://twitter.com/share?url=%1$s&amp;text=%2$s&amp;hashtags=%3$s',

                                                /**
                                                 *  1. Get the post permalink
                                                 *  -------------------------
                                                 */
                                                esc_url( $_post_link ),

                                                /**
                                                 *  2. Get The Title
                                                 *  ----------------
                                                 */
                                                esc_attr( $_post_title),

                                                /**
                                                 *  3. Get Blog Info
                                                 *  ----------------
                                                 */
                                                esc_attr( $_blog_info )
                                            ) )
                        );
                    }

                    /**
                     *  Is Digg ?
                     *  ---------
                     */
                    elseif( $value == 'digg' ){

                        $_collection[ sanitize_key( $value ) ]   =    array(

                            'name'      =>  esc_attr( 'Digg' ),

                            'icon'      =>  '<i class="fa fa-digg" aria-hidden="true"></i>',

                            'class'     =>  'sdweddingdirectory-social-digg',

                            'link'      =>  esc_url( sprintf( 'http://www.digg.com/submit?url=%1$s',

                                                /**
                                                 *  1. Get the post permalink
                                                 *  -------------------------
                                                 */
                                                esc_url( $_post_link )

                                            ) )
                        );
                    }

                    /**
                     *  Is LinkedIn
                     *  -----------
                     */
                    elseif( $value == 'linkedIn' ){

                        $_collection[ sanitize_key( $value ) ]   =    array(

                            'name'      =>  esc_attr( 'LinkedIn' ),

                            'icon'      =>  '<i class="fa fa-linkedin" aria-hidden="true"></i>',

                            'class'     =>  'sdweddingdirectory-social-linkedin',

                            'link'      =>  esc_url( sprintf( 'http://www.linkedin.com/shareArticle?mini=true&amp;url=%1$s',

                                                /**
                                                 *  1. Get the post permalink
                                                 *  -------------------------
                                                 */
                                                esc_url( $_post_link )

                                            ) )
                        );
                    }

                    /**
                     *  Is Pinterest
                     *  ------------
                     */
                    elseif( $value == 'pinterest' ){

                        $_collection[ sanitize_key( $value ) ]   =    array(

                            'name'      =>  esc_attr( 'Pinterest' ),

                            'icon'      =>  '<i class="fa fa-pinterest-p" aria-hidden="true"></i>',

                            'class'     =>  'sdweddingdirectory-social-pinterest',

                            'link'      =>  esc_url( sprintf( 'http://pinterest.com/pin/create/button/?url=%1$s&description=%2$s',

                                                /**
                                                 *  1. Get the post permalink
                                                 *  -------------------------
                                                 */
                                                esc_url( $_post_link ),

                                                /**
                                                 *  2. Get The Title
                                                 *  ----------------
                                                 */
                                                esc_attr( $_post_title )

                                            ) )
                        );
                    }

                    /**
                     *  Is reddit
                     *  ---------
                     */
                    elseif( $value == 'reddit' ){

                        $_collection[ sanitize_key( $value ) ]   =    array(

                            'name'      =>  esc_attr( 'Reddit' ),

                            'icon'      =>  '<i class="fa fa-reddit" aria-hidden="true"></i>',

                            'class'     =>  'sdweddingdirectory-social-reddit',

                            'link'      =>  esc_url( sprintf( 'http://reddit.com/submit?url=%1$s&amp;title=%2$s',

                                                /**
                                                 *  1. Get the post permalink
                                                 *  -------------------------
                                                 */
                                                esc_url( $_post_link ),

                                                /**
                                                 *  2. Get The Title
                                                 *  ----------------
                                                 */
                                                esc_attr( $_post_title )

                                            ) )
                        );
                    }

                    /**
                     *  Is stumbleUpon
                     *  --------------
                     */
                    elseif( $value == 'stumbleUpon' ){

                        $_collection[ sanitize_key( $value ) ]   =    array(

                            'name'      =>  esc_attr( 'StumbleUpon' ),

                            'icon'      =>  '<i class="fa fa-stumbleupon-circle" aria-hidden="true"></i>',

                            'class'     =>  'sdweddingdirectory-social-stumbleupon',

                            'link'      =>  esc_url( sprintf( 'http://www.stumbleupon.com/submit?url=%1$s&amp;title=%2$s',

                                                /**
                                                 *  1. Get the post permalink
                                                 *  -------------------------
                                                 */
                                                esc_url( $_post_link ),

                                                /**
                                                 *  2. Get The Title
                                                 *  ----------------
                                                 */
                                                esc_attr( $_post_title )

                                            ) )
                        );
                    }

                    /**
                     *  Is tumblr
                     *  ---------
                     */
                    elseif( $value == 'tumblr' ){

                        $_collection[ sanitize_key( $value ) ]   =    array(

                            'name'      =>  esc_attr( 'Tumblr' ),

                            'icon'      =>  '<i class="fa fa-tumblr" aria-hidden="true"></i>',

                            'class'     =>  'sdweddingdirectory-social-tumblr',

                            'link'      =>  esc_url( sprintf( 'http://www.tumblr.com/share/link?url=%1$s&amp;title=%2$s',

                                                /**
                                                 *  1. Get the post permalink
                                                 *  -------------------------
                                                 */
                                                esc_url( $_post_link ),

                                                /**
                                                 *  2. Get The Title
                                                 *  ----------------
                                                 */
                                                esc_attr( $_post_title )

                                            ) )
                        );
                    }

                    /**
                     *  Is VK
                     *  -----
                     */
                    elseif( $value == 'vk' ){

                        $_collection[ sanitize_key( $value ) ]   =    array(

                            'name'      =>  esc_attr( 'VK' ),

                            'icon'      =>  '<i class="fa fa-vk" aria-hidden="true"></i>',

                            'class'     =>  'sdweddingdirectory-social-vk',

                            'link'      =>  esc_url( sprintf( 'http://vkontakte.ru/share.php?url=%1$s',

                                                /**
                                                 *  1. Get the post permalink
                                                 *  -------------------------
                                                 */
                                                esc_url( $_post_link )

                                            ) )
                        );                
                    }

                    /**
                     *  Is Whatsapp
                     *  -----------
                     */
                    elseif( $value == 'whatsapp' ){

                        $_collection[ sanitize_key( $value ) ]   =    array(

                            'name'      =>  esc_attr( 'Whatsapp' ),

                            'icon'      =>  '<i class="fa fa-whatsapp" aria-hidden="true"></i>',

                            'class'     =>  'sdweddingdirectory-social-whatsappgreen',

                            'link'      =>  sprintf( 'https://wa.me/?text={%1$s}',

                                                /**
                                                 *  1. Get the post permalink
                                                 *  -------------------------
                                                 */
                                                $_post_link
                                            )
                        );
                    }

                    /**
                     *  Is Telegram
                     *  -----------
                     */
                    elseif( $value == 'telegram' ){

                        $_collection[ sanitize_key( $value ) ]   =    array(

                            'name'      =>  esc_attr( 'Whatsapp' ),

                            'icon'      =>  '<i class="fa fa-telegram" aria-hidden="true"></i>',

                            'class'     =>  'sdweddingdirectory-social-telegram',

                            'link'      =>  esc_url( sprintf( 'https://telegram.me/share/url?url=%1$s&text=%2$s',

                                                /**
                                                 *  1. Get the post permalink
                                                 *  -------------------------
                                                 */
                                                esc_url( $_post_link ),

                                                /**
                                                 *  2. Post Title
                                                 *  -------------
                                                 */
                                                esc_attr( $_post_title )

                                            ) )
                        );
                    }

                    /**
                     *  Is Email
                     *  --------
                     */
                    elseif( $value == 'email' ){

                        $_collection[ sanitize_key( $value ) ]   =    array(

                            'name'      =>  esc_attr( 'Email' ),

                            'icon'      =>  '<i class="fa fa-envelope-o" aria-hidden="true"></i>',

                            'class'     =>  'sdweddingdirectory-social-email',

                            'link'      =>  esc_url( sprintf( 'mailto:?subject={%2$s}&body={%1$s}',

                                                /**
                                                 *  1. Get the post permalink
                                                 *  -------------------------
                                                 */
                                                esc_url( $_post_link ),

                                                /**
                                                 *  2. Post Title
                                                 *  -------------
                                                 */
                                                esc_attr( $_post_title )

                                            ) )
                        );
                    }

                    /**
                     *  Is Skype
                     *  --------
                     */
                    elseif( $value == 'skype' ){

                        $_collection[ sanitize_key( $value ) ]   =    array(

                            'name'      =>  esc_attr( 'Skype' ),

                            'icon'      =>  '<i class="fa fa-skype" aria-hidden="true"></i>',

                            'class'     =>  'sdweddingdirectory-social-skypeblue',

                            'link'      =>  esc_url( sprintf( 'https://web.skype.com/share?url={%1$s}&text={%2$s}',

                                                /**
                                                 *  1. Get the post permalink
                                                 *  -------------------------
                                                 */
                                                esc_url( $_post_link ),

                                                /**
                                                 *  2. Post Title
                                                 *  -------------
                                                 */
                                                esc_attr( $_post_title )

                                            ) )
                        );
                    }

                    /**
                     *  Is weibo
                     *  --------
                     */
                    elseif( $value == 'weibo' ){

                        $_collection[ sanitize_key( $value ) ]   =    array(

                            'name'      =>  esc_attr( 'Weibo' ),

                            'icon'      =>  '<i class="fa fa-weibo" aria-hidden="true"></i>',

                            'class'     =>  'sdweddingdirectory-social-weibo',

                            'link'      =>  esc_url( sprintf( 'http://service.weibo.com/share/share.php?url={%1$s}&appkey=&title={%2$s}&pic=&ralateUid=',

                                                /**
                                                 *  1. Get the post permalink
                                                 *  -------------------------
                                                 */
                                                esc_url( $_post_link ),

                                                /**
                                                 *  2. Post Title
                                                 *  -------------
                                                 */
                                                esc_attr( $_post_title )

                                            ) )
                        );
                    }



                }
            }

            /**
             *  Return Collection
             *  -----------------
             */
            return      $_collection;
        }

        /**
         *  4. Social Icons Load with Post Link
         *  -----------------------------------
         */
        public static function post_share_with_social( $args = [] ){

            /**
             *  Make sure it's not empty!
             *  -------------------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'       =>      absint( '0' ),

                    'layout'        =>      absint( '1' )

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Get Collection
                 *  --------------
                 */
                $_collection    =   self:: post_share_link( $post_id );

                /**
                 *  Make sure collection is not empty!
                 *  ----------------------------------
                 */
                if( ! parent:: _is_array( $_collection ) ){

                    return;
                }

                /**
                 *  Get data
                 *  --------
                 */
                $_data      =       '';

                /**
                 *  Layout 1 ?
                 *  ----------
                 */
                if( $layout == absint( '1' ) ){

                    foreach( $_collection as $key => $value ){

                        /**
                         *  Extract
                         *  -------
                         */
                        extract( $value );

                        /**
                         *  Collection of Social Media
                         *  --------------------------
                         */
                        $_data  .=  sprintf(   '<a href="%1$s" target="_blank" class="fs-5 d-flex align-items-center justify-content-center %2$s">%3$s</a>',

                                                /**
                                                 *  1. Link
                                                 *  -------
                                                 */
                                                esc_url( $link ),

                                                /**
                                                 *  2. Class
                                                 *  --------
                                                 */
                                                sanitize_html_class( $class ),

                                                /**
                                                 *  3. Icon
                                                 *  -------
                                                 */
                                                $icon
                                    );
                    }
                }

                /**
                 *  Layout 2 ?
                 *  ----------
                 */
                if( $layout == absint( '2' ) ){

                    foreach( $_collection as $key => $value ){

                        /**
                         *  Extract
                         *  -------
                         */
                        extract( $value );

                        /**
                         *  Collection of Social Media
                         *  --------------------------
                         */
                        $_data  .=  sprintf(   '<a href="%1$s" target="_blank">%3$s</a>',

                                                /**
                                                 *  1. Link
                                                 *  -------
                                                 */
                                                esc_url( $link ),

                                                /**
                                                 *  2. Class
                                                 *  --------
                                                 */
                                                sanitize_html_class( $class ),

                                                /**
                                                 *  3. Icon
                                                 *  -------
                                                 */
                                                $icon
                                    );
                    }
                }

                /**
                 *  Return Social Media
                 *  -------------------
                 */
                return  $_data;
            }
        }

    }

    /**
     *  SDWeddingDirectory - Social Media
     *  -------------------------
     */
    SDWeddingDirectory_Social_Media::get_instance();
}
