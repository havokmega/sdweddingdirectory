<?php
/**
 *  SDWeddingDirectory Search Vendor
 *  -------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Search_Vendor' ) && class_exists( 'SDWeddingDirectory_Find_Post_Helper' ) ){

    /**
     *  SDWeddingDirectory Search Vendor
     *  -------------------------------
     */
    class SDWeddingDirectory_Search_Vendor extends SDWeddingDirectory_Find_Post_Helper{

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

        /**
         *  Initiator
         *  ---------
         */
        public static function get_instance(){

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
             *  1. Load Script
             *  --------------
             */
            add_action( 'wp_enqueue_scripts', [ $this, 'sdweddingdirectory_vendor_script' ] );

            /**
             *  2. Localize Vendor Filters
             *  ---------------------------
             */
            add_filter( 'sdweddingdirectory/localize_script', [ $this, 'localize_vendor' ], absint( '20' ), absint( '1' ) );

            /**
             *  3. Have AJAX action ?
             *  ---------------------
             */
            if( wp_doing_ajax() ){

                if( isset( $_POST['action'] ) && $_POST['action'] !== '' ){

                    $action     =   esc_attr( trim( $_POST['action'] ) );

                    $allowed_actions = array(

                        esc_attr( 'sdweddingdirectory_load_vendor_data' )
                    );

                    if( in_array( $action, $allowed_actions, true ) ) {

                        if( is_user_logged_in() ){

                            add_action( 'wp_ajax_' . $action, [ $this, $action ] );

                        }else{

                            add_action( 'wp_ajax_nopriv_' . $action, [ $this, $action ] );
                        }
                    }
                }
            }
        }

        /**
         *  Vendor Filter Script
         *  --------------------
         */
        public static function sdweddingdirectory_vendor_script(){

            if( is_tax( 'vendor-category' ) ){

                wp_enqueue_script(

                    /**
                     *  1. File Name
                     *  ------------
                     */
                    esc_attr( sanitize_title( __CLASS__ ) ),

                    /**
                     *  2. File Path
                     *  ------------
                     */
                    esc_url( plugin_dir_url( __FILE__ ) . 'script.js' ),

                    /**
                     *  3. Load After "JQUERY" Load
                     *  ---------------------------
                     */
                    array( 'jquery' ),

                    /**
                     *  4. Version
                     *  ----------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'script.js' ) ),

                    /**
                     *  5. Load in Footer
                     *  -----------------
                     */
                    true
                );
            }
        }

        /**
         *  Localize Vendor Filter Data
         *  ---------------------------
         */
        public static function localize_vendor( $args = [] ){

            if( is_tax( 'vendor-category' ) ){

                return array_merge( $args, [

                    'vendor_filter_nonce'     =>  wp_create_nonce( 'sdweddingdirectory_vendor_filter' ),

                    'vendor_no_results_title' =>  esc_attr__( 'No vendors match your current filters.', 'sdweddingdirectory' ),

                    'vendor_no_results_text'  =>  esc_attr__( 'Try removing some filters.', 'sdweddingdirectory' ),

                    'vendor_no_results_clear' =>  esc_attr__( 'Clear All Filters', 'sdweddingdirectory' ),

                ] );
            }

            return $args;
        }

        /**
         *  Sanitize List Values
         *  --------------------
         */
        private static function sanitize_list( $values ){

            if( empty( $values ) ){

                return [];
            }

            $raw = $values;

            if( ! is_array( $raw ) ){

                $raw = preg_split( "/\,/", sanitize_text_field( wp_unslash( $raw ) ) );
            }

            $raw = array_map( 'sanitize_text_field', (array) wp_unslash( $raw ) );

            return array_values( array_filter( $raw ) );
        }

        /**
         *  Find Vendor Data Page
         *  ---------------------
         */
        public static function sdweddingdirectory_load_vendor_data(){

            check_ajax_referer( 'sdweddingdirectory_vendor_filter', 'nonce' );

            $term_id    =   isset( $_POST['vendor_category'] ) ? absint( $_POST['vendor_category'] ) : absint( '0' );

            $taxonomy   =   isset( $_POST['taxonomy'] ) ? sanitize_key( $_POST['taxonomy'] ) : sanitize_key( 'vendor-category' );

            $paged      =   isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : absint( '1' );

            $posts_per_page = apply_filters( 'sdweddingdirectory/vendor/post-per-page', absint( '12' ) );

            if( empty( $term_id ) || empty( $taxonomy ) ){

                wp_send_json( [

                    'vendor_html_data'  =>  '',

                    'pagination'        =>  '',

                    'found_result'      =>  absint( '0' )
                ] );
            }

            $selected_pricing      =   self:: sanitize_list( $_POST['vendor_pricing'] ?? [] );

            $selected_services     =   self:: sanitize_list( $_POST['vendor_services'] ?? [] );

            $selected_style        =   self:: sanitize_list( $_POST['vendor_style'] ?? [] );

            $selected_specialties  =   self:: sanitize_list( $_POST['vendor_specialties'] ?? [] );

            /**
             *  Meta Query
             *  ----------
             */
            $meta_query = [];

            $meta_query[] = array(

                                'key'       =>  esc_attr( 'profile_banner' ),

                                'compare'   =>  '!=',

                                'value'     =>  ''
                            );

            if( parent:: _is_array( $selected_pricing ) ){

                $pricing_clause = [ 'relation' => 'OR' ];

                foreach( $selected_pricing as $value ){

                    $pricing_clause[] = [

                        'key'       =>  esc_attr( 'vendor_pricing' ),

                        'compare'   =>  esc_attr( 'LIKE' ),

                        'value'     =>  '"' . esc_attr( $value ) . '"'
                    ];
                }

                $meta_query[] = $pricing_clause;
            }

            if( parent:: _is_array( $selected_services ) ){

                foreach( $selected_services as $value ){

                    $meta_query[] = [

                        'key'       =>  esc_attr( 'vendor_services' ),

                        'compare'   =>  esc_attr( 'LIKE' ),

                        'value'     =>  '"' . esc_attr( $value ) . '"'
                    ];
                }
            }

            if( parent:: _is_array( $selected_style ) ){

                foreach( $selected_style as $value ){

                    $meta_query[] = [

                        'key'       =>  esc_attr( 'vendor_style' ),

                        'compare'   =>  esc_attr( 'LIKE' ),

                        'value'     =>  '"' . esc_attr( $value ) . '"'
                    ];
                }
            }

            if( parent:: _is_array( $selected_specialties ) ){

                foreach( $selected_specialties as $value ){

                    $meta_query[] = [

                        'key'       =>  esc_attr( 'vendor_specialties' ),

                        'compare'   =>  esc_attr( 'LIKE' ),

                        'value'     =>  '"' . esc_attr( $value ) . '"'
                    ];
                }
            }

            /**
             *  Vendor Query
             *  ------------
             */
            $query_args = [

                'post_type'         =>  esc_attr( 'vendor' ),

                'post_status'       =>  esc_attr( 'publish' ),

                'posts_per_page'    =>  absint( $posts_per_page ),

                'paged'             =>  absint( $paged ),

                'tax_query'         =>  [

                                            [
                                                'taxonomy'  =>  $taxonomy,
                                                'terms'     =>  $term_id,
                                            ]
                                        ],
            ];

            if( parent:: _is_array( $meta_query ) ){

                $query_args['meta_query'] = array_merge( [ 'relation' => 'AND' ], $meta_query );
            }

            $query = new WP_Query( $query_args );

            $vendor_html = '';

            $pagination  = '';

            if( $query->have_posts() && class_exists( 'SDWeddingDirectory_Vendor' ) ){

                while( $query->have_posts() ){ $query->the_post();

                    $vendor_html .= sprintf( '<div class="col-12">%1$s</div>',

                        apply_filters( 'sdweddingdirectory/vendor/post', [

                            'post_id'   =>  absint( get_the_ID() ),

                            'layout'    =>  absint( '3' )
                        ] )
                    );
                }

                if( absint( $query->max_num_pages ) >= absint( '2' ) ){

                    $pagination_args = [];

                    if( parent:: _is_array( $selected_pricing ) ){

                        $pagination_args['vendor_pricing'] = $selected_pricing;
                    }

                    if( parent:: _is_array( $selected_services ) ){

                        $pagination_args['vendor_services'] = $selected_services;
                    }

                    if( parent:: _is_array( $selected_style ) ){

                        $pagination_args['vendor_style'] = $selected_style;
                    }

                    if( parent:: _is_array( $selected_specialties ) ){

                        $pagination_args['vendor_specialties'] = $selected_specialties;
                    }

                    $pagination = apply_filters( 'sdweddingdirectory/pagination', [

                        'numpages'      =>  absint( $query->max_num_pages ),

                        'paged'         =>  absint( $paged ),

                        'add_args'      =>  $pagination_args
                    ] );
                }

                wp_reset_postdata();
            }

            wp_send_json( [

                'vendor_html_data'  =>  $vendor_html,

                'pagination'        =>  $pagination,

                'found_result'      =>  absint( $query->found_posts )
            ] );
        }
    }

    /**
    *  Kicking this off by calling 'get_instance()' method
    */
    SDWeddingDirectory_Search_Vendor::get_instance();
}
