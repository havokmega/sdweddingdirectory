<?php
/**
 *  SDWeddingDirectory Couple Real Wedding AJAX Script Action
 *  -------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Real_Wedding_AJAX' ) && class_exists( 'SDWeddingDirectory_Real_Wedding_Database' ) ){

    /**
     *  SDWeddingDirectory Couple Real Wedding AJAX Script Action
     *  --------------------------------------------------
     */
    class SDWeddingDirectory_Real_Wedding_AJAX extends SDWeddingDirectory_Real_Wedding_Database{

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
                         *  AJAX Group query Handler
                         *  ------------------------
                         */
                        esc_attr( 'sdweddingdirectory_couple_realwedding_add_new_request_handler' ),

                        /**
                         *  Bride And Groom Setting
                         *  -----------------------
                         */
                        esc_attr( 'couple_real_wedding_header_info' ),

                        /**
                         *  Wedding Info Setting
                         *  --------------------
                         */
                        esc_attr( 'couple_real_wedding_info' ),

                        /**
                         *  Venue Services
                         *  ----------------
                         */
                        esc_attr( 'couple_real_wedding_venue_services' ),

                        /**
                         *  Outside Vendors
                         *  ---------------
                         */
                        esc_attr( 'couple_realwedding_vendors' ),

                        /**
                         *  Choose Color
                         *  ------------
                         */
                        esc_attr( 'couple_real_wedding_select_color_filter' ),

                        /**
                         *  Choose Season
                         *  -------------
                         */
                        esc_attr( 'couple_real_wedding_select_season_filter' ),

                        /**
                         *  Choose Style
                         *  ------------
                         */
                        esc_attr( 'couple_real_wedding_select_style_filter' ),

                        /**
                         *  Choose Designer
                         *  ---------------
                         */
                        esc_attr( 'couple_real_wedding_select_designer_filter' ),

                        /**
                         *  Choose Honeymoon Vendor
                         *  -----------------------
                         */
                        esc_attr( 'couple_real_wedding_select_honeymoon_vendor_filter' ),
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
         *  New Fields Added via AJAX
         *  -------------------------
         */
        public static function sdweddingdirectory_couple_realwedding_add_new_request_handler(){

            /**
             *  Allowlist callbacks that can be requested by real-wedding repeaters
             *  -------------------------------------------------------------------
             */
            $allowed_callbacks = [
                'SDWeddingDirectory_Real_Wedding_Database::get_outside_vendor',
            ];

            /**
             *  Requested callback
             *  ------------------
             */
            $_class         = isset( $_POST['class'] ) ? sanitize_text_field( wp_unslash( $_POST['class'] ) ) : '';
            $_member        = isset( $_POST['member'] ) ? sanitize_text_field( wp_unslash( $_POST['member'] ) ) : '';
            $_requested     = $_class . '::' . $_member;

            /**
             *  Reject invalid callback requests
             *  -------------------------------
             */
            if( ! in_array( $_requested, $allowed_callbacks, true ) || ! is_callable( [ $_class, $_member ] ) ){

                wp_send_json_error( [
                    'message'   =>  esc_attr__( 'Invalid request.', 'sdweddingdirectory-real-wedding' ),
                    'notice'    =>  absint( '0' ),
                ] );
            }

            /**
             *  Get Object
             *  ----------
             */
            die( json_encode( array(

                'sdweddingdirectory_ajax_data'  =>  call_user_func( [ $_class, $_member ] )

            ) ) );
        }

        /**
         *  Form Update Notice
         *  ------------------
         */
        public static function form_successfully_updated(){

            /**
             *  Form Successfully Updated
             *  -------------------------
             */
            die( json_encode( array(

                'message'   =>  esc_attr__( 'RealWedding Data Updated Successfully!', 'sdweddingdirectory-real-wedding' ),

                'notice'    =>  absint( '1' )

            ) ) );
        }

        /**
         *  Update
         *  ------
         */
        public static function form_name( $name = '' ){

            /**
             *  Security Check
             *  --------------
             */
            if( wp_verify_nonce( $_POST['security'], $name . '-security' ) ){

                /**
                 *  Real Wedding Post ID
                 *  --------------------
                 */
                $_post_id  =  absint( parent:: realwedding_post_id() );

                /**
                 *  Group Pass
                 *  ----------
                 */
                if( isset( $_POST[ 'input_group' ] ) && parent:: _is_array( $_POST[ 'input_group' ] ) ){

                    foreach( $_POST[ 'input_group' ] as $key => $value ){

                        update_post_meta( absint( $_post_id ), sanitize_key( $key ), $value  );
                    }
                }

                /**
                 *  Group Pass
                 *  ----------
                 */
                if( isset( $_POST[ 'input_group' ] ) && parent:: _is_array( $_POST[ 'input_group' ] ) ){

                    foreach( $_POST[ 'input_group' ] as $key => $value ){

                        update_post_meta( absint( $_post_id ), sanitize_key( $key ), $value  );
                    }
                }

                /**
                 *  Group Pass
                 *  ----------
                 */
                if( isset( $_POST[ 'taxonomy_group' ] ) && parent:: _is_array( $_POST[ 'taxonomy_group' ] ) ){

                    foreach( $_POST[ 'taxonomy_group' ] as $key => $value ){

                        /**
                         *  Taxonomy Update
                         *  ---------------
                         */
                        wp_set_post_terms(

                            /**
                             *  1. Post ID
                             *  ----------
                             */
                            absint( $_post_id ),

                            /**
                             *  2. Taxonomy IDS
                             *  ---------------
                             */
                            $value,

                            /**
                             *  3. Taxonomy Name ( SLUG )
                             *  -------------------------
                             */
                            esc_attr( $key )
                        );
                    }
                }

                /**
                 *  Group Pass
                 *  ----------
                 */
                if( isset( $_POST[ 'location_parent_update' ] ) && parent:: _is_array( $_POST[ 'location_parent_update' ] ) ){

                    foreach( $_POST[ 'location_parent_update' ] as $taxonomy => $term_id ){

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
                            absint( $_post_id ),

                            /**
                             *  2. Taxonomy IDS
                             *  ---------------
                             */
                            array_merge(

                                [ $term_id ],

                                get_ancestors( absint( $term_id ), esc_attr( $taxonomy ) )
                            ),

                            /**
                             *  3. Taxonomy Name ( SLUG )
                             *  -------------------------
                             */
                            $taxonomy
                        );
                    }
                }

                /**
                 *  Group Pass
                 *  ----------
                 */
                if( isset( $_POST[ 'editor_group' ] ) && parent:: _is_array( $_POST[ 'editor_group' ] ) ){

                    /**
                     *  Group Editor
                     *  ------------
                     */
                    foreach( $_POST[ 'editor_group' ] as $key => $value ){

                        /**
                         *  Only For post content condition
                         *  -------------------------------
                         */
                        if( $key == esc_attr( 'post_content' ) ){

                            /**
                             *  Update Post Content
                             *  -------------------
                             */
                            wp_update_post( array(

                                    'ID'                =>   absint( $_post_id ),
                                
                                    'post_author'       =>   absint( parent:: author_id() ),

                                    'post_title'        =>   wp_strip_all_tags( get_the_title( $_post_id ) ),

                                    'post_content'      =>   $value,

                                    'post_status'       =>   esc_attr( 'publish' ),

                                    'post_type'         =>   esc_attr( 'real-wedding' )

                            ), true );
                        }

                        /**
                         *  Else
                         *  ----
                         */
                        else{

                            /**
                             *  Update Post Meta
                             *  ----------------
                             */
                            update_post_meta( absint( $_post_id ), sanitize_key( $key ), $value  );
                        }
                    }
                }

                /**
                 *  Auto-derive location from Venue Booked selection
                 *  -------------------------------------------------
                 */
                if( isset( $_POST['input_group']['our_website_vendor_credits'] ) ){

                    $venue_ids = array_filter( array_map( 'absint', explode( ',', sanitize_text_field( $_POST['input_group']['our_website_vendor_credits'] ) ) ) );

                    if( ! empty( $venue_ids ) ){

                        $venue_id = $venue_ids[0];
                        $venue_locations = wp_get_post_terms( $venue_id, 'venue-location', [ 'fields' => 'names' ] );

                        if( ! is_wp_error( $venue_locations ) && ! empty( $venue_locations ) ){

                            $rw_location_ids = [];

                            foreach( $venue_locations as $location_name ){

                                $rw_term = get_term_by( 'name', $location_name, 'real-wedding-location' );

                                if( $rw_term ){
                                    $rw_location_ids[] = absint( $rw_term->term_id );
                                }
                            }

                            if( ! empty( $rw_location_ids ) ){
                                wp_set_post_terms( $_post_id, $rw_location_ids, 'real-wedding-location' );
                            }
                        }
                    }
                }

                /**
                 *  Form Update Notice
                 *  ------------------
                 */
                self:: form_successfully_updated();
            }

            /**
             *  Security Error
             *  --------------
             */
            else{

                /**
                 *  Security Error
                 *  --------------
                 */
                SDWeddingDirectory_AJAX:: security_issue_found();
            }
        }

        /**
         *  Bride And Groom Setting
         *  -----------------------
         */
        public static function couple_real_wedding_header_info(){

            /**
             *  Form Update
             *  -----------
             */
            self:: form_name( __FUNCTION__ );
        }

        /**
         *  Wedding Info Setting
         *  --------------------
         */
        public static function couple_real_wedding_info(){

            /**
             *  Form Update
             *  -----------
             */
            self:: form_name( __FUNCTION__ );
        }

        /**
         *  Venue Services
         *  ----------------
         */
        public static function couple_real_wedding_venue_services(){

            /**
             *  Form Update
             *  -----------
             */
            self:: form_name( __FUNCTION__ );
        }

        /**
         *  Outside Vendors
         *  ---------------
         */
        public static function couple_realwedding_vendors(){

            /**
             *  Form Update
             *  -----------
             */
            self:: form_name( __FUNCTION__ );
        }

        /**
         *  Color Update
         *  ------------
         */
        public static function couple_real_wedding_select_color_filter(){

            /**
             *  Real Wedding Post ID
             *  --------------------
             */
            $post_id        =   absint( parent:: realwedding_post_id() );

            $term_id        =   absint( $_POST[ 'term_id' ] );

            $taxonomy       =   esc_attr( $_POST[ 'taxonomy' ] );

            /**
             *  Taxonomy Update
             *  ---------------
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
                [ $term_id ],

                /**
                 *  3. Taxonomy Name ( SLUG )
                 *  -------------------------
                 */
                $taxonomy
            );

            /**
             *  Object
             *  ------
             */
            $obj    =   get_term_by(

                            /**
                             *  1. Venue Name
                             *  ---------------
                             */
                            esc_attr( 'id' ),

                            /**
                             *  2. Get Title
                             *  ------------
                             */
                            $term_id, 

                            /**
                             *  3. Categroy Slug
                             *  ----------------
                             */
                            $taxonomy
                        );

            /**
             *  Form Successfully Updated
             *  -------------------------
             */
            die( json_encode( array(

                'message'   =>  esc_attr__( 'Your Color Updated!', 'sdweddingdirectory-real-wedding' ),

                'notice'    =>  absint( '1' ),

                'name'      =>  esc_attr( $obj->name ),

                'count'     =>  sprintf( esc_attr__( '%1$s couples', 'sdweddingdirectory-real-wedding' ),

                                    /**
                                     *  1. Count
                                     *  --------
                                     */
                                    absint( $obj->count )
                                ),

                'color'     =>  sdwd_get_term_field(

                                    /**
                                     *  1. Term Key
                                     *  -----------
                                     */
                                    'color',

                                    /**
                                     *  2. Term ID
                                     *  ----------
                                     */
                                    $term_id
                                )

            ) ) );
        }

        /**
         *  Season Update
         *  -------------
         */
        public static function couple_real_wedding_select_season_filter(){

            /**
             *  Real Wedding Post ID
             *  --------------------
             */
            $post_id        =   absint( parent:: realwedding_post_id() );

            $term_id        =   absint( $_POST[ 'term_id' ] );

            $taxonomy       =   esc_attr( $_POST[ 'taxonomy' ] );

            /**
             *  Taxonomy Update
             *  ---------------
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
                [ $term_id ],

                /**
                 *  3. Taxonomy Name ( SLUG )
                 *  -------------------------
                 */
                $taxonomy
            );

            /**
             *  Object
             *  ------
             */
            $obj    =   get_term_by(

                            /**
                             *  1. Venue Name
                             *  ---------------
                             */
                            esc_attr( 'id' ),

                            /**
                             *  2. Get Title
                             *  ------------
                             */
                            $term_id, 

                            /**
                             *  3. Categroy Slug
                             *  ----------------
                             */
                            $taxonomy
                        );

            /**
             *  Form Successfully Updated
             *  -------------------------
             */
            die( json_encode( array(

                'message'   =>  esc_attr__( 'Your Season Updated!', 'sdweddingdirectory-real-wedding' ),

                'notice'    =>  absint( '1' ),

                'name'      =>  esc_attr( $obj->name ),

                'count'     =>  sprintf( esc_attr__( '%1$s couples', 'sdweddingdirectory-real-wedding' ),

                                    /**
                                     *  1. Count
                                     *  --------
                                     */
                                    absint( $obj->count )
                                ),

                'icon'      =>  sdwd_get_term_field(

                                    /**
                                     *  1. Term Key
                                     *  -----------
                                     */
                                    'icon',

                                    /**
                                     *  2. Term ID
                                     *  ----------
                                     */
                                    $term_id
                                )

            ) ) );
        }

        /**
         *  Style Update
         *  ------------
         */
        public static function couple_real_wedding_select_style_filter(){

            /**
             *  Real Wedding Post ID
             *  --------------------
             */
            $post_id        =   absint( parent:: realwedding_post_id() );

            $term_id        =   absint( $_POST[ 'term_id' ] );

            $taxonomy       =   esc_attr( $_POST[ 'taxonomy' ] );

            /**
             *  Taxonomy Update
             *  ---------------
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
                [ $term_id ],

                /**
                 *  3. Taxonomy Name ( SLUG )
                 *  -------------------------
                 */
                $taxonomy
            );

            /**
             *  Object
             *  ------
             */
            $obj    =   get_term_by(

                            /**
                             *  1. Venue Name
                             *  ---------------
                             */
                            esc_attr( 'id' ),

                            /**
                             *  2. Get Title
                             *  ------------
                             */
                            $term_id, 

                            /**
                             *  3. Categroy Slug
                             *  ----------------
                             */
                            $taxonomy
                        );

            /**
             *  Form Successfully Updated
             *  -------------------------
             */
            die( json_encode( array(

                'message'   =>  esc_attr__( 'Your Style Updated!', 'sdweddingdirectory-real-wedding' ),

                'notice'    =>  absint( '1' ),

                'name'      =>  esc_attr( $obj->name ),

                'count'     =>  sprintf( esc_attr__( '%1$s couples', 'sdweddingdirectory-real-wedding' ),

                                    /**
                                     *  1. Count
                                     *  --------
                                     */
                                    absint( $obj->count )
                                ),

                'icon'      =>  sdwd_get_term_field(

                                    /**
                                     *  1. Term Key
                                     *  -----------
                                     */
                                    'icon',

                                    /**
                                     *  2. Term ID
                                     *  ----------
                                     */
                                    $term_id
                                )

            ) ) );
        }

        /**
         *  Select Designer
         *  ---------------
         */
        public static function couple_real_wedding_select_designer_filter(){

            /**
             *  Global Query
             *  ------------
             */
            global $post, $wp_query;

            /**
             *  Real Wedding Post ID
             *  --------------------
             */
            $post_id        =   absint( parent:: realwedding_post_id() );

            /**
             *  Dress Vendor
             *  ------------
             */
            $_venue_id    =   isset( $_POST[ 'realwedding-dress' ] ) && $_POST['realwedding-dress'] != '';

            /**
             *  Make sure post id and dress vendor id not empty!
             *  ------------------------------------------------
             */
            if( ! empty( $post_id ) && ! empty( $_venue_id )  ){

                /**
                 *  Update Post Meta
                 *  ----------------
                 */
                update_post_meta( $post_id, sanitize_key( 'realwedding-dress' ), $_POST['realwedding-dress'] );

                /**
                 *  Message
                 *  -------
                 */
                die( json_encode( [

                    'message'   =>  esc_attr__( 'Your Designer Updated!', 'sdweddingdirectory-real-wedding' ),

                    'notice'    =>  absint( '1' ),

                    'name'      =>  esc_attr( get_the_title( absint( $_POST['realwedding-dress'] ) ) ),

                    'count'     =>  sprintf( esc_attr__( '%1$s couples', 'sdweddingdirectory-real-wedding' ),

                                        /**
                                         *  1. Count
                                         *  --------
                                         */
                                        parent:: realwedding_meta_value_count( [

                                            'meta_key'      =>      esc_attr( 'realwedding-dress' ),

                                            'meta_value'    =>      absint( $_POST['realwedding-dress'] ),

                                        ] )
                                    )
                ] ) );
            }

            /**
             *  Error
             *  -----
             */
            else{

                /**
                 *  Security Error
                 *  --------------
                 */
                SDWeddingDirectory_AJAX:: security_issue_found();
            }
        }

        /**
         *  Choose Honeymoon Vendor
         *  -----------------------
         */
        public static function couple_real_wedding_select_honeymoon_vendor_filter(){

            /**
             *  Real Wedding Post ID
             *  --------------------
             */
            $_post_id           =   absint( parent:: realwedding_post_id() );

            $taxonomy_value     =   absint( $_POST[ 'term_id' ] );

            $taxonomy_name      =   esc_attr( 'real-wedding-location' );

            /**
             *  Tax list
             *  --------
             */
            $_taxonomy_list     =    get_term_parents_list(

                                        /**
                                         *  1. Location ID
                                         *  --------------
                                         */
                                        absint( $taxonomy_value ),

                                        /**
                                         *  2. Taxonomy
                                         *  -----------
                                         */
                                        $taxonomy_name,

                                        /**
                                         *  3. Args
                                         *  -------
                                         */
                                        [
                                            'format'    =>  esc_attr( 'slug' ),

                                            'link'      =>  false
                                        ]
                                    );
            /**
             *  Tax object
             *  ----------
             */
            $_taxonomy_object       =   array_filter( explode( '/', $_taxonomy_list ) );

            /**
             *  Have Data ?
             *  -----------
             */
            foreach( $_taxonomy_object as $key => $value ){

                $_taxonomy_object[]     =   get_term_by(

                                                /**
                                                 *  1. Venue Name
                                                 *  ---------------
                                                 */
                                                esc_attr( 'slug' ),

                                                /**
                                                 *  2. Get Title
                                                 *  ------------
                                                 */
                                                $value, 

                                                /**
                                                 *  3. Categroy Slug
                                                 *  ----------------
                                                 */
                                                $taxonomy_name

                                            )->term_id;
            }

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
                absint( $_post_id ),

                /**
                 *  2. Taxonomy IDS
                 *  ---------------
                 */
                $_taxonomy_object,

                /**
                 *  3. Taxonomy Name ( SLUG )
                 *  -------------------------
                 */
                $taxonomy_name
            );

            /**
             *  Object
             *  ------
             */
            $obj    =   get_term_by(

                            /**
                             *  1. Venue Name
                             *  ---------------
                             */
                            esc_attr( 'id' ),

                            /**
                             *  2. Get Title
                             *  ------------
                             */
                            $taxonomy_value,

                            /**
                             *  3. Categroy Slug
                             *  ----------------
                             */
                            $taxonomy_name
                        );

            /**
             *  Form Successfully Updated
             *  -------------------------
             */
            die( json_encode( array(

                'message'   =>  esc_attr__( 'Your Honymoon Locatrion Updated!', 'sdweddingdirectory-real-wedding' ),

                'notice'    =>  absint( '1' ),

                'name'      =>  esc_attr( $obj->name ),

                'count'     =>  sprintf( esc_attr__( '%1$s couples', 'sdweddingdirectory-real-wedding' ),

                                    /**
                                     *  1. Count
                                     *  --------
                                     */
                                    absint( $obj->count )
                                )
            ) ) );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Real_Wedding_AJAX:: get_instance();
}
