<?php
/**
 *  ----------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Feature Box ]
 *  ----------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Feature_Box' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  ----------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Feature Box ]
     *  ----------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Feature_Box extends SDWeddingDirectory_Shortcode {

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

            return      [
                            'style'                 =>      '',

                            'target'                =>      esc_attr( '_self' ),

                            'link'                  =>      esc_url( home_url( '/' ) ),

                            'icon'                  =>      esc_attr( 'sdweddingdirectory-budget' ),

                            'button_action'         =>      esc_attr( 'page_link' ),

                            'modal_id'              =>      '',

                            'dashboard_page'        =>      ''

                        ];
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Button ShortCode
             *  ----------------
             */
            add_shortcode( 'sdweddingdirectory_featuer_box', [ $this, 'sdweddingdirectory_featuer_box' ] );

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

                            'sdweddingdirectory_featuer_box'    =>  sprintf( '[sdweddingdirectory_featuer_box %1$s]<h4>Budget</h4>[/sdweddingdirectory_featuer_box]',

                                                                parent:: _shortcode_atts( self:: default_args() )
                                                            )
                        ] );
            } );
        }

        /**
         *  Output
         *  ------
         */
        public static function sdweddingdirectory_featuer_box( $atts, $content = '' ){

            /**
             *  Extract Attr Data
             *  -----------------
             */
            extract( shortcode_atts( self:: default_args(), $atts, esc_attr( __FUNCTION__ ) ) );

            /**
             *  Collectoin
             *  ----------
             */
            $collection         =       '';

            /**
             *  Style
             *  -----
             */
            $collection         .=      parent:: _shortcode_style_start( $style );

            /**
             *  If Modal Popup ?
             *  ----------------
             */
            if( $button_action == esc_attr( 'model_popup' ) ){

                /**
                 *  Handler
                 *  -------
                 */
                $handler        =       '';

                /**
                 *  Conditions
                 *  ----------
                 */
                if( $modal_id == esc_attr( 'couple_login' ) ){

                    /**
                     *  Is Couple ?
                     *  -----------
                     */
                    if( parent:: is_couple() ){

                        $handler        =       sprintf(    'href="%1$s"', esc_url( $dashboard_page ) );
                    }

                    elseif( is_user_logged_in() ){

                        $handler        =       sprintf(    'href="%1$s"', 'javascript:' );
                    }

                    else{

                        $handler        =       sprintf(    'href="javascript:" role="button" data-bs-toggle="modal" data-bs-target="#%1$s" 

                                                            data-modal-redirection="%2$s" data-modal-id="%3$s"', 

                                                            /** 
                                                             *  1. Target ?
                                                             *  -----------
                                                             */
                                                            parent:: popup_id( $modal_id ),

                                                            /** 
                                                             *  2. Button Link
                                                             *  --------------
                                                             */
                                                            esc_url( $dashboard_page ),

                                                            /**
                                                             *  3. Redirection Link
                                                             *  -------------------
                                                             */
                                                            esc_attr( $modal_id )
                                                );
                    }
                }

                elseif( $modal_id == esc_attr( 'vendor_login' ) ){

                    /**
                     *  Is Vendor ?
                     *  -----------
                     */
                    if( parent:: is_vendor() ){

                        $handler        =       sprintf(    'href="%1$s"', esc_url( $dashboard_page ) );
                    }

                    elseif( is_user_logged_in() ){

                        $handler        =       sprintf(    'href="%1$s"', 'javascript:' );
                    }

                    else{

                        $handler        =       sprintf(    'href="javascript:" role="button" data-bs-toggle="modal" data-bs-target="#%1$s" 

                                                            data-modal-redirection="%2$s" data-modal-id="%3$s"', 

                                                            /** 
                                                             *  3. Target ?
                                                             *  -----------
                                                             */
                                                            parent:: popup_id( $modal_id ),

                                                            /** 
                                                             *  4. Button Link
                                                             *  --------------
                                                             */
                                                            esc_url( $dashboard_page ),

                                                            /**
                                                             *  5. Redirection Link
                                                             *  -------------------
                                                             */
                                                            esc_attr( $modal_id )
                                                );
                    }
                }

                /**
                 *  Handler
                 *  -------
                 */
                $collection         .=      sprintf(   '<div class="why-choose-icons">

                                                            <a %3$s>

                                                                <div class="icon-big-cirlce mx-auto">

                                                                    <i class="%1$s"></i>

                                                                </div>

                                                            </a>

                                                            <span>%2$s</span>

                                                            <a %3$s class="circle-arrow">

                                                                <i class="fa fa-angle-right"></i>

                                                            </a>

                                                        </div>',

                                                        /**
                                                         *  1. Icon
                                                         *  -------
                                                         */
                                                        $icon,

                                                        /**
                                                         *  2. Button Text
                                                         *  --------------
                                                         */
                                                        do_shortcode(

                                                            apply_filters(  'sdweddingdirectory_clean_shortcode',  $content )
                                                        ),

                                                        /** 
                                                         *  3. Args
                                                         *  -------
                                                         */
                                                        $handler
                                            );
            }

            /**
             *  Normal Page Link 
             *  ----------------
             */
            else{

                $collection         .=      sprintf(   '<div class="why-choose-icons">

                                                            <a target="%3$s" href="%4$s">

                                                                <div class="icon-big-cirlce mx-auto">

                                                                    <i class="%1$s"></i>

                                                                </div>

                                                            </a>

                                                            <span>%2$s</span>

                                                            <a target="%3$s" href="%4$s" class="circle-arrow">

                                                                <i class="fa fa-angle-right"></i>

                                                            </a>

                                                        </div>',

                                                        /**
                                                         *  1. Icon
                                                         *  -------
                                                         */
                                                        $icon,

                                                        /**
                                                         *  2. Button Text
                                                         *  --------------
                                                         */
                                                        do_shortcode(

                                                            apply_filters(  'sdweddingdirectory_clean_shortcode',  $content )
                                                        ),

                                                        /** 
                                                         *  3. Target ?
                                                         *  -----------
                                                         */
                                                        esc_attr( $target ),

                                                        /** 
                                                         *  4. Button Link
                                                         *  --------------
                                                         */
                                                        esc_url( $link )
                                            );
            }

            /**
             *  Style
             *  -----
             */
            $collection         .=      parent:: _shortcode_style_end( $style );

            /**
             *  Return Data
             *  -----------
             */
            return      $collection;
        }

        /**
         *  Page Builder : Args
         *  -------------------
         */
        public static function page_builder( $args = [] ){

            /** 
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Setting with Merge Empty Fields
                 *  ---------------------------------------
                 */
                extract( wp_parse_args( $args, self:: default_args() ) );

                /**
                 *  Return Button HTML
                 *  ------------------
                 */
                return  do_shortcode( sprintf(

                            '[sdweddingdirectory_featuer_box 

                                style="%1$s" target="%2$s" link="%3$s" icon="%4$s" button_action="%5$s" modal_id="%6$s" 

                                dashboard_page="%7$s"]%8$s[/sdweddingdirectory_featuer_box]',

                            /**
                             *  1. Style
                             *  --------
                             */
                            $style,

                            /**
                             *  2. Target
                             *  ---------
                             */
                            $target,

                            /**
                             *  3. Link
                             *  -------
                             */
                            $link,

                            /**
                             *  4. Icon ?
                             *  ---------
                             */
                            $icon,

                            /**
                             *  5. Button Action
                             *  ----------------
                             */
                            $button_action,

                            /**
                             *  6. Modal ID
                             *  -----------
                             */
                            $modal_id,

                            /**
                             *  7. Redirection Page
                             *  -------------------
                             */
                            $dashboard_page,

                            /**
                             *  8. Button Text
                             *  --------------
                             */
                            $content

                        ) );
            }
        }
    }

    /**
     *  ----------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Feature Box ]
     *  ----------------------------------------
     */
    SDWeddingDirectory_Shortcode_Feature_Box::get_instance();
}