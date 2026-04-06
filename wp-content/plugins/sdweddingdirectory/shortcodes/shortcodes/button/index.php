<?php
/**
 *  -----------------------------------
 *  SDWeddingDirectory - ShortCode - [ Button ]
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Button' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  -----------------------------------
     *  SDWeddingDirectory - ShortCode - [ Button ]
     *  -----------------------------------
     */
    class SDWeddingDirectory_Shortcode_Button extends SDWeddingDirectory_Shortcode {

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
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Button ShortCode
             *  ----------------
             */
            add_shortcode( 'sdweddingdirectory_button', [ $this, 'sdweddingdirectory_button' ] );

            /**
             *  Button : Filters
             *  ----------------
             */
            self:: shortcode_filters();
        }

        /**
         *  Have Filters ?
         *  --------------
         */
        public static function shortcode_filters(){

            /**
             *  1. SDWeddingDirectory ShortCode Filter
             *  ------------------------------
             */
            add_filter( 'sdweddingdirectory_shortcode_list', function( $args = [] ){

                /**
                 *  Get Plugin Information
                 *  ----------------------
                 */
                return  array_merge(

                            /**
                             *  Have Args ?
                             *  -----------
                             */
                            $args,

                            /**
                             *  Merge New Args
                             *  --------------
                             */
                            array(

                                'sdweddingdirectory_button'  =>    '[sdweddingdirectory_button target="_self" class="" id="" link="" ] Button Text Here [/sdweddingdirectory_button]'
                            )
                        );
            } );

            /**
             *  3. SDWeddingDirectory - Default Setting
             *  -------------------------------
             */
            add_filter( 'sdweddingdirectory_button_setting', function(){

                /**
                 *  Add Slider Placeholder
                 *  ----------------------
                 */
                return  array(

                            'target'                =>      esc_attr( '_self' ),

                            'class'                 =>      '',

                            'id'                    =>      '',

                            'link'                  =>      esc_url( home_url( '/' ) ),

                            'button_text'           =>      '',

                            'button_action'         =>      esc_attr( 'page_link' ), // popup

                            'model_popup'           =>      esc_attr( parent:: popup_id( 'couple_register' ) )
                        );
            } );

            /**
             *  SDWeddingDirectory - Button Role Filter
             *  -------------------------------
             */
            add_filter( 'sdweddingdirectory_button_role_filter', function( $args = [] ){

                /**
                 *  Merge the layouts
                 *  -----------------
                 */
                return  array_merge(

                    /**
                     *  1. Have Args ?
                     *  --------------
                     */
                    $args,

                    /**
                     *  2. Filter Default Value
                     *  -----------------------
                     */
                    array( 

                        esc_attr( 'model_popup' )       =>      esc_attr__( 'Model Popup ?', 'sdweddingdirectory-shortcodes' ),

                        esc_attr( 'page_link' )         =>      esc_attr__( 'Page Link ?', 'sdweddingdirectory-shortcodes' ),
                    )
                );

            } );

            /**
             *  SDWeddingDirectory - Total Model Popup List
             *  -----------------------------------
             */
            add_filter( 'sdweddingdirectory_button_model_popup_filter', function( $args = [] ){

                /**
                 *  Merge the layouts
                 *  -----------------
                 */
                return  array_merge(

                    /**
                     *  1. Have Args ?
                     *  --------------
                     */
                    $args,

                    /**
                     *  2. Filter Default Value
                     *  -----------------------
                     */
                    array( 

                        esc_attr( parent:: popup_id( 'couple_register' ) )      =>      esc_attr__( 'Couple Register', 'sdweddingdirectory-shortcodes' ),

                        esc_attr( parent:: popup_id( 'couple_login' ) )         =>      esc_attr__( 'Couple Login', 'sdweddingdirectory-shortcodes' ),

                        esc_attr( parent:: popup_id( 'vendor_register' ) )      =>      esc_attr__( 'Vendor Register', 'sdweddingdirectory-shortcodes' ),

                        esc_attr( parent:: popup_id( 'vendor_login' ) )         =>      esc_attr__( 'Vendor Login', 'sdweddingdirectory-shortcodes' ),

                        esc_attr( parent:: popup_id( 'forgot_password' ) )      =>      esc_attr__( 'Forgot Password', 'sdweddingdirectory-shortcodes' ),
                    )
                );

            } );

            /**
             *  SDWeddingDirectory - Button Layout Filter
             *  ---------------------------------
             */
            add_filter( 'sdweddingdirectory_button_layout_filter', function( $args = [] ){

                /**
                 *  Merge the layouts
                 *  -----------------
                 */
                return  array_merge(

                    /**
                     *  1. Have Args ?
                     *  --------------
                     */
                    $args,

                    /**
                     *  2. Filter Default Value
                     *  -----------------------
                     */
                    array(

                        'btn-default'       =>      esc_attr__( 'Default Button', 'sdweddingdirectory-shortcodes' ),

                        'btn-primary'       =>      esc_attr__( 'Primary Button', 'sdweddingdirectory-shortcodes' ),

                        'btn-secondary'     =>      esc_attr__( 'Secondary Button', 'sdweddingdirectory-shortcodes' ),

                        'btn-success'       =>      esc_attr__( 'Success Button', 'sdweddingdirectory-shortcodes' ),

                        'btn-danger'        =>      esc_attr__( 'Danger Button', 'sdweddingdirectory-shortcodes' ),

                        'btn-warning'       =>      esc_attr__( 'Warning Button', 'sdweddingdirectory-shortcodes' ),

                        'btn-info'          =>      esc_attr__( 'Info Button', 'sdweddingdirectory-shortcodes' ),

                        'btn-light'         =>      esc_attr__( 'Light Button', 'sdweddingdirectory-shortcodes' ),

                        'btn-dark'          =>      esc_attr__( 'Dark Button', 'sdweddingdirectory-shortcodes' ),

                        'btn-link'          =>      esc_attr__( 'Link Button', 'sdweddingdirectory-shortcodes' )
                    )
                );

            } );

            /**
             *  SDWeddingDirectory - Button Align Filter
             *  --------------------------------
             */
            add_filter( 'sdweddingdirectory_button_align_filter', function( $args = [] ){

                /**
                 *  Merge the layouts
                 *  -----------------
                 */
                return  array_merge(

                    /**
                     *  1. Have Args ?
                     *  --------------
                     */
                    $args,

                    /**
                     *  2. Filter Default Value
                     *  -----------------------
                     */
                    array( 

                        'text-start'            =>      esc_attr__( 'Left side', 'sdweddingdirectory-shortcodes' ),

                        'text-center'           =>      esc_attr__( 'Center side', 'sdweddingdirectory-shortcodes' ),

                        'text-end'              =>      esc_attr__( 'Right side', 'sdweddingdirectory-shortcodes' ),
                    )
                );

            } );

            /**
             *  SDWeddingDirectory - Button Size Filter
             *  --------------------------------
             */
            add_filter( 'sdweddingdirectory_button_size_filter', function( $args = [] ){

                /**
                 *  Merge the layouts
                 *  -----------------
                 */
                return  array_merge(

                    /**
                     *  1. Have Args ?
                     *  --------------
                     */
                    $args,

                    /**
                     *  2. Filter Default Value
                     *  -----------------------
                     */
                    array( 

                        'btn'               =>      esc_attr__( 'Normal Size', 'sdweddingdirectory-shortcodes' ),

                        'btn-sm'            =>      esc_attr__( 'Small Size', 'sdweddingdirectory-shortcodes' ),

                        'btn-lg'            =>      esc_attr__( 'Large Size', 'sdweddingdirectory-shortcodes' ),
                    )
                );

            } );

            /**
             *  SDWeddingDirectory - Button Style Filter
             *  --------------------------------
             */
            add_filter( 'sdweddingdirectory_button_style_filter', function( $args = [] ){

                /**
                 *  Merge the layouts
                 *  -----------------
                 */
                return  array_merge(

                    /**
                     *  1. Have Args ?
                     *  --------------
                     */
                    $args,

                    /**
                     *  2. Filter Default Value
                     *  -----------------------
                     */
                    array(

                        ''                          =>      esc_attr__( 'Default Style', 'sdweddingdirectory-shortcodes' ),

                        'btn-outline'               =>      esc_attr__( 'Outline Style', 'sdweddingdirectory-shortcodes' ),

                        'btn-rounded'               =>      esc_attr__( 'Rounded Style', 'sdweddingdirectory-shortcodes' ),

                        'btn-outline btn-rounded'   =>      esc_attr__( 'Rounded with Outline Style', 'sdweddingdirectory-shortcodes' ),

                        'btn-link'                  =>      esc_attr__( 'Link Style', 'sdweddingdirectory-shortcodes' ),
                    )
                );

            } );
        }

        /**
         *  Output
         *  ------
         */
        public static function sdweddingdirectory_button( $atts, $content = '' ){

            /**
             *  Default Args
             *  ------------
             */
            $atts = shortcode_atts( array(

                'id'                =>  '',

                'class'             =>  esc_attr( 'btn btn-default' ),

                'link'              =>  esc_url( '#' ),

                'target'            =>  esc_attr( '_self' ),

                'button_action'     =>  esc_attr( 'link' ),

                'model_popup'       =>  esc_attr( parent:: popup_id( 'couple_register' ) )

            ), $atts, esc_attr( __FUNCTION__ ) );

            /**
             *  Extract Args
             *  ------------
             */
            extract( $atts );

            /**
             *  Return Button HTML
             *  ------------------
             */
            return  sprintf(  ' <a %1$s class="btn %2$s" %6$s target="%3$s" href="%4$s">%5$s</a> ',

                        /**
                         *  1. Have ID ?
                         *  ------------
                         */
                        parent:: _have_attr_value( array(

                            'attr'    =>   esc_attr( 'id' ),

                            'val'    =>   $id
                        ) ),

                        /** 
                         *  2. Button Class
                         *  ---------------
                         */
                        esc_attr( $class ),

                        /** 
                         *  3. Target ?
                         *  -----------
                         */
                        esc_attr( $target ),

                        /** 
                         *  4. Button Link
                         *  --------------
                         */
                        isset( $button_action ) &&  parent:: _have_data( $button_action ) && $button_action == esc_attr( 'model_popup' )

                        ?   esc_attr( 'javascript:' )

                        :   esc_url( $link ),

                        /**
                         *  5. Button Text
                         *  --------------
                         */
                        do_shortcode(

                            apply_filters(  'sdweddingdirectory_clean_shortcode',  $content )
                        ),

                        /**
                         *  5. Have Action is Model Poup ?
                         *  ------------------------------
                         */
                        isset( $button_action )    &&  parent:: _have_data( $button_action ) && $button_action == esc_attr( 'model_popup' ) && 

                        isset( $model_popup )  &&  parent:: _have_data( $model_popup )

                        ?   sprintf( 'data-bs-toggle="modal" data-bs-target="#%1$s"', 

                                /**
                                 *  1. Model Poup ID
                                 *  ----------------
                                 */
                                esc_attr( $model_popup )
                            )

                        :   ''
                    );
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
                 *  Extract Args
                 *  ------------
                 */
                extract(

                    wp_parse_args(

                        $args,

                        apply_filters( 'sdweddingdirectory_button_setting', [] )
                    )
                );

                /**
                 *  Return Button HTML
                 *  ------------------
                 */
                return  do_shortcode( 

                            sprintf( '[sdweddingdirectory_button target="%1$s" class="%2$s" id="%3$s" link="%4$s" button_action="%6$s" model_popup="%7$s" ] %5$s [/sdweddingdirectory_button]',

                                /**
                                 *  1. Button Target
                                 *  ----------------
                                 */
                                $target,

                                /**
                                 *  2. Button Class
                                 *  ---------------
                                 */
                                $class,

                                /**
                                 *  3. Button ID
                                 *  ------------
                                 */
                                $id,

                                /**
                                 *  4. Button Link
                                 *  --------------
                                 */
                                $link,

                                /**
                                 *  5. Button Text
                                 *  --------------
                                 */
                                $button_text,

                                /**
                                 *  6. Button Action ?
                                 *  ------------------
                                 */
                                $button_action,

                                /**
                                 *  7. Button Model Popup ID ?
                                 *  --------------------------
                                 */
                                $model_popup
                            )
                        );
            }
        }
    }

    /**
     *  -----------------------------------
     *  SDWeddingDirectory - ShortCode - [ Button ]
     *  -----------------------------------
     */
    SDWeddingDirectory_Shortcode_Button::get_instance();
}