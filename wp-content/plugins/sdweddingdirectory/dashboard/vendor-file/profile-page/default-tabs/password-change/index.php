<?php
/**
 *  -----------------------------------------------
 *  SDWeddingDirectory - Vendor Profile Tab Fields - Object
 *  -----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Profile_Password_Change_Tab' ) && class_exists( 'SDWeddingDirectory_Vendor_Profile_Database' ) ){

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Vendor Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Profile_Password_Change_Tab extends SDWeddingDirectory_Vendor_Profile_Database{

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
         *  Tab Name
         *  --------
         */
        public static function tab_name(){

            return      esc_attr__( 'Password Change', 'sdweddingdirectory' );
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Add Filter
             *  ----------
             */
            add_filter( 'sdweddingdirectory/vendor-profile/tabs', function( $args = [] ){

                return  array_merge( $args, [

                            'password-change'        =>  [

                                'id'        =>  esc_attr( parent:: _rand() ),

                                'name'      =>  esc_attr( self:: tab_name() ),

                                'callback'  =>  [ 'class' => __CLASS__, 'member' => 'tab_content' ],

                                'create_form'      =>  [

                                    'form_before'   =>  '',

                                    'form_after'    =>  '',

                                    'form_id'       =>  esc_attr( 'vendor_user_password_change' ),

                                    'form_class'    =>  '',

                                    'button_before' =>  '',

                                    'button_after'  =>  '',

                                    'button_id'     =>  esc_attr( 'vendor_user_password_change_button' ),

                                    'button_class'  =>  '',

                                    'button_name'   =>  esc_attr__( 'Change Password','sdweddingdirectory' ),

                                    'security'      =>  esc_attr( 'change_password_security' ),
                                ]
                            ]

                        ] );

            }, absint( '30' ) );
        }

        /**
         *.  Tab Content
         *   -----------
         */
        public static function tab_content(){

            /**
             *   Section Information
             *   -------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>    esc_attr( 'info' ),

                    'class'       =>    sanitize_html_class( 'mb-0' ),

                    'title'       =>    esc_attr( self:: tab_name() ),
                )

            ) );

            /**
             *  Password Field
             *  --------------
             */
            parent:: create_section( array(

                /**
                 *  Div Managment
                 *  -------------
                 */
                'div'     =>   array(

                    'start'       =>   true,

                    'end'         =>   false,

                    'class'       =>   esc_attr( join( ' ', array_map( 'sanitize_html_class', explode(' ', 'card-body' ) ) ) )
                ),

                /**
                 *  Row Managment
                 *  -------------
                 */
                'row'     =>   array(

                    'start'       =>   true,

                    'end'         =>   false,
                ),

                /**
                 *  Column Managment
                 *  ----------------
                 */
                'column'     =>   array(

                    'grid'        =>   absint( '12' ),

                    'id'          =>   '',

                    'class'       =>   '',

                    'start'       =>   true,

                    'end'         =>   true,
                ),

                /**
                 *  Fields Arguments
                 *  ----------------
                 */
                'field'    =>  array(

                    'field_type'        =>  esc_attr( 'password' ),

                    'type'              =>  esc_attr( 'password' ),

                    'lable'             =>  esc_attr__( 'Old Password', 'sdweddingdirectory' ),

                    'placeholder'       =>  esc_attr__( 'Old Password', 'sdweddingdirectory' ),

                    'id'                =>  esc_attr( 'old_pwd' ),

                    'name'              =>  esc_attr( 'old_pwd' ),
                )

            ) );

            /**
             *  Password Field
             *  --------------
             */
            parent:: create_section( array(

                /**
                 *  Column Managment
                 *  ----------------
                 */
                'column'     =>   array(

                    'grid'        =>   absint( '6' ),

                    'id'          =>   '',

                    'class'       =>   '',

                    'start'       =>   true,

                    'end'         =>   true,
                ),

                /**
                 *  Fields Arguments
                 *  ----------------
                 */
                'field'    =>  array(

                    'field_type'        =>  esc_attr( 'password' ),

                    'type'              =>  esc_attr( 'password' ),

                    'lable'             =>  esc_attr__( 'New Password', 'sdweddingdirectory' ),

                    'placeholder'       =>  esc_attr__( 'New Password', 'sdweddingdirectory' ),

                    'id'                =>  esc_attr( 'new_pwd' ),

                    'name'              =>  esc_attr( 'new_pwd' ),

                    'pattern'           =>  "(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}",

                    'title'             =>  esc_attr__( "Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters", 'sdweddingdirectory' ),

                    'require'       =>      true,
                )

            ) );

            /**
             *  Password Field
             *  --------------
             */
            parent:: create_section( array(

                /**
                 *  Div Managment
                 *  -------------
                 */
                'div'     =>   array(

                    'start'       =>   false,

                    'end'         =>   true,
                ),

                /**
                 *  Row Managment
                 *  -------------
                 */
                'row'     =>   array(

                    'start'       =>   false,

                    'end'         =>   true,
                ),

                /**
                 *  Column Managment
                 *  ----------------
                 */
                'column'     =>   array(

                    'grid'        =>   absint( '6' ),

                    'id'          =>   '',

                    'class'       =>   '',

                    'start'       =>   true,

                    'end'         =>   true,
                ),

                /**
                 *  Fields Arguments
                 *  ----------------
                 */
                'field'    =>  array(

                    'field_type'        =>  esc_attr( 'password' ),

                    'type'              =>  esc_attr( 'password' ),

                    'lable'             =>  esc_attr__( 'Confirm Password', 'sdweddingdirectory' ),

                    'placeholder'       =>  esc_attr__( 'Confirm Password', 'sdweddingdirectory' ),

                    'id'                =>  esc_attr( 'confirm_pwd' ),

                    'name'              =>  esc_attr( 'confirm_pwd' ),

                    'pattern'           =>  "(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}",

                    'title'             =>  esc_attr__( "Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters", 'sdweddingdirectory' ),

                    'require'       =>      true,
                )

            ) );
        }
    }

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Vendor Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    SDWeddingDirectory_Vendor_Profile_Password_Change_Tab::get_instance();
}