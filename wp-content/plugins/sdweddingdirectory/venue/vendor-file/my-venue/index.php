<?php
/**
 *  SDWeddingDirectory - My Venue Dashboard
 *  ---------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dashboard_Venue' ) and class_exists( 'SDWeddingDirectory_Vendor_Venue_Files' ) ){

    /**
     *  SDWeddingDirectory - My Venue Dashboard
     *  ---------------------------------
     */
    class SDWeddingDirectory_Dashboard_Venue extends SDWeddingDirectory_Vendor_Venue_Files{

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

        /**
         *  Initiator
         *  ---------
         */
        public static function get_instance() {
          
            if ( ! isset( self::$instance ) ) {

                self::$instance = new self;
            }

            return self::$instance;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct(){

            /**
             *  1. Dashboard Scripts
             *  --------------------
             */
            add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'], absint( '20' ) );

            /**
             *  2. Dashboard Page
             *  -----------------
             */
            add_action( 'sdweddingdirectory/vendor-dashboard', [$this, 'dashboard_page'], absint( '20' ), absint( '1' ) );

            /**
             *  3. All Venue Tabs
             *  -------------------
             */
            add_filter( 'sdweddingdirectory/my-venue/tabs', [ $this, 'all_venue_tab' ], absint( '10' ), absint( '1' ) );

            /**
             *  4. Publish Venue Tab
             *  ----------------------
             */
            add_filter( 'sdweddingdirectory/my-venue/tabs', [ $this, 'approval_tab' ], absint( '20' ), absint( '1' ) );

            /**
             *  4. Pending Venue Tab
             *  ----------------------
             */
            add_filter( 'sdweddingdirectory/my-venue/tabs', [ $this, 'pending_tab' ], absint( '30' ), absint( '1' ) );

            /**
             *  5. Removed Venue Tab
             *  ----------------------
             */
            add_filter( 'sdweddingdirectory/my-venue/tabs', [ $this, 'removed_tab' ], absint( '40' ), absint( '1' ) );

            /**
             *  5. Removed Venue Tab
             *  ----------------------
             */
            add_filter( 'sdweddingdirectory/my-venue/tabs', [ $this, 'expired_tab' ], absint( '50' ), absint( '1' ) );

            /**
             *  6. Badge Asssign Tab
             *  --------------------
             */
            add_filter( 'sdweddingdirectory/my-venue/tabs', [ $this, 'badge_tab' ], absint( '60' ), absint( '1' ) );
        }

        /**
         *  1. Load Script
         *  --------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Condition Check
             *  ---------------
             */
            $_condition_1 =  parent:: dashboard_page_set( 'vendor-dashboard' );

            $_condition_2 =  parent:: dashboard_page_set( 'vendor-venue' );

            /**
             *  Check Condition
             *  ---------------
             */
            if( $_condition_1 || $_condition_2 ){

                /**
                 *  Load Script
                 *  -----------
                 */
                wp_enqueue_script( 

                    /**
                     *  1. File Name
                     *  ------------
                     */
                    esc_attr( sanitize_title( __CLASS__ ) ),

                    /**
                     *  2. File Path
                     *  ------------
                     */
                    esc_url(   plugin_dir_url( __FILE__ )   .   'script.js'   ),

                    /**
                     *  3. Load After "JQUERY" Load
                     *  ---------------------------
                     */
                    array( 'jquery', 'toastr' ),

                    /**
                     *  4. Bootstrap - Library Version
                     *  ------------------------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ )   .   'script.js' ) ),

                    /**
                     *  5. Load in Footer
                     *  -----------------
                     */
                    true
                );
            }
        }

        /**
         *  2. Dashboard Page
         *  -----------------
         */
        public static function dashboard_page( $args = [] ){

            /**
             *  Have Args
             *  ---------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args,[

                    'layout'        =>      absint( '1' ),

                    'post_id'       =>      '',

                    'page'          =>      ''

                ] ) );

                /**
                 *  Make sure this page is couple dashboard page
                 *  --------------------------------------------
                 */
                if( ! empty( $page ) && $page == esc_attr( 'vendor-venue' )  ){

                    /**
                     *  Extract
                     *  -------
                     */
                    extract( [

                        'plan_id'               =>      parent:: get_data( 'pricing_plan_id' ),

                        'plan_expired'          =>      strtotime( esc_attr( parent:: get_data( 'pricing_plan_end' ) ) ) >= strtotime( esc_attr( date( 'Y-m-d' ) ) ),

                        'expiry_date_exists'    =>      ! empty( strtotime( esc_attr( parent:: get_data( 'pricing_plan_end' ) ) ) ),

                    ] );

                    ?><div class="container"><?php

                        /**
                         *  1. Page Title
                         *  -------------
                         */
                        printf('<div class="section-title">

                                    <div class="d-sm-flex justify-content-between align-items-center">

                                        <h2>%1$s</h2>

                                        <div class="add-venue-button">

                                            <a href="%2$s" class="btn btn-primary">%3$s %4$s</a>

                                        </div>

                                    </div>

                                </div>',

                                /**
                                 *  1. Title
                                 *  --------
                                 */
                                esc_attr__( 'My Venues', 'sdweddingdirectory-venue' ),

                                /**
                                 *  2. Add New Venue Page
                                 *  -----------------------
                                 */
                                !       empty( $plan_id ) && ( $plan_expired && $expiry_date_exists )

                                ?       apply_filters( 'sdweddingdirectory/vendor-menu/page-link', esc_attr( 'add-venue' ) )

                                :       apply_filters( 'sdweddingdirectory/vendor-menu/page-link', esc_attr( 'add-venue' ) ),

                                /**
                                 *  2. Icon
                                 *  -------
                                 */
                                !       empty( $plan_id ) && ( $plan_expired && $expiry_date_exists )

                                ?       '<i class="fa fa-plus"></i>'

                                :       '<i class="fa fa-plus"></i>',

                                /**
                                 *  4. Button Name
                                 *  --------------
                                 */
                                !       empty( $plan_id ) && ( $plan_expired && $expiry_date_exists )

                                ?       esc_attr__( 'Add New Venue', 'sdweddingdirectory-venue' )

                                :       esc_attr__( 'Add New Venue', 'sdweddingdirectory-venue' )
                        );


                        /**
                         *  Make sure plan working
                         *  ----------------------
                         */
                        if(  !  empty( $plan_id ) && ( $plan_expired && $expiry_date_exists )  ){

                            /**
                             *  My Venue Page - Plan Information
                             *  ----------------------------------
                             */
                            do_action( 'sdweddingdirectory/vendor/plan-info', [

                                'plan_id'       =>      absint( $plan_id ),

                                'author_id'     =>      absint( parent:: author_id() ),

                                'post_id'       =>      absint( parent:: post_id() ),

                            ] );
                        }

                        /**
                         *  2. My Venue Page Content
                         *  --------------------------
                         */
                        ?>
                        <div class="card-shadow">

                            <div class="card-shadow-body">
                                <div><strong>
                                <?php 

                                    printf( esc_attr__( 'Create your wedding venue on %1$s to start building customers.', 'sdweddingdirectory-venue' ),


                                        /**
                                         *  1. Site Name
                                         *  ------------
                                         */
                                        esc_attr( get_bloginfo( 'name' ) )

                                    );
                                ?>
                                </strong></div>
                            </div>  

                        </div>
                        <?php

                        self:: sdweddingdirectory_venue_body_content();

                    ?></div><?php
                }
            }
        }

        /**
         *  Create Horizotal Tabs
         *  ---------------------
         */
        public static function sdweddingdirectory_venue_body_content(){

            /**
             *  Tabs
             *  ----
             */
            $get_tabs       =       apply_filters( 'sdweddingdirectory/my-venue/tabs', [] );

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $get_tabs ) ){

                /**
                 *  Create Tabs
                 *  -----------
                 */
                parent:: _create_tabs( $get_tabs, [

                    'structure_layout'      =>      absint( '3' ),

                    'structure_class'       =>      sanitize_html_class( 'my-venue-tab' ),

                ] );
            }
        }

        /**
         *  Load the venue tabs
         *  ---------------------
         */
        public static function all_venue_tab( $args = [] ){

            return      array_merge( $args, [

                            array(

                                'id'            =>      esc_attr( parent:: _rand() ),

                                'a_class'       =>      esc_attr( 'd-flex justify-content-between' ),

                                'active'        =>      true,

                                'name'          =>      sprintf( '<span class="">%1$s</span>', 

                                                            esc_attr__( 'All Venues', 'sdweddingdirectory-venue' ) 
                                                        ),

                                'suffix'        =>      sprintf(    '<span class="">(%1$s)</span>', 

                                                            /**
                                                             *  1. Counter
                                                             *  ----------
                                                             */
                                                            apply_filters( 'sdweddingdirectory/vendor/venue/found', [

                                                                'post_status'       =>      [ 'publish', 'pending', 'trash', 'draft' ]

                                                            ] )
                                                        ),

                                'callback'      =>      [ 'class' => __CLASS__, 'member' => 'sdweddingdirectory_number_of_venue_markup' ]
                            )

                        ] );
        }

        /**
         *  Approval Tab
         *  ------------
         */
        public static function approval_tab( $args = [] ){

            return      array_merge( $args, [

                            array(

                                'id'            =>      esc_attr( parent:: _rand() ),

                                'a_class'       =>      esc_attr( 'd-flex justify-content-between' ),

                                'name'          =>      sprintf( '<span class="">%1$s</span>', 

                                                            esc_attr__( 'Approved', 'sdweddingdirectory-venue' ) 
                                                        ),

                                'suffix'        =>      sprintf( '<span class="">(%1$s)</span>', 

                                                            /**
                                                             *  1. Counter
                                                             *  ----------
                                                             */
                                                            apply_filters( 'sdweddingdirectory/vendor/venue/found', [

                                                                'post_status'       =>      [ 'publish' ]

                                                            ] )
                                                        ),

                                'callback'      =>      [ 'class' => __CLASS__, 'member' => 'sdweddingdirectory_venue_approved_markup' ]
                            )

                        ] );
        }

        /**
         *  Pending Tab
         *  -----------
         */
        public static function pending_tab( $args = [] ){

            return      array_merge( $args, [

                            array(

                                'id'            =>      esc_attr( parent:: _rand() ),

                                'a_class'       =>      esc_attr( 'd-flex justify-content-between' ),

                                'name'          =>      sprintf( '<span class="">%1$s</span>', 

                                                            esc_attr__( 'Pending', 'sdweddingdirectory-venue' ) 
                                                        ),

                                'suffix'        =>      sprintf( '<span class="">(%1$s)</span>', 

                                                            /**
                                                             *  1. Counter
                                                             *  ----------
                                                             */
                                                            apply_filters( 'sdweddingdirectory/vendor/venue/found', [

                                                                'post_status'       =>      [ 'pending' ]

                                                            ] )
                                                        ),

                                'callback'      =>      [ 'class' => __CLASS__, 'member' => 'sdweddingdirectory_venue_pending_markup' ]
                            )

                        ] );
        }

        /**
         *  Remove Tab
         *  ----------
         */
        public static function removed_tab( $args = [] ){

            return      array_merge( $args, [

                            array(

                                'id'            =>      esc_attr( parent:: _rand() ),

                                'a_class'       =>      esc_attr( 'd-flex justify-content-between' ),

                                'name'          =>      sprintf( '<span class="">%1$s</span>', 

                                                            esc_attr__( 'Removed', 'sdweddingdirectory-venue' ) 
                                                        ),

                                'suffix'        =>      sprintf( '<span class="">(%1$s)</span>',

                                                            /**
                                                             *  1. Counter
                                                             *  ----------
                                                             */
                                                            apply_filters( 'sdweddingdirectory/vendor/venue/found', [

                                                                'post_status'       =>      [ 'trash' ]

                                                            ] )
                                                        ),                

                                'callback'      =>      [ 'class' => __CLASS__, 'member' => 'sdweddingdirectory_venue_removed_markup' ]
                            )

                        ] );
        }

        /**
         *  Expired Tab
         *  -----------
         */
        public static function expired_tab( $args = [] ){

            return      array_merge( $args, [

                            array(

                                'id'            =>      esc_attr( parent:: _rand() ),

                                'a_class'       =>      esc_attr( 'd-flex justify-content-between' ),

                                'name'          =>      sprintf(  '<span class="">%1$s</span>', 

                                                            esc_attr__( 'Expired', 'sdweddingdirectory-venue' ) 
                                                        ),

                                'suffix'        =>      sprintf(  '<span class="">(%1$s)</span>',

                                                            /**
                                                             *  1. Counter
                                                             *  ----------
                                                             */
                                                            apply_filters( 'sdweddingdirectory/vendor/venue/found', [

                                                                'post_status'       =>      [ 'draft' ]

                                                            ] )
                                                        ),

                                'callback'      =>      [ 'class' => __CLASS__, 'member' => 'sdweddingdirectory_venue_expired_markup' ]
                            )

                        ] );
        }

        /**
         *  Badge Tab
         *  ---------
         */
        public static function badge_tab( $args = [] ){

            /**
             *  Plan ID
             *  -------
             */
            $plan_id            =       parent:: get_data( 'pricing_plan_id' );

            $badge_section      =       [];

            /**
             *  Args
             *  ----
             */
            $badge_args         =       [

                'spotlight'     =>      [

                    'limit'     =>      get_post_meta( $plan_id, sanitize_key( 'spotlight_venue_capacity' ), true  ),

                    'name'      =>      esc_attr__( 'Spotlight', 'sdweddingdirectory-venue' ),
                ],

                'featured'      =>      [

                    'limit'     =>      get_post_meta( $plan_id, sanitize_key( 'featured_venue_capacity' ), true  ),

                    'name'      =>      esc_attr__( 'Featured', 'sdweddingdirectory-venue' ),
                ],

                'professional'  =>      [

                    'limit'     =>      get_post_meta( $plan_id, sanitize_key( 'professional_venue_capacity' ), true  ),

                    'name'      =>      esc_attr__( 'Professional', 'sdweddingdirectory-venue' ),
                ],
            ];

            /**
             *  Have Badge Args ?
             *  -----------------
             */
            if( parent:: _is_array( $badge_args ) ){

                foreach ( $badge_args as $key => $value ) {

                    /**
                     *  Extract Args
                     *  ------------
                     */
                    extract( $value );

                    /**
                     *  Make sure have limit
                     *  --------------------
                     */
                    if( ! empty( $limit ) &&  $limit >= absint( '1' ) ){

                        /**
                         *  Spotlight Tab
                         *  -------------
                         */
                        $args[]        =       array(

                            'id'                =>      esc_attr( parent:: _rand() ),

                            'a_class'           =>      esc_attr( 'd-flex justify-content-between' ),

                            'active'            =>      false,

                            'name'              =>      sprintf( '<span class="">%1$s</span>', 

                                                            esc_attr( $name )
                                                        ),

                            'suffix'            =>      sprintf(

                                                            '<span class="">(%1$s)</span>', 

                                                            /**
                                                             *  1. Counter
                                                             *  ----------
                                                             */
                                                            apply_filters( 'sdweddingdirectory/vendor/venue/found', [

                                                                'post_status'       =>      [ 'publish', 'pending', 'trash', 'draft' ],

                                                                'badge'             =>      esc_attr( $key )

                                                            ] )
                                                        ),

                            'callback'          =>      [ 

                                                            'class'         =>  __CLASS__, 

                                                            'member'        =>  sprintf( 'sdweddingdirectory_%1$s_badge_venue', $key ) 
                                                        ],

                            'create_form'       =>      [
                                                            'form_before'   =>  '',

                                                            'form_after'    =>  '',

                                                            'form_id'       =>  sprintf( 'sdweddingdirectory_%1$s_badge_assign', $key ),

                                                            'form_class'    =>  '',

                                                            'button_before' =>  '',

                                                            'button_after'  =>  '',

                                                            'button_id'     =>  sprintf( '%1$s_badge', $key ),

                                                            'button_class'  =>  '',

                                                            'button_name'   =>  sprintf( esc_attr__( 'Update %1$s Badges','sdweddingdirectory-venue' ), $name ),

                                                            'security'      =>  sprintf( '%1$s_badge_security', $key ),
                                                        ]
                        );
                    }
                }
            }

            /**
             *  Return the Tabs
             *  ---------------
             */
            return      $args;
        }

        /**
         *  Badge Info
         *  ----------
         */
        public static function sdweddingdirectory_my_venue_badge_tab( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Have Args ?
                 *  -----------
                 */
                extract( wp_parse_args( $args, [

                    'plan_id'       =>      parent:: get_data( 'pricing_plan_id' ),

                    'key'           =>      '',

                    'name'          =>      ''

                ] ) );

                /**
                 *   Section Information
                 *   -------------------
                 */
                parent:: create_section( array(

                    'field'     =>   array(

                        'field_type'  =>    esc_attr( 'info' ),

                        'title'       =>    sprintf( esc_attr__( 'Select %1$s Venues', 'sdweddingdirectory-venue' ), $name ),

                        'class'       =>    sanitize_html_class( 'col-12' )
                    )

                ) );

                /**
                 *  Get Badge Limit
                 *  ---------------
                 */
                $badge_limit        =       get_post_meta( $plan_id, sanitize_key( $key . '_venue_capacity' ), true  );



                /**
                 *  Found Post ?
                 *  ------------
                 */
                $found_post         =       apply_filters( 'sdweddingdirectory/post/data', [

                                                'post_type'     =>      esc_attr( 'venue' ),

                                                'author_id'     =>      parent:: author_id()

                                            ] );

                /**
                 *  Selected Option
                 *  ---------------
                 */
                $selected_option    =     $options      =     [];


                /**
                 *  Found Post
                 *  ----------
                 */
                if(  parent:: _is_array( $found_post )  ){

                    foreach( $found_post as $post_id => $value ){

                        /**
                         *  Badge Name
                         *  ----------
                         */
                        $badge_name     =   get_post_meta( $post_id, 'venue_badge', $key );

                        /**
                         *  Is Not Empty !
                         *  --------------
                         */
                        if( ! empty( $badge_name ) &&  $key  ==  $badge_name ){

                            $selected_option[ $post_id ]        =       $post_id;
                        }

                        if(  ! in_array( $badge_name, array_diff(  [ 'spotlight', 'featured', 'professional' ], [ $key ]  ) )   ){

                            $options[ $post_id ]    =       $value;
                        }
                    }
                }

                /**
                 *  Multiple Select Fields
                 *  ----------------------
                 */
                parent:: create_section( array(

                        /**
                         *  DIV Managment
                         *  -------------
                         */
                        'div'        =>  array(

                            'id'          =>   '',

                            'class'       =>   sanitize_html_class( 'card-body' ),

                            'start'       =>   true,

                            'end'         =>   true,
                        ),

                        /**
                         *  Row Managment
                         *  -------------
                         */
                        'row'        =>  array(

                            'id'          =>   '',

                            'class'       =>   '',

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
                        'field'                 =>      array(

                            'field_type'        =>      esc_attr( 'multiple_select' ),

                            'id'                =>      esc_attr( $key . '_venues' ),

                            'name'              =>      esc_attr( $key . '_venues' ),

                            'parent_class'      =>      '',

                            'select_limit'      =>      absint( $badge_limit ),

                            'placeholder'       =>      sprintf( esc_attr__( 'Select %1$s Badge Venues', 'sdweddingdirectory-venue' ),

                                                            /**
                                                             *  1. Name
                                                             *  -------
                                                             */
                                                            esc_attr( $name ),
                                                        ),

                            'selected'          =>      $selected_option,

                            'options'           =>      $options
                        )
                ) );
            }
        }
 
        /**
         *  Spotlight Badge
         *  ---------------
         */
        public static function sdweddingdirectory_spotlight_badge_venue(){

            /**
             *  Load Spotlight Badge Tab
             *  ------------------------
             */
            self:: sdweddingdirectory_my_venue_badge_tab( [

                'key'       =>      esc_attr( 'spotlight' ),

                'name'      =>      esc_attr( 'Spotlight' )

            ] );
        }

        /**
         *  Featured Badge
         *  ---------------
         */
        public static function sdweddingdirectory_featured_badge_venue(){

            /**
             *  Load Featured Badge Tab
             *  ------------------------
             */
            self:: sdweddingdirectory_my_venue_badge_tab( [

                'key'       =>      esc_attr( 'featured' ),

                'name'      =>      esc_attr( 'Featured' )

            ] );
        }

        /**
         *  Professional Badge
         *  ---------------
         */
        public static function sdweddingdirectory_professional_badge_venue(){

            /**
             *  Load Professional Badge Tab
             *  ---------------------------
             */
            self:: sdweddingdirectory_my_venue_badge_tab( [

                'key'       =>      esc_attr( 'professional' ),

                'name'      =>      esc_attr( 'Professional' )

            ] );
        }

        /**
         *  Expired Venue
         *  ---------------
         */
        public static function sdweddingdirectory_venue_expired_markup(){

            global $post, $wp_query;

            $args = array( 

                    'post_type'         => esc_attr( 'venue' ),

                    'post_status'       => array( 'draft' ),

                    'posts_per_page'    => -1,

                    'orderby'           => 'menu_order ID',

                    'order'             => esc_attr( 'post_date' ),

                    'author'            => absint( parent:: author_id() )
            );

            parent:: display_venue( $args );
        }

        /**
         *  Load publish venues
         *  ---------------------
         */
        public static function sdweddingdirectory_number_of_venue_markup(){
        
            $args = array(

                'post_type'         =>  esc_attr( 'venue' ),

                'post_status'       =>  array( 'publish', 'pending', 'draft', 'trash' ),

                'posts_per_page'    =>  -1,

                'orderby'           => 'menu_order ID',

                'order'             => esc_attr( 'post_date' ),

                'author'            => absint( parent:: author_id() )
            );

            parent:: display_venue( $args );
        }

        public static function sdweddingdirectory_venue_approved_markup(){
        
            $args = array( 

                'post_type'         => esc_attr( 'venue' ),

                'post_status'       => esc_attr( 'publish' ),

                'posts_per_page'    => -1,

                'orderby'           => 'menu_order ID',

                'order'             => esc_attr( 'post_date' ),

                'author'            => absint( parent:: author_id()  )
            );

            parent:: display_venue( $args );
        }

        public static function sdweddingdirectory_venue_pending_markup(){
        
            $args = array( 

                'post_type'         => esc_attr( 'venue' ),

                'post_status'       => esc_attr( 'pending' ),

                'posts_per_page'    => -1,

                'orderby'           => 'menu_order ID',

                'order'             => esc_attr( 'post_date' ),

                'author'            => absint( parent:: author_id() )
            );

            parent:: display_venue( $args );
        }

        public static function sdweddingdirectory_venue_removed_markup(){
        
            $args = array(

                'post_type'         => esc_attr( 'venue' ),

                'post_status'       => 'trash', 

                'posts_per_page'    => -1,

                'orderby'           => 'menu_order ID',

                'order'             => esc_attr( 'post_date' ),

                'author'            => absint( parent:: author_id()  )
            );

            parent:: display_venue( $args );
        }
    }   

    /**
     *  SDWeddingDirectory - My Venue Dashboard
     *  ---------------------------------
     */
    SDWeddingDirectory_Dashboard_Venue::get_instance();
}
