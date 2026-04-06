<?php
/**
 *  SDWeddingDirectory - Register Meta
 *  --------------------------
 */
if ( ! class_exists( 'SDWeddingDirectory_Register_Meta' ) && class_exists( 'SDWeddingDirectory_Admin_Settings' ) ) {

    /**
     *  SDWeddingDirectory - Register Meta
     *  --------------------------
     */
    class SDWeddingDirectory_Register_Meta extends SDWeddingDirectory_Admin_Settings {

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

            if (!isset(self::$instance)) {

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
             *  SDWeddingDirectory All Option To Create Theme Option Page.
             *  --------------------------------------------------
             */
            add_action( 'admin_init', [ $this, 'sdweddingdirectory_create_metabox' ] );

            /**
             *  General Setting for SDWeddingDirectory!
             *  -------------------------------
             */
            add_filter( 'sdweddingdirectory/meta', [$this, 'sdweddingdirectory_general_setting' ] );

            /**
             *  Post Setting for SDWeddingDirectory!
             *  ----------------------------
             */
            add_filter( 'sdweddingdirectory/meta', [$this, 'sdweddingdirectory_post_setting' ] );

            /**
             *  Real Wedding Post Setting for SDWeddingDirectory!
             *  -----------------------------------------
             */
            add_filter( 'sdweddingdirectory/meta', [$this, 'sdweddingdirectory_user_setting' ] );

            /**
             *  Social Media Page Metabox
             *  -------------------------
             */
            add_filter( 'sdweddingdirectory/meta', [ $this, 'sdweddingdirectory_social_profile_setting' ] );

            /**
             *  User Meta
             *  ---------
             */
            add_action( 'edit_user_profile', array( $this, 'sdweddingdirectory_user_profile_fields' ) );

            add_action( 'show_user_profile', array( $this, 'sdweddingdirectory_user_profile_fields' ) );

            add_action( 'edit_user_profile_update', array( $this, 'sdweddingdirectory_save_profile_fields' ) );
            
            add_action( 'personal_options_update',  array( $this, 'sdweddingdirectory_save_profile_fields' ) );
        }

        /**
         *  User Data
         *  ---------
         */
        public static function sdweddingdirectory_user_meta_data(){

            return  [
                        [   'section'               =>      esc_attr__( 'Register User Data', 'sdweddingdirectory' ),

                            'fields'                =>      [

                                [   'type'                  =>      'textarea',

                                    'id'                    =>      'register_user_data',

                                    'label'                 =>      esc_attr__( 'Form Data', 'sdweddingdirectory' ),
                                ],

                                [   'type'                  =>      'input',

                                    'id'                    =>      'sdweddingdirectory_user_token',

                                    'label'                 =>      esc_attr__( 'User Token', 'sdweddingdirectory' ),
                                ],

                                [   'type'                  =>      'input',

                                    'id'                    =>      'sdweddingdirectory_user_verify',

                                    'label'                 =>      esc_attr__( 'User Verify', 'sdweddingdirectory' ),
                                ],

                                [   'type'                  =>      'input',

                                    'id'                    =>      'sdweddingdirectory_user_last_login',

                                    'label'                 =>      esc_attr__( 'Last Login', 'sdweddingdirectory' ),
                                ],

                                [   'type'                  =>      'verification_link',

                                    'id'                    =>      'sdweddingdirectory_user_verificaton_link',

                                    'label'                 =>      esc_attr__( 'Verificatoin Link', 'sdweddingdirectory' ),
                                ],
                            ]
                        ],

                        [   'section'               =>      esc_attr__( 'Development Purpose', 'sdweddingdirectory' ),

                            'fields'                =>      [

                                [   'type'                  =>      'input',

                                    'id'                    =>      'sdweddingdirectory_demo_user',

                                    'label'                 =>      esc_attr__( 'Is Demo User ?', 'sdweddingdirectory' ),
                                ],
                            ]
                        ],
                    ];
        }

        /**
         *  Insert aurhor fields
         *  --------------------
         */
        public static function sdweddingdirectory_user_profile_fields( $user ){

            /**
             *  User Data
             *  ---------
             */
            $_collection        =       self:: sdweddingdirectory_user_meta_data();

            /**
             *  Have Data
             *  ---------
             */
            if( parent:: _is_array( $_collection ) ){

                /**
                 *  Have Collection
                 *  ---------------
                 */
                foreach( $_collection as $auth_key => $auth_value ){

                    /**
                     *  Extract
                     *  -------
                     */
                    extract( $auth_value );

                    /**
                     *  Table Start
                     *  -----------
                     */
                    printf( '<h2>%1$s</h2>', $section );

                    /**
                     *  Have Fields
                     *  -----------
                     */
                    if( parent:: _is_array( $fields ) ){

                        ?><table class="form-table"><?php

                        /**
                         *  Author Meta
                         *  -----------
                         */
                        foreach( $fields as $field_key => $field_value ){

                            /**
                             *  Extract
                             *  -------
                             */
                            extract( $field_value );

                            /**
                             *  Is Input
                             *  --------
                             */
                            if( $type   ==  'input' ){

                                printf( '<tr>

                                            <th>

                                                <label for="%1$s">%3$s</label>

                                            </th>

                                            <td>

                                                <input type="text" name="%1$s" id="%1$s" class="regular-text" value="%2$s" />

                                            </td>

                                        </tr>',

                                        /**
                                         *  Key
                                         *  ---
                                         */
                                        esc_attr( $id ),

                                        /**
                                         *  Author Meta
                                         *  -----------
                                         */
                                        esc_attr( get_the_author_meta( $id, $user->ID ) ),

                                        /**
                                         *  Value
                                         *  -----
                                         */
                                        esc_attr( $label )
                                );
                            }

                            elseif( $type   ==  'textarea' ){

                                printf( '<tr>

                                            <th>

                                                <label for="%1$s">%3$s</label>

                                            </th>

                                            <td>

                                                <textarea rows="4" name="%1$s" id="%1$s" class="regular-text">%2$s</textarea>

                                            </td>

                                        </tr>',

                                        /**
                                         *  Key
                                         *  ---
                                         */
                                        esc_attr( $id ),

                                        /**
                                         *  Author Meta
                                         *  -----------
                                         */
                                        esc_attr( get_the_author_meta( $id, $user->ID ) ),

                                        /**
                                         *  Value
                                         *  -----
                                         */
                                        esc_attr( $label )
                                );
                            }

                            elseif( $type == 'verification_link' ){

                                /**
                                 *  Token is Generated
                                 *  ------------------
                                 */
                                $_is_verified_user  =   get_the_author_meta( 'sdweddingdirectory_user_verify', $user->ID );

                                $_tokan             =   get_the_author_meta( 'sdweddingdirectory_user_token', $user->ID );

                                /**
                                 *  Make sure it's not verified user
                                 *  --------------------------------
                                 */
                                if( ( empty( $_is_verified_user ) || $_is_verified_user == esc_attr( 'no' ) ) && ! empty( $_tokan ) ){

                                    printf( '<tr>

                                                <th>

                                                    <label for="%1$s">%3$s</label>

                                                </th>

                                                <td>

                                                    <input type="text" name="%1$s" id="%1$s" class="regular-text" value="%2$s" />

                                                </td>

                                            </tr>',

                                            /**
                                             *  Key
                                             *  ---
                                             */
                                            esc_attr( $id ),

                                            /**
                                             *  Author Meta
                                             *  -----------
                                             */
                                            esc_url( add_query_arg( 

                                                [  'sdweddingdirectory_user_token'     =>  esc_attr( $_tokan )  ],

                                                home_url( '/' )

                                            ), null, null ),

                                            /**
                                             *  Value
                                             *  -----
                                             */
                                            esc_attr( $label )
                                    );
                                }
                            }
                        }   

                        ?></table><?php
                    }
                }
            }
        }

        /**
         *  Save author meta value
         *  ----------------------
         */
        public static function sdweddingdirectory_save_profile_fields( $user_id ) {

            if ( ! current_user_can( 'edit_user', $user_id ) ){

                return  false;
            }

            /**
             *  User Data
             *  ---------
             */
            $_collection        =       self:: sdweddingdirectory_user_meta_data();

            /**
             *  Have Data
             *  ---------
             */
            if( parent:: _is_array( $_collection ) ){

                /**
                 *  Have Collection
                 *  ---------------
                 */
                foreach( $_collection as $auth_key => $auth_value ){

                    /**
                     *  Extract
                     *  -------
                     */
                    extract( $auth_value );

                    /**
                     *  Have Fields
                     *  -----------
                     */
                    if( parent:: _is_array( $fields ) ){

                        /**
                         *  Author Meta
                         *  -----------
                         */
                        foreach( $fields as $field_key => $field_value ){

                            /**
                             *  Extract
                             *  -------
                             */
                            extract( $field_value );

                            /**
                             *  One Field Ignore
                             *  ----------------
                             */
                            if( $id != 'sdweddingdirectory_user_verificaton_link' ){

                                /**
                                 *  Update User Meta
                                 *  ----------------
                                 */
                                update_user_meta( $user_id, $id, sanitize_text_field( $_POST[ $id ] ) );
                            }
                        }
                    }
                }
            }
        }

        /**
         *  SDWeddingDirectory - User Configuration Meta
         *  ------------------------------------
         */
        public static function sdweddingdirectory_user_setting($args = [] ) {

            $new_args           =       [

                'id'            =>      esc_attr( 'sdweddingdirectory_user_configuration_settings' ),

                'title'         =>      esc_attr__( 'Email Configuration', 'sdweddingdirectory' ),

                'pages'         =>      apply_filters( 'sdweddingdirectory/user/configuration/meta', [] ),

                'context'       =>      esc_attr( 'side' ),

                'priority'      =>      esc_attr( 'high' ),

                'fields'        =>      array(

                    array(

                        'id'        =>  esc_attr( 'user_email' ),

                        'type'      =>  esc_attr( 'text' ) 
                    ),
                )
            ];

            /**
             *  Email Configuration
             *  -------------------
             */
            return      array_merge(  $args, [ $new_args ]  );
        }

        /**
         *  SDWeddingDirectory Page & Post Metaboxes
         *  ---------------------------------
         *
         *  1. Body Customization Metabox
         *
         *  2. Header Metabox
         *
         *  3. Page Header banner Metabox
         *
         *  4. Page Structure Metabox
         *
         *  5. Footer Metabox
         *  
         *  -----------------
         */
        public static function sdweddingdirectory_general_setting( $args = [] ){
            /**
             *  Add Meta Setting
             *  ----------------
             */
            $new_args = array(

                'id'        =>  esc_attr( 'sdweddingdirectory_page_settings' ),

                'title'     =>  esc_attr__( 'Page Settings', 'sdweddingdirectory' ),

                'pages'     =>  array( 'post', 'page', 'product' ),

                'context'   =>  esc_attr( 'normal' ),

                'priority'  =>  esc_attr( 'high' ),

                'fields'    =>  array(

                    /**
                     *  Header Setting
                     *  --------------
                     */
                    array(

                        'label'     =>  esc_attr__( 'Page Header', 'sdweddingdirectory' ),

                        'id'        =>  esc_attr('sdweddingdirectory_header_meta_setting'),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                    array(

                        'id'        =>  esc_attr( 'choose_page_header' ),

                        'label'     =>  esc_attr__( 'Select Header Version on this page ?', 'sdweddingdirectory' ),

                        'desc'      =>  esc_attr__( 'In Setting Option Globally declare the header layout. if you wish to enable different header on this page. you can change it.', 'sdweddingdirectory' ),

                        'std'       =>  esc_attr( 'off' ),

                        'type'      =>  esc_attr( 'on-off' ),
                    ),

                    array(

                        'id'        =>  esc_attr( 'header_style' ),

                        'label'     =>  esc_attr__( 'Header Style', 'sdweddingdirectory' ),

                        'std'       =>  esc_attr( 'style-2' ),

                        'type'      =>  esc_attr( 'select' ),

                        'operator'  =>  esc_attr( 'and' ),

                        'condition' =>  'choose_page_header:is(on)',

                        'choices'   =>  array(

                            array(

                                'value'     =>  esc_attr( 'style-1' ),

                                'label'     =>  esc_attr__( 'Header Version One', 'sdweddingdirectory' ),

                                'src'       =>  '',
                            ),

                            array(

                                'value'     =>  esc_attr( 'style-2' ),

                                'label'     =>  esc_attr__( 'Header Version Two', 'sdweddingdirectory' ),

                                'src'       =>  '',
                            ),
                        ),
                    ),

                    array(

                        'id'        =>  esc_attr( 'enable_page_banner' ),

                        'label'     =>  esc_attr__( 'Page Header Banner Show / Hide Option', 'sdweddingdirectory' ),

                        'std'       =>  esc_attr( 'on' ),

                        'type'      =>  esc_attr( 'on-off' ),
                    ),

                    array(

                        'id'        =>  esc_attr( 'page_banner' ),

                        'label'     =>  esc_attr__( 'Upoad Your Custom Page Banner Image', 'sdweddingdirectory' ),

                        'type'      =>  esc_attr( 'upload' ),

                        'condition' =>  esc_attr( 'enable_page_banner:is(on)' ),

                        'operator'  =>  esc_attr( 'and' ),
                    ),

                    /**
                     *  Page Structure
                     *  --------------
                     */
                    array(

                        'label'     =>  esc_attr__('Page Structure', 'sdweddingdirectory'),

                        'id'        =>  esc_attr('sdweddingdirectory_page_container_setting'),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                    array(

                        'id'        =>  esc_attr('container_style'),

                        'label'     =>  esc_attr__('Page Structure', 'sdweddingdirectory'),

                        'std'       =>  esc_attr( 'container' ),

                        'type'      =>  esc_attr( 'select' ),

                        'choices'   =>  array(

                            array(

                                'value'     =>  esc_attr( 'container' ),

                                'label'     =>  esc_attr__('Container', 'sdweddingdirectory'),

                                'src'       =>  plugin_dir_url(__FILE__) . '/core/option-tree/images/container/container.jpg',
                            ),

                            array(

                                'value'     => 'custom_structure',

                                'label'     => esc_attr__('Custom Structure', 'sdweddingdirectory'),

                                'src'       => plugin_dir_url(__FILE__) . '/core/option-tree/images/container/custom-div.jpg',
                            ),
                        ),
                    ),

                    array(

                        'id'            =>  esc_attr('sidebar_position'),

                        'label'         =>  esc_attr__('If you want added Sidebar ?', 'sdweddingdirectory'),

                        'std'           =>  esc_attr( 'right-sidebar' ),

                        'type'          =>  esc_attr( 'select' ),

                        'condition'     =>  esc_attr( 'container_style:not(custom_structure)' ),

                        'operator'      =>  esc_attr( 'and' ),

                        'choices'       =>  array(

                            array(

                                'value'   =>  esc_attr( 'no-sidebar' ),

                                'label'   =>  esc_attr__('No Sidebar', 'sdweddingdirectory'),

                                'src'     =>  plugin_dir_url(__FILE__) . '/core/option-tree/images/sidebar/no-sidebar.jpg',
                            ),

                            array(

                                'value'   =>  esc_attr( 'right-sidebar' ),

                                'label'   =>  esc_attr__('Right Sidebar', 'sdweddingdirectory'),

                                'src'     =>  plugin_dir_url(__FILE__) . '/core/option-tree/images/sidebar/right-sidebar.jpg',
                            ),

                            array(

                                'value'   =>  esc_attr( 'left-sidebar' ),

                                'label'   =>  esc_attr__('Left Sidebar', 'sdweddingdirectory'),

                                'src'     =>  plugin_dir_url(__FILE__) . '/core/option-tree/images/sidebar/left-sidebar.jpg',
                            ),
                        ),
                    ),

                    array(

                        'id'            => esc_attr( 'get_sidebar' ),

                        'label'         => esc_attr__('Please select sidebar', 'sdweddingdirectory'),

                        'desc'          => esc_attr__('Select sidebar that can be display on page with selectd sidebar', 'sdweddingdirectory'),

                        'std'           => esc_attr( 'sdweddingdirectory-widget-primary' ),

                        'type'          => esc_attr( 'sidebar-select' ),

                        'condition'     => esc_attr( 'container_style:not(custom_structure),sidebar_position:not(no-sidebar)' ),

                        'operator'      => esc_attr( 'and' ),
                    ),

                    /**
                     *  Footer Options
                     *  --------------
                     */
                    array(

                        'label'     =>  esc_attr__('Footer Options', 'sdweddingdirectory'),

                        'id'        =>  esc_attr('sdweddingdirectory_footer_meta_setting'),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                    array(

                        'id'        =>  esc_attr( 'page_footer_on_off' ),

                        'label'     =>  esc_attr__( 'Page Footer On/Off', 'sdweddingdirectory' ),

                        'desc'      =>  esc_attr__( 'This setting apply page footer show or hide on only this page.', 'sdweddingdirectory' ),

                        'std'       =>  esc_attr( 'on' ),

                        'type'      =>  esc_attr( 'on-off' ),
                    ),

                    array(
                        'id'        =>  esc_attr('page_tiny_footer_on_off'),

                        'label'     =>  esc_attr__('Page Tiny Footer On/Off', 'sdweddingdirectory'),

                        'desc'      =>  esc_attr__('This setting apply page tiny footer show or hide on only this page.', 'sdweddingdirectory'),

                        'std'       =>  esc_attr( 'on' ),

                        'type'      =>  esc_attr( 'on-off' ),
                    ),
                ),
            );

            return array_merge( $args, array( $new_args ) );
        }

        /**
         *  SDWeddingDirectory Post Setting
         *  -----------------------
         */
        public static function sdweddingdirectory_post_setting( $args = [] ) {

            $new_args = array(

                'id'        =>  esc_attr( 'post-formate-helper' ),

                'title'     =>  esc_attr__('Post formate helper', 'sdweddingdirectory'),

                'pages'     =>  array( 'post' ),

                'context'   =>  esc_attr( 'normal' ),

                'priority'  =>  esc_attr( 'high' ),

                'fields'    =>  array(

                    array(

                        'id'    =>  esc_attr( 'gallery_meta' ),

                        'label' =>  esc_attr__( 'Gallery', 'sdweddingdirectory' ),

                        'type'  =>  esc_attr( 'gallery' ),
                    ),

                    array(

                        'id'    => esc_attr('video_meta'),

                        'label' => esc_attr__('Video Link Here', 'sdweddingdirectory'),

                        'type'  => esc_attr( 'text' ),
                    ),

                    array(
                        'id'    => esc_attr('audio_meta'),

                        'label' => esc_attr__('Audio Link Here', 'sdweddingdirectory'),

                        'type'  => esc_attr( 'text' ),
                    ),
                ),
            );

            return array_merge( $args, array( $new_args ) );
        }

        /**
         *  SDWeddingDirectory All Option Retrive in Option Tree Function
         *  -----------------------------------------------------
         */
        public static function sdweddingdirectory_create_metabox() {

            $sdweddingdirectory_meta    =   apply_filters( 'sdweddingdirectory/meta', [] );

            /**
             *  Make sure is array ?
             *  --------------------
             */
            if ( parent:: _is_array( $sdweddingdirectory_meta ) ) {

                foreach ( $sdweddingdirectory_meta as $args ) {

                    /**
                     *  Make sure is array ?
                     *  --------------------
                     */
                    if ( parent:: _is_array( $args ) && function_exists( 'ot_register_meta_box' ) ) {

                        ot_register_meta_box( $args );
                    }
                }
            }
        }

        /**
         *  SDWeddingDirectory - Social Profile Meta
         *  ---------------------------------
         */
        public static function sdweddingdirectory_social_profile_setting( $args = [] ) {

            $_social_data       =   [];

            $_social_profile    =   apply_filters( 'sdweddingdirectory/social-media', [] );

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $_social_profile ) ){

                foreach( $_social_profile as $key => $value ){

                    /**
                     *  Value
                     *  -----
                     */
                    extract( $value );

                    $_social_data[]  =  array(

                                            'value'     =>  esc_attr( $icon ),

                                            'label'     =>  esc_attr( $name ),

                                            'src'       =>  '',
                                        );
                }
            }

            $new_args = array(

                'id'        =>  sanitize_title( __FUNCTION__ ),

                'title'     =>  esc_attr__( 'Social Media', 'sdweddingdirectory'),

                'pages'     =>  array( 'couple', 'vendor' ),

                'context'   =>  esc_attr( 'normal' ),

                'priority'  =>  esc_attr( 'low' ),

                'fields'    =>  array(

                    array(

                        'id'        =>  sanitize_key( 'social_profile' ),

                        'type'      =>  esc_attr( 'list-item' ),

                        'operator'  =>  esc_attr( 'or' ),

                        'choices'   =>  [],

                        'settings'  =>  array(

                            array(

                                'id'        =>  esc_attr( 'platform' ),

                                'label'     =>  esc_attr__( 'Social Media Platform', 'sdweddingdirectory'),

                                'std'       =>  absint( '0' ),

                                'type'      =>  esc_attr( 'select' ),

                                'choices'   =>  parent:: _is_array( $_social_data ) ? $_social_data : []
                            ),

                            array(

                                'id'        =>  sanitize_key( 'link' ),

                                'label'     =>  esc_attr__( 'Social Media Link', 'sdweddingdirectory' ),

                                'type'      =>  esc_attr( 'text' ),
                            ),
                        ),
                    )
                )
            );

            return  array_merge( $args, array( $new_args ) );
        }
    }

    /**
     *  Kicking this off by calling 'get_instance()' method
     *  ---------------------------------------------------
     */
    SDWeddingDirectory_Register_Meta::get_instance();
}
