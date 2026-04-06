<?php
/**
 *  -------------------------------------------------
 *  SDWeddingDirectory - Couple Wedding Website Tabs - Object
 *  -------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Dashboard_Website_About_Us' ) && class_exists( 'SDWeddingDirectory_Couple_Dashboard_Website' ) ){

    /**
     *  -------------------------------------------------
     *  SDWeddingDirectory - Couple Wedding Website Tabs - Object
     *  -------------------------------------------------
     */
    class SDWeddingDirectory_Couple_Dashboard_Website_About_Us extends SDWeddingDirectory_Couple_Dashboard_Website{

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
            add_filter( 'sdweddingdirectory/couple-wedding-website/tabs', [ $this, 'tab' ], absint( '20' ), absint( '1' ) );
        }

        /**
         *  Tab Name
         *  --------
         */
        public static function tab_name(){

            return      esc_attr__( 'About Us', 'sdweddingdirectory-couple-website' );
        }

        /**
         *  Tab ID
         *  ------
         */
        public static function tab_id(){

            return      esc_attr( 'couple_wedding_website_about_us' );
        }

        /**
         *  Tab
         *  ---
         */
        public static function tab( $args = [] ){

            /**
             *  Tabs
             *  ----
             */
            return  array_merge( $args, [  self:: tab_id()  =>  [

                        'active'            =>      false,

                        'id'                =>      esc_attr( parent:: _rand() ),

                        'name'              =>      self:: tab_name(),

                        'callback'          =>      [ 'class' => __CLASS__, 'member' => 'tab_content' ],

                        'create_form'       =>      [

                            'form_before'       =>      '',

                            'form_after'        =>      '',

                            'form_id'           =>      self:: tab_id(),

                            'form_class'        =>      '',

                            'button_before'     =>      '',

                            'button_after'      =>      '',

                            'button_id'         =>      self:: tab_id() . '-button',

                            'button_class'      =>      '',

                            'button_name'       =>      esc_attr__( 'Save Changes', 'sdweddingdirectory-couple-website' ),

                            'security'          =>      self:: tab_id() . '-security',
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

                        'field_type'  =>  esc_attr( 'info' ),

                        'class'       =>  sanitize_html_class( 'mb-0' ),

                        'title'       =>  esc_attr__( 'About Us', 'sdweddingdirectory-couple-website' ),
                    )

                ) );

                /**
                 *  Heading
                 *  -------
                 */
                parent:: create_section( array(

                    /**
                     *  Row Managment
                     *  -------------
                     */
                    'div'     =>   array(

                        'start'     =>  true,

                        'end'       =>  false,

                        'class'     =>  esc_attr( 'card-shadow-body pb-2' )
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

                        'field_type'        =>  esc_attr( 'input' ),

                        'type'              =>  esc_attr( 'input' ),

                        'placeholder'       =>  esc_attr__( 'The Couple', 'sdweddingdirectory-couple-website' ),

                        'name'              =>  esc_attr( 'couple_info_heading' ),

                        'id'                =>  esc_attr( 'couple_info_heading' ),

                        'value'             =>  parent:: website_post_data( sanitize_key( 'couple_info_heading' ) ),
                    )

                ) );

                /**
                 *  Heading
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

                        'field_type'        =>  esc_attr( 'textarea-simple' ),

                        'type'              =>  esc_attr( 'input' ),

                        'placeholder'       =>  esc_attr__( 'Write Description', 'sdweddingdirectory-couple-website' ),

                        'name'              =>  esc_attr( 'couple_info_description' ),

                        'id'                =>  esc_attr( 'couple_info_description' ),

                        'rows'              =>  absint( '3' ),

                        'value'             =>  parent:: website_post_data( sanitize_key( 'couple_info_description' ) ),
                    )

                ) );

                ?>
                <div class="row">

                    <div class="col-md-4">
                        
                        <div class="row">
                        <?php

                            /**
                             *  Heading
                             *  -------
                             */
                            parent:: create_section( array(

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

                                    'field_type'        =>  esc_attr( 'textarea-simple' ),

                                    'type'              =>  esc_attr( 'input' ),

                                    'placeholder'       =>  esc_attr__( 'About Groom', 'sdweddingdirectory-couple-website' ),

                                    'name'              =>  esc_attr( 'about_groom' ),

                                    'id'                =>  esc_attr( 'about_groom' ),

                                    'rows'              =>  absint( '3' ),

                                    'value'             =>  parent:: website_post_data( sanitize_key( 'about_groom' ) ),
                                )

                            ) );

                            $_social    =   array(

                                'groom_instagram'     =>  esc_attr__( 'Groom Instagram', 'sdweddingdirectory-couple-website' ),

                                'groom_facebook'      =>  esc_attr__( 'Groom Facebook', 'sdweddingdirectory-couple-website' ),

                                'groom_twitter'       =>  esc_attr__( 'Groom Twitter', 'sdweddingdirectory-couple-website' ),
                            );

                            /**
                             *  Have Data ?
                             *  -----------
                             */
                            if( parent:: _is_array( $_social ) ){

                                foreach( $_social as $key => $value ){

                                    /**
                                     *  Social
                                     *  ------
                                     */
                                    parent:: create_section( array(

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

                                            'type'              =>  esc_attr( 'url' ),

                                            'placeholder'       =>  esc_attr( $value ),

                                            'name'              =>  esc_attr( $key ),

                                            'id'                =>  esc_attr( $key ),

                                            'value'             =>  parent:: website_post_data( sanitize_key( $key ) ),
                                        )

                                    ) );

                                }
                            }

                        ?>
                        </div>

                    </div>

                    <div class="col-md-4">
                        
                        <div class="row">
                        <?php

                            /**
                             *  Heading
                             *  -------
                             */
                            parent:: create_section( array(

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

                                    'field_type'    =>  esc_attr( 'single_img_upload' ),

                                    'database_key'  =>  esc_attr( '_thumbnail_id' ),

                                    'post_id'       =>  absint( parent:: website_post_id() ),

                                    'is_ajax'       =>  true,

                                    'class'         =>  esc_attr( 'btn-default text-center' ),

                                    'default_img'   =>  esc_url( parent:: placeholder( 'web-layout-1-couple-frame' ) ),

                                    'image_size'    =>  esc_attr( 'sdweddingdirectory_img_600x600' )
                                )

                            ) );

                        ?>
                        </div>

                    </div>

                    <div class="col-md-4">
                        
                        <div class="row">
                        <?php

                            /**
                             *  Heading
                             *  -------
                             */
                            parent:: create_section( array(

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

                                    'field_type'        =>  esc_attr( 'textarea-simple' ),

                                    'type'              =>  esc_attr( 'input' ),

                                    'placeholder'       =>  esc_attr__( 'About Bride', 'sdweddingdirectory-couple-website' ),

                                    'name'              =>  esc_attr( 'about_bride' ),

                                    'id'                =>  esc_attr( 'about_bride' ),

                                    'rows'              =>  absint( '3' ),

                                    'value'             =>  parent:: website_post_data( sanitize_key( 'about_bride' ) ),
                                )

                            ) );

                            $_social    =   array(

                                'bride_instagram'     =>  esc_attr__( 'Bride Instagram', 'sdweddingdirectory-couple-website' ),

                                'bride_facebook'      =>  esc_attr__( 'Bride Facebook', 'sdweddingdirectory-couple-website' ),

                                'bride_twitter'       =>  esc_attr__( 'Bride Twitter', 'sdweddingdirectory-couple-website' ),
                            );

                            /**
                             *  Have Data ?
                             *  -----------
                             */
                            if( parent:: _is_array( $_social ) ){

                                foreach( $_social as $key => $value ){

                                    /**
                                     *  Social
                                     *  ------
                                     */
                                    parent:: create_section( array(

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

                                            'type'              =>  esc_attr( 'url' ),

                                            'placeholder'       =>  esc_attr( $value ),

                                            'name'              =>  esc_attr( $key ),

                                            'id'                =>  esc_attr( $key ),

                                            'value'             =>  parent:: website_post_data( sanitize_key( $key ) ),
                                        )

                                    ) );

                                }
                            }


                        ?>
                        </div>

                    </div>

                </div>
                <?php

                parent:: create_section( array(

                    /**
                     *  Row Managment
                     *  -------------
                     */
                    'div'     =>   array(

                        'start'     =>  false,

                        'end'       =>  true,
                    ),

                ) );

        }
    }

    /**
     *  -------------------------------------------------
     *  SDWeddingDirectory - Couple Wedding Website Tabs - Object
     *  -------------------------------------------------
     */
    SDWeddingDirectory_Couple_Dashboard_Website_About_Us::get_instance();
}