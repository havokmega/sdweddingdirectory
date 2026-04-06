<?php
/**
 *  -----------------------------
 *  SDWeddingDirectory - Venue - Object
 *  -----------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Venue_Files' ) && class_exists( 'SDWeddingDirectory_Vendor_Venue_Database' ) ){

    /**
     *  -----------------------------
     *  SDWeddingDirectory - Venue - Object
     *  -----------------------------
     */
    class SDWeddingDirectory_Vendor_Venue_Files extends SDWeddingDirectory_Vendor_Venue_Database{

        /**
         *  A reference to an instance of this class
         *  ----------------------------------------
         */
        private static $instance;

        /**
         *  Member variable
         *  ---------------
         *  @var array
         *  ----------
         */
        private $files = [];

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
         *  Venue Post ID
         *  ---------------
         */
        public static function venue_post_ID(){

            return  isset( $_GET[ 'venue_id' ] ) && $_GET[ 'venue_id' ] !== ''

                    ?   absint( $_GET[ 'venue_id' ] )

                    :   absint( '0' );
        }

        /**
         *  Venue Create Permissions
         *  --------------------------
         */
        public static function _have_venue_permission(){

            $message                =   [];

            $post_id                =   absint( self:: venue_post_ID() );

            $vendor_id              =   absint( parent:: post_id() );

            $post_obj               =   get_post( $post_id );

            /**
             *  Vendor Have Active Plan ?
             *  -------------------------
             */
            $plan_id                =   get_post_meta( $vendor_id, sanitize_key( 'pricing_plan_id' ), true );
            $enforce_capacity       =   apply_filters( 'sdsdweddingdirectoryectory/enable_venue_capacity_check', false );
            $vendor_can_venue     =   $enforce_capacity ? 0 : PHP_INT_MAX;

            /**
             *  Make sure venue Id 
             *  --------------------
             */
            if( $enforce_capacity && ! empty( $vendor_id ) &&  ! empty( $plan_id ) ){

                /**
                 *  Create Venue Capacity
                 *  -----------------------
                 */
                $vendor_can_venue     =   get_post_meta( $plan_id, sanitize_key( 'create_venue_capacity' ), true );

                /**
                 *  Expired Venue Time
                 *  --------------------
                 */
                $expired_time           =   get_post_meta( $vendor_id, sanitize_key( 'pricing_plan_end' ), true );

                /**
                 *  Plan ID
                 *  -------
                 */
                if( ! empty( $plan_id ) &&  ! empty( $expired_time ) ){

                    /**
                     *  Make sure expired date not greater then with today date
                     *  -------------------------------------------------------
                     */
                    if( strtotime( $expired_time ) < strtotime( date( 'Y-m-d' ) ) ){

                        $message[]     =   sprintf( esc_attr__( 'Your pricing plan %1$s is expired dated on %2$s.', 'sdweddingdirectory-venue' ), 

                                                /**
                                                 *  1. Plan Name
                                                 *  ------------
                                                 */
                                                parent:: get_data( esc_attr( 'vendor_plan_name' ) ),

                                                /**
                                                 *  2. Last Name
                                                 *  ------------
                                                 */
                                                date( 'd-m-Y', strtotime( $expired_time ) )
                                            );
                    }
                }
            }

            /**
             *  Venue Capacity Done ?
             *  -----------------------
             */
            if( $enforce_capacity && parent:: dashboard_page_set( 'add-venue' ) ){

                /**
                 *  Find Out Total Venue Created This Author
                 *  ------------------------------------------
                 */
                $author_venues       =    parent:: _count_venue( [

                                                'publish', 'pending', 'trash', 'draft'

                                            ] );

                /**
                 *  Make sure vendor already created venue limit
                 *  ----------------------------------------------
                 */
                if( $author_venues >= $vendor_can_venue ){

                    $message[]     =   sprintf( esc_attr__( 'You can create %1$s venues as your pricing plan.', 'sdweddingdirectory-venue' ),

                                            /**
                                             *  1. First Name
                                             *  -------------
                                             */
                                            absint( $vendor_can_venue )
                                        );
                }
            }

            /**
             *  Edit Post is Author ?
             *  ---------------------
             */
            if ( ! empty( $post_id ) ) {

                if(  absint( parent:: author_id() )  !==  absint( $post_obj->post_author )  ){

                    $message[]     =   esc_attr__( 'You don\'t have the rights to edit this Venue.', 'sdweddingdirectory-venue' );
                }
            }

            /**
             *  Have Message ?
             *  --------------
             */
            if( parent:: _is_array( $message ) ){

                /**
                 *  1. User Info
                 *  ------------
                 */
                array_unshift( $message,

                    /**
                     *  1. Translation Ready String
                     *  ---------------------------
                     */
                    sprintf( esc_attr__( 'Hello %1$s %2$s,', 'sdweddingdirectory-venue' ),

                        /**
                         *  1. First Name
                         *  -------------
                         */
                        parent:: get_data( esc_attr( 'first_name' ) ),

                        /**
                         *  2. Last Name
                         *  ------------
                         */
                        parent:: get_data( esc_attr( 'last_name' ) )
                    )
                );

                /**
                 *  Thank you.
                 *  ----------
                 */
                array_push( $message, esc_attr__( 'Thank you.', 'sdweddingdirectory-venue' ) );

                ?><div class="card border-danger"><div class="card-body"><?php

                    /**
                     *  Message Show it.
                     *  ----------------
                     */
                    foreach( $message as $key => $value ){

                        printf( '<p>%1$s<p>', $value );
                    }

                ?></div></div><?php
            }

            /**
             *  Error Message Get ?
             *  -------------------
             */
            return          parent:: _is_array( $message )     ?   true    :   false;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Load one by one shortcode file
             *  ------------------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once $file;
            }
        }
    }

    /**
     *  -----------------------------
     *  SDWeddingDirectory - Venue - Object
     *  -----------------------------
     */
    SDWeddingDirectory_Vendor_Venue_Files::get_instance();
}
