<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Venue_Expired_Filters' ) && class_exists( 'SDWeddingDirectory_Vendor_Venue_Filters' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Vendor_Venue_Expired_Filters extends SDWeddingDirectory_Vendor_Venue_Filters{

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
             *  SDWeddingDirectory - Cron Process
             *  -------------------------
             *  Vendor Pricing Plan is Expired ?
             *  --------------------------------
             */
            add_action( 'sdweddingdirectory_cron', [ $this, 'sdweddingdirectory_vendor_plan_expired' ], absint( '50' ) );
        }

        /**
         *  --------------------------------
         *  Vendor Pricing Plan is Expired ?
         *  --------------------------------
         */
        public static function sdweddingdirectory_vendor_plan_expired(){
            if( ! apply_filters( 'sdsdweddingdirectoryectory/enable_venue_capacity_check', false ) ){
                return;
            }

            /**
             *  Import : Demo Hire Vendor in Real Wedding
             *  -----------------------------------------
             */
            $_vendor_query          =   new WP_Query( array(

                'post_type'         =>  esc_attr( 'vendor' ), 

                'post_status'       =>  esc_attr( 'publish' ),

                'posts_per_page'    =>  -1

            ) );

            /**
             *  Time Array
             *  ----------
             */
            $_collection_of_vendor      =   [];

            $_time_management           =   [];

            $_today_date                =   strtotime( date( 'Y-m-d' ) );

            $_one_week_ago              =   strtotime(  date( 'Y-m-d' )  . "-1 week"  );

            $_one_day_ago               =   strtotime(  date( 'Y-m-d' )  . "-1 days"  );

            /**
             *  Have Post ?
             *  -----------
             */
            if ( $_vendor_query->have_posts() ){

                while ( $_vendor_query->have_posts() ){  $_vendor_query->the_post(); 

                    $week = $tomorrow = $today = [];

                    /**
                     *  Vendor ID
                     *  ---------
                     */
                    $_vendor_id         =   absint( get_the_ID() );

                    /**
                     *  Vendor ID
                     *  ---------
                     */
                    $_expired_date      =   get_post_meta( absint( $_vendor_id ), sanitize_key( 'pricing_plan_end' ), true );

                    /**
                     *  Expiry Date in Time
                     *  -------------------
                     */
                    $_expired_time      =   strtotime( $_expired_date );

                    /**
                     *  Vendor Email
                     *  ------------
                     */
                    $_vendor_email      =   sanitize_email(

                                                get_post_meta( absint( $_vendor_id ), sanitize_key( 'user_email' ), true )
                                            );

                    /**
                     *  Vendor User ID
                     *  --------------
                     */
                    $_vendor_user_id    =   parent:: author_id_get_via_post_id( $_vendor_id );

                    /**
                     *  Vendor Active Pricing Plan Name
                     *  -------------------------------
                     */
                    $_pricing_plan_id   =   absint( get_post_meta( absint( $_vendor_id ), sanitize_key( 'pricing_plan_id' ), true ) );

                    /**
                     *  Have Pricing Plan ?
                     *  -------------------
                     */
                    $_vendor_plan_name  =   parent:: _have_data( $_pricing_plan_id )

                                        ?   esc_attr( get_the_title(

                                                /**
                                                 *  1. Pricing Plan Name
                                                 *  --------------------
                                                 */
                                                absint( get_post_meta( absint( $_vendor_id ), sanitize_key( 'pricing_plan_id' ), true ) )

                                            ) )

                                        :   esc_attr__( 'Free', 'sdweddingdirectory-venue' );

                    /**
                     *  Expired Time Empty!
                     *  -------------------
                     *  Make sure date is less then today
                     *  ---------------------------------
                     */
                    if( $_today_date >= $_expired_time ){

                        /**
                         *  Have Venue ?
                         *  --------------
                         */
                        $_have_venues     =   self:: vendor_venues( absint( $_vendor_user_id ) );

                        /**
                         *  Vendor Have Venues ?
                         *  ----------------------
                         */
                        if( parent:: _is_array( $_have_venues ) ){

                            $_vendor_data               =   [

                                'vendor_username'       =>  esc_attr( get_the_title( absint( $_vendor_id ) ) ),

                                'vendor_post_id'        =>  absint( $_vendor_id ),

                                'vendor_register_id'    =>  absint( $_vendor_user_id ),

                                'expired_date'          =>  $_expired_date,

                                'vendor_email'          =>  sanitize_email( $_vendor_email ),

                                'venues'              =>  $_have_venues,

                                'pricing_package_name'  =>  $_vendor_plan_name
                            ];

                            $_collection_of_vendor[]    =   $_vendor_data;

                            /**
                             *  1. Vendor Venue Expiry Date is Today ?
                             *  ----------------------------------------
                             */
                            if( $_today_date == $_expired_time ){

                                $_time_management[ 'today' ][]        =  $_vendor_data;
                            }

                            /**
                             *  2. Vendor Venue Expiry Date is Tomorrow ?
                             *  -------------------------------------------
                             */
                            if( $_one_day_ago == $_expired_time ){

                                $_time_management[ 'tomorrow' ][]     =  $_vendor_data;
                            }

                            /**
                             *  3. Vendor Venue Will Expiry in Week ?
                             *  ---------------------------------------
                             */
                            if( $_one_week_ago == $_expired_time ){

                                $_time_management[ 'week' ][]         =   $_vendor_data;
                            }
                        }
                    }
                }
            }

            /**
             *  Reset Query
             *  -----------
             */
            if ( isset( $_vendor_query ) ) {

                wp_reset_postdata();
            }

            /**
             *  Start Collection
             *  ----------------
             */
            if( parent:: _is_array( $_time_management ) ){

                foreach( $_time_management as $key => $value ){

                    /**
                     *  Week Expire : Data
                     *  ------------------
                     */
                    if( $key == esc_attr( 'week' ) ){

                        if( parent:: _is_array( $week ) ){

                            /**
                             *  Expired Venue Process Start with Email to Vendor
                             *  --------------------------------------------------
                             */
                            self:: vendor_venue_expired_in_week( $week );
                        }
                    }

                    /**
                     *  Tomorrow Expire : Data
                     *  ----------------------
                     */
                    if( $key == esc_attr( 'tomorrow' ) ){

                        if( parent:: _is_array( $tomorrow ) ){

                            /**
                             *  Expired Venue Process Start with Email to Vendor
                             *  --------------------------------------------------
                             */
                            self:: vendor_venue_expired_tomorrow( $tomorrow );
                        }
                    }

                    /**
                     *  Today Expire : Data
                     *  -------------------
                     */
                    if( $key == esc_attr( 'today' ) ){

                        if( parent:: _is_array( $today ) ){

                            /**
                             *  Expired Venue Process Start with Email to Vendor
                             *  --------------------------------------------------
                             */
                            self:: vendor_venue_expired_today( $today );
                        }
                    }
                }
            }
        }

        /**
         *  Vendor ID to get venues Post
         *  ------------------------------
         */
        public static function vendor_venues( $vendor_id = 0 ){

            /**
             *  Collection
             *  ----------
             */
            $_collection    =  [];

            /**
             *  Import : Demo Hire Vendor in Real Wedding
             *  -----------------------------------------
             */
            $item                   =   new WP_Query( array(

                'post_type'         =>  esc_attr( 'venue' ), 

                'post_status'       =>  esc_attr( 'publish' ),

                'author'            =>  absint( $vendor_id )

            ) );
            
            /**
             *  Found Total Number of Venue
             *  -----------------------------
             */
            $total_element      =   $item->found_posts;

            /**
             *  Have Venue at least 1 ?
             *  -------------------------
             */
            if( $total_element >= absint( '1' ) ){

                $_collection    =  wp_list_pluck( $item->posts, 'ID' );
            }

            /**
             *  Reset Query
             *  -----------
             */
            if ( isset( $item ) ) {

                wp_reset_postdata();
            }

            /**
             *  Collection
             *  ----------
             */
            return  $_collection;
        }

        /**
         *  Expired Venue In Week
         *  -----------------------
         */
        public static function vendor_venue_expired_in_week( $_data = [] ){

            /**
             *  Email Setting ID
             *  ----------------
             */
            $_setting_id    =   esc_attr( 'venue-expired-week' );

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $_data ) ){

                foreach( $_data as $key => $value ){

                    /**
                     *  Extract
                     *  -------
                     */
                    extract( $value );

                    /**
                     *  Email Process
                     *  -------------
                     */
                    if( class_exists( 'SDWeddingDirectory_Email' ) ){

                        /**
                         *  Sending Email
                         *  -------------
                         */
                        SDWeddingDirectory_Email:: sending_email( array(

                            /**
                             *  1. Setting ID : Email PREFIX_
                             *  -----------------------------
                             */
                            'setting_id'        =>      esc_attr( $_setting_id ),

                            /**
                             *  2. Sending Email ID
                             *  -------------------
                             */
                            'sender_email'      =>      sanitize_email( $vendor_email ),

                            /**
                             *  3. Email Data Key and Value as Setting Body Have {{...}} all
                             *  ------------------------------------------------------------
                             */
                            'email_data'        =>      array(

                                                            'vendor_username'        =>  sanitize_user( $vendor_username ),

                                                            'pricing_plan_name'      =>  esc_attr( $pricing_package_name ),

                                                            'pricing_plan_expire'    =>  sanitize_email( $expired_date ),
                                                        )
                        ) );
                    }
                }
            }
        }

        /**
         *  Expired Venue Tomorrow
         *  ------------------------
         */
        public static function vendor_venue_expired_tomorrow( $_data = [] ){

            /**
             *  Email Setting ID
             *  ----------------
             */
            $_setting_id    =   esc_attr( 'venue-expired-tomorrow' );

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $_data ) ){

                foreach( $_data as $key => $value ){

                    /**
                     *  Extract
                     *  -------
                     */
                    extract( $value );

                    /**
                     *  Email Process
                     *  -------------
                     */
                    if( class_exists( 'SDWeddingDirectory_Email' ) ){

                        /**
                         *  Sending Email
                         *  -------------
                         */
                        SDWeddingDirectory_Email:: sending_email( array(

                            /**
                             *  1. Setting ID : Email PREFIX_
                             *  -----------------------------
                             */
                            'setting_id'        =>      esc_attr( $_setting_id ),

                            /**
                             *  2. Sending Email ID
                             *  -------------------
                             */
                            'sender_email'      =>      sanitize_email( $vendor_email ),

                            /**
                             *  3. Email Data Key and Value as Setting Body Have {{...}} all
                             *  ------------------------------------------------------------
                             */
                            'email_data'        =>      array(

                                                            'vendor_username'        =>  sanitize_user( $vendor_username )
                                                        )
                        ) );
                    }
                }
            }
        }

        /**
         *  Expired Venue Today
         *  ---------------------
         */
        public static function vendor_venue_expired_today( $_data = [] ){

            /**
             *  Email Setting ID
             *  ----------------
             */
            $_setting_id    =   esc_attr( 'venue-expired-today' );

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $_data ) ){

                foreach( $_data as $key => $value ){

                    /**
                     *  Extract
                     *  -------
                     */
                    extract( $value );

                    /**
                     *  Have Venue Data ?
                     *  -------------------
                     */
                    if( parent:: _is_array( $venues ) ){

                        foreach( $venues as $_venue_key => $_venue_post_id ){

                            /**
                             *  Post Status Change with Draft
                             *  -----------------------------
                             */
                            wp_update_post( array(

                                'ID'            =>  absint( $_venue_post_id ),

                                'post_status'   =>  esc_attr( 'draft' )
                            ));
                        }
                    }

                    /**
                     *  Email Process
                     *  -------------
                     */
                    if( class_exists( 'SDWeddingDirectory_Email' ) ){

                        /**
                         *  Sending Email
                         *  -------------
                         */
                        SDWeddingDirectory_Email:: sending_email( array(

                            /**
                             *  1. Setting ID : Email PREFIX_
                             *  -----------------------------
                             */
                            'setting_id'        =>      esc_attr( $_setting_id ),

                            /**
                             *  2. Sending Email ID
                             *  -------------------
                             */
                            'sender_email'      =>      sanitize_email( $vendor_email ),

                            /**
                             *  3. Email Data Key and Value as Setting Body Have {{...}} all
                             *  ------------------------------------------------------------
                             */
                            'email_data'        =>      array(

                                                            'vendor_username'        =>  sanitize_user( $vendor_username )
                                                        )
                        ) );
                    }
                }
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Vendor_Venue_Expired_Filters:: get_instance();
}
