<?php
/**
 *  ----------------------------------------------
 *  SDWeddingDirectory - Couple Real Wedding Tabs - Object
 *  ----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dashboard_Realwedding_Hire_Vendors' ) && class_exists( 'SDWeddingDirectory_Dashboard_Realwedding' ) ){

    /**
     *  ----------------------------------------------
     *  SDWeddingDirectory - Couple Real Wedding Tabs - Object
     *  ----------------------------------------------
     */
    class SDWeddingDirectory_Dashboard_Realwedding_Hire_Vendors extends SDWeddingDirectory_Dashboard_Realwedding{

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
            add_filter( 'sdweddingdirectory/couple-real-wedding/tabs', [ $this, 'tab' ], absint( '40' ), absint( '1' ) );
        }

        /**
         *  Tab Name
         *  --------
         */
        public static function tab_name(){

            return      esc_attr__( 'Venue Booked', 'sdweddingdirectory-real-wedding' );
        }

        /**
         *  Tab ID
         *  ------
         */
        public static function tab_id(){

            return      esc_attr( 'couple_realwedding_vendors' );
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

            /**
             *   Section Information
             *   -------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>    esc_attr( 'info' ),

                    'class'       =>    sanitize_html_class( 'mb-0' ),

                    'title'       =>    esc_attr__( 'Venue Booked', 'sdweddingdirectory-real-wedding' ),
                )

            ) );

            ?><div class="card-shadow-body"><?php

            /**
             *  Our Website Vendor Credits
             *  --------------------------
             */

            $_website_vendor_team   =   get_post_meta( 

                                            absint( parent:: realwedding_post_id() ),

                                            sanitize_key( 'our_website_vendor_credits' ),

                                            true 
                                        );
            /**
             *  Create Section
             *  --------------
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

                    'start'       =>   false,

                    'end'         =>   false,
                ),

                /**
                 *  Fields Arguments
                 *  ----------------
                 */
                'field'                     =>      array(

                    'field_type'            =>      esc_attr( 'select' ),

                    'id'                    =>      esc_attr( 'our_website_vendor_credits' ),

                    'name'                  =>      esc_attr( 'our_website_vendor_credits' ),

                    'options'               =>      SDWeddingDirectory_Taxonomy:: create_select_option(

                                                        apply_filters( 'sdweddingdirectory/post/data', [

                                                            'post_type'     =>      esc_attr( 'venue' )

                                                        ] ),

                                                        array( '' => esc_attr__( 'Select your wedding venue', 'sdweddingdirectory-real-wedding' ) ),

                                                        ! empty( $_website_vendor_team ) ? sanitize_text_field( $_website_vendor_team ) : ''

                                                    )
                )

            ) );

            ?></div><?php

        }

        public static function real_wedding_support_vendors(){

            $_write_cat         = esc_attr__( 'Which Category Vendor', 'sdweddingdirectory-real-wedding' );

            $_business_name     = esc_attr__( 'Vendor Business Name', 'sdweddingdirectory-real-wedding' );

            $_website           = esc_attr__( 'https://vendor-website.com/', 'sdweddingdirectory-real-wedding' );

            ?>
            <div class="vendor-input">
                <div id="add_new_vendor_section">
                <?php

                    $_vendor_credits = parent:: get_realwedding_data( 'out_side_vendor_credits' );

                    if( parent:: _is_array( $_vendor_credits ) ){

                        foreach ( $_vendor_credits as $key => $value) {
                            
                            printf( '<div class="input-wraps">
                                        <div class="d-flex">

                                            <div class="col">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <input autocomplete="off" type="text" class="form-control category" placeholder="%1$s" value="%2$s" />
                                                        </div>                                        
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <input autocomplete="off" type="text" class="form-control company" placeholder="%3$s" value="%4$s" />
                                                        </div>                                        
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <input autocomplete="off" type="text" class="form-control website" placeholder="%5$s" value="%6$s" />
                                                        </div>                                        
                                                    </div>
                                                </div>
                                            </div>           

                                            <div class="col-auto p-0">
                                                <span>
                                                    <a href="javascript:" class="remove_vendor_service action-links ps-3 py-3">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </span>
                                            </div>

                                        </div>
                                    </div>',

                                    // 1
                                    esc_attr__( 'Write Category', 'sdweddingdirectory-real-wedding' ),

                                    // 2
                                    esc_attr( $value[ 'category' ] ),

                                    // 3
                                    esc_attr__( 'Business Name', 'sdweddingdirectory-real-wedding' ),

                                    // 4
                                    esc_attr( $value[ 'company' ] ),

                                    // 5
                                    esc_attr__( 'https://example.com/', 'sdweddingdirectory-real-wedding' ),

                                    // 6
                                    esc_url( $value[ 'website' ] )
                            );
                        }

                    }

                ?>
                </div>

                <?php

                    printf(

                        '<div class="text-center">

                              <button class="btn btn-default btn-sm mx-3" 

                                data-category-placeholder="%1$s" 

                                data-business-name-placeholder="%2$s"

                                data-website-placeholder="%3$s"

                                id="add_new_vendor_service"><i class="fa fa-plus"></i> %4$s</button>

                        </div>',

                        /**
                         *  1. write categoty placeholder
                         *  -----------------------------
                         */
                        esc_attr( $_write_cat ),

                        /**
                         *  2. Business Name Placeholder
                         *  ----------------------------
                         */
                        esc_attr( $_business_name ),

                        /**
                         *  3. Website Input Placeholder
                         *  ----------------------------
                         */
                        esc_attr( $_website ),

                        /**
                         *  5. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr__( 'Add New Vendor', 'sdweddingdirectory-real-wedding' )
                    );

                ?>

            </div>

            <?php
        }
    }

    /**
     *  ----------------------------------------------
     *  SDWeddingDirectory - Couple Real Wedding Tabs - Object
     *  ----------------------------------------------
     */
    SDWeddingDirectory_Dashboard_Realwedding_Hire_Vendors::get_instance();
}