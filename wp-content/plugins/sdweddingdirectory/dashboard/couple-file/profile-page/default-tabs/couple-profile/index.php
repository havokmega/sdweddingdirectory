<?php
/**
 *  -----------------------------------------------
 *  SDWeddingDirectory - Couple Profile Tab Fields - Object
 *  -----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Profile_Information_Tab' ) && class_exists( 'SDWeddingDirectory_Couple_Profile_Database' ) ){

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Couple Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    class SDWeddingDirectory_Couple_Profile_Information_Tab extends SDWeddingDirectory_Couple_Profile_Database{

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

            return      esc_attr__( 'My Profile', 'sdweddingdirectory' );
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
            add_filter( 'sdweddingdirectory/couple-profile/tabs', [ $this, 'tab_info' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Tab Info
         *  --------
         */
        public static function tab_info( $args = [] ){

            return  array_merge( $args, [

                        'my-profile'            =>  [

                            'active'            =>  true,

                            'id'                =>  esc_attr( parent:: _rand() ),

                            'name'              =>  esc_attr( self:: tab_name() ),

                            'callback'          =>  [ 'class' => __CLASS__, 'member' => 'tab_content' ],

                            'create_form'       =>  [

                                'form_before'   =>  '',

                                'form_after'    =>  '',

                                'form_id'       =>  esc_attr( 'sdweddingdirectory_couple_profile_update' ),

                                'form_class'    =>  '',

                                'button_before' =>  '',

                                'button_after'  =>  '',

                                'button_id'     =>  esc_attr( 'profile_update_btn' ),

                                'button_class'  =>  parent:: _class( [ 'loader', 'sdweddingdirectory-submit'] ),

                                'button_name'   =>  esc_attr__( 'Update Profile','sdweddingdirectory' ),

                                'security'      =>  esc_attr( 'profile_update' ),
                            ]
                        ]

                    ] );
        }

        /**
         *  Profile Update
         *  --------------
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

            print '<div class="card-shadow-body">';

            /**
             *  First Name
             *  ----------
             */
            parent:: create_section( array(

                /**
                 *  Row Managment
                 *  -------------
                 */
                'row'               =>      array(

                    'start'         =>      true,

                    'end'           =>      false,
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

                    'field_type'        =>  esc_attr( 'input' ),

                    'type'              =>  esc_attr( 'text' ),

                    'lable'             =>  esc_attr__( 'First Name', 'sdweddingdirectory' ),

                    'placeholder'       =>  esc_attr__( 'First Name', 'sdweddingdirectory' ),

                    'id'                =>  esc_attr( 'first_name' ),

                    'name'              =>  esc_attr( 'first_name' ),

                    'value'             =>  parent:: get_data( esc_attr( 'first_name' ) ),
                )

            ) );

            /**
             *  Last Name
             *  ---------
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

                    'field_type'        =>  esc_attr( 'input' ),

                    'type'              =>  esc_attr( 'text' ),

                    'lable'             =>  esc_attr__( 'Last Name', 'sdweddingdirectory' ),

                    'placeholder'       =>  esc_attr__( 'Last Name', 'sdweddingdirectory' ),

                    'id'                =>  esc_attr( 'last_name' ),

                    'name'              =>  esc_attr( 'last_name' ),

                    'value'             =>  parent:: get_data( esc_attr( 'last_name' ) ),
                )

            ) );

            /**
             *  Email ID
             *  --------
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

                    'field_type'        =>  esc_attr( 'input' ),

                    'type'              =>  esc_attr( 'email' ),

                    'lable'             =>  esc_attr__( 'Email ID', 'sdweddingdirectory' ),

                    'placeholder'       =>  esc_attr__( 'johndea@example.com', 'sdweddingdirectory' ),

                    'id'                =>  esc_attr( 'user_email' ),

                    'name'              =>  esc_attr( 'user_email' ),

                    'value'             =>  parent:: get_data( esc_attr( 'user_email' ) ),

                    'disable'           =>  true
                )

            ) );

            /**
             *  Contact Number
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

                    'field_type'        =>  esc_attr( 'input' ),

                    'type'              =>  esc_attr( 'tel' ),

                    'lable'             =>  esc_attr__( 'Contact Number', 'sdweddingdirectory' ),

                    'placeholder'       =>  esc_attr__( 'Write Phone Number', 'sdweddingdirectory' ),

                    'id'                =>  esc_attr( 'user_contact' ),

                    'name'              =>  esc_attr( 'user_contact' ),

                    'value'             =>  parent:: get_data( esc_attr( 'user_contact' ) ),
                )

            ) );

            /**
             *  Address
             *  -------
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

                    'field_type'        =>  esc_attr( 'input' ),

                    'type'              =>  esc_attr( 'text' ),

                    'lable'             =>  esc_attr__( 'Address', 'sdweddingdirectory' ),

                    'placeholder'       =>  esc_attr__( 'Private Address', 'sdweddingdirectory' ),

                    'id'                =>  esc_attr( 'user_address' ),

                    'name'              =>  esc_attr( 'user_address' ),

                    'value'             =>  parent:: get_data( esc_attr( 'user_address' ) ),
                )

            ) );

            /**
             *  Write Description ( Editor )
             *  ----------------------------
             */
            parent:: create_section( array(

               /**
                 *  Row Managment
                 *  -------------
                 */
                'row'     =>   array(

                    'start'       =>   true,

                    'end'         =>   true,
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

                    'field_type'        =>  esc_attr( 'textarea' ),

                    'formgroup'         =>  false,

                    'type'              =>  esc_attr( 'textarea' ),

                    'lable'             =>  esc_attr__( 'About Us', 'sdweddingdirectory' ),

                    'placeholder'       =>  esc_attr__( 'About Us', 'sdweddingdirectory' ),

                    'name'              =>  esc_attr( 'post_content' ),

                    'id'                =>  esc_attr( 'post_content' ),

                    'height'            =>  absint( '300' ),

                    'length'            =>  absint( '3000' ),

                    'value'             =>  get_post_field( 'post_content', parent:: post_id() )
                )

            ) );

            print '</div>';
        }
    }

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Couple Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    SDWeddingDirectory_Couple_Profile_Information_Tab::get_instance();
}