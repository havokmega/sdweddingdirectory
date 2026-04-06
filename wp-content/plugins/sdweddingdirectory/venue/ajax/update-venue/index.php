<?php
/**
 *  SDWeddingDirectory Vendor Venue Page AJAX Script Action HERE
 *  ------------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Update_Venue_AJAX' ) && class_exists( 'SDWeddingDirectory_Vendor_Venue_AJAX' ) ){

    /**
     *  SDWeddingDirectory Vendor Venue Page AJAX Script Action HERE
     *  ------------------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Update_Venue_AJAX extends SDWeddingDirectory_Vendor_Venue_AJAX{

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
             *  1. Have AJAX action ?
             *  ---------------------
             */
            if(  wp_doing_ajax()  ){

                /**
                 *  Have AJAX action ?
                 *  ------------------
                 */
                if( isset( $_POST['action'] ) && $_POST['action'] !== '' ){

                    /**
                     *  Have AJAX action ?
                     *  ------------------
                     */
                    $action     =   esc_attr( trim( $_POST['action'] ) );

                    /**
                     *  1.  Bit of security
                     *  -------------------
                     */
                    $allowed_actions = array(

                        /**
                         *  7. Update Venue Data
                         *  ----------------------
                         */
                        esc_attr( 'sdweddingdirectory_update_venue_action' ),
                    );

                    /**
                     *  If have action then load this object members
                     *  --------------------------------------------
                     */
                    if( in_array( $action, $allowed_actions) ) {

                        /**
                         *  Check the AJAX action with login user
                         *  -------------------------------------
                         */
                        if( is_user_logged_in() ){

                            /**
                             *  1. If user already login then AJAX action
                             *  -----------------------------------------
                             */
                            add_action( 'wp_ajax_' . $action, [ $this, $action ] );

                        }else{

                            /**
                             *  2. If user not login that mean is front end AJAX action
                             *  -------------------------------------------------------
                             */
                            add_action( 'wp_ajax_nopriv_' . $action, [ $this, $action ] );
                        }
                    }
                }
            }
        }

        /**
         *  7. Update Venue Data
         *  ----------------------
         *  @link https://wordpress.stackexchange.com/questions/342115/convert-this-textarea-to-rich-html-format-via-wp-editor#answer-342117
         *  ----------------------------------------------------
         *  Post Content How to sanitize with update in database
         *  ----------------------------------------------------
         */
        public static function sdweddingdirectory_update_venue_action(){

            /**
             *  Need
             *  ----
             */
            global $current_user, $post, $wp_query;

            /**
             *  Check Security 
             *  --------------
             */
            if( wp_verify_nonce( $_POST[ 'security' ], esc_attr( 'sdweddingdirectory_update_venue' ) ) ){

                /**
                 *  Validation with Mandatory Venue Form Fields
                 *  ---------------------------------------------
                 */
                $fields_validation      =       apply_filters( 'sdweddingdirectory/venue-fields/required', [] );

                /**
                 *  Fields Test
                 *  -----------
                 */
                if(  parent:: _is_array(  $fields_validation  )  ){

                    foreach( $fields_validation as $key => $value ){

                        /**
                         *  Required Fields Message
                         *  -----------------------
                         */
                        if(  empty( $_POST[ $key ] )  ){

                            /**
                             *  Extract Args
                             *  ------------
                             */
                            extract(  $value  );

                            /**
                             *  Venue Added in SDWeddingDirectory Site : Successfully 
                             *  -----------------------------------------------
                             */
                            die( json_encode( array(

                                'notice'                =>      absint( $notice ),

                                'message'               =>      esc_attr( $message ),

                            ) ) );
                        }
                    }
                }

                /**
                 *  Have Venue ID ?
                 *  -----------------
                 */
                if( isset( $_POST[ 'venue_id' ] ) && ! empty( $_POST[ 'venue_id' ] ) ){

                    /**
                     *   Is Venue Author ?
                     *   -------------------
                     */
                    $post_id   =    absint( $_POST[ 'venue_id' ] );

                    /**
                     *  Check Post Author is Updated Own POST ?
                     *  ---------------------------------------
                     */
                    if ( $current_user->ID != get_post( $post_id )->post_author ) {

                        /**
                         *  It's another venue edit request
                         *  ---------------------------------
                         */
                        die( json_encode( [

                            'message'   =>  esc_attr__( 'You don\'t have the rights to edit this Venue.', 'sdweddingdirectory-venue' ),

                            'notice'    =>  absint( '2' )

                        ] ) );
                    }

                    /**
                     *  Update Post
                     *  -----------
                     */
                    $post_id    =   wp_update_post( [

                                        'ID'                =>   absint( $post_id ),
                                    
                                        'post_author'       =>   absint( parent:: author_id() ),

                                        'post_title'        =>   wp_strip_all_tags( $_POST[ 'post_title' ] ),

                                        'post_content'      =>   $_POST[ 'post_content' ],

                                        'post_status'       =>   esc_attr( parent:: sdweddingdirectory_lisitng_post_status() ),

                                        'post_type'         =>   esc_attr( 'venue' )

                                    ] );

                    if( ! is_wp_error( $post_id ) && absint( $post_id ) > 0 ){

                        $slug_source = ! empty( $_POST['sdweddingdirectory_venue_slug'] )
                                        ? sanitize_text_field( $_POST['sdweddingdirectory_venue_slug'] )
                                        : wp_strip_all_tags( $_POST[ 'post_title' ] );

                        $slug_update = parent:: apply_validated_slug(
                                            absint( $post_id ),
                                            $slug_source,
                                            [
                                                'user_id'       => absint( parent:: author_id() ),
                                                'post_types'    => [ 'vendor', 'venue' ],
                                            ]
                                        );

                        if( empty( $slug_update['updated'] ) ){

                            die( json_encode( array(

                                'notice'        =>      absint( '2' ),

                                'redirect'      =>      false,

                                'message'       =>      ! empty( $slug_update['message'] ) ? esc_attr( $slug_update['message'] ) : esc_attr__( 'Venue slug update failed.', 'sdweddingdirectory-venue' )

                            ) ) );
                        }
                    }
                }

                /**
                 *  Create Post
                 *  -----------
                 */
                else{

                    /**
                     *  Insert New Post 
                     *  ---------------
                     */
                    $post_id    =   wp_insert_post( array(
                        
                                        'post_author'       =>   absint( parent:: author_id() ),

                                        'post_name'         =>   wp_strip_all_tags( $_POST[ 'post_title' ] ),

                                        'post_title'        =>   wp_strip_all_tags( $_POST[ 'post_title' ] ),

                                        'post_content'      =>   $_POST[ 'post_content' ],

                                        'post_status'       =>   esc_attr( parent:: sdweddingdirectory_lisitng_post_status() ),

                                        'post_type'         =>   esc_attr( 'venue' )

                                    ) );
                }


                /**
                 *  Have WordPress Error ?
                 *  ----------------------
                 */
                if ( is_wp_error( $post_id ) ) {

                    /**
                     *  Showing the WP Error
                     *  --------------------
                     */
                    die( json_encode( array(

                        'notice'        =>      absint( '2' ),
                        
                        'redirect'      =>      false, 

                        'message'       =>      $post_id->get_error_messages()

                    ) ) );
                }

                /**
                 *  No WP Error Found! 
                 *  ------------------
                 *  Process to update post meta / tax
                 *  ---------------------------------
                 */
                else{

                    /**
                     *  Extract Args
                     *  ------------
                     */
                    extract( [

                        'location_tax'                  =>      esc_attr( 'venue-location' ),

                        'category_tax'                  =>      esc_attr( 'venue-type' ),

                        'group_accordion'               =>      [ 'venue_faq', 'venue_menu', 'venue_facilities', 'venue_team' ],

                        'term_meta_collection'          =>      [],

                        'calander_meta_collection'      =>      []

                    ] );

                    /**
                     *  Have Location ?
                     *  ---------------
                     */
                    if( isset( $_POST[ 'venue_location' ] ) && ! empty( $_POST[ 'venue_location' ] ) ){

                        /**
                         *   ---------------------------------------------------
                         *   Insert venue locations ( country / state / city )
                         *   ---------------------------------------------------
                         *   @link : https://developer.wordpress.org/reference/functions/wp_set_post_terms/
                         *   ------------------------------------------------------------------------------
                         */
                        wp_set_post_terms(

                            /**
                             *  1. Post ID
                             *  ----------
                             */
                            absint( $post_id ),

                            /**
                             *  2. Taxonomy IDS
                             *  ---------------
                             */
                            [ absint( $_POST[ 'venue_location' ] ) ],

                            /**
                             *  3. Taxonomy Name ( SLUG )
                             *  -------------------------
                             */
                            $location_tax
                        );
                    }

                    /**
                     *  Have Category ?
                     *  ---------------
                     */
                    if( isset( $_POST[ 'venue_category' ] ) && ! empty( $_POST[ 'venue_category' ] ) ){

                        /**
                         *   -----------------------
                         *   Insert venue category
                         *   -----------------------
                         *   @link : https://developer.wordpress.org/reference/functions/wp_set_post_terms/
                         *   ------------------------------------------------------------------------------
                         */
                        wp_set_post_terms( 

                            /**
                             *  1. Post ID
                             *  ----------
                             */
                            absint( $post_id ), 

                            /**
                             *  2. Category IDS
                             *  ---------------
                             */
                            array_merge(

                                /**
                                 *  1. Get Parent Category
                                 *  ----------------------
                                 */
                                array( absint( $_POST[ 'venue_category' ] ) ),

                                /**
                                 *  2. Get Sub Category Array Keys
                                 *  ------------------------------
                                 */
                                isset( $_POST['venue_sub_category'] ) &&  $_POST['venue_sub_category'] !== ''

                                ?   array_keys( $_POST['venue_sub_category'] )

                                :   []
                            ),

                            /**
                             *  3. Get Taxonomy SLUG
                             *  --------------------
                             */
                            esc_attr( $category_tax )
                        );
                    }

                    /**
                     *  Group Accordion
                     *  ---------------
                     */
                    if( parent:: _is_array( $group_accordion ) ){

                        foreach( $group_accordion as $key => $value ){

                            if( isset( $_POST[ $value ] ) && parent:: _is_array( $_POST[ $value ] ) ){

                                update_post_meta( absint( $post_id ), sanitize_key( $value ), $_POST[ $value ] );
                            }
                        }
                    }

                    /**
                     *  Make sure term group box exits
                     *  ------------------------------
                     */
                    if( isset( $_POST[ 'term_group_box' ] ) && parent:: _is_array( $_POST[ 'term_group_box' ] ) ){

                        if( parent:: _is_array( $_POST[ 'term_group_box' ] ) ){

                            foreach( $_POST[ 'term_group_box' ] as $key => $value ){

                                if( parent:: _is_array( $value ) ){

                                    foreach( $value as $term_key => $term_value ){

                                        $term_meta_collection[ $key ][ $term_key ]   =   $term_key;
                                    }
                                }
                            }
                        }
                    }

                    /**
                     *  Make sure Calender Data exits
                     *  -----------------------------
                     */
                    if( isset( $_POST[ 'calender_data' ] ) && parent:: _is_array( $_POST[ 'calender_data' ] ) ){

                        if( parent:: _is_array( $_POST[ 'calender_data' ] ) ){

                            foreach( $_POST[ 'calender_data' ] as $key => $value ){

                                if( parent:: _is_array( $value ) ){

                                    foreach( $value as $calander_key => $calander_value ){

                                        $calander_meta_collection[ $key ][ $calander_key ]   =   $calander_key;
                                    }
                                }

                                else{

                                    $calander_meta_collection[ $key ]   =   $value;
                                }
                            }
                        }
                    }


                    /**
                     *  Form Data
                     *  ---------
                     */                
                    $_FORM_DATA     =   [   'venue_address'         =>    ! empty( $_POST['venue_address'] ) 

                                                                            ?   esc_attr( $_POST['venue_address'] )

                                                                            :   '',

                                            'venue_latitude'        =>    ! empty( $_POST['venue_latitude'] ) 

                                                                            ?   esc_attr( $_POST['venue_latitude'] )

                                                                            :   '',

                                            'venue_longitude'       =>    ! empty( $_POST['venue_longitude'] ) 

                                                                            ?   esc_attr( $_POST['venue_longitude'] )

                                                                            :   '',

                                            'venue_pincode'         =>    ! empty( $_POST['venue_pincode'] ) 

                                                                            ?   esc_attr( $_POST['venue_pincode'] )

                                                                            :   '',

                                            'venue_gallery'         =>    ! empty( $_POST['venue_gallery'] ) 

                                                                            ?   esc_attr( $_POST['venue_gallery'] )

                                                                            :   '',

                                            'venue_video'           =>    ! empty( $_POST['venue_video'] ) 

                                                                            ?   esc_url( $_POST['venue_video'] )

                                                                            :   '',

                                            'venue_seat_capacity'   =>    ! empty( $_POST['venue_seat_capacity'] ) 

                                                                            ?   absint( $_POST['venue_seat_capacity'] )

                                                                            :   '',

                                            'venue_min_price'       =>    ! empty( $_POST['venue_min_price'] ) 

                                                                            ?   absint( $_POST['venue_min_price'] )

                                                                            :   '',

                                            'venue_max_price'       =>    ! empty( $_POST['venue_max_price'] ) 

                                                                            ?   absint( $_POST['venue_max_price'] )

                                                                            :   '',

                                            'preferred_venues'       =>    ! empty( $_POST['preferred_venues'] ) 

                                                                            ?   esc_attr( $_POST['preferred_venues'] )

                                                                            :   '',

                                            'map_address'             =>    ! empty( $_POST['map_address'] ) 

                                                                            ?   esc_attr( $_POST['map_address'] )

                                                                            :   '',

                                            '_thumbnail_id'           =>    !   empty( $_POST[ '_thumbnail_id' ] ) 

                                                                            ?   absint( $_POST[ '_thumbnail_id' ] )

                                                                            :   '',
                                        ];
                    /**
                     *  Server-side geocode fallback
                     *  ----------------------------
                     *  If address is present but lat/lng are missing, attempt to geocode.
                     */
                    if( ! empty( $_FORM_DATA['venue_address'] ) && ( empty( $_FORM_DATA['venue_latitude'] ) || empty( $_FORM_DATA['venue_longitude'] ) ) ){

                        $geocode_address = $_FORM_DATA['venue_address'];

                        if( ! empty( $_FORM_DATA['venue_pincode'] ) ){
                            $geocode_address .= ', ' . $_FORM_DATA['venue_pincode'];
                        }

                        $api_key = SDWeddingDirectory_Google_Map_Config:: map_api_key();

                        $geocode_url = add_query_arg( [
                            'address' => urlencode( $geocode_address . ', San Diego, CA' ),
                            'key'     => $api_key,
                        ], 'https://maps.googleapis.com/maps/api/geocode/json' );

                        $response = wp_remote_get( esc_url_raw( $geocode_url ), [ 'timeout' => 5 ] );

                        if( ! is_wp_error( $response ) ){

                            $body = json_decode( wp_remote_retrieve_body( $response ), true );

                            if( ! empty( $body['results'][0]['geometry']['location'] ) ){

                                $location = $body['results'][0]['geometry']['location'];

                                $_FORM_DATA['venue_latitude']  = sanitize_text_field( $location['lat'] );
                                $_FORM_DATA['venue_longitude'] = sanitize_text_field( $location['lng'] );

                                if( empty( $_FORM_DATA['map_address'] ) ){
                                    $_FORM_DATA['map_address'] = sanitize_text_field( $body['results'][0]['formatted_address'] );
                                }
                            }
                        }
                    }

                    /**
                     *  Group
                     *  -----
                     */
                    $collection         =       array_merge( $_FORM_DATA, $term_meta_collection, $calander_meta_collection );

                    /**
                     *  Update Meta
                     *  -----------
                     */
                    if( parent:: _is_array( $collection ) ){

                        foreach( $collection as $key => $value ){

                            update_post_meta( absint( $post_id ), sanitize_key( $key ), $value );
                        }
                    }

                    /**
                     *  Working Hours
                     *  -------------
                     */
                    if( parent:: _is_array( $_POST[ 'working_hours' ] ) ){

                        foreach( $_POST[ 'working_hours' ] as $key => $value ){

                            update_post_meta( $post_id, $key, $value );
                        }
                    }

                    /**
                     *  Have Customisation ?
                     *  --------------------
                     */
                    if( isset( $_POST[ 'customisation_task' ] ) && ! empty( $_POST[ 'customisation_task' ] ) ){

                        $action     =   $_POST[ 'customisation_task' ][ 'action' ];

                        $args       =   $_POST[ 'customisation_task' ][ 'args' ];

                        /**
                         *  Action with Args
                         *  ----------------
                         */
                        if( ! empty( $action ) && ! empty( $args ) ){

                            /**
                             *  Create Action with Args
                             *  -----------------------
                             */
                            do_action( $action, array_merge( $args, [ 'post_id' => $post_id ] ) );
                        }
                    }

                    /**
                     *  Venue Added in SDWeddingDirectory Site : Successfully 
                     *  -----------------------------------------------
                     */
                    die( json_encode( array(

                        'notice'                =>      absint( '1' ),

                        'redirect'              =>      true,

                        'redirect_link'         =>      apply_filters( 'sdweddingdirectory/vendor-menu/page-link', esc_attr( 'vendor-venue' ) ),

                        'message'               =>      esc_attr__( 'Your Venue Updated Successfully!', 'sdweddingdirectory-venue' ),

                    ) ) );
                }
            }

            /**
             *  Security Issue
             *  --------------
             */
            else{

                parent:: security_issue_found();
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Vendor_Update_Venue_AJAX:: get_instance();
}
