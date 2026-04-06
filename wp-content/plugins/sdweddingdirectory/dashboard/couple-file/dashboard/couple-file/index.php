<?php
/**
 *  SDWeddingDirectory - Couple Dashboard
 *  -----------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Dashboard' ) && class_exists( 'SDWeddingDirectory_Form_Fields' ) ){

    /**
     *  SDWeddingDirectory - Couple Dashboard
     *  -----------------------------
     */
    class SDWeddingDirectory_Couple_Dashboard extends SDWeddingDirectory_Form_Fields{

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
        public function __construct() {

            /**
             *  1. Dashboard Scripts
             *  --------------------
             */
            add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'], absint( '10' ) );

            /**
             *  2. Dashboard Page
             *  -----------------
             */
            add_action( 'sdweddingdirectory/couple-dashboard', [$this, 'dashboard_page'], absint( '10' ), absint( '1' ) );

            /**
             *  Edit Button Widget
             *  ------------------
             */
            add_action( 'sdweddingdirectory/couple-dashboard/overview/widget', [ $this, 'edit_button' ], absint( '10' ), absint( '1' ) );

            /**
             *  User Photo with Info Widget
             *  ---------------------------
             */
            add_action( 'sdweddingdirectory/couple-dashboard/overview/widget', [ $this, 'user_photo_with_info' ], absint( '20' ), absint( '1' ) );

            /**
             *  Couple - Wedding Date
             *  ---------------------
             */
            add_action( 'sdweddingdirectory/couple/dashboard/wedding-date', [ $this, 'couple_wedding_date' ], absint( '10' ) );
        }

        /**
         *  Edit Button Widget
         *  ------------------
         */
        public static function edit_button( $args = [] ){

            /**
             *  Have Args
             *  ---------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Args
                 *  ----
                 */
                extract( wp_parse_args( $args, [

                    'layout'        =>      absint( '1' ),

                    'post_id'       =>      absint( '0' )

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Is Layout 1 ?
                 *  -------------
                 */
                if( $layout == absint( '1' ) ){

                    /**
                     *  Edit Button
                     *  -----------
                     */
                    printf( '<div class="edit-btn">

                                <a href="%1$s" class="btn btn-outline-white btn-sm"><i class="fa fa-pencil"></i> %2$s</a>

                            </div>',

                            /**
                             *  1. Get the My Profile Page Link
                             *  -------------------------------
                             */
                            apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'my-profile' ) ),
                            
                            /**
                             *  2. Edit Text
                             *  ------------
                             */
                            esc_attr__( 'Edit', 'sdweddingdirectory' )
                    );
                }
            }
        }

        /**
         *  Edit Button Widget
         *  ------------------
         */
        public static function user_photo_with_info( $args = [] ){

            /**
             *  Have Args
             *  ---------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Args
                 *  ----
                 */
                extract( wp_parse_args( $args, [

                    'layout'        =>      absint( '1' ),

                    'post_id'       =>      absint( '0' )

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Is Layout 1 ?
                 *  -------------
                 */
                if( $layout == absint( '1' ) ){

                    /**
                     *  Get Data
                     *  --------
                     */
                    $wedding_date           =       parent:: get_data( 'wedding_date' );

                    /**
                     *  1. Bride and Groom Images
                     *  -------------------------
                     */
                    printf( '<div class="row">

                                <div class="col-md-5 col-12">

                                    <div class="d-flex justify-content-center mb-3">%1$s %2$s</div>

                                </div>

                                <div class="col-md-7 col-12 d-flex justify-content-center align-items-center">

                                    <div class="d-flex flex-column couple-name">

                                        <h2>%3$s & %4$s</h2>

                                        <div class="mt-2">%5$s</div>

                                    </div>

                                </div>

                            </div>',

                            /**
                             *  1. Groom Image
                             *  --------------
                             */
                            apply_filters( 'sdweddingdirectory/field/single-media', [

                                'database_key'          =>      esc_attr( 'bride_image' ),

                                'icon_layout'           =>      absint( '1' ),

                                'image_class'           =>      'rounded-circle border border-2',

                                'image_size'            =>      esc_attr( 'thumbnail' ),

                                'is_ajax'               =>      true,

                                'default_img'           =>      esc_url( parent:: placeholder( 'couple-groom-image' ) )

                            ] ),

                            /**
                             *  2. Bride Image
                             *  --------------
                             */
                            apply_filters( 'sdweddingdirectory/field/single-media', [

                                'database_key'          =>      esc_attr( 'groom_image' ),

                                'icon_layout'           =>      absint( '1' ),

                                'image_class'           =>      'rounded-circle border border-2',

                                'image_size'            =>      esc_attr( 'thumbnail' ),

                                'is_ajax'               =>      true,

                                'default_img'           =>      esc_url( parent:: placeholder( 'couple-bride-image' ) )

                            ] ),

                            /**
                             *  3. Groom Name
                             *  -------------
                             */
                            parent:: get_data( 'groom_first_name' ),

                            /**
                             *  4. Bride Name
                             *  -------------
                             */
                            parent:: get_data( 'bride_first_name' ),

                            /**
                             *  5. Wedding Date
                             *  ---------------
                             */
                            sprintf( '<p class=""><i class="fa fa-calendar me-2"></i> %1$s</p>',

                                /**
                                 *  1. Wedding Date
                                 *  ---------------
                                 */
                                apply_filters( 'sdweddingdirectory/date-format', [

                                    'date'      =>      esc_attr( $wedding_date )

                                ] )
                            )
                    );
                }
            }
        }

        /**
         *  1. Dashboard Scripts
         *  --------------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Isset Couple Dashboard ?
             *  ------------------------
             */
            if( parent:: dashboard_page_set( 'couple-dashboard' ) ){

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
                    array( 'jquery' ),

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

                /**
                 *  Load Library Condition
                 *  ----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/date-picker', function( $args = [] ){

                    return  array_merge( $args, [

                                'couple-dashboard'   =>  true

                            ] );
                } );

                /**
                 *  Load Library Condition
                 *  ----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/media-upload', function( $args = [] ){

                    return  array_merge( $args, [

                                'couple-dashboard'   =>  true

                            ] );
                } );
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
                if( ! empty( $page ) && $page == esc_attr( 'couple-dashboard' )  ){

                    ?><div class="container"><?php

                        /**
                         *  2.1 Couple Dashboard Overview
                         *  -----------------------------
                         */
                        self:: dashboard_overview();

                        /**
                         *  2.2 : Couple Widgets
                         *  --------------------
                         */
                        self:: dashboard_couple_widgets();

                    ?></div><?php
                }
            }
        }

        /**
         *  2.1 Couple Dashboard Overview
         *  -----------------------------
         */
        public static function dashboard_overview(){

            ?>
            <!-- Couple Info Section -->
            <div class="card-shadow">

                <div class="card-shadow-body p-0">

                    <div class="row">

                        <div class="col-lg-6 col-xl-5 col-md-6">

                            <div class="couple-img-wrap">

                                <div class="couple-img">
                                <?php

                                    print 

                                    apply_filters( 'sdweddingdirectory/field/single-media', [

                                        'database_key'          =>      esc_attr( 'couple_dashboard_image' ),

                                        'icon_layout'           =>      absint( '1' ),

                                        'image_size'            =>      esc_attr( 'sdweddingdirectory_img_350x530' ),

                                        'is_ajax'               =>      true,

                                        'default_img'           =>      esc_url( parent:: placeholder( 'couple-dashboard' ) )

                                    ] );

                                    ?><div class="couple-btn"><?php

                                        /**
                                         *  Have Action ?
                                         *  -------------
                                         */
                                        do_action( 'sdweddingdirectory/couple-dashboard/share-button', [

                                            'post_id'       =>      absint( parent:: post_id() )

                                        ] );

                                    ?>
                                    </div>

                                </div>
                                <?php

                                    /**
                                     *  Couple Wedding Date
                                     *  -------------------
                                     */
                                    do_action( 'sdweddingdirectory/couple/dashboard/wedding-date' );
                                ?>

                            </div>
                        </div>

                        <div class="col-lg-6 col-xl-7 col-md-6">

                            <div class="couple-info">
                            <?php

                                /**
                                 *  OverView Widget
                                 *  ---------------
                                 */
                                do_action( 'sdweddingdirectory/couple-dashboard/overview/widget', [

                                    'layout'        =>      absint( '1' ),

                                    'post_id'       =>      parent:: post_id()

                                ] );

                                ?>
                                <div class="row row-cols-1 row-cols-md-2 row-cols-sm-2">
                                <?php

                                    /**
                                     *  Dashboard Overview Summary
                                     *  --------------------------
                                     */
                                    
                                    do_action( 'sdweddingdirectory/couple/dashboard/widget/summary' );

                                ?>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <!-- Couple Info Section -->
            <?php
        }

        /**
         *  2.2 : Couple Widgets
         *  --------------------
         */
        public static function dashboard_couple_widgets(){

            ?>
            <div class="row">
                <div class="col-xl-8">
                    <?php 

                        /**
                         *  Couple Full Width Widget
                         *  ------------------------
                         */
                        
                        do_action( 'sdweddingdirectory/couple/dashboard/widget/left-side' );
                    ?>
                </div>

                <div class="col-xl-4">
                <?php 

                    /**
                     *  Couple Sidebar Widget
                     *  ---------------------
                     */
                    do_action( 'sdweddingdirectory/couple/dashboard/widget/right-side' );
                ?>
                </div>

            </div>
            <?php
        }

        /**
         *  2.3 Couple Wedding Date
         *  -----------------------
         */
        public static function couple_wedding_date(){

            $wedding_date   =   esc_attr( date( 'Y-m-d', strtotime( parent:: get_data( 'wedding_date' ) ) ) );

            $wedding_days   =   esc_attr__( 'Days','sdweddingdirectory' );

            $wedding_hours  =   esc_attr__( 'Hours','sdweddingdirectory' );

            $wedding_min    =   esc_attr__( 'Minutes','sdweddingdirectory' );

            $wedding_sec    =   esc_attr__( 'Seconds', 'sdweddingdirectory' );

            $wedding_msg    =   esc_attr__( 'Happily Married 🎉', 'sdweddingdirectory' );

            $just_married   =   esc_url( plugin_dir_url( __FILE__ ) . 'images/just-married.png' );

            /**
             *  Check Wedding Date is Up Comming or it's done ?
             *  -----------------------------------------------
             */
            if( strtotime( date( 'Y-m-d' ) ) >= strtotime( $wedding_date ) ){

                $datetime1  =   new DateTime( $wedding_date );

                $datetime2  =   new DateTime( date( 'Y-m-d' ) );

                $difference =   $datetime1->diff($datetime2);

                printf(    '<div class="couple-counter wedding-done">

                                <ul id="happy-wedding" 

                                    class="list-unstyled list-inline text-cener">

                                        <li class="counter-icon">

                                            <img src="%4$s" alt="%1$s"/>

                                        </li>

                                        <li class="list-inline-item">

                                            <span class="days"> %2$s </span> 

                                            <div class="days_text"> %3$s </div>

                                        </li>
                                </ul>

                            </div>', 

                            /**
                             *  1. Message
                             *  ----------
                             */
                            esc_attr( $wedding_msg ),

                            /**
                             *  2. Count After Wedding Days
                             *  ----------------------------
                             */
                            absint( $difference->days ),

                            /**
                             *  3. Translation Ready String
                             *  ---------------------------
                             */
                            esc_attr__( 'Days', 'sdweddingdirectory' ),

                            /**
                             *  4. Just Mariage
                             *  ---------------
                             */
                            esc_url( $just_married )
                );

            }else{

                /**
                 *  CountDown
                 *  ---------
                 */
                printf(    '<div class="couple-counter">

                                <ul id="wedding-countdown" 

                                    class="list-unstyled list-inline" 

                                    data-wedding-date  = "%2$s"

                                    data-wedding-days  = "%3$s"

                                    data-wedding-hours = "%4$s"

                                    data-wedding-min   = "%5$s"

                                    data-wedding-sec   = "%6$s"

                                    data-wedding-msg   = "%7$s" >

                                        <li class="list-inline-item">

                                            <span class="days"> %1$s </span> 

                                            <div class="days_text"> %3$s </div>

                                        </li>

                                        <li class="list-inline-item">

                                            <span class="hours"> %1$s </span>

                                            <div class="hours_text"> %4$s </div>

                                        </li>

                                        <li class="list-inline-item">

                                            <span class="minutes">%1$s</span>

                                            <div class="minutes_text"> %5$s </div>

                                        </li>

                                        <li class="list-inline-item">

                                            <span class="seconds"> %1$s </span>

                                            <div class="seconds_text"> %6$s </div>

                                        </li>
                                </ul>

                            </div>',

                            /**
                             *  1. By Default Time Show *00*
                             *  ----------------------------
                             */
                            esc_attr( '00' ),

                            /**
                             *  2. Wedding : wedding_date
                             *  -------------------------
                             */
                            esc_attr( $wedding_date ),

                            /**
                             *  3. Wedding : wedding_days
                             *  -------------------------
                             */                        
                            esc_attr( $wedding_days ),

                            /**
                             *  4. Wedding : wedding_hour
                             *  -------------------------
                             */                        
                            esc_attr( $wedding_hours ),

                            /**
                             *  5. Wedding : wedding_min 
                             *  -------------------------
                             */                        
                            esc_attr( $wedding_min ),

                            /**
                             *  6. Wedding : wedding_sec 
                             *  -------------------------
                             */                        
                            esc_attr( $wedding_sec ),

                            /**
                             *  7. Wedding : wedding_msg 
                             *  ------------------------
                             */                        
                            esc_attr( $wedding_msg )
                        );
            }
        }
    }

    /**
     *  SDWeddingDirectory - Couple Dashboard
     *  -----------------------------
     */
    SDWeddingDirectory_Couple_Dashboard::get_instance();
}