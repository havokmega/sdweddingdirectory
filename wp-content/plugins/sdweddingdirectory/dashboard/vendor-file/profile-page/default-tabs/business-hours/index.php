<?php
/**
 *  -----------------------------------------------
 *  SDWeddingDirectory - Business Hours Tab
 *  -----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Profile_Business_Hours_Tab' ) && class_exists( 'SDWeddingDirectory_Vendor_Profile_Database' ) ){

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Business Hours Tab
     *  -----------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Profile_Business_Hours_Tab extends SDWeddingDirectory_Vendor_Profile_Database{

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

            return      esc_attr__( 'Business Hours', 'sdweddingdirectory' );
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
            add_filter( 'sdweddingdirectory/vendor-profile/tabs', [ $this, 'get_tabs' ], absint( '21' ), absint( '1' ) );
        }

        /**
         *  Get Tabs
         *  --------
         */
        public static function get_tabs( $args = [] ){

            return  array_merge( $args, [

                        'business-hours'        =>  [

                            'id'        =>  esc_attr( parent:: _rand() ),

                            'name'      =>  esc_attr( self:: tab_name() ),

                            'callback'  =>  [ 'class' => __CLASS__, 'member' => 'tab_content' ],

                            'create_form'      =>  [

                                'form_before'   =>  '',

                                'form_after'    =>  '',

                                'form_id'       =>  esc_attr( 'sdweddingdirectory_vendor_business_profile' ),

                                'form_class'    =>  '',

                                'button_before' =>  '',

                                'button_after'  =>  '',

                                'button_id'     =>  esc_attr( 'vendor_business_profile_submit' ),

                                'button_class'  =>  '',

                                'button_name'   =>  esc_attr__( 'Update Business Hours','sdweddingdirectory' ),

                                'security'      =>  esc_attr( 'vendor_business_profile' ),
                            ]
                        ]

                    ] );
        }

        /**
         *.  Tab Content
         *   -----------
         */
        public static function tab_content(){

            $days_array = [
                'saturday'  => esc_attr__( 'Saturday', 'sdweddingdirectory' ),
                'sunday'    => esc_attr__( 'Sunday', 'sdweddingdirectory' ),
                'monday'    => esc_attr__( 'Monday', 'sdweddingdirectory' ),
                'tuesday'   => esc_attr__( 'Tuesday', 'sdweddingdirectory' ),
                'wednesday' => esc_attr__( 'Wednesday', 'sdweddingdirectory' ),
                'thursday'  => esc_attr__( 'Thursday', 'sdweddingdirectory' ),
                'friday'    => esc_attr__( 'Friday', 'sdweddingdirectory' ),
            ];

            $post_id = absint( parent:: post_id() );

            $hours_list = apply_filters(
                'sdweddingdirectory/24-hours/list',
                $start = 0, $end = 86400, $step = 3600, $format = 'g:i A'
            );

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

            if( is_array( $days_array ) ){

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
                                                esc_attr( $key ),
                                                esc_attr__( 'Enable', 'sdweddingdirectory' ),
                                                get_post_meta( $post_id, $key . '_enable', true ) == 'on' ? 'checked' : ''
                                        );
                                    ?>
                                    </div>

                                    <div class="col-md-2 col-6 mb-xxl-0 mb-xl-0 mb-lg-0 mb-md-0 mb-3 d-flex align-items-center">
                                    <?php
                                        printf( '<input autocomplete="off" type="checkbox" name="%1$s_open" id="%1$s_open" %3$s class="form-check-input" />
                                                <label class="form-check-label" for="%1$s_open">%2$s</label>',
                                                esc_attr( $key ),
                                                esc_attr__( '24 Hours', 'sdweddingdirectory' ),
                                                get_post_meta( $post_id, $key . '_open', true ) == 'on' || empty( $post_id ) ? 'checked' : ''
                                        );
                                    ?>
                                    </div>

                                    <div class="col-md-3 mb-xxl-0 mb-xl-0 mb-lg-0 mb-md-0 mb-3">
                                    <?php
                                        printf( '<input type="text" disabled id="%1$s" class="form-dark form-control" value="%2$s" />', esc_attr( $key ), esc_html( $value ) );
                                    ?>
                                    </div>

                                    <div class="col-md-5 mb-xxl-0 mb-xl-0 mb-lg-0 mb-md-0 mb-3">
                                        <div class="row">
                                            <div class="col">
                                            <?php
                                                $start_option = '';
                                                if( is_array( $hours_list ) ){
                                                    foreach( $hours_list as $time_key => $time_value ){
                                                        $start_option .= sprintf(
                                                            '<option value="%1$s" %3$s>%2$s</option>',
                                                            $time_key,
                                                            $time_value,
                                                            get_post_meta( $post_id, $key . '_start', true ) == esc_attr( $time_key ) ? 'selected' : ''
                                                        );
                                                    }
                                                }
                                                if( ! empty( $start_option ) ){
                                                    printf( '<select id="%1$s_start" class="form-dark form-control-sm">%2$s</select>', esc_attr( $key ), $start_option );
                                                }
                                            ?>
                                            </div>
                                            <div class="col">
                                            <?php
                                                $close_option = '';
                                                if( is_array( $hours_list ) ){
                                                    foreach( $hours_list as $time_key => $time_value ){
                                                        $close_option .= sprintf(
                                                            '<option value="%1$s" %3$s>%2$s</option>',
                                                            $time_key,
                                                            $time_value,
                                                            get_post_meta( $post_id, $key . '_close', true ) == esc_attr( $time_key ) ? 'selected' : ''
                                                        );
                                                    }
                                                }
                                                if( ! empty( $close_option ) ){
                                                    printf( '<select id="%1$s_close" class="form-dark form-control-sm">%2$s</select>', esc_attr( $key ), $close_option );
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
     *  -----------------------------------------------
     *  SDWeddingDirectory - Business Hours Tab
     *  -----------------------------------------------
     */
    SDWeddingDirectory_Vendor_Profile_Business_Hours_Tab::get_instance();
}
