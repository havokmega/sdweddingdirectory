<?php
/**
 *  SDWeddingDirectory - Page Template Helper
 *  ---------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Template' ) && class_exists( 'SDWeddingDirectory_Config' )  ){

    /**
     *  SDWeddingDirectory - Page Template Helper
     *  ---------------------------------
     */
    class SDWeddingDirectory_Template extends SDWeddingDirectory_Config{

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
             *  1. Find Template Filter
             *  -----------------------
             */
            add_filter( 'sdweddingdirectory/template/link', [ $this, 'find_template_link' ], absint( '10' ), absint( '1' ) );

            /**
             *  2. Create link with find venue page args
             *  ------------------------------------------
             */
            add_filter( 'sdweddingdirectory/find-venue-page', [ $this, 'find_venue_page' ], absint( '10' ), absint( '1' ) );

            /**
             *  3. Get Page ID via Page Title
             *  -----------------------------
             */
            add_filter( 'sdweddingdirectory/get-page-id', [ $this, 'found_post_title_exists' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  3. Get Page ID via Page Title
         *  -----------------------------
         */
        public static function found_post_title_exists( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'post_name'     =>      '',

                    'post_type'     =>      'page'

                ] ) );

                /**
                 *  Make sure page name not empty!
                 *  ------------------------------
                 */
                if( empty( $post_name ) ){

                    return  null;
                }

                /**
                 *  WP Query
                 *  --------
                 */
                $query  =   new WP_Query( [

                                'post_type'              =>     $post_type,

                                'title'                  =>     $post_name,

                                'post_status'            =>     'all',

                                'posts_per_page'         =>     1,

                                'no_found_rows'          =>     true,

                                'ignore_sticky_posts'    =>     true,

                                'update_post_term_cache' =>     false,

                                'update_post_meta_cache' =>     false,

                                'orderby'                =>     'post_date ID',

                                'order'                  =>     'ASC',

                            ] );

                /**
                 *  Is Empty ?
                 *  ----------
                 */
                if ( ! empty( $query->post ) ) {

                    return      absint( $query->post->ID );
                }

                else {

                    return      false;
                }
            }
        }

        /**
         *  Create args with find venue page link
         *  ---------------------------------------
         */
        public static function find_venue_page( $args = [] ){

            /**
             *  Have args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Search Venue Page with Paramerter added
                 *  -----------------------------------------
                 */
                return      esc_url_raw( add_query_arg(  $args,  self:: search_venue_template() ) );

            }else{

                /**
                 *  Search Venue Page
                 *  -------------------
                 */
                return      esc_url( self:: search_venue_template() );
            }
        }

        /**
         *  Get template link
         *  -----------------
         */
        public static function find_template_link( $template = '' ){

            /**
             *  Is Empty ?
             *  ----------
             */
            if( empty( $template ) ){

                return;
            }

            /**
             *  Have link ?
             *  -----------
             */
            $_have_template     =   self:: get_template( $template, 'link' );

            /**
             *  Return
             *  ------
             */
            return  parent:: _have_data( $_have_template )  

                    ?   $_have_template     

                    :   esc_url( home_url() );            
        }

        /**
         *  Page Template
         *  -------------
         */
        public static function get_template( $template, $response = 'link' ){

            /**
             *  Is Empty ?
             *  ----------
             */
            if( empty( $template ) ){

                return;
            }

            /**
             *  Collection
             *  ----------
             */
            $pages  =   get_pages( array(

                            'meta_key'      =>  '_wp_page_template',

                            'meta_value'    =>  sprintf(    'user-template/%1$s',   $template   )

                        ) );

            /**
             *  Is Array ?
             *  ----------
             */
            if( $response == 'url' || $response == 'link' ){

                return  parent:: _is_array( $pages )

                        ?   esc_url( get_permalink( $pages[0]->ID ) )

                        :   esc_url( home_url( '/' ) );          
            }

            /**
             *  Is Page ID ?
             *  ------------
             */
            if( $response == 'id' ){

                return  parent:: _is_array( $pages )

                        ?   absint( $pages[0]->ID )

                        :   get_option( 'page_on_front' );
            }

            /**
             *  Is Page Title ?
             *  ---------------
             */
            if( $response == 'title' ){

                return  parent:: _is_array( $pages )

                        ?   esc_attr( $pages[0]->post_title )

                        :   get_the_title( get_option( 'page_on_front' ) );
            }

            /**
             *  Collection ?
             *  ------------
             */
            if( $response == 'data' ){

                return  parent:: _is_array( $pages )    ?   $pages  :   [];
            }
        }

        /**
         *  Vendor Dashboard Page Template
         *  ------------------------------
         */
        public static function vendor_dashboard_template(){

            return  self:: find_template_link( 'vendor-dashboard.php' );
        }

        /**
         *  Venue Category Page Template
         *  ------------------------------
         */
        public static function venue_category_template(){

            return  self:: find_template_link( 'venue-type.php' );
        }

        /**
         *  Couple Dashboard Page Template
         *  ------------------------------
         */
        public static function couple_dashboard_template(){

            return  self:: find_template_link( 'couple-dashboard.php' );
        }

        /**
         *  Search Venue Template
         *  -----------------------
         */
        public static function search_venue_template(){

            return  self:: find_template_link( 'search-venue.php' );
        }

        /**
         *  Privacy Policy Page Template
         *  ----------------------------
         */
        public static function privacy_policy_template(){

            return  self:: find_template_link( 'privacy-policy.php' );
        }

        /**
         *  Terms Of Use Page Template
         *  --------------------------
         */
        public static function terms_of_use_template(){

            return  self:: find_template_link( 'terms-of-use.php' );
        }
    }

    /**
    *  Kicking this off by calling 'get_instance()' method
    */
    SDWeddingDirectory_Template::get_instance();
}