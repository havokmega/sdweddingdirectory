<?php
/**
 *  SDWeddingDirectory - Request Quote Plugin Meta
 *  --------------------------------------
 *  
 *  Option Tree Plugin
 *  ------------------
 *
 *  @link( https://github.com/valendesigns/option-tree )
 *
 *  @link( https://github.com/valendesigns/option-tree-theme/blob/master/inc/meta-boxes.php, link)
 *  
 */
if( ! class_exists( 'SDWeddingDirectory_Request_Quote_Meta' ) ){

    /**
     *  SDWeddingDirectory - Request Quote Plugin Meta
     *  --------------------------------------
     */
    class SDWeddingDirectory_Request_Quote_Meta{

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
             *  1. Venue Post Meta in Left Side Bar
             *  -------------------------------------
             */
            add_filter( 'sdweddingdirectory/meta', [ $this, 'sdweddingdirectory_request_quote_post_meta' ], absint( '10' ) );

            /**
             *  2. Post Type show column data
             *  -----------------------------
             */
            add_filter( 'manage_edit-venue-request_columns', [$this, 'post_type_column_label'] );

            /**
             *  3. Post Type Column Show Result ( DATA )
             *  ----------------------------------------
             */
            add_action( 'manage_venue-request_posts_custom_column', [$this, 'post_type_column_show_data'], 10, 2 );

            /**
             *  4. After Post Title Showing Highlight Title - Post State
             *  --------------------------------------------------------
             */
            // add_filter( 'display_post_states', [$this, 'have_post_state_after_post_title'], 10, 2 );
        }

        /**
         *  1. Request Quote Custom Post Type Meta
         *  --------------------------------------
         */
        public static function sdweddingdirectory_request_quote_post_meta( $args = [] ) {

            $new_args       =   array(

                'id'        =>  esc_attr( 'sdweddingdirectory_venue_request_quote' ),

                'title'     =>  esc_attr__( 'Request Quote', 'sdweddingdirectory-request-quote' ),

                'pages'     =>  array( 'venue-request' ),

                'context'   =>  esc_attr( 'normal' ),

                'priority'  =>  esc_attr( 'high' ),

                'fields'    =>  array(

                    array(

                        'id'      =>    sanitize_key( 'couple_id' ),

                        'label'   =>    esc_attr__( 'Couple ID', 'sdweddingdirectory-request-quote' ),

                        'type'    =>    esc_attr( 'text' ),
                    ),

                    array(

                        'id'      =>    sanitize_key( 'vendor_id' ),

                        'label'   =>    esc_attr__( 'Vendor ID', 'sdweddingdirectory-request-quote' ),

                        'type'    =>    esc_attr( 'text' ),
                    ),

                    array(

                        'id'      =>    sanitize_key( 'venue_id' ),

                        'label'   =>    esc_attr__( 'Venue ID', 'sdweddingdirectory-request-quote' ),

                        'type'    =>    esc_attr( 'text' ),
                    ),

                    array(

                        'id'    =>  esc_attr( 'request_quote_guest' ),

                        'label' =>  esc_attr__( 'Request For Number of Guest', 'sdweddingdirectory-request-quote' ),

                        'type'  =>  esc_attr( 'text' ),
                    ),

                    array(

                        'id'    =>  esc_attr( 'request_quote_budget' ),

                        'label' =>  esc_attr__( 'Couple Budget Amount', 'sdweddingdirectory-request-quote' ),

                        'type'  =>  esc_attr( 'text' ),
                    ),

                    array(

                        'id'    =>  esc_attr( 'request_quote_name' ),

                        'label' =>  esc_attr__( 'Couple Full Name', 'sdweddingdirectory-request-quote' ),

                        'type'  =>  esc_attr( 'text' ),
                    ),

                    array(

                        'id'    =>  esc_attr( 'request_quote_wedding_date' ),

                        'label' =>  esc_attr__( 'Couple Wedding Date', 'sdweddingdirectory-request-quote' ),

                        'type'  =>  esc_attr( 'text' ),
                    ),

                    array(

                        'id'    =>  esc_attr( 'request_quote_flexible_date' ),

                        'label' =>  esc_attr__( 'Couple Enable Flexible Date ?', 'sdweddingdirectory-request-quote' ),

                        'type'  =>  esc_attr( 'on-off' ),

                        'std'   =>  esc_attr( 'off' )
                    ),

                    array(

                        'id'    =>  esc_attr( 'request_quote_select_month' ),

                        'label' =>  esc_attr__( 'Couple Select Month ?', 'sdweddingdirectory-request-quote' ),

                        'type'  =>  esc_attr( 'text' ),
                    ),

                    array(

                        'id'    =>  esc_attr( 'request_quote_select_year' ),

                        'label' =>  esc_attr__( 'Couple Select Year Date ?', 'sdweddingdirectory-request-quote' ),

                        'type'  =>  esc_attr( 'text' ),
                    ),

                    array(

                        'id'    =>  esc_attr( 'request_quote_message' ),

                        'label' =>  esc_attr__( 'Request Message.', 'sdweddingdirectory-request-quote' ),

                        'type'  =>  esc_attr( 'textarea-simple' ),

                        'rows'  =>  absint( '10' ),
                    ),

                    array(

                        'id'    =>  esc_attr( 'request_quote_contact' ),

                        'label' =>  esc_attr__( 'Contact Number.', 'sdweddingdirectory-request-quote' ),

                        'type'  =>  esc_attr( 'text' ),
                    ),

                    array(

                        'id'        =>  esc_attr('request_quote_contact_option'),

                        'label'     =>  esc_attr__('Couple wish to contact via', 'sdweddingdirectory-request-quote' ),

                        'std'       =>  absint( '0' ),

                        'type'      =>  esc_attr( 'select' ),

                        'choices'   =>  array(

                            array(

                                'value'     =>  absint( '0' ),

                                'label'     =>  esc_attr__( 'Contact Me', 'sdweddingdirectory-request-quote' ),

                                'src'       =>  '',
                            ),

                            array(

                                'value'     =>  absint( '1' ),

                                'label'     =>  esc_attr__( 'Call', 'sdweddingdirectory-request-quote' ),

                                'src'       =>  '',
                            ),

                            array(

                                'value'     =>  absint( '2' ),

                                'label'     =>  esc_attr__( 'Email', 'sdweddingdirectory-request-quote' ),

                                'src'       =>  '',
                            ),

                            array(

                                'value'     =>  absint( '3' ),

                                'label'     =>  esc_attr__( 'Video Call', 'sdweddingdirectory-request-quote' ),

                                'src'       =>  '',
                            ),
                        ),
                    ),
                ),
            );

            /**
             *  Meta Args
             *  ---------
             */
            return      array_merge( $args, array( $new_args ) );
        }

        /**
         *  2. Post Type show column data
         *  -----------------------------
         */
        public static function post_type_column_label( $columns = [] ) {

            $columns['venue_id']      =   esc_attr__( 'Venue Info', 'sdweddingdirectory-request-quote' );

            $columns['vendor_id']       =   esc_attr__( 'Vendor Info', 'sdweddingdirectory-request-quote' );

            $columns['request_quote_contact_option']       =   esc_attr__( 'Couple Wish to Contact', 'sdweddingdirectory-request-quote' );

            $columns['request_quote_message']       =   esc_attr__( 'Couple Message', 'sdweddingdirectory-request-quote' );

            return $columns;
        }

        /**
         *  3. Post Type Show Front End Backend Data
         *  ----------------------------------------
         */
        public static function post_type_column_show_data( $column, $post_id ) {

            global $wp_query, $post;

            /**
             *  Is Column is Venue ID ?
             *  -------------------------
             */
            $_condition_1   =   get_post_type( $post_id ) == esc_attr( 'venue-request' );

            /**
             *  Check security is TRUE ?
             *  ------------------------
             */
            if( $column == esc_attr( 'venue_id' ) && $_condition_1  ){

                /**
                 *  Get Venue ID
                 *  --------------
                 */
                $_venue_id    =   absint( get_post_meta( $post_id, sanitize_key( "venue_id" ), true ) );

                /**
                 *  Is Valid Venue ID ?
                 *  ---------------------
                 */
                if( $_venue_id !== '' && $_venue_id !== absint( '0' ) ){

                    /**
                     *  Show Venue Information
                     *  ------------------------
                     */
                    printf( '<a href="%1$s" target="_blank">%2$s</a>',

                        /**
                         *  1. Get Front Page ( Singular Venue Link )
                         *  -------------------------------------------
                         */
                        esc_url( get_the_permalink( $_venue_id ) ),

                        /**
                         *  2. Get Name of Venue
                         *  ----------------------
                         */
                        esc_attr( get_the_title( $_venue_id ) )
                    );
                }
            }

            /**
             *  Check security is TRUE ?
             *  ------------------------
             */
            if( $column == esc_attr( 'vendor_id' ) && $_condition_1  ){

                /**
                 *  Get Venue ID
                 *  --------------
                 */
                $vendor_id    =   absint( get_post_meta( $post_id, sanitize_key( "vendor_id" ), true ) );

                /**
                 *  Is Valid Venue ID ?
                 *  ---------------------
                 */
                if( $vendor_id !== '' && $vendor_id !== absint( '0' ) ){

                    /**
                     *  Show Venue Information
                     *  ------------------------
                     */
                    printf( '<a href="%1$s" target="_blank">%2$s</a>',

                        /**
                         *  1. Get Front Page ( Singular Venue Link )
                         *  -------------------------------------------
                         */
                        esc_url( get_the_permalink( $vendor_id ) ),

                        /**
                         *  2. Get Name of Venue
                         *  ----------------------
                         */
                        esc_attr( get_the_title( $vendor_id ) )
                    );
                }
            }

            /**
             *  Couple Message
             *  ------------------------
             */
            if( $column == esc_attr( 'request_quote_message' ) && $_condition_1  ){

                /**
                 *  Get Venue ID
                 *  --------------
                 */
                $couple_message    =   get_post_meta( $post_id, sanitize_key( "request_quote_message" ), true );

                /**
                 *  Is Valid Venue ID ?
                 *  ---------------------
                 */
                if( $couple_message !== '' ){

                    /**
                     *  Show Venue Information
                     *  ------------------------
                     */
                    print esc_attr( $couple_message );
                }
            }

            /**
             *  Couple Wish to Contact Via ( email / call / video call ) ?
             *  ----------------------------------------------------------
             */
            if( $column == esc_attr( 'request_quote_contact_option' ) && $_condition_1  ){

                /**
                 *  Get Venue ID
                 *  --------------
                 */
                $_contact_via    =   absint( get_post_meta( $post_id, sanitize_key( "request_quote_contact_option" ), true ) );

                /**
                 *  Is Valid Venue ID ?
                 *  ---------------------
                 */
                if( $_contact_via !== '' && $_contact_via !== absint( '0' ) ){

                    if( $_contact_via == absint( '1' ) ){

                        printf( '<span class="text-center">%1$s</span>',

                            /**
                             *  1. Translation Ready String
                             *  ---------------------------
                             */
                            esc_attr__( 'Call', 'sdweddingdirectory-request-quote' )
                        );

                    }elseif( $_contact_via == absint( '2' ) ){

                        printf( '<span class="text-center">%1$s</span>',

                            /**
                             *  1. Translation Ready String
                             *  ---------------------------
                             */
                            esc_attr__( 'Email', 'sdweddingdirectory-request-quote' )    
                        );
                    
                    }elseif( $_contact_via == absint( '3' ) ){

                        printf( '<span class="text-center">%1$s</span>',

                            /**
                             *  1. Translation Ready String
                             *  ---------------------------
                             */
                            esc_attr__( 'Video Call', 'sdweddingdirectory-request-quote' )
                        );
                    }
                }
            }
        }

        /**
         *  Have Post State - After Post Title Highlight
         *  --------------------------------------------
         */
        public static function have_post_state_after_post_title( $_post_state, $post ){

            if( get_post_type( $post ) == 'venue-request' ) {

                $_status    = get_post_meta( $post->ID, sanitize_key( "request_quote_contact_option" ), true );

                if( $_status == absint( '1' ) ){

                    $_post_state[] = 

                    sprintf( '<span class="text-center">%1$s</span>',

                        // 1
                        esc_attr__( 'Approved', 'sdweddingdirectory-request-quote' )
                    );
                }

                if( $_status == absint( '0' ) ){

                    $_post_state[] = 

                    sprintf( '<span class="text-center">%1$s</span>',

                        // 1
                        esc_attr__( 'Pending', 'sdweddingdirectory-request-quote' )
                    );

                }elseif( $_status == absint( '2' ) ){

                    $_post_state[] =

                    sprintf( '<span class="text-center">%1$s</span>',

                        // 1
                        esc_attr__( 'Declain', 'sdweddingdirectory-request-quote' )    
                    );
                }
            }

            return $_post_state;
        }
    }

    /**
     *  Kicking this off by calling 'get_instance()' method
     *  ---------------------------------------------------
     */
    SDWeddingDirectory_Request_Quote_Meta::get_instance();
}
