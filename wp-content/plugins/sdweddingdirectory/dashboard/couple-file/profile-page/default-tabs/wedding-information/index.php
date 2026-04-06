<?php
/**
 *  -----------------------------------------------
 *  SDWeddingDirectory - Couple Profile Tab Fields - Object
 *  -----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Profile_Wedding_Information_Tab' ) && class_exists( 'SDWeddingDirectory_Couple_Profile_Database' ) ){

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Couple Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    class SDWeddingDirectory_Couple_Profile_Wedding_Information_Tab extends SDWeddingDirectory_Couple_Profile_Database{

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

            return      esc_attr__( 'Wedding Information', 'sdweddingdirectory' );
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
            add_filter( 'sdweddingdirectory/couple-profile/tabs', [ $this, 'tab_info' ], absint( '20' ), absint( '1' ) );
        }

        /**
         *  Tab Info
         *  --------
         */
        public static function tab_info( $args = [] ){

            return      array_merge( $args, [

                            'wedding-information'   =>  [

                                'id'                =>  esc_attr( parent:: _rand() ),

                                'name'              =>  esc_attr( self:: tab_name() ),

                                'callback'          =>  [ 'class' => __CLASS__, 'member' => 'tab_content' ],

                                'create_form'       =>  [

                                    'form_before'   =>  '',

                                    'form_after'    =>  '',

                                    'form_id'       =>  esc_attr( 'sdweddingdirectory_couple_wedding_info_account' ),

                                    'form_class'    =>  '',

                                    'button_before' =>  '',

                                    'button_after'  =>  '',

                                    'button_id'     =>  esc_attr( 'couple_wedding_info_submit' ),

                                    'button_class'  =>  parent:: _class( [ 'loader', 'sdweddingdirectory-submit'] ),

                                    'button_name'   =>  esc_attr__( 'Update Wedding Information','sdweddingdirectory' ),

                                    'security'      =>  esc_attr( 'wedding_information' ),
                                ]
                            ]

                        ] );
        }

        /**
         *  Content
         *  -------
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
             *  Wedding Date
             *  ------------
             */
            parent:: create_section( array(

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

                    'type'              =>  esc_attr( 'date' ),

                    'placeholder'       =>  sprintf( 'e.g : %1$s', date( get_option( 'date_format' ) ) ),

                    'lable'             =>  esc_attr__( 'Wedding Date', 'sdweddingdirectory' ),

                    'name'              =>  esc_attr( 'wedding_date' ),

                    'id'                =>  esc_attr( 'wedding_date' ),

                    'value'             =>  parent:: get_data( esc_attr( 'wedding_date' ) ),
                )

            ) );

            /**
             *  Wedding Address
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

                    'placeholder'       =>  esc_attr__( 'Wedding Location Address', 'sdweddingdirectory' ),

                    'lable'             =>  esc_attr__( 'Wedding Location Address', 'sdweddingdirectory' ),

                    'name'              =>  esc_attr( 'wedding_address' ),

                    'id'                =>  esc_attr( 'wedding_address' ),

                    'value'             =>  parent:: get_data( esc_attr( 'wedding_address' ) ),
                )

            ) );

            /**
             *  Bride First Name
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

                    'placeholder'       =>  esc_attr__( 'Bride First Name', 'sdweddingdirectory' ),

                    'lable'             =>  esc_attr__( 'Bride First Name', 'sdweddingdirectory' ),

                    'name'              =>  esc_attr( 'bride_first_name' ),

                    'id'                =>  esc_attr( 'bride_first_name' ),

                    'value'             =>  parent:: get_data( esc_attr( 'bride_first_name' ) ),
                )

            ) );

            /**
             *  Bride Last Name
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

                    'lable'             =>  esc_attr__( 'Bride Last Name', 'sdweddingdirectory' ),

                    'placeholder'       =>  esc_attr__( 'Bride Last Name', 'sdweddingdirectory' ),

                    'name'              =>  esc_attr( 'bride_last_name' ),

                    'id'                =>  esc_attr( 'bride_last_name' ),

                    'value'             =>  parent:: get_data( esc_attr( 'bride_last_name' ) ),
                )

            ) );

            /**
             *  Groom First Name
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

                    'lable'             =>  esc_attr__( 'Groom First Name', 'sdweddingdirectory' ),

                    'placeholder'       =>  esc_attr__( 'Groom First Name', 'sdweddingdirectory' ),

                    'name'              =>  esc_attr( 'groom_first_name' ),

                    'id'                =>  esc_attr( 'groom_first_name' ),

                    'value'             =>  parent:: get_data( esc_attr( 'groom_first_name' ) ),
                )

            ) );

            /**
             *  Groom Last Name
             *  ---------------
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

                    'field_type'        =>  esc_attr( 'input' ),

                    'type'              =>  esc_attr( 'text' ),

                    'lable'             =>  esc_attr__( 'Groom last Name', 'sdweddingdirectory' ),

                    'placeholder'       =>  esc_attr__( 'Groom last Name', 'sdweddingdirectory' ),

                    'name'              =>  esc_attr( 'groom_last_name' ),

                    'id'                =>  esc_attr( 'groom_last_name' ),

                    'value'             =>  parent:: get_data( esc_attr( 'groom_last_name' ) ),
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
    SDWeddingDirectory_Couple_Profile_Wedding_Information_Tab::get_instance();
}