<?php
/**
 *  -----------------------------------------------
 *  SDWeddingDirectory - Couple Profile Tab Fields - Object
 *  -----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Wishlist_Favorite_Tab' ) && class_exists( 'SDWeddingDirectory_Wishlist' ) ){

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Couple Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    class SDWeddingDirectory_Couple_Wishlist_Favorite_Tab extends SDWeddingDirectory_Wishlist{

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
             *  Add Filter
             *  ----------
             */
            add_filter( 'sdweddingdirectory/couple-wishlist/tabs', function( $args = [] ){

                return  array_merge( $args, [

                            'couple-wishlist-favorite'        =>  [

                                'active'            =>  false,

                                'id'                =>  esc_attr( 'favorite-vendors' ),

                                'name'              =>  esc_attr__( 'Saved Vendors', 'sdweddingdirectory-wishlist' ),

                                'callback'          =>  [ 'class' => __CLASS__, 'member' => 'tab_content' ],
                            ]

                        ] );

            }, absint( '20' ) );
        }

        /**
         *  Saved Vendors Tab Content
         *  -------------------------
         */
        public static function tab_content(){

            $saved_ids = function_exists( 'sdwd_profile_user_items' )
                       ? sdwd_profile_user_items( 'sdwd_saved_profiles' )
                       : [];

            if( empty( $saved_ids ) ){

                printf( '<div class="text-center py-5 text-muted"><p>%s</p></div>',
                    esc_html__( 'You haven\'t saved any vendors yet. Browse vendors and click Save to add them here.', 'sdweddingdirectory-wishlist' )
                );
                return;
            }

            /**
             *  Group saved vendors by vendor-category
             *  --------------------------------------
             */
            $grouped = [];

            foreach( $saved_ids as $post_id ){

                $post = get_post( absint( $post_id ) );

                if( ! $post || $post->post_status !== 'publish' ){
                    continue;
                }

                $terms = wp_get_post_terms( $post_id, 'vendor-category', [ 'fields' => 'all' ] );

                $cat_id   = ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms[0]->term_id : 0;
                $cat_slug = ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms[0]->slug    : 'uncategorized';
                $cat_name = ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms[0]->name    : esc_attr__( 'Other', 'sdweddingdirectory-wishlist' );

                if( ! isset( $grouped[ $cat_slug ] ) ){

                    $grouped[ $cat_slug ] = [
                        'term_id'  => $cat_id,
                        'name'     => $cat_name,
                        'slug'     => $cat_slug,
                        'posts'    => [],
                    ];
                }

                $grouped[ $cat_slug ]['posts'][] = $post_id;
            }

            if( empty( $grouped ) ){
                return;
            }

            $tab = 0;
            $tab_content = 0;

            ?>
            <div class="row">

                <div class="col-12 col-xl-3">

                    <div class="d-flex flex-column flex-xl-column-reverse">

                        <div class="nav flex-column nav-pills theme-tabbing-vertical budget-tab mb-4" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <?php

                            foreach( $grouped as $cat_slug => $cat_data ){

                                printf( '<a class="nav-link %1$s" id="%2$s-tab" href="#%2$s" role="tab"

                                            data-bs-toggle="pill" aria-controls="%2$s" aria-selected="%5$s">

                                            <i class="%3$s"></i> %4$s

                                        </a>',

                                        $tab === 0 ? sanitize_html_class( 'active' ) : '',

                                        esc_attr( $cat_slug ),

                                        $cat_data['term_id'] > 0
                                            ? apply_filters( 'sdweddingdirectory/term/icon', [ 'term_id' => absint( $cat_data['term_id'] ), 'taxonomy' => 'vendor-category' ] )
                                            : 'fa fa-folder',

                                        esc_html( $cat_data['name'] ),

                                        $tab === 0 ? 'true' : 'false'
                                );

                                $tab++;
                            }

                        ?>
                        </div>

                    </div>

                </div>

                <div class="col-12 col-xl-9">

                    <div class="tab-content" id="v-pills-tabContent">
                    <?php

                        foreach( $grouped as $cat_slug => $cat_data ){

                            printf( '<div class="tab-pane fade %1$s" id="%2$s" role="tabpanel"

                                        aria-labelledby="%2$s-tab">

                                        <div class="row row-cols-1 row-cols-md-3 row-cols-sm-2">',

                                    $tab_content === 0 ? esc_attr( 'active show' ) : '',

                                    esc_attr( $cat_slug )
                            );

                            foreach( $cat_data['posts'] as $post_id ){

                                $post_type = get_post_type( absint( $post_id ) );

                                $filter = $post_type === 'vendor'
                                        ? 'sdweddingdirectory/vendor/post'
                                        : 'sdweddingdirectory/venue/post';

                                printf( '<div class="col">%1$s</div>',

                                    apply_filters( $filter, [

                                        'layout'    =>  absint( '1' ),

                                        'post_id'   =>  absint( $post_id ),

                                        'unique_id' =>  absint( $post_id )

                                    ] )
                                );
                            }

                            print '</div></div>';

                            $tab_content++;
                        }

                    ?>
                    </div>

                </div>

            </div>
            <?php
        }
    }

    /**
     *  -----------------------------------------------
     *  SDWeddingDirectory - Couple Profile Tab Fields - Object
     *  -----------------------------------------------
     */
    SDWeddingDirectory_Couple_Wishlist_Favorite_Tab::get_instance();
}