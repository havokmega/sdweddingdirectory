<?php
/**
 *  -------------------------------------------------
 *  SDWeddingDirectory - Couple Wedding Website Tabs - Object
 *  -------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Dashboard_Website_Groom_Bride_Info' ) && class_exists( 'SDWeddingDirectory_Couple_Dashboard_Website' ) ){

    /**
     *  -------------------------------------------------
     *  SDWeddingDirectory - Couple Wedding Website Tabs - Object
     *  -------------------------------------------------
     */
    class SDWeddingDirectory_Couple_Dashboard_Website_Groom_Bride_Info extends SDWeddingDirectory_Couple_Dashboard_Website{

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

            if( null == self::$instance ) {

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
            add_filter( 'sdweddingdirectory/couple-wedding-website/tabs', [ $this, 'tab' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Tab Name
         *  --------
         */
        public static function tab_name(){

            return      esc_attr__( 'Header', 'sdweddingdirectory-couple-website' );
        }

        /**
         *  Tab ID
         *  ------
         */
        public static function tab_id(){

            return      esc_attr( 'couple_wedding_website_header' );
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

                        'active'            =>      true,

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

            ?>
            <div class="card-shadow-body">
            
                <div class="row">

                    <!-- col-md-6 -->
                    <div class="col-md-6 border-right no-mobile">
                    <div class="d-flex">

                        <div class="me-4">
                          <?php

                            /**
                             *  Groom Profile Upload
                             *  --------------------
                             */
                            parent:: create_section( array(

                                /**
                                 *  Fields Arguments
                                 *  ----------------
                                 */
                                'field'    =>  array(

                                    'field_type'            =>      esc_attr( 'single_img_upload' ),

                                    'icon_layout'           =>      absint( '2' ),

                                    'image_class'           =>      sanitize_html_class( 'rounded-circle' ),

                                    'database_key'          =>      esc_attr( 'groom_image' ),

                                    'image_size'            =>      esc_attr( 'thumbnail' ),

                                    'post_id'               =>      absint( parent:: website_post_id() ),

                                    'is_ajax'               =>      true,

                                    'default_img'           =>      esc_url( parent:: placeholder( 'couple-groom-image' ) ),
                                )

                            ) );
                          ?>
                        </div>

                        <div class="w-100">
                          <div class="col-12">
                              <h3 class="mb-4"><?php esc_attr_e( 'Groom Info', 'sdweddingdirectory-couple-website' ); ?></h3>
                          </div>
                          <?php

                              /**
                               *  Groom First Name
                               *  ----------------
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

                                      'type'              =>  esc_attr( 'text' ),

                                      'placeholder'       =>  esc_attr__( 'Groom First Name', 'sdweddingdirectory-couple-website' ),

                                      'id'                =>  esc_attr( 'groom_first_name' ),

                                      'name'              =>  esc_attr( 'groom_first_name' ),

                                      'value'             =>  parent:: website_post_data( sanitize_key( 'groom_first_name' ) ),
                                  )

                              ) );


                              /**
                               *  Groom Last Name
                               *  ----------------
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

                                      'type'              =>  esc_attr( 'text' ),

                                      'placeholder'       =>  esc_attr__( 'Groom Last Name', 'sdweddingdirectory-couple-website' ),

                                      'id'                =>  esc_attr( 'groom_last_name' ),

                                      'name'              =>  esc_attr( 'groom_last_name' ),

                                      'value'             =>  parent:: website_post_data( sanitize_key( 'groom_last_name' ) ),
                                  )

                              ) );
                          ?>
                        </div>

                    </div>
                    </div>
                    <!-- / col-md-6 -->

                    <!-- col-md-6 -->
                    <div class="col-md-6 mt-4 mt-md-0">
                    <div class="d-flex">

                        <div class="me-4">
                          <?php

                           /**
                            *  Groom Profile Upload
                            *  --------------------
                            */
                            parent:: create_section( array(

                                /**
                                 *  Fields Arguments
                                 *  ----------------
                                 */
                                'field'    =>  array(

                                    'field_type'            =>      esc_attr( 'single_img_upload' ),

                                    'icon_layout'           =>      absint( '2' ),

                                    'image_class'           =>      sanitize_html_class( 'rounded-circle' ),

                                    'database_key'          =>      esc_attr( 'bride_image' ),

                                    'image_size'            =>      esc_attr( 'thumbnail' ),

                                    'post_id'               =>      absint( parent:: website_post_id() ),

                                    'is_ajax'               =>      true,

                                    'default_img'           =>      esc_url( parent:: placeholder( 'couple-bride-image' ) ),
                                )

                            ) );

                          ?>
                        </div>

                        <div class="w-100">
                            <div class="col-12">
                                <h3 class="mb-4"><?php esc_attr_e( 'Bride Info', 'sdweddingdirectory-couple-website' ); ?></h3>
                            </div>
                            <?php

                                /**
                                 *  Groom First Name
                                 *  ----------------
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

                                        'type'              =>  esc_attr( 'text' ),

                                        'placeholder'       =>  esc_attr__( 'Bride First Name', 'sdweddingdirectory-couple-website' ),

                                        'id'                =>  esc_attr( 'bride_first_name' ),

                                        'name'              =>  esc_attr( 'bride_first_name' ),

                                        'value'             =>  parent:: website_post_data( sanitize_key( 'bride_first_name' ) ),
                                    )

                                ) );

                                /**
                                 *  Groom Last Name
                                 *  ----------------
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

                                        'type'              =>  esc_attr( 'text' ),

                                        'placeholder'       =>  esc_attr__( 'Bride Last Name', 'sdweddingdirectory-couple-website' ),

                                        'id'                =>  esc_attr( 'bride_last_name' ),

                                        'name'              =>  esc_attr( 'bride_last_name' ),

                                        'value'             =>  parent:: website_post_data( sanitize_key( 'bride_last_name' ) ),
                                    )

                                ) );
                            ?>
                        </div>

                    </div>
                    </div>
                    <!-- / col-md-6 -->

                </div>

            </div>
            <?php


            /**
             *   Section Information
             *   -------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>    esc_attr( 'info' ),

                    'class'       =>    sanitize_html_class( 'mb-0' ),

                    'title'       =>    esc_attr__( 'Header Image', 'sdweddingdirectory-couple-website' ),
                )

            ) );

            /**
             *   Upload Featured Image
             *  ----------------------
             */
            parent:: create_section( array(

                /**
                 *  Row Managment
                 *  -------------
                 */
                'row'     =>   array(

                    'start'       =>   true,

                    'end'         =>   true,

                    'class'       =>   sanitize_html_class( 'card-shadow-body' )
                ),

                /**
                 *  Column Managment
                 *  ----------------
                 */
                'column'     =>   array(

                    'grid'        =>   absint( '12' ),

                    'start'       =>   true,

                    'end'         =>   true,
                ),

                /**
                 *  Fields Arguments
                 *  ----------------
                 */
                'field'    =>  array(

                    'field_type'    =>  esc_attr( 'single_img_upload' ),

                    'database_key'  =>  esc_attr( 'header_image' ),

                    'image_size'    =>  esc_attr( 'sdweddingdirectory_img_1920x700' ),

                    'post_id'       =>  absint( parent:: website_post_id() ),

                    'is_ajax'       =>  true,

                    'class'         =>  esc_attr( 'btn-default text-center' ),

                    'default_img'   =>  esc_url( parent:: placeholder( 'web-layout-1-slider' ) ),
                )

            ) );
        }
    }

    /**
     *  -------------------------------------------------
     *  SDWeddingDirectory - Couple Wedding Website Tabs - Object
     *  -------------------------------------------------
     */
    SDWeddingDirectory_Couple_Dashboard_Website_Groom_Bride_Info::get_instance();
}