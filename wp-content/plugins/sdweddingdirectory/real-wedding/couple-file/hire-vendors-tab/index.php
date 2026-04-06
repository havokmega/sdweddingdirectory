<?php
/**
 *  SDWeddingDirectory - Couple Real Wedding Tabs: Hire Vendors
 *  -----------------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dashboard_Realwedding_Hire_Vendors_Tab' ) && class_exists( 'SDWeddingDirectory_Dashboard_Realwedding' ) ){

    class SDWeddingDirectory_Dashboard_Realwedding_Hire_Vendors_Tab extends SDWeddingDirectory_Dashboard_Realwedding{

        private static $instance;

        public static function get_instance() {

            if ( null == self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        public function __construct() {

            add_filter( 'sdweddingdirectory/couple-real-wedding/tabs', [ $this, 'tab' ], absint( '50' ), absint( '1' ) );
        }

        public static function tab_name(){

            return      esc_attr__( 'Hire Vendors', 'sdweddingdirectory-real-wedding' );
        }

        public static function tab_id(){

            return      esc_attr( 'couple_realwedding_hire_vendors' );
        }

        public static function tab( $args = [] ){

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

        public static function tab_content(){

            /**
             *   Section Information
             *   -------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>    esc_attr( 'info' ),

                    'class'       =>    sanitize_html_class( 'mb-0' ),

                    'title'       =>    esc_attr__( 'Hire Vendors', 'sdweddingdirectory-real-wedding' ),
                )

            ) );

            ?><div class="card-shadow-body"><?php

            /**
             *  Website Vendor Credits
             *  ----------------------
             */

            $_website_vendor_team   =   get_post_meta(

                                            absint( parent:: realwedding_post_id() ),

                                            sanitize_key( 'realwedding_hired_vendors' ),

                                            true
                                        );

            parent:: create_section( array(

                'column'     =>   array(

                    'grid'        =>   absint( '12' ),

                    'id'          =>   '',

                    'class'       =>   '',

                    'start'       =>   false,

                    'end'         =>   false,
                ),

                'field'                     =>      array(

                    'field_type'            =>      esc_attr( 'multiple_select' ),

                    'id'                    =>      esc_attr( 'realwedding_hired_vendors' ),

                    'name'                  =>      esc_attr( 'realwedding_hired_vendors' ),

                    'placeholder'           =>      sprintf( esc_attr__( 'Select the vendors you hired on %1$s', 'sdweddingdirectory-real-wedding' ),

                                                        esc_attr( get_bloginfo( 'name' ) )
                                                    ),

                    'options'               =>      apply_filters( 'sdweddingdirectory/post/data', [

                                                        'post_type'     =>      esc_attr( 'vendor' )

                                                    ] ),

                    'selected'              =>      !       empty( $_website_vendor_team )

                                                    ?       preg_split ("/\,/", $_website_vendor_team )

                                                    :       []
                )

            ) );

            ?></div><?php

            /**
             *   Outside Vendors Section
             *   -----------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>    esc_attr( 'info' ),

                    'class'       =>    sanitize_html_class( 'mb-0' ),

                    'title'       =>    esc_attr__( 'Outside Vendor\'s Team', 'sdweddingdirectory-real-wedding' ),
                )

            ) );

            ?><div class="card-shadow-body"><?php

                printf( '<div class="%1$s">

                            <div class="row row-cols-1 %1$s" id="%1$s">%4$s</div>

                            <div class="text-center">

                                <a href="javascript:void(0)"

                                class="btn btn-primary btn-rounded sdweddingdirectory_core_group_accordion %1$s"

                                data-class="SDWeddingDirectory_Real_Wedding_Database"

                                data-member="get_outside_vendor"

                                data-parent="%1$s"

                                id="%2$s">%3$s</a>

                            </div>

                        </div>',

                        esc_attr( 'outside_vendor' ),

                        esc_attr( parent:: _rand() ),

                        esc_attr__( 'Add New Vendor Team', 'sdweddingdirectory-real-wedding' ),

                        parent:: get_outside_vendor( [ 'post_id' => parent:: realwedding_post_id() ] )
                );

            ?></div><?php

        }
    }

    SDWeddingDirectory_Dashboard_Realwedding_Hire_Vendors_Tab::get_instance();
}
