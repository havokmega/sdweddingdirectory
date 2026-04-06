<?php
/**
 *  -----------------------------------------------
 *  SDWeddingDirectory - Couple Profile Tab Fields - Object
 *  -----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Profile_PassWord_Change_Tab' ) && class_exists( 'SDWeddingDirectory_Couple_Profile_Database' ) ){

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Couple Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    class SDWeddingDirectory_Couple_Profile_PassWord_Change_Tab extends SDWeddingDirectory_Couple_Profile_Database{

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
            add_filter( 'sdweddingdirectory/couple-profile/tabs', [ $this, 'tab_info' ], absint( '30' ), absint( '1' ) );
        }

        /**
         *  Tab Info
         *  --------
         */
        public static function tab_info( $args = [] ){

            return  array_merge( $args, [

                        'password-change'   =>  [

                            'id'        =>  esc_attr( parent:: _rand() ),

                            'name'      =>  esc_attr( self:: tab_name() ),

                            'callback'  =>  [ 'class' => __CLASS__, 'member' => 'tab_content' ],

                            'create_form'      =>  [

                                'form_before'   =>  '',

                                'form_after'    =>  '',

                                'form_id'       =>  esc_attr( 'user_password_change' ),

                                'form_class'    =>  '',

                                'button_before' =>  '',

                                'button_after'  =>  '',

                                'button_id'     =>  esc_attr( 'couple_user_password_change_btn' ),

                                'button_class'  =>  parent:: _class( [ 'loader', 'sdweddingdirectory-submit'] ),

                                'button_name'   =>  esc_attr__( 'Change Password','sdweddingdirectory' ),

                                'security'      =>  esc_attr( 'change_password_security' ),
                            ]
                        ]

                    ] );
        }

        /**
         *  Password Change
         *  ----------------
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
                 *  Row Managment
                 *  -------------
                 */
                'row'     =>   array(

                    'start'       =>   true,

                    'end'         =>   false,

                    'class'       =>   esc_attr( 'px-3 pt-4 pb-2' )
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

                    'require'           =>  true,
                )

            ) );

            /**
             *  Password Field
             *  --------------
             */
            parent:: create_section( array(

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

                    'require'           =>  true,
                )

            ) );
        }
    }

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Couple Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    SDWeddingDirectory_Couple_Profile_PassWord_Change_Tab::get_instance();
}