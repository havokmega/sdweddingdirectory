<?php
/**
 *  SDWeddingDirectory - Database information here
 *  --------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Budget_Database' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory - Database information here
     *  --------------------------------------
     */
    class SDWeddingDirectory_Budget_Database extends SDWeddingDirectory_Config{

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
             *  Default Budget Calculator Data
             *  ------------------------------
             */
            add_action( 'sdweddingdirectory/user-register/couple', [ $this, 'default_budget_data' ], absint( '20' ), absint( '1' ) );
        }

        /**
         *  Default Budget Calculator Data
         *  ------------------------------
         */
        public static function default_budget_data( $args = [] ){

            /**
             *  Make sure get args
             *  ------------------
             */
            if( ! parent:: _is_array( $args ) ){
                return;
            }

            $post_id  = isset( $args['post_id'] ) ? absint( $args['post_id'] ) : 0;
            $meta_key = sanitize_key( 'sdweddingdirectory_budget_data' );

            if( empty( $post_id ) ){
                return;
            }

            /**
             *  Hardcoded default budget categories
             *  ------------------------------------
             */
            $defaults = [
                [ 'title' => 'DJ',               'icon' => 'sdweddingdirectory-music',          'estimate' => '1900' ],
                [ 'title' => 'Catering',          'icon' => 'sdweddingdirectory-wine',           'estimate' => '3000' ],
                [ 'title' => 'Photography',       'icon' => 'sdweddingdirectory-camera',         'estimate' => '2200' ],
                [ 'title' => 'Cake',              'icon' => 'sdweddingdirectory-cake-floor',     'estimate' => '550'  ],
                [ 'title' => 'Flowers',           'icon' => 'sdweddingdirectory-flowers',        'estimate' => '1600' ],
                [ 'title' => 'Wedding Planner',   'icon' => 'sdweddingdirectory-checklist',      'estimate' => '1400' ],
                [ 'title' => 'Hair and Makeup',   'icon' => 'sdweddingdirectory-birde',          'estimate' => '400'  ],
                [ 'title' => 'Venue',             'icon' => 'sdweddingdirectory-location-heart', 'estimate' => '8500' ],
                [ 'title' => 'Videographer',      'icon' => 'sdweddingdirectory-videographer',   'estimate' => '1800' ],
                [ 'title' => 'Dress',             'icon' => 'sdweddingdirectory-bridal-wear',    'estimate' => '2000' ],
                [ 'title' => 'Tuxedo Rental',     'icon' => 'sdweddingdirectory-fashion',        'estimate' => '200'  ],
                [ 'title' => 'Officiant',         'icon' => 'sdweddingdirectory-church',         'estimate' => '400'  ],
                [ 'title' => 'Ceremony Music',    'icon' => 'sdweddingdirectory-guitar',         'estimate' => '500'  ],
                [ 'title' => 'Photo Booth',       'icon' => 'sdweddingdirectory-camera-alt',     'estimate' => '650'  ],
                [ 'title' => 'Event Rentals',     'icon' => 'sdweddingdirectory-tent',           'estimate' => '650'  ],
                [ 'title' => 'Transportation',    'icon' => 'sdweddingdirectory-bus',            'estimate' => '750'  ],
            ];

            $handler = [];

            foreach( $defaults as $cat ){

                $handler[] = [

                    'title'                    => esc_attr( $cat['title'] ),

                    'budget_category_name'     => esc_attr( $cat['title'] ),

                    'budget_category_overview'  => esc_attr( $cat['title'] ),

                    'budget_json_data'         => json_encode( [
                        [
                            'expense_name'     => esc_attr( $cat['title'] ),
                            'estimate_amount'  => esc_attr( $cat['estimate'] ),
                            'actual_amount'    => '',
                            'paid_amount'      => '',
                        ]
                    ] ),

                    'budget_category_icon'     => esc_attr( $cat['icon'] ),

                    'budget_unique_id'         => absint( rand() ),
                ];
            }

            update_post_meta( $post_id, sanitize_key( $meta_key ), $handler );
        }

        /**
         *  File Version
         *  ------------
         */
        public static function _file_version( $file = '' ){

            /**
             *  Is Empty ?
             *  ----------
             */
            if( empty( $file ) ){

                /**
                 *  Get Style Version
                 *  -----------------
                 */

                return      esc_attr( SDWEDDINGDIRECTORY_BUDGET_VERSION );

            }else{

                /*
                 *  For File Save timestrap version to clear the catch auto
                 *  -------------------------------------------------------
                 *  # https://developer.wordpress.org/reference/functions/wp_enqueue_style/#comment-6386
                 *  ------------------------------------------------------------------------------------
                 */

                return      esc_attr( SDWEDDINGDIRECTORY_BUDGET_VERSION ) . '.' . absint( filemtime(  $file ) );
            }
        }

        /** 
         *  1. Get Budget Backend Data
         *  --------------------------
         */
        public static function get_budget_data(){

            $_data = parent:: get_data( 'sdweddingdirectory_budget_data' );

            if( SDWeddingDirectory_Loader:: _is_array( $_data ) ){

                return $_data;

            }else{

                return;
            }
        }

        public static function get_counter( $args = '', $unique_id = '' ){

            $_data = self:: get_budget_data();

            $_counter = absint( '0' );

            if( SDWeddingDirectory_Loader:: _is_array( $_data ) && $args !== '' ){

                foreach ( $_data as $key => $value) {

                    if( $value[ 'budget_unique_id' ] == absint( $unique_id ) ){

                        $_get_esitmate_value = json_decode( $value[ 'budget_json_data' ], true );

                        if( SDWeddingDirectory_Loader:: _is_array( $_get_esitmate_value ) ){

                            foreach ( $_get_esitmate_value as $k => $v ) {
                                
                                if( $args == esc_attr( 'estimate_amount' ) ){

                                    $_counter +=  absint( $v[ esc_attr( 'estimate_amount' ) ] );
                                }

                                if( $args == esc_attr( 'actual_amount' ) ){

                                    $_counter +=  absint( $v[ esc_attr( 'actual_amount' ) ] );
                                }

                                if( $args == esc_attr( 'paid_amount' ) ){

                                    $_counter +=  absint( $v[ esc_attr( 'paid_amount' ) ] );
                                }
                            }
                        }
                    }
                }
            }

            return $_counter;
        }

        public static function get_number_of_category( $args ){

            $_data = self:: get_budget_data();

            $_return_data = [];

            if( SDWeddingDirectory_Loader:: _is_array( $_data ) && $args !== '' ){

                foreach ( $_data as $key => $value) {

                    $i = absint(self:: get_counter( esc_attr('estimate_amount'),  absint($value[ 'budget_unique_id' ])  ));

                    if( $i >= absint( '1' ) ){

                        if( $args == esc_attr( 'budget_category_name' ) ){

                            $_return_data[]  = esc_attr( $value[ 'budget_category_name' ] );
                        }

                        if( $args == esc_attr( 'estimate_amount' ) ){

                            $_return_data[]  =

                            absint( self:: get_counter( 

                                esc_attr( 'estimate_amount' ),

                                absint( $value[ 'budget_unique_id' ] )

                            ) );
                        }
                    }
                }
            }

            return $_return_data;
        }

        public static function get_budget_counter( $args ){

            $_data = self:: get_budget_data();

            $_counter = absint( '0' );

            if( SDWeddingDirectory_Loader:: _is_array( $_data ) && $args !== '' ){

                foreach ( $_data as $key => $value) {

                    $_get_esitmate_value = json_decode( $value[ 'budget_json_data' ], true );

                    if( SDWeddingDirectory_Loader:: _is_array( $_get_esitmate_value ) ){

                        foreach ( $_get_esitmate_value as $k => $v ) {
                            
                            /**
                             *  Get Estimate Amount
                             *  -------------------
                             */
                            if( $args == esc_attr( 'estimate_amount' ) ){

                                $_counter +=  absint( $v[ esc_attr( 'estimate_amount' ) ] );
                            }

                            /**
                             *  Get Actual Amount
                             *  -----------------
                             */
                            if( $args == esc_attr( 'actual_amount' ) ){

                                $_counter +=  absint( $v[ esc_attr( 'actual_amount' ) ] );
                            }

                            /**
                             *  Get Paid Amount
                             *  ---------------
                             */
                            if( $args == esc_attr( 'paid_amount' ) ){

                                $_counter +=  absint( $v[ esc_attr( 'paid_amount' ) ] );
                            }
                        }
                    }
                }
            }

            return absint( $_counter );
        }

    } /* class end **/

    /**
     *  SDWeddingDirectory - Database information here
     *  --------------------------------------
     */
    SDWeddingDirectory_Budget_Database:: get_instance();
}