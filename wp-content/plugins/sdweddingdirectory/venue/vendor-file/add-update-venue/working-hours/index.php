<?php
/**
 *  ---------------------------
 *  SDWeddingDirectory - Venue Fields
 *  ---------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Fields_Post_Working_Hours' ) && class_exists( 'SDWeddingDirectory_Dashboard_Venue_Update' ) ){

    /**
     *  ---------------------------
     *  SDWeddingDirectory - Venue Fields
     *  ---------------------------
     */
    class SDWeddingDirectory_Venue_Fields_Post_Working_Hours extends SDWeddingDirectory_Dashboard_Venue_Update{

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

            return  self::$instance;
        }

        /**
         *  Tab ID
         *  ------
         */
        public static function tab_id(){

            return      sanitize_title( self:: tab_name() );
        }

        /**
         *  Tab Name
         *  --------
         */
        public static function tab_name(){

            return      esc_attr__( 'Working Hours', 'sdweddingdirectory-venue' );
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
            add_filter( 'sdweddingdirectory/add-update-venue/tabs', [ $this, 'tab_info' ], absint( '60' ), absint( '1' ) );
        }

        /**
         *  Tab Info
         *  --------
         */
        public static function tab_info( $args = [] ){

            /**
             *  Merge Tabs
             *  ----------
             */
            return      array_merge( $args, [

                            self:: tab_id()         =>      [

                                'active'            =>      false,

                                'id'                =>      esc_attr( parent:: _rand() ),

                                'name'              =>      esc_attr( self:: tab_name() ),

                                'callback'          =>      [ 'class' => __CLASS__, 'member' => 'tab_content' ],
                            ]

                        ] );
        }

        /**
         *  Working Hours
         *  -------------
         */
        public static function get_working_hour_value( $post_id, $meta_key ){

            /**
             *  Make sure post id not empty
             *  ---------------------------
             */
            if(  empty( $post_id ) ){

                return;
            }

            return      get_post_meta( $post_id, $meta_key, true );
        }

        /**
         *  Select Category Field
         *  ---------------------
         */
        public static function tab_content(){

            /**
             *  Extract Args
             *  ------------
             */
            extract( [

                'post_id'       =>  parent:: venue_post_ID(),

                'days_array'    =>  [

                                        'saturday'     => esc_attr__( 'Saturday', 'sdweddingdirectory-venue' ),

                                        'sunday'       => esc_attr__( 'Sunday', 'sdweddingdirectory-venue' ),

                                        'monday'       => esc_attr__( 'Monday', 'sdweddingdirectory-venue' ),

                                        'tuesday'      => esc_attr__( 'Tuesday', 'sdweddingdirectory-venue' ),

                                        'wednesday'    => esc_attr__( 'Wednesday', 'sdweddingdirectory-venue' ),

                                        'thursday'     => esc_attr__( 'Thursday', 'sdweddingdirectory-venue' ),

                                        'friday'       => esc_attr__( 'Friday', 'sdweddingdirectory-venue' ),
                                    ],

                'start'         =>  apply_filters( 

                                        'sdweddingdirectory/24-hours/list', 

                                        $start = 0, $end = 86400, $step = 3600, $format = 'g:i A' 
                                    ),

                'close'         =>  apply_filters(

                                        'sdweddingdirectory/24-hours/list', 

                                        $start = 0, $end = 86400, $step = 3600, $format = 'g:i A' 
                                    )
            ]  );

            /**
             *   Section Information
             *   -------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>    esc_attr( 'info' ),

                    'class'       =>    esc_attr( 'mb-0' ),

                    'title'       =>    esc_attr__( 'Venue Hours', 'sdweddingdirectory-venue' )
                )

            ) );

            if( parent:: _is_array( $days_array ) ){

                foreach( $days_array as $key => $value ){

                    ?>
                    <div class="card-body">

                        <div class="row">

                            <div class="col-12">

                                <div class="row">

                                    <div class="col-md-2 col-6 d-flex align-items-center mb-xxl-0 mb-xl-0 mb-lg-0 mb-md-0 mb-3">
                                    <?php

                                        printf( '<input autocomplete="off" type="checkbox" name="%1$s_enable" id="%1$s_enable" %3$s class="form-check-input" />

                                                <label class="form-check-label" for="%1$s_enable">%2$s</label>',

                                                /**
                                                 *  1. Key
                                                 *  ------
                                                 */
                                                esc_attr( $key ),

                                                /**
                                                 *  2. Translation Ready String
                                                 *  ---------------------------
                                                 */
                                                esc_attr__( 'Enable', 'sdweddingdirectory-venue' ),

                                                /**
                                                 *  3. Have Post ID ?
                                                 *  -----------------
                                                 */
                                                self:: get_working_hour_value( $post_id, $key . '_enable' )     ==      esc_attr( 'on' )

                                                ?   esc_attr( 'checked' )

                                                :   ''
                                        );

                                    ?>
                                    </div>

                                    <div class="col-md-2 col-6 mb-xxl-0 mb-xl-0 mb-lg-0 mb-md-0 mb-3 d-flex align-items-center">
                                    <?php

                                        printf( '<input autocomplete="off" type="checkbox" name="%1$s_open" id="%1$s_open" %3$s class="form-check-input" />

                                                <label class="form-check-label" for="%1$s_open">%2$s</label>',

                                                /**
                                                 *  1. Key
                                                 *  ------
                                                 */
                                                esc_attr( $key ),

                                                /**
                                                 *  2. Translation Ready String
                                                 *  ---------------------------
                                                 */
                                                esc_attr__( '24 Hours', 'sdweddingdirectory-venue' ),

                                                /**
                                                 *  3. Have Post ID ?
                                                 *  -----------------
                                                 */
                                                self:: get_working_hour_value( $post_id, $key . '_open' )     ==      esc_attr( 'on' )  ||  empty( $post_id )

                                                ?   esc_attr( 'checked' )

                                                :   ''
                                        );

                                    ?>
                                    </div>
                                
                                    <div class="col-md-3 mb-xxl-0 mb-xl-0 mb-lg-0 mb-md-0 mb-3">
                                    <?php 

                                        printf( '<input type="text" disabled id="%1$s" class="form-dark form-control" value="%2$s" />', $key, $value );

                                    ?>
                                    </div>

                                    <div class="col-md-5 mb-xxl-0 mb-xl-0 mb-lg-0 mb-md-0 mb-3">

                                        <div class="row">
                                            
                                            <div class="col">
                                            <?php

                                                $start_option   =   '';

                                                if( parent:: _is_array( $start ) ){

                                                    foreach( $start as $time_key => $time_value ){

                                                        $start_option   .=  

                                                        sprintf( '<option value="%1$s" %3$s>%2$s</option>', 

                                                            /**
                                                             *  Time Key
                                                             *  --------
                                                             */
                                                            $time_key, 

                                                            /**
                                                             *  Time Value
                                                             *  ----------
                                                             */
                                                            $time_value,

                                                            /**
                                                             *  3. Have Post ID ?
                                                             *  -----------------
                                                             */
                                                            self:: get_working_hour_value( $post_id, $key . '_start' )     ==      esc_attr( $time_key )

                                                            ?   esc_attr( 'selected' )

                                                            :   ''
                                                        );
                                                    }
                                                }

                                                if( ! empty( $start_option ) ){

                                                    printf( '<select id="%1$s_start" class="form-dark form-control-sm">%2$s</select>', $key, $start_option );
                                                }

                                            ?>
                                            </div>

                                            <div class="col">
                                            <?php

                                                $close_option   =   '';

                                                if( parent:: _is_array( $close ) ){

                                                    foreach( $close as $time_key => $time_value ){

                                                        $close_option   .=  

                                                        sprintf( '<option value="%1$s" %3$s>%2$s</option>', 

                                                            /**
                                                             *  Time Key
                                                             *  --------
                                                             */
                                                            $time_key,

                                                            /**
                                                             *  Time Value
                                                             *  ----------
                                                             */
                                                            $time_value,

                                                            /**
                                                             *  3. Have Post ID ?
                                                             *  -----------------
                                                             */
                                                            self:: get_working_hour_value( $post_id, $key . '_close' )     ==      esc_attr( $time_key )

                                                            ?   esc_attr( 'selected' )

                                                            :   ''
                                                        );
                                                    }
                                                }

                                                if( ! empty( $close_option ) ){

                                                    printf( '<select id="%1$s_close" class="form-dark form-control-sm">%2$s</select>', $key, $close_option );
                                                }

                                            ?>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>
                    <?php

                }
            }
        }
    }

    /**
     *  ---------------------------
     *  SDWeddingDirectory - Venue Fields
     *  ---------------------------
     */
    SDWeddingDirectory_Venue_Fields_Post_Working_Hours::get_instance();
}
