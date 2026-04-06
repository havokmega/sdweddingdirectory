<?php
/**
 *  -----------------------------------------------
 *  SDWeddingDirectory - Vendor Profile Tab Fields - Object
 *  -----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Profile_Information_Tab' ) && class_exists( 'SDWeddingDirectory_Vendor_Profile_Database' ) ){

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Vendor Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Profile_Information_Tab extends SDWeddingDirectory_Vendor_Profile_Database{

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
            add_filter( 'sdweddingdirectory/vendor-profile/tabs', function( $args = [] ){

                return  array_merge( $args, [

                            'vendor-profile'        =>  [

                                'active'    =>  true,

                                'id'        =>  esc_attr( parent:: _rand() ),

                                'name'      =>  esc_attr( self:: tab_name() ),

                                'callback'  =>  [ 'class' => __CLASS__, 'member' => 'tab_content' ],

                                'create_form'      =>  [

                                    'form_before'   =>  '',

                                    'form_after'    =>  '',

                                    'form_id'       =>  esc_attr( 'sdweddingdirectory_vendor_profile_update' ),

                                    'form_class'    =>  '',

                                    'button_before' =>  '',

                                    'button_after'  =>  self:: view_business_page_button(),

                                    'button_id'     =>  esc_attr( 'profile_update_btn' ),

                                    'button_class'  =>  '',

                                    'button_name'   =>  esc_attr__( 'Update Profile','sdweddingdirectory' ),

                                    'security'      =>  esc_attr( 'vendor_profile_update' ),
                                ]
                            ]

                        ] );

            }, absint( '10' ) );
        }

        /**
         *  View Business Page Button
         *  -------------------------
         */
        public static function view_business_page_button(){

            $post_id = absint( parent:: post_id() );

            if( $post_id > 0 ){

                $permalink = get_permalink( $post_id );

                if( ! empty( $permalink ) ){

                    return sprintf(
                        '<a href="%1$s" target="_blank" class="btn btn-outline-dark btn-sm rounded-pill ms-2">%2$s</a>',
                        esc_url( $permalink ),
                        esc_html__( 'View Business Page', 'sdweddingdirectory' )
                    );
                }
            }

            return '';
        }

        /**
         *.  Tab Content
         *   -----------
         */
        public static function tab_content(){

            $post_id = absint( parent:: post_id() );

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
             *  First Name
             *  ----------
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

                    'class'       =>   '',
                ),


                /**
                 *  Column Managment
                 *  ----------------
                 */
                'column'     =>   array(

                    'grid'        =>   absint( '6' ),

                    'id'          =>   '',

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

                    'lable'             =>  esc_attr__( 'Email', 'sdweddingdirectory' ),

                    'placeholder'       =>  esc_attr__( 'johndea@example.com', 'sdweddingdirectory' ),

                    'id'                =>  esc_attr( 'user_email' ),

                    'name'              =>  esc_attr( 'user_email' ),

                    'value'             =>  parent:: get_data( esc_attr( 'user_email' ) ),

                    'disable'           =>  true
                )

            ) );

            ?><div class="col-md-12">
                <div class="form-group">
                    <label><?php esc_html_e( 'Profile URL Slug', 'sdweddingdirectory' ); ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><?php echo esc_url( home_url( '/vendor/' ) ); ?></span>
                        <input type="text" class="form-control" name="sdweddingdirectory_vendor_slug" id="sdweddingdirectory_vendor_slug" value="<?php echo esc_attr( get_post_field( 'post_name', absint( $post_id ) ) ); ?>" />
                    </div>
                    <small class="form-text text-muted"><?php esc_html_e( 'This controls your public profile URL. Use lowercase letters, numbers, and hyphens only.', 'sdweddingdirectory' ); ?></small>
                </div>
            </div><?php

            /**
             *  Contact Number
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

                    'class'       =>   '',
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

                    'field_type'        =>  esc_attr( 'input' ),

                    'type'              =>  esc_attr( 'tel' ),

                    'lable'             =>  esc_attr__( 'Contact Number', 'sdweddingdirectory' ),

                    'placeholder'       =>  esc_attr__( 'Write Phone Number', 'sdweddingdirectory' ),

                    'id'                =>  esc_attr( 'user_contact' ),

                    'name'              =>  esc_attr( 'user_contact' ),

                    'value'             =>  parent:: get_data( esc_attr( 'user_contact' ) ),
                )

            ) );
        }
    }

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Vendor Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    SDWeddingDirectory_Vendor_Profile_Information_Tab::get_instance();
}
