<?php
/**
 *  -----------------------------------------------
 *  SDWeddingDirectory - Vendor Profile Tab Fields - Object
 *  -----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Profile_Business_Information_Tab' ) && class_exists( 'SDWeddingDirectory_Vendor_Profile_Database' ) ){

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Vendor Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Profile_Business_Information_Tab extends SDWeddingDirectory_Vendor_Profile_Database{

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
             *  Add Filter
             *  ----------
             */
            add_filter( 'sdweddingdirectory/vendor-profile/tabs', [ $this, 'get_tabs' ], absint( '20' ), absint( '1' ) );
        }

        /**
         *  Tab Name
         *  --------
         */
        public static function tab_name(){

            return      esc_attr__( 'Business Profile', 'sdweddingdirectory' );
        }

        /**
         *  Get Tabs
         *  --------
         */
        public static function get_tabs( $args = [] ){

            return  array_merge( $args, [

                        'business-info'        =>  [

                            'id'        =>  esc_attr( parent:: _rand() ),

                            'name'      =>  esc_attr( self:: tab_name() ),

                            'callback'  =>  [ 'class' => __CLASS__, 'member' => 'tab_content' ],

                            'create_form'      =>  [

                                'form_before'   =>  '',

                                'form_after'    =>  '',

                                'form_id'       =>  esc_attr( 'sdweddingdirectory_vendor_business_profile' ),

                                'form_class'    =>  '',

                                'button_before' =>  '',

                                'button_after'  =>  '',

                                'button_id'     =>  esc_attr( 'vendor_business_profile_submit' ),

                                'button_class'  =>  '',

                                'button_name'   =>  esc_attr__( 'Update Business Profile','sdweddingdirectory' ),

                                'security'      =>  esc_attr( 'vendor_business_profile' ),
                            ]
                        ]

                    ] );
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
             *  Business Name
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

                    'class'       =>   esc_attr( 'card-body' ),
                ),

                /**
                 *  Row Managment
                 *  -------------
                 */
                'row'     =>   array(

                    'start'       =>   true,

                    'end'         =>   false,

                    'class'       =>   ''
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

                    'lable'             =>  esc_attr__( 'Business name', 'sdweddingdirectory' ),

                    'placeholder'       =>  esc_attr__( 'Business name', 'sdweddingdirectory' ),

                    'id'                =>  esc_attr( 'company_name' ),

                    'name'              =>  esc_attr( 'company_name' ),

                    'value'             =>  parent:: get_data( esc_attr( 'company_name' ) ),

                )

            ) );

            /**
             *  Business Website
             *  ----------------
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

                    'type'              =>  esc_attr( 'url' ),

                    'lable'             =>  esc_attr__( 'Business Website', 'sdweddingdirectory' ),

                    'placeholder'       =>  esc_attr__( 'Business Website', 'sdweddingdirectory' ),

                    'id'                =>  esc_attr( 'company_website' ),

                    'name'              =>  esc_attr( 'company_website' ),

                    'value'             =>  parent:: get_data( esc_attr( 'company_website' ) ),
                )

            ) );

            /**
             *  Bussiness Email
             *  ---------------
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

                    'lable'             =>  esc_attr__( 'Business Email', 'sdweddingdirectory' ),

                    'placeholder'       =>  esc_attr__( 'Business Email', 'sdweddingdirectory' ),

                    'id'                =>  esc_attr( 'company_email' ),

                    'name'              =>  esc_attr( 'company_email' ),

                    'value'             =>  parent:: get_data( esc_attr( 'company_email' ) ),
                ),

            ) );

            /**
             *  Bussiness Contact Info
             *  ----------------------
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

                    'lable'             =>  esc_attr__( 'Business Phone Number', 'sdweddingdirectory' ),

                    'placeholder'       =>  esc_attr__( 'Business Phone Number', 'sdweddingdirectory' ),

                    'id'                =>  esc_attr( 'company_contact' ),

                    'name'              =>  esc_attr( 'company_contact' ),

                    'value'             =>  parent:: get_data( esc_attr( 'company_contact' ) ),
                ),

            ) );

            /**
             *  Write Description ( Editor )
             *  ----------------------------
             */
            parent:: create_section( array(

                /**
                 *  Div Managment
                 *  -------------
                 */
                'div'     =>   array(

                    'start'       =>   false,

                    'end'         =>   true,

                    'class'       =>   '',
                ),

                /**
                 *  Row Managment
                 *  -------------
                 */
                'row'       =>  array(

                    'start'     =>   false,

                    'end'       =>   true,
                ),

                /**
                 *  Column Managment
                 *  ----------------
                 */
                'column'     =>   array(

                    'grid'        =>   absint( '12' ),

                    'id'          =>   '',

                    'class'       =>   'mt-3',

                    'start'       =>   true,

                    'end'         =>   true,
                ),

                /**
                 *  Fields Arguments
                 *  ----------------
                 */
                'field'    =>  array(

                    'formgroup'         => false,

                    'field_type'        =>  esc_attr( 'textarea' ),

                    'type'              =>  esc_attr( 'textarea' ),

                    'lable'             =>  esc_attr__( 'About Business', 'sdweddingdirectory' ),

                    'placeholder'       =>  esc_attr__( 'About Business', 'sdweddingdirectory' ),

                    'name'              =>  esc_attr( 'post_content' ),

                    'id'                =>  esc_attr( 'post_content' ),

                    'height'            =>  absint( '300' ),

                    'length'            =>  absint( '3000' ),

                    'value'             =>  get_post_field( 'post_content', parent:: post_id() )
                )

            ) );
        }
    }

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Vendor Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    SDWeddingDirectory_Vendor_Profile_Business_Information_Tab::get_instance();
}
