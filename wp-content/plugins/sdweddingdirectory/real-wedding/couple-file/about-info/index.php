<?php
/**
 *  ----------------------------------------------
 *  SDWeddingDirectory - Couple Real Wedding Tabs - Object
 *  ----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dashboard_Realwedding_Header_Info' ) && class_exists( 'SDWeddingDirectory_Dashboard_Realwedding' ) ){

    /**
     *  ----------------------------------------------
     *  SDWeddingDirectory - Couple Real Wedding Tabs - Object
     *  ----------------------------------------------
     */
    class SDWeddingDirectory_Dashboard_Realwedding_Header_Info extends SDWeddingDirectory_Dashboard_Realwedding{

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
            add_filter( 'sdweddingdirectory/couple-real-wedding/tabs', [ $this, 'tab' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Tab Name
         *  --------
         */
        public static function tab_name(){

            return      esc_attr__( 'About', 'sdweddingdirectory-real-wedding' );
        }

        /**
         *  Tab ID
         *  ------
         */
        public static function tab_id(){

            return      esc_attr( 'couple_real_wedding_header_info' );
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

                            'button_name'       =>      esc_attr__( 'Save Changes', 'sdweddingdirectory-real-wedding' ),

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

                                        'field_type'        =>      esc_attr( 'single_img_upload' ),

                                        'icon_layout'       =>      absint( '2' ),

                                        'image_class'       =>      sanitize_html_class( 'rounded-circle' ),

                                        'database_key'      =>      esc_attr( 'groom_image' ),

                                        'image_size'        =>      esc_attr( 'thumbnail' ),

                                        'is_ajax'           =>      true,

                                        'default_img'       =>      esc_url( parent:: placeholder( 'couple-groom-image' ) ),
                                    )

                                ) );
                              ?>
                            </div>

                            <div class="w-100">
                              <div class="col-12">
                                  <h3 class="mb-4"><?php esc_attr_e( 'Groom Info', 'sdweddingdirectory-real-wedding' ); ?></h3>
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

                                          'field_type'        =>        esc_attr( 'input' ),

                                          'type'              =>        esc_attr( 'text' ),

                                          'placeholder'       =>        esc_attr__( 'Groom First Name', 'sdweddingdirectory-real-wedding' ),

                                          'id'                =>        esc_attr( 'groom_first_name' ),

                                          'name'              =>        esc_attr( 'groom_first_name' ),

                                          'value'             =>        parent:: get_realwedding_data( sanitize_key( 'groom_first_name' ) ),
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

                                          'field_type'        =>        esc_attr( 'input' ),

                                          'type'              =>        esc_attr( 'text' ),

                                          'placeholder'       =>        esc_attr__( 'Groom Last Name', 'sdweddingdirectory-real-wedding' ),

                                          'id'                =>        esc_attr( 'groom_last_name' ),

                                          'name'              =>        esc_attr( 'groom_last_name' ),

                                          'value'             =>        parent:: get_realwedding_data( sanitize_key( 'groom_last_name' ) ),
                                      )

                                  ) );
                              ?>
                            </div>

                        </div>
                    </div>

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

                                          'field_type'          =>      esc_attr( 'single_img_upload' ),

                                          'icon_layout'         =>      absint( '2' ),

                                          'image_class'         =>      sanitize_html_class( 'rounded-circle' ),

                                          'database_key'        =>      esc_attr( 'bride_image' ),

                                          'image_size'          =>      esc_attr( 'thumbnail' ),

                                          'is_ajax'             =>      true,

                                          'default_img'         =>      esc_url( parent:: placeholder( 'couple-bride-image' ) ),
                                      )

                                  ) );

                              ?>
                            </div>

                            <div class="w-100">
                                <div class="col-12">
                                    <h3 class="mb-4"><?php esc_attr_e( 'Bride Info', 'sdweddingdirectory-real-wedding' ); ?></h3>
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

                                            'field_type'        =>      esc_attr( 'input' ),

                                            'type'              =>      esc_attr( 'text' ),

                                            'placeholder'       =>      esc_attr__( 'Bride First Name', 'sdweddingdirectory-real-wedding' ),

                                            'id'                =>      esc_attr( 'bride_first_name' ),

                                            'name'              =>      esc_attr( 'bride_first_name' ),

                                            'value'             =>      parent:: get_realwedding_data( sanitize_key( 'bride_first_name' ) ),
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

                                            'field_type'        =>      esc_attr( 'input' ),

                                            'type'              =>      esc_attr( 'text' ),

                                            'placeholder'       =>      esc_attr__( 'Bride Last Name', 'sdweddingdirectory-real-wedding' ),

                                            'id'                =>      esc_attr( 'bride_last_name' ),

                                            'name'              =>      esc_attr( 'bride_last_name' ),

                                            'value'             =>      parent:: get_realwedding_data( sanitize_key( 'bride_last_name' ) ),
                                        )

                                    ) );
                                ?>
                            </div>

                        </div>
                    </div>


                </div>

            </div>
            <?php

            /**
             *   Section Information
             *   -------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>  esc_attr( 'info' ),

                    'class'       =>  sanitize_html_class( 'mb-0' ),

                    'title'       =>  esc_attr__( 'Wedding Date', 'sdweddingdirectory-real-wedding' ),
                )

            ) );

            ?><div class="card-shadow-body"><?php

                /**
                 *  Real Wedding : Date
                 *  -------------------
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

                        'field_type'        =>      esc_attr( 'input' ),

                        'type'              =>      esc_attr( 'date' ),

                        'placeholder'       =>      sprintf( 'e.g : %1$s', date( get_option( 'date_format' ) ) ),

                        'name'              =>      esc_attr( 'wedding_date' ),

                        'id'                =>      esc_attr( 'wedding_date' ),

                        'value'             =>      parent:: get_realwedding_data( sanitize_key( 'wedding_date' ) ),
                    )

                ) );

            ?></div><?php


            /**
             *   Section Information
             *   -------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>  esc_attr( 'info' ),

                    'class'       =>  sanitize_html_class( 'mb-0' ),

                    'title'       =>  esc_attr__( 'Page Header Banner', 'sdweddingdirectory-real-wedding' ),
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
                'div'     =>   array(

                    'start'       =>   true,

                    'end'         =>   true,

                    'class'       =>   sanitize_html_class( 'card-shadow-body' )
                ),

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

                    'start'       =>   true,

                    'end'         =>   true,
                ),

                /**
                 *  Fields Arguments
                 *  ----------------
                 */
                'field'                     =>      array(

                    'field_type'            =>      esc_attr( 'single_img_upload' ),

                    'database_key'          =>      esc_attr( 'page_header_banner' ),

                    'post_id'               =>      absint( parent:: realwedding_post_id() ),

                    'is_ajax'               =>      true,

                    'class'                 =>      esc_attr( 'btn-default text-center' ),

                    'default_img'           =>      esc_url( parent:: placeholder( 'real-wedding-banner' ) ),

                    'input_class'           =>      sanitize_html_class( 'page-header-banner-image' )
                )

            ) );

            /**
             *   Section Information
             *   -------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>  esc_attr( 'info' ),

                    'class'       =>  sanitize_html_class( 'mb-0' ),

                    'title'       =>  esc_attr__( 'Your story', 'sdweddingdirectory-real-wedding' ),
                )

            ) );

            ?><div class="card-shadow-body mb-0 pb-0"><?php

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

                        'placeholder'       =>  esc_attr__( 'Tell your story by adding it here!', 'sdweddingdirectory-real-wedding' ),

                        'name'              =>  esc_attr( 'post_content' ),

                        'id'                =>  esc_attr( 'post_content' ),

                        'value'             =>  get_post_field( 'post_content', parent:: realwedding_post_id() ),
                    )

                ) );

            ?></div><?php



            /**
             *   Section Information
             *   -------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>  esc_attr( 'info' ),

                    'class'       =>  sanitize_html_class( 'mb-0' ),

                    'title'       =>  esc_attr__( 'Couple Image', 'sdweddingdirectory-real-wedding' ),
                )

            ) );

            ?><div class="card-shadow-body"><?php

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
                    ),

                    /**
                     *  Column Managment
                     *  ----------------
                     */
                    'column'     =>   array(

                        'grid'        =>   esc_attr( '4' ),

                        'start'       =>   true,

                        'end'         =>   true,
                    ),

                    /**
                     *  Fields Arguments
                     *  ----------------
                     */
                    'field'                     =>      array(

                        'field_type'            =>      esc_attr( 'single_img_upload' ),

                        'database_key'          =>      esc_attr( '_thumbnail_id' ),

                        'post_id'               =>      absint( parent:: realwedding_post_id() ),

                        'is_ajax'               =>      false,

                        'class'                 =>      esc_attr( 'btn-default text-center' ),

                        'default_img'           =>      esc_url( parent:: placeholder( 'real-wedding' ) ),

                        'input_class'           =>      sanitize_html_class( 'real-wedding-featured-image' )
                    )

                ) );

            ?></div><?php

            /**
             *   Section Information
             *   -------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>  esc_attr( 'info' ),

                    'class'       =>  sanitize_html_class( 'mb-0' ),

                    'title'       =>  esc_attr__( 'Upload Gallery Images', 'sdweddingdirectory-real-wedding' ),
                )

            ) );

            ?><div class="card-shadow-body"><?php

                /**
                 *   Upload Gallery Images
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
                    ),

                    /**
                     *  Column Managment
                     *  ----------------
                     */
                    'column'     =>   array(

                        'grid'        =>   absint( '12' ),

                        'start'       =>   true,

                        'end'         =>   true,

                        'class'       =>   sanitize_html_class( 'text-center' )
                    ),

                    /**
                     *  Fields Arguments
                     *  ----------------
                     */
                    'field'                     =>      array(

                        'field_type'            =>      esc_attr( 'gallery_img_upload' ),

                        'database_key'          =>      esc_attr( 'realwedding_gallery' ),

                        'image_size'            =>      esc_attr( 'thumbnail' ),

                        'min_limit'             =>      absint( '3' ),

                        'max_limit'             =>      absint( '6' ),

                        'is_ajax'               =>      false,

                        'btn_lable'             =>      sprintf( esc_attr__('%1$s Browse Image', 'sdweddingdirectory-real-wedding'),

                                                            '<i class="fa fa-cloud-upload me-2"></i>'
                                                        ),

                        'post_id'               =>      absint( parent:: realwedding_post_id() ),

                        'input_class'           =>      sanitize_html_class( 'real-wedding-gallery-images' )
                    )

                ) );

            ?></div><?php
        }
    }

    /**
     *  ----------------------------------------------
     *  SDWeddingDirectory - Couple Real Wedding Tabs - Object
     *  ----------------------------------------------
     */
    SDWeddingDirectory_Dashboard_Realwedding_Header_Info::get_instance();
}