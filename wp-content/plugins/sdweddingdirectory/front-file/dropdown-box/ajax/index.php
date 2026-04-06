<?php
/**
 *  ------------------------------
 *  SDWeddingDirectory - Dropdown - Object
 *  ------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Dropdown_AJAX' ) && class_exists( 'SDWeddingDirectory_Dropdown_Script' ) ){

    /**
     *  ------------------------------
     *  SDWeddingDirectory - Dropdown - Object
     *  ------------------------------
     */
    class SDWeddingDirectory_Dropdown_AJAX extends SDWeddingDirectory_Dropdown_Script {

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
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Load one by one file
             *  --------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once $file;
            }

            /**
             *  1. Have AJAX action ?
             *  ---------------------
             */
            if(  wp_doing_ajax() ){

                /**
                 *  Have AJAX action ?
                 *  ------------------
                 */
                if( isset( $_POST['action'] ) && $_POST['action'] !== '' ){

                    /**
                     *  Have AJAX action ?
                     *  ------------------
                     */
                    $action             =   esc_attr( trim( $_POST['action'] ) );

                    /**
                     *  1.  Bit of security
                     *  -------------------
                     */
                    $allowed_actions    =   array(

                        /**
                         *  Get Venue Category + Added Location ID  Information
                         *  -----------------------------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_find_category_with_location_id' ),

                        /**
                         *  Venue Location Load with Term ID
                         *  ----------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_find_location_with_category_id' ),

                        /**
                         *  Get Venue Category Information
                         *  --------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_category_field_write_keyword_action' ),

                        /**
                         *  Location Dropdown Load with JS
                         *  ------------------------------
                         */
                        esc_attr( 'sdweddingdirectory_location_dropdown_load_with_js' )
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
         *  Venue Category Load with Location Information
         *  -----------------------------------------------
         */
        public static function sdweddingdirectory_find_category_with_location_id(){

                /**
                 *  Location ID added in Category Dropdown
                 *  --------------------------------------
                 */
                die( json_encode( [

                    'data'      =>      apply_filters( 'sdweddingdirectory/find-category/location-id', [

                                            'state_id'      =>      isset( $_POST[ 'state_id' ] ) && ! empty( $_POST[ 'state_id' ] )

                                                                    ?   absint( $_POST[ 'state_id' ] )

                                                                    :   absint( '0' ),

                                            'location'      =>      isset( $_POST[ 'location' ] ) && ! empty( $_POST[ 'location' ] )

                                                                    ?   sanitize_title( wp_unslash( $_POST[ 'location' ] ) )

                                                                    :   '',

                                            'region_id'     =>      isset( $_POST[ 'region_id' ] ) && ! empty( $_POST[ 'region_id' ] )

                                                                    ?   absint( $_POST[ 'region_id' ] )

                                                                    :   absint( '0' ),

                                            'city_id'       =>      isset( $_POST[ 'city_id' ] ) && ! empty( $_POST[ 'city_id' ] )

                                                                    ?   absint( $_POST[ 'city_id' ] )

                                                                    :   absint( '0' ),

                                            'term_id'       =>      isset( $_POST[ 'term_id' ] ) && ! empty( $_POST[ 'term_id' ] )

                                                                    ?   absint( $_POST[ 'term_id' ] )

                                                                    :   '',

                                            'post_type'     =>      isset( $_POST[ 'post_type' ] ) && ! empty( $_POST[ 'post_type' ] )

                                                                    ?   esc_attr( $_POST[ 'post_type' ] )

                                                                    :   esc_attr( 'venue' )
                                        ] )
                ] ) );
        }

        /**
         *  Venue Location with Term ID update
         *  ------------------------------------
         */
        public static function sdweddingdirectory_find_location_with_category_id(){

            /**
             *  Collection
             *  ----------
             */
            die( json_encode( [

                'data'          =>      apply_filters( 'sdweddingdirectory/find-location/category-id', [

                                            'post_type'         =>      isset( $_POST[ 'post_type' ] ) && ! empty( $_POST[ 'post_type' ] )

                                                                        ?   esc_attr( $_POST[ 'post_type' ] )

                                                                        :   esc_attr( 'venue' ),

                                            'category_id'       =>      isset( $_POST[ 'term_id' ] ) && ! empty( $_POST[ 'term_id' ] )

                                                                        ?   absint( $_POST[ 'term_id' ] )

                                                                        :   absint( '0' ),

                                            'depth_level'       =>      isset( $_POST[ 'depth_level' ] ) && ! empty( $_POST[ 'depth_level' ] )

                                                                        ?   absint( $_POST[ 'depth_level' ] )

                                                                        :   absint( '3' ),

                                            'display_tab'       =>      isset( $_POST[ 'display_tab' ] ) && ! empty( $_POST[ 'display_tab' ] )

                                                                        ?   esc_attr( $_POST[ 'display_tab' ] )

                                                                        :   esc_attr( 'all' ),

                                        ] )
            ] ) );
        }

        /**
         *  Find Venue Category Taxonomy + Venue
         *  ----------------------------------------
         */
        public static function sdweddingdirectory_category_field_write_keyword_action(){

            /**
             *  Default Data Loaded
             *  -------------------
             */
            die( json_encode( [

                'data'          =>      apply_filters( 'sdweddingdirectory/find-category/location-id', [

                                            'input_data'    =>      isset( $_POST['input_data'] ) && ! empty( $_POST['input_data'] )

                                                                    ?   esc_attr( $_POST['input_data'] ) 

                                                                    :   '',

                                            'state_id'      =>      isset( $_POST[ 'state_id' ] ) && ! empty( $_POST[ 'state_id' ] )

                                                                    ?   absint( $_POST[ 'state_id' ] )

                                                                    :   absint( '0' ),

                                            'location'      =>      isset( $_POST[ 'location' ] ) && ! empty( $_POST[ 'location' ] )

                                                                    ?   sanitize_title( wp_unslash( $_POST[ 'location' ] ) )

                                                                    :   '',


                                            'region_id'     =>      isset( $_POST[ 'region_id' ] ) && ! empty( $_POST[ 'region_id' ] )

                                                                    ?   absint( $_POST[ 'region_id' ] )

                                                                    :   absint( '0' ),

                                            'city_id'       =>      isset( $_POST[ 'city_id' ] ) && ! empty( $_POST[ 'city_id' ] )

                                                                    ?   absint( $_POST[ 'city_id' ] )

                                                                    :   absint( '0' ),

                                            'term_id'       =>      isset( $_POST[ 'term_id' ] ) && ! empty( $_POST[ 'term_id' ] )

                                                                    ?   absint( $_POST[ 'term_id' ] )

                                                                    :   '',

                                            'post_type'     =>      isset( $_POST[ 'post_type' ] ) && ! empty( $_POST[ 'post_type' ] )

                                                                    ?   esc_attr( $_POST[ 'post_type' ] )

                                                                    :   esc_attr( 'venue' )
                                        ] )
            ] ) );
        }

        /**
         *  Find Location
         *  -------------
         */
        public static function sdweddingdirectory_location_dropdown_load_with_js(){

            /**
             *  Default Data Loaded
             *  -------------------
             */
            die( json_encode( [

                'data'          =>      apply_filters( 'sdweddingdirectory/find-location/category-id', [

                                            'hide_empty'        =>      isset( $_POST[ 'hide_empty' ] ) && empty( $_POST[ 'hide_empty' ] )

                                                                        ?   absint( '0' )

                                                                        :   absint( '1' ),

                                            'post_type'         =>      isset( $_POST[ 'post_type' ] ) && ! empty( $_POST[ 'post_type' ] )

                                                                        ?   esc_attr( $_POST[ 'post_type' ] )

                                                                        :   esc_attr( 'venue' ),

                                            'category_id'       =>      isset( $_POST[ 'term_id' ] ) && ! empty( $_POST[ 'term_id' ] )

                                                                        ?   absint( $_POST[ 'term_id' ] )

                                                                        :   absint( '0' ),

                                            'depth_level'       =>      isset( $_POST[ 'depth_level' ] ) && ! empty( $_POST[ 'depth_level' ] )

                                                                        ?   absint( $_POST[ 'depth_level' ] )

                                                                        :   absint( '3' ),

                                            'display_tab'       =>      isset( $_POST[ 'display_tab' ] ) && ! empty( $_POST[ 'display_tab' ] )

                                                                        ?   esc_attr( $_POST[ 'display_tab' ] )

                                                                        :   esc_attr( 'all' ),
                                        ] )
            ] ) );
        }
    }

    /**
     *  ------------------------------
     *  SDWeddingDirectory - Dropdown - Object
     *  ------------------------------
     */
    SDWeddingDirectory_Dropdown_AJAX::get_instance();
}
