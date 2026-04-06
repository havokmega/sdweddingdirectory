<?php
/**
 *  SDWeddingDirectory - Duplicate Venue
 *  ------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Venue_Category_Data_AJAX' ) && class_exists( 'SDWeddingDirectory_Vendor_Venue_AJAX' ) ){

    /**
     *  SDWeddingDirectory - Duplicate Venue
     *  ------------------------------
     */
    class SDWeddingDirectory_Vendor_Venue_Category_Data_AJAX extends SDWeddingDirectory_Vendor_Venue_AJAX{

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
                         *  Category Wise Data Collection
                         *  -----------------------------
                         */
                        esc_attr( 'sdweddingdirectory_venue_category_related_data' )
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
         *  Get Category Term Wise Related Data
         *  -----------------------------------
         */
        public static function sdweddingdirectory_venue_category_related_data(){

            /**
             *  Selected Terms
             *  --------------
             */
            $post_terms     =       [];

            $taxonomy       =       esc_attr( 'venue-type' );

            $term_id        =       isset( $_POST[ 'term_id' ] ) && $_POST[ 'term_id' ] !== ''

                                    ?   absint( $_POST[ 'term_id' ] )

                                    :   absint( '0' );

            $post_id        =       isset( $_POST[ 'post_id' ] ) && $_POST[ 'post_id' ] !== ''

                                    ?   absint( $_POST[ 'post_id' ] )

                                    :   absint( '0' );

            /**
             *  Post ID wise collection of Data
             *  -------------------------------
             */
            if( ! empty( $post_id ) ){

                /**
                 *  Have Term ?
                 *  -----------
                 */
                $term_exits         =       wp_get_post_terms( $post_id, $taxonomy, [ 'fields' => 'ids' ] );

                /**
                 *  Make sure have term data in post
                 *  --------------------------------
                 */
                if( parent:: _is_array( $term_exits )  ){

                    $post_terms        =       $term_exits;
                }
            }

            /**
             *  Return Data
             *  -----------
             */
            die( json_encode( [

                'show_hide_fields'  =>  apply_filters( 'sdweddingdirectory/venue-type/show-hide/fields', [], [

                                            'term_id'               =>      absint( $term_id ),

                                            'post_id'               =>      absint( $post_id ),

                                            'selected_terms'        =>      $post_terms,

                                            'taxonomy'              =>      $taxonomy

                                        ] ),

                'checkbox_fields'   =>  apply_filters( 'sdweddingdirectory/venue-type/checkbox/fields', [], [

                                            'term_id'               =>      absint( $term_id ),

                                            'post_id'               =>      absint( $post_id ),

                                            'selected_terms'        =>      $post_terms,

                                            'taxonomy'              =>      $taxonomy

                                        ] ),

                'multiple_select'   =>  apply_filters( 'sdweddingdirectory/venue-type/multi-select/fields', [], [

                                            'term_id'               =>      absint( $term_id ),

                                            'post_id'               =>      absint( $post_id ),

                                            'selected_terms'        =>      $post_terms,

                                            'taxonomy'              =>      $taxonomy

                                        ] ),

                'rename_label'      =>  apply_filters( 'sdweddingdirectory/venue-type/rename-label/fields', [], [

                                            'term_id'               =>      absint( $term_id ),

                                            'post_id'               =>      absint( $post_id ),

                                            'selected_terms'        =>      $post_terms,

                                            'taxonomy'              =>      $taxonomy

                                        ] ),

            ] ) );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Vendor_Venue_Category_Data_AJAX:: get_instance();
}