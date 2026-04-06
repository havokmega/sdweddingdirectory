<?php
/**
 *  SDWeddingDirectory Review : AJAX
 *  ------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Review_AJAX' ) && class_exists( 'SDWeddingDirectory_Reviews_Database' ) ){

    /**
     *  SDWeddingDirectory Review : AJAX
     *  ------------------------
     */
    class SDWeddingDirectory_Review_AJAX extends SDWeddingDirectory_Reviews_Database{

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
                         *  1. Load More review button to load the next review comments
                         *  -----------------------------------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_load_review_comment_posts' ),

                        /**
                         *  2. Vendor Review Response
                         *  -------------------------
                         */
                        esc_attr( 'sdweddingdirectory_vendor_review_response_action' ),

                        /**
                         *  3. Couple Submit Venue Review
                         *  -------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_submit_venue_review' )
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
         *  Security Issue Found!
         *  ---------------------
         */
        public static function security_issue_found(){

            die( json_encode( array(

                'message'   =>  esc_attr__( 'Security Issue Found', 'sdweddingdirectory-reviews' ),

                'notice'    =>  absint( '0' )

            ) ) );
        }

        /**
         *  Check Is Owner of Venue ?
         *  ---------------------------
         */
        public static function is_venue_owner( $_post_id = '' ){

            if( isset( $_post_id ) && $_post_id !== '' && $_post_id !== absint( '0' ) ){

                global $current_user, $post, $wp_query;

                $_condition_1   = isset( $_post_id ) && $_post_id !== '';

                $_venue_id    = $_condition_1 ? absint( $_post_id ) : '';

                $user_roles     = get_userdata( absint( get_post( absint( $_venue_id ) )->post_author ) )->roles;

                /**
                 *   1. Is Venue or Vendor Post Type ?
                 *   -----------------------------------
                 */
                $_post_type  = get_post_type( absint( $_venue_id ) );

                $_security_1 = in_array( $_post_type, [ 'venue', 'vendor' ], true );

                /**
                 *  2. Venue Id to Get > Author ID  to get > Author "ROLE" is vendor ?
                 *  --------------------------------------------------------------------
                 */
                $_security_2    =  in_array( esc_attr( 'vendor' ), $user_roles, true );

                /**
                 *  Check Security & Permission
                 *  ---------------------------
                 */
                if( $_security_1 && $_security_2 ){

                    return true;

                }else{

                    return false;
                }

            }else{

                return false;
            }
        }

        /**
         *  1. Insert Realwedding data through couple login : read wedding page ( backend )
         *  -------------------------------------------------------------------------------
         */
        public static function sdweddingdirectory_submit_venue_review(){

            /**
             *  Check Security
             *  --------------
             */
            $_condition_1       =       isset( $_POST[ 'venue_id' ] ) && $_POST[ 'venue_id' ] !== '';

            $_condition_2       =       isset( $_POST[ 'security' ] ) && $_POST[ 'security' ] !== '';

            $_condition_3       =       wp_verify_nonce( $_POST[ 'security' ], esc_attr( 'security_' . $_POST[ 'form_id' ] ) );

            $_condition_4       =       $_condition_1 ? self:: is_venue_owner( absint( $_POST[ 'venue_id' ] ) ) : false;

            $rating_update      =       ! empty( $_POST[ 'post_id' ] ) && isset( $_POST[ 'post_id' ] ) && 

                                        in_array( get_post_type( $_POST[ 'post_id' ] ), [ 'venue-review' ] )

                                        ?   true    :   false;
 
            /**
             *  Security Check
             *  --------------
             */
            if( $_condition_1 && $_condition_2 && $_condition_3 && $_condition_4 ){

                $_listin_post_id    =   absint( $_POST['venue_id'] );

                $_vendor_id         =   parent:: user_access_post_id( get_user_by(

                                            /**
                                             *  1. ID
                                             *  -----
                                             */
                                            esc_attr( 'id' ),

                                            /**
                                             *  2. User ID
                                             *  ----------
                                             */
                                            get_post( absint( $_POST['venue_id'] ) )->post_author

                                        ) );

                /**
                 *  Make sure user is login
                 *  -----------------------
                 */
                if( ! is_user_logged_in() ){

                    die( json_encode( array(

                        'notice'    =>  absint( '2' ),

                        'redirect'  =>  false,

                        'message'   =>  esc_attr__( 'You must be logged in to review this venue.', 'sdweddingdirectory-reviews' )

                    ) ) );
                }

                /**
                 *  Make sure user is login as vendor and it's not own venue too
                 *  --------------------------------------------------------------
                 */
                if( is_user_logged_in() &&  parent:: is_vendor()  &&

                    absint( get_post( absint( $_POST['venue_id'] ) )->post_author ) ==  absint( parent:: author_id() )
                ){

                    die( json_encode( array(

                        'notice'    =>  absint( '2' ),

                        'redirect'  =>  false,

                        'message'   =>  sprintf( esc_attr__( 'Hello %1$s, You can not review for your own venue.', 'sdweddingdirectory-reviews' ),

                                            // 1
                                            parent:: user_login()
                                        )
                    ) ) );
                }

                $_review_title    =   esc_attr( $_POST[ 'review_title' ] );

                $_review_comment  =   esc_textarea( $_POST['couple_comment'] );

                /**
                 *  Check Review Title is Empty ?
                 *  -----------------------------
                 */
                if( $_review_title == '' || empty( $_review_title ) ){

                    /**
                     *  Error Review Reason is Missing
                     *  ------------------------------
                     */
                    die( json_encode( array(

                        'notice'    =>  absint( '2' ),

                        'redirect'  =>  false,
                      
                        'message'   =>  esc_attr__( 'Please write review reason','sdweddingdirectory-reviews'),

                    ) ) );
                }

                /**
                 *  Check Review Comment is Empty ?
                 *  -----------------------------
                 */
                if( $_review_comment == '' || empty( $_review_comment ) ){

                    /**
                     *  Error Review Comment is Missing
                     *  -------------------------------
                     */
                    die( json_encode( array(

                        'notice'    =>  absint( '2' ),

                        'redirect'  =>  false,
                      
                        'message'   =>  esc_attr__( 'Please write review comment','sdweddingdirectory-reviews'),

                    ) ) );
                }

                /**
                 *  Review Title + Comment is not empty! and it's couple login security pass
                 *  ------------------------------------------------------------------------
                 */
                if( parent:: is_couple() ){

                    /**
                     *  Review Title
                     *  ------------
                     */
                    $_post_title        =     esc_attr( $_POST[ 'review_title' ] );

                    /**
                     *  Have Rating ID Found ?
                     *  ----------------------
                     */
                    if( $rating_update ){

                        /**
                         *  Create Venue Post
                         *  -------------------
                         */
                        $_review_post_ID    =       wp_update_post( [

                                                        'ID'                    =>      absint( $_POST[ 'post_id' ] ),
                            
                                                        'post_author'           =>      absint( '1' ),

                                                        'post_name'             =>      $_post_title,

                                                        'post_title'            =>      $_post_title,

                                                        'post_status'           =>      sdweddingdirectory_option( 'rating_approval', esc_attr( 'pending' ) ),
                                                        
                                                        'post_type'             =>      esc_attr( 'venue-review' )

                                                    ] );
                    }

                    /**
                     *  Create Post
                     *  -----------
                     */
                    else{

                        /**
                         *  Create Venue Post
                         *  -------------------
                         */
                        $_review_post_ID    =       wp_insert_post( [
                            
                                                        'post_author'           =>      absint( '1' ),

                                                        'post_name'             =>      $_post_title,

                                                        'post_title'            =>      $_post_title,

                                                        'post_status'           =>      esc_attr( sdweddingdirectory_option( 'rating_approval', esc_attr( 'pending' ) ) ),
                                                        
                                                        'post_type'             =>      esc_attr( 'venue-review' )

                                                    ] );
                    }

                    /**
                     *  Form Data
                     *  ---------
                     */
                    $_FORM_DATA                 =   array(

                          'couple_id'           =>  absint( parent:: post_id() ),

                          'vendor_id'           =>  absint( $_vendor_id ),

                          'venue_id'          =>  absint( $_listin_post_id ),

                          'quality_service'     =>  absint( $_POST['quality_service'] ),

                          'facilities'          =>  absint( $_POST['facilities'] ),

                          'staff'               =>  absint( $_POST['staff'] ),

                          'flexibility'         =>  absint( $_POST['flexibility'] ),

                          'value_of_money'      =>  absint( $_POST['value_of_money'] ),

                          'couple_comment'      =>  esc_textarea( $_POST['couple_comment'] ),

                          'vendor_comment'      =>  '',

                          'couple_comment_time' =>  esc_attr( date( 'Y-m-d' ) ),

                          'vendor_comment_time' =>  esc_attr( date( 'Y-m-d' ) ),
                    );

                    /**
                     *  Submiting Meta
                     *  --------------
                     */
                    if( parent:: _is_array( $_FORM_DATA ) ){

                        foreach ( $_FORM_DATA as $key => $value) {
                          
                            /**
                             *  Update Review Meta
                             *  ------------------
                             */
                            update_post_meta( absint( $_review_post_ID ), sanitize_key( $key ), $value );
                        }
                    }

                    /**
                     *  Admin Email for review recived for vendor
                     *  -----------------------------------------
                     */
                    $post               =   get_post( $_review_post_ID );

                    $action             =   '&action=edit';

                    $post_type_object   =   get_post_type_object( $post->post_type );

                    $_edit_review_post  =   admin_url( sprintf( $post_type_object->_edit_link . $action, $post->ID ) );

                    /**
                     *  Successfully submited review
                     *  ----------------------------
                     */
                    die( json_encode( array(

                        'notice'        =>  absint( '1' ),

                        'redirect'      =>  true,
                      
                        'message'       =>  $rating_update 

                                            ?   esc_attr__( 'Your Review Updated Successfully!', 'sdweddingdirectory-reviews' )

                                            :   esc_attr__( 'Your Review Send Successfully!', 'sdweddingdirectory-reviews' ),

                        'review_body'   =>  parent:: couple_already_publish_review_message()

                    ) ) );

                }else{

                      die( json_encode( array(

                        'notice'    =>  absint( '2' ),

                        'redirect'  =>  false,

                        'message'   =>  sprintf( esc_attr__( 'Hello %1$s, You can not review for venue.', 'sdweddingdirectory-reviews' ),

                                            // 1
                                            parent:: user_login()
                                        )
                      ) ) );
                }

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  2. Vendor Review Dashboard : Vendor Response for couple
         *  -------------------------------------------------------
         */
        public static function sdweddingdirectory_vendor_review_response_action(){

            /**
             *  Check Security
             *  --------------
             */
            $_condition_1   =   isset( $_POST['review_post_id'] ) && $_POST['review_post_id'] !== '';

            $_condition_2   =   isset( $_POST[ 'security' ] ) && $_POST[ 'security' ] !== '';

            $_condition_3   =   wp_verify_nonce( $_POST[ 'security' ], esc_attr( "vendor_comment-{$_REQUEST['review_post_id']}" ) );

            /**
             *  Review Post ID to Get Venue ID
             *  --------------------------------
             */
            $_venue_id    =   get_post_meta( absint( $_POST['review_post_id'] ), sanitize_key( 'venue_id' ), true );

            $_condition_4   =   ( $_condition_1 && $_venue_id ) 

                                ?   self:: is_venue_owner( $_venue_id ) 

                                :   false;

            /**
             *  Security Check
             *  --------------
             */
            if( $_condition_1 && $_condition_2 && $_condition_3 && $_condition_4 ){

                global $post, $wp_query;

                $_FORM_DATA     =   array(

                    'vendor_comment'        =>  esc_textarea( $_POST[ 'vendor_comment' ] ),

                    'vendor_comment_time'   =>  esc_attr( date( 'Y-m-d' ) ),
                );

                /**
                 *  Update Vendor Response in Review POST
                 *  -------------------------------------
                 */
                
                if( parent:: _is_array( $_FORM_DATA ) ){

                    foreach ( $_FORM_DATA as $key => $value) {
                        
                        update_post_meta( 

                            /**
                             *  Review Post ID
                             *  --------------
                             */
                            absint( $_POST['review_post_id'] ),

                            /**
                             *  Meta Name
                             *  ---------
                             */
                            sanitize_key( $key ), 

                            /**
                             *  Meta Value
                             *  ----------
                             */
                            esc_attr( $value ) 
                        );
                    }
                }

                $vendor_id          =       absint( parent:: post_id() );

                $vendor_img         =       apply_filters( 'sdweddingdirectory/vendor-business-image', [

                                                'post_id'       =>      absint( $vendor_id )

                                            ] );

                $vendor_name        =       apply_filters( 'sdweddingdirectory/user/full-name', [

                                                'post_id'       =>      absint( $vendor_id )

                                            ] );

                /**
                 *  Couple + Vendor : Response Time
                 *  -------------------------------
                 */
                $vendor_comment_time        =       get_post_meta( 

                                                        absint( $_POST[ 'review_post_id' ] ), 

                                                        sanitize_key( 'vendor_comment_time' ), 

                                                        true  
                                                    );
                /**
                 *  Success
                 *  -------
                 */
                die( json_encode( array(

                    'notice'    =>      absint( '1' ),

                    'message'   =>      esc_attr__( 'Response Updated', 'sdweddingdirectory-reviews' ),

                    'html'      =>      sprintf(   '<div class="media reply-box">

                                                        <img src="%1$s" alt="" class="thumb">

                                                        <div class="media-body">

                                                            <div class="d-md-flex justify-content-between mb-3">

                                                                <h4 class="mb-0">%2$s</h4>

                                                                <small class="txt-blue">%4$s</small>

                                                            </div>

                                                            %3$s

                                                        </div>
                                                    </div>', 

                                                    /**
                                                     *  1. Vendor Media
                                                     *  ---------------
                                                     */
                                                    esc_url( $vendor_img ),

                                                    /**
                                                     *  2. Vendor Name
                                                     *  --------------
                                                     */
                                                    esc_attr( $vendor_name ),

                                                    /**
                                                     *  3. Vendor Comment
                                                     *  -----------------
                                                     */
                                                    esc_attr( esc_textarea( $_POST[ 'vendor_comment' ] ) ),

                                                    /**
                                                     *  4. Vendor Response Time
                                                     *  -----------------------
                                                     */
                                                    apply_filters( 'sdweddingdirectory/date-format', [

                                                        'date'      =>      esc_attr( $vendor_comment_time )

                                                    ] )
                                        )
                ) ) );

            }else{

                self:: security_issue_found();
            }
        }

        /**
         *  3. Load Review Commet on Venue Singular Page
         *  ----------------------------------------------
         */
        public static function sdweddingdirectory_load_review_comment_posts() {

            global $wp_query, $post, $page;

            /**
             *  Meta Query Collection
             *  ---------------------
             */
            $meta_query   = [];

            /**
             *  Query have venue ID ?
             *  -----------------------
             */
            if( isset( $_POST['venue_id'] ) && $_POST['venue_id'] !== '' ){

                /**
                 *  Venue ID
                 *  ----------
                 */
                $meta_query[] = array(

                    'key'       =>  esc_attr( 'venue_id' ),
                    'type'      =>  esc_attr( 'numeric' ),
                    'compare'   =>  esc_attr( '=' ),
                    'value'     =>  absint( $_POST['venue_id'] ),
                );
            }

            /**
             *  Query have vendor ID ?
             *  ----------------------
             */
            if( isset( $_POST['vendor_id'] ) && $_POST['vendor_id'] !== '' ){

                /**
                 *  Venue ID
                 *  ----------
                 */
                $meta_query[] = array(

                    'key'       =>  esc_attr( 'vendor_id' ),
                    'type'      =>  esc_attr( 'numeric' ),
                    'compare'   =>  esc_attr( '=' ),
                    'value'     =>  absint( $_POST[ 'vendor_id' ] ),
                );
            }

            /**
             *  Have POST ?
             *  -----------
             */
            $_load_review_comment_query   =   new WP_Query(

                array_merge( 

                    /**
                     *  1. First Args
                     *  -------------
                     */
                    array(

                        'post_type'         =>  esc_attr( 'venue-review' ),

                        'post_status'       =>  array( 'publish' ),

                        'posts_per_page'    =>  absint( '3' ),

                        'paged'             =>  absint( $_POST['page'] ),
                    ),

                    /**
                     *  2. Have Meta Query ?
                     *  --------------------
                     */
                    ( parent:: _is_array( $meta_query ) )

                    ?   array(

                            'meta_query'        => array(

                                    'relation'  => 'AND',

                                    $meta_query,
                            )
                        )

                    :   []
                    
                )
            );

            /**
             *  Collection Data Variable
             *  ------------------------
             */
            $_review_comment                =   '';

            /**
             *  Loader Hide / Show ?
             *  --------------------
             */
            if( $_load_review_comment_query->max_num_pages == absint( $_POST['page'] )  ){

                $_hide_loader_button    =    true;

            }else{

                $_hide_loader_button    =    false;
            }

            /**
             *  Get Review Comment
             *  ------------------
             */
            if ( $_load_review_comment_query->have_posts() ){

                while ( $_load_review_comment_query->have_posts() ) {  $_load_review_comment_query->the_post();

                    /**
                     *  Load the review comment
                     *  -----------------------
                     */
                    $_review_comment        .=      apply_filters( 'sdweddingdirectory/rating', [

                                                        'post_id'       =>      absint( get_the_ID() )

                                                    ] );
                }
            }

            /**
             *  Reset Filter
             *  ------------
             */
            if(  isset( $_load_review_comment_query ) && $_load_review_comment_query !== '' ){

                wp_reset_postdata();
            }

            /**
             *  Success
             *  -------
             */
            die( json_encode( array(

                'review_comments'       =>   $_review_comment,

                'hide_loader_button'    =>   $_hide_loader_button,
 
            ) ) );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Review_AJAX:: get_instance();
}
