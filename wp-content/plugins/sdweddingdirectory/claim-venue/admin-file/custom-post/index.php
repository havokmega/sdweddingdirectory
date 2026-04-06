<?php
/**
 *  Register Post and Taxonomy
 *  --------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Register_Claim_Post' ) && class_exists( 'SDWeddingDirectory_Register_Posts' ) ) {

    /**
     *  Register Post and Taxonomy
     *  --------------------------
     */
    class SDWeddingDirectory_Register_Claim_Post extends SDWeddingDirectory_Register_Posts{

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
         *  Post Type
         *  ---------
         */
        public static function post_type(){

            return      esc_attr( 'claim-venue' );
        }

        /**
         *  Menu Position
         *  -------------
         */
        public static function menu_position(){

            return      absint( '25' );
        }        

        /**
         *  Object Start
         *  ------------
         */
        public function __construct(){

            /**
             *  Register : Post
             *  ---------------
             */
            add_action( 'init', [ $this, 'create_post_type' ], absint( '10' ) );

            /**
             *  2. Column
             *  ---------
             */
            add_filter( 'manage_edit-claim-venue_columns', [$this, 'display_column'] );

            add_action( 'manage_claim-venue_posts_custom_column', [$this, 'display_data'], 10, 2 );

            /**
             *  2.1 Pending Claim Count on Admin Menu
             *  -------------------------------------
             */
            add_action( 'admin_menu', [ $this, 'add_pending_claims_badge' ], 999 );

            /**
             *  2.2 Claim Row Actions
             *  ---------------------
             */
            add_filter( 'post_row_actions', [ $this, 'claim_row_actions' ], 10, 2 );

            /**
             *  2.3 Handle Claim Row Actions
             *  ----------------------------
             */
            add_action( 'admin_init', [ $this, 'handle_claim_action' ] );

            /**
             *  3. When admin approved ( Publish ) this venue after claim venue assing with vendor and email sending too
             *  ------------------------------------------------------------------------------------------------------------
             */
            add_action( 'post_updated', [ $this, 'post_update_action' ], absint( '10' ), absint( '3' ) );            
        }

        /**
         *  1. Register : Post
         *  ------------------
         */
        public static function create_post_type(){

            /**
             *  1. Translation Ready String
             *  ---------------------------
             */
            $string                     =   esc_attr__( 'Claims', 'sdweddingdirectory-claim-venue' );

            /**
             *  WP_Query
             *  --------
             */
            $_found_post                =   apply_filters( 'sdweddingdirectory/post/data', [

                                                'post_type'     =>      esc_attr( 'claim-venue' ),

                                                'post_status'   =>      [ 'pending' ]

                                            ] );
            /**
             *  label
             *  -----
             */
            $labels                     =   [

                'name'                      =>       _x( 'Claims', 'Post Type General Name', 'sdweddingdirectory-claim-venue' ),

                'singular_name'             =>       _x( 'Claims', 'Post Type Singular Name', 'sdweddingdirectory-claim-venue' ),

                'menu_name'                 =>       __( 'Claims', 'sdweddingdirectory-claim-venue' ),

                'name_admin_bar'            =>       __( 'Claims', 'sdweddingdirectory-claim-venue' ),

                'archives'                  =>       __( 'Claims', 'sdweddingdirectory-claim-venue' ),

                'attributes'                =>       __( 'Claims', 'sdweddingdirectory-claim-venue' ),

                'parent_item_colon'         =>       __( 'Claims', 'sdweddingdirectory-claim-venue' ),

                'all_items'                 =>      SDWeddingDirectory_Loader:: _is_array( $_found_post )

                                            ?       sprintf( '%1$s <span class="awaiting-mod"> %2$s </span>',

                                                        /**
                                                         *  1. Translation Ready String
                                                         *  ---------------------------
                                                         */
                                                        esc_attr( $string ),

                                                        /**
                                                         *  2. Counter Notification
                                                         *  -----------------------
                                                         */
                                                        absint( count( $_found_post ) )
                                                    )

                                            :       esc_attr( $string ),

                'add_new_item'              =>      __( 'Claims', 'sdweddingdirectory-claim-venue' ),

                'add_new'                   =>      __( 'Claims', 'sdweddingdirectory-claim-venue' ),

                'new_item'                  =>      __( 'Claims', 'sdweddingdirectory-claim-venue' ),

                'edit_item'                 =>      __( 'Edit Claims', 'sdweddingdirectory-claim-venue' ),

                'update_item'               =>      __( 'Update Claims', 'sdweddingdirectory-claim-venue' ),

                'view_item'                 =>      __( 'View Claims', 'sdweddingdirectory-claim-venue' ),

                'view_items'                =>      __( 'View Claims', 'sdweddingdirectory-claim-venue' ),

                'search_items'              =>      __( 'Search Claims', 'sdweddingdirectory-claim-venue' ),

                'not_found'                 =>      __( 'Not found Claims', 'sdweddingdirectory-claim-venue' ),

                'not_found_in_trash'        =>      __( 'Not found in Trash Claims', 'sdweddingdirectory-claim-venue' ),

                'featured_image'            =>      __( 'Featured Image For Claims', 'sdweddingdirectory-claim-venue' ),

                'set_featured_image'        =>      __( 'Set featured image For Claims', 'sdweddingdirectory-claim-venue' ),

                'remove_featured_image'     =>      __( 'Remove featured image For Claims', 'sdweddingdirectory-claim-venue' ),

                'use_featured_image'        =>      __( 'Use as featured image In Claims', 'sdweddingdirectory-claim-venue' ),

                'insert_into_item'          =>      __( 'Insert into Claims', 'sdweddingdirectory-claim-venue' ),

                'uploaded_to_this_item'     =>      __( 'Uploaded to this Claims', 'sdweddingdirectory-claim-venue' ),

                'items_list'                =>      __( 'Claims', 'sdweddingdirectory-claim-venue' ),

                'items_list_navigation'     =>      __( 'Claims list navigation', 'sdweddingdirectory-claim-venue' ),

                'filter_items_list'         =>      __( 'Filter Claims', 'sdweddingdirectory-claim-venue' ),
            ];

            /**
             *  Args
             *  ----
             */
            $args                           =       [

                'label'                     =>      __( 'Claims', 'sdweddingdirectory-claim-venue' ),

                'description'               =>      __( 'Venue claim here', 'sdweddingdirectory-claim-venue' ),

                'labels'                    =>      $labels,

                'supports'                  =>      array( 'title' ),

                'taxonomies'                =>      [],

                'hierarchical'              =>      false,

                'public'                    =>      true,

                'show_ui'                   =>      true,

                'show_in_menu'              =>      true,

                'menu_position'             =>      self:: menu_position(),

                'menu_icon'                 =>      esc_attr( 'dashicons-pressthis' ),

                'show_in_admin_bar'         =>      false,

                'show_in_nav_menus'         =>      false,

                'can_export'                =>      false,

                'has_archive'               =>      false,

                'exclude_from_search'       =>      false,

                'publicly_queryable'        =>      false,

                'capability_type'           =>      esc_attr( 'post' ),

                'map_meta_cap'              =>      true,

                'query_var'                 =>      true,

                'rewrite'                   =>      array( 'slug' => self:: post_type(), 'with_front' => true   ),
            ];

            /**
             *  Register Post Type
             *  ------------------
             */
            register_post_type( self:: post_type(), array_merge( $args,

                /**
                 *  Enable Rest API
                 *  ---------------
                 */
                apply_filters( 'sdweddingdirectory/rest-api/' . self:: post_type(),  false  )

                ?       parent:: rest_api_enable(  self:: post_type()  )

                :       [],

                /**
                 *  Create Post Capacity ?
                 *  ----------------------
                 */
                apply_filters( 'sdweddingdirectory/post-cap/' . self:: post_type(), true  )

                ?   [   'capabilities'      =>     array( 'create_posts' => 'do_not_allow' )  ]

                :   []

            ) );
        }

        /**
         *  Display Column
         *  --------------
         */
        public static function display_column( $columns ) {

            /**
             *  Claimant Name
             *  -------------
             */
            $columns['claimant_name']     =   esc_attr__( 'Claimant', 'sdweddingdirectory-claim-venue' );

            /**
             *  Contact Info
             *  ------------
             */
            $columns['contact_info']      =   esc_attr__( 'Contact Info', 'sdweddingdirectory-claim-venue' );

            /**
             *  Target Profile
             *  --------------
             */
            $columns['target_profile']    =   esc_attr__( 'Target Profile', 'sdweddingdirectory-claim-venue' );

            /**
             *  Return Column
             *  -------------
             */
            return      $columns;
        }

        /**
         *  Show Column Fill Data
         *  ---------------------
         */
        public static function display_data( $column, $post_id ) {

            /**
             *  Claimant Name
             *  -------------
             */
            if( $column == esc_attr( 'claimant_name' ) ){

                echo esc_html( get_post_meta( absint( $post_id ), sanitize_key( 'claimant_name' ), true ) );
            }

            /**
             *  1. Contact Information
             *  ----------------------
             */
            elseif( $column == esc_attr( 'contact_info' ) ){

                $claimant_email = sanitize_email( get_post_meta( absint( $post_id ), sanitize_key( 'claimant_email' ), true ) );
                $claimant_phone = sanitize_text_field( get_post_meta( absint( $post_id ), sanitize_key( 'claimant_phone' ), true ) );

                printf( '<span>

                            <strong>%1$s:</strong> 

                            <a href="mailto:%2$s">%2$s</a>

                        </span>

                        <br/>

                        <span>

                            <strong>%3$s:</strong> 

                            <a href="tel:%4$s">%4$s</a>

                        </span>',

                        /**
                         *  1. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr__( 'Email', 'sdweddingdirectory-claim-venue' ),

                        /**
                         *  2. Get Vendor Email ID
                         *  ----------------------
                         */
                        esc_html( $claimant_email ),

                        /**
                         *  3. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr__( 'Contact', 'sdweddingdirectory-claim-venue' ),

                        /**
                         *  4. Get Vendor Email ID
                         *  ----------------------
                         */
                        esc_html( $claimant_phone )
                );
            }

            /**
             *  Target Profile Information
             *  --------------------------
             */
            elseif( $column == esc_attr( 'target_profile' ) ){

                $target_post_id   = absint( get_post_meta( absint( $post_id ), sanitize_key( 'target_post_id' ), true ) );
                $target_post_type = sanitize_key( get_post_meta( absint( $post_id ), sanitize_key( 'target_post_type' ), true ) );
                $target_slug      = sanitize_title( get_post_meta( absint( $post_id ), sanitize_key( 'target_slug' ), true ) );

                if( $target_post_id > 0 ){

                    printf(
                        '<span class="text-center"><a href="%1$s" target="_blank">%2$s</a></span><br/><code>%3$s</code><br/><small>%4$s</small>',
                        esc_url( get_the_permalink( absint( $target_post_id ) ) ),
                        esc_html( get_the_title( absint( $target_post_id ) ) ),
                        esc_html( $target_slug ),
                        esc_html( strtoupper( $target_post_type ) )
                    );
                }
            }
        }

        /**
         *  Add Pending Claims Count Badge to Admin Menu
         *  --------------------------------------------
         */
        public static function add_pending_claims_badge() {
            global $menu;

            $count   = wp_count_posts( self:: post_type() );
            $pending = isset( $count->pending ) ? absint( $count->pending ) : 0;

            if ( $pending > 0 && is_array( $menu ) ) {
                foreach ( $menu as $key => $item ) {
                    if ( isset( $item[2] ) && $item[2] === 'edit.php?post_type=claim-venue' ) {
                        $menu[ $key ][0] .= sprintf(
                            ' <span class="awaiting-mod count-%1$d"><span class="pending-count">%1$d</span></span>',
                            $pending
                        );
                        break;
                    }
                }
            }
        }

        /**
         *  Add Approve/Reject Row Actions
         *  ------------------------------
         */
        public static function claim_row_actions( $actions, $post ) {
            if ( $post->post_type === self:: post_type() && $post->post_status === 'pending' ) {
                $approve_url = wp_nonce_url(
                    admin_url( 'admin.php?action=approve_claim&claim_id=' . $post->ID ),
                    'approve_claim_' . $post->ID
                );
                $reject_url = wp_nonce_url(
                    admin_url( 'admin.php?action=reject_claim&claim_id=' . $post->ID ),
                    'reject_claim_' . $post->ID
                );

                $actions['approve_claim'] = sprintf(
                    '<a href="%s" style="color: #46b450; font-weight: bold;">%s</a>',
                    esc_url( $approve_url ),
                    esc_html__( 'Approve', 'sdweddingdirectory' )
                );
                $actions['reject_claim'] = sprintf(
                    '<a href="%s" style="color: #dc3232;">%s</a>',
                    esc_url( $reject_url ),
                    esc_html__( 'Reject', 'sdweddingdirectory' )
                );
            }
            return $actions;
        }

        /**
         *  Handle Approve/Reject Claim Actions
         *  -----------------------------------
         */
        public static function handle_claim_action() {
            if ( ! isset( $_GET['action'] ) || ! isset( $_GET['claim_id'] ) ) {
                return;
            }

            $action   = sanitize_text_field( $_GET['action'] );
            $claim_id = absint( $_GET['claim_id'] );

            if ( ! current_user_can( 'manage_options' ) ) {
                return;
            }

            if ( $action === 'approve_claim' ) {
                check_admin_referer( 'approve_claim_' . $claim_id );
                wp_update_post( array(
                    'ID'          => $claim_id,
                    'post_status' => 'publish',
                ) );
                wp_redirect( admin_url( 'edit.php?post_type=claim-venue&claim_approved=1' ) );
                exit;
            }

            if ( $action === 'reject_claim' ) {
                check_admin_referer( 'reject_claim_' . $claim_id );
                wp_update_post( array(
                    'ID'          => $claim_id,
                    'post_status' => 'trash',
                ) );
                wp_redirect( admin_url( 'edit.php?post_type=claim-venue&claim_rejected=1' ) );
                exit;
            }
        }

        /**
         *  4. When admin approved ( Publish ) this venue after claim venue assing with vendor and email sending too
         *  ------------------------------------------------------------------------------------------------------------
         */
        public static function post_update_action( $post_id, $post_after, $post_before ){


            /**
             *  Make sure post id not empty!
             *  ----------------------------
             */
            if( empty( $post_id ) ){

                return;
            }

            /**
             *  Make sure post type match = Pricing 
             *  -----------------------------------
             */
            if( $post_after->post_type == self:: post_type() ){

                $approved_claim         =   $post_after->post_status == esc_attr( 'publish' ) && $post_before->post_status !== esc_attr( 'publish' );

                if( $approved_claim ){

                    $target_post_id = absint( get_post_meta( absint( $post_id ), sanitize_key( 'target_post_id' ), true ) );

                    if( $target_post_id === 0 ){
                        return;
                    }

                    $claimant_user_id = absint( $post_after->post_author );

                    if( $claimant_user_id === 0 ){

                        $claimant_email = sanitize_email( get_post_meta( absint( $post_id ), sanitize_key( 'claimant_email' ), true ) );
                        $claimant_user  = get_user_by( 'email', $claimant_email );

                        $claimant_user_id = ! empty( $claimant_user ) ? absint( $claimant_user->ID ) : 0;
                    }

                    if( $claimant_user_id > 0 ){

                        /**
                         *  Action 1: Set profile ownership to claimant user.
                         */
                        wp_update_post( [

                            'ID'            => absint( $target_post_id ),

                            'post_author'   => absint( $claimant_user_id ),

                        ] );

                        /**
                         *  Action 2: Trigger one-time welcome flow flag.
                         */
                        update_user_meta( absint( $claimant_user_id ), sanitize_key( 'sdwd_claim_approved_welcome' ), absint( '1' ) );
                    }
                }
            }
        }
    }

    /**
     *  Register Post and Taxonomy
     *  --------------------------
     */
    SDWeddingDirectory_Register_Claim_Post::get_instance(); 
}
