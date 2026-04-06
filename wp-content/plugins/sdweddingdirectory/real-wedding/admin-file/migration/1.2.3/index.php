<?php
/**
 *  SDWeddingDirectory - Migration Filter 
 *  -----------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Real_Wedding_Migration_Version_123' ) && class_exists( 'SDWeddingDirectory_Real_Wedding_Migration' ) ){

    /**
     *  SDWeddingDirectory - Migration Filter 
     *  -----------------------------
     */
    class SDWeddingDirectory_Couple_Real_Wedding_Migration_Version_123 extends SDWeddingDirectory_Real_Wedding_Migration{

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
        public function __construct(){

            /**
             *  Add New Changes
             *  ---------------
             */
            add_filter( 'sdweddingdirectory/migration', function( $args = [] ){

                return      array_merge( $args, [

                                'sdweddingdirectory_migration_real_wedding_taxonomy'        =>  [

                                    'version'         =>    esc_attr( '1.1.2' ),

                                    'log'             =>    esc_attr( 'Migration with Update' ),

                                    'name'            =>    esc_attr( 'Couple Tools Real Wedding Migration' ),

                                    'message'         =>    esc_attr( 'Setting Option Value Transfer to Taxonomy with update every user taxonomy value' ),
                                ],

                            ] );

            }, absint( '20' ) );


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
                    $allowed_actions    =   array(

                        /**
                         *  Migration
                         *  ---------
                         */
                        esc_attr( 'sdweddingdirectory_migration_real_wedding_taxonomy' ),
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
         *  SDWeddingDirectory - Plugin
         *  -------------------
         */
        public static function sdweddingdirectory_migration_real_wedding_taxonomy(){

            /**
             *  Global Variable
             *  ---------------
             */
            global $post, $wp_query;

            /**
             *  Option Name
             *  -----------
             */
            $_option_name   =   esc_attr( $_POST[ 'option' ] );

            /**
             *  Make sure this wizard first time
             *  --------------------------------
             */
            if( get_option( $_option_name ) == absint( '0' ) ){


                /**
                 *  Color Setting Import in Taxonomy
                 *  --------------------------------
                 */
                $color                  =       sdweddingdirectory_option( 'realwedding-color' );

                $taxonomy_name          =       esc_attr( 'real-wedding-color' );

                /**
                 *  Make sure color is not empty and it's array
                 *  -------------------------------------------
                 */
                if( parent:: _is_array( $color ) ){

                    foreach( $color as $key => $value ){

                        /**
                         *  Insert Term to get Term ID
                         *  --------------------------
                         */
                        extract( wp_insert_term( 

                            /**
                             *  1. Term Name
                             *  ------------
                             */
                            esc_attr( $value[ 'title' ] ),

                            /**
                             *  2. Taxonomy Name
                             *  ----------------
                             */
                            esc_attr( $taxonomy_name )

                        ) );

                        /**
                         *  Term ID
                         *  -------
                         */
                        update_term_meta( absint( $term_id ), sanitize_key( 'color' ), esc_attr( $value[ 'color' ] ) );
                    }
                }

                /**
                 *  Color Setting Import in Taxonomy
                 *  --------------------------------
                 */
                $season                 =       sdweddingdirectory_option( 'realwedding-season' );

                $taxonomy_name          =       sanitize_html_class( esc_attr( 'real-wedding-season' ) );

                /**
                 *  Make sure color is not empty and it's array
                 *  -------------------------------------------
                 */
                if( parent:: _is_array( $season ) ){

                    foreach( $season as $key => $value ){

                        /**
                         *  Insert Term to get Term ID
                         *  --------------------------
                         */
                        extract( wp_insert_term( 

                            /**
                             *  1. Term Name
                             *  ------------
                             */
                            esc_attr( $value[ 'title' ] ),

                            /**
                             *  2. Taxonomy Name
                             *  ----------------
                             */
                            esc_attr( $taxonomy_name )

                        ) );

                        /**
                         *  Term ID
                         *  -------
                         */
                        update_term_meta( absint( $term_id ), sanitize_key( 'icon' ), esc_attr( $value[ 'icon' ] ) );
                    }
                }

                /**
                 *  Color Setting Import in Taxonomy
                 *  --------------------------------
                 */
                $community              =       sdweddingdirectory_option( 'realwedding-cultures' );

                $taxonomy_name          =       sanitize_html_class( esc_attr( 'real-wedding-community' ) );

                /**
                 *  Make sure color is not empty and it's array
                 *  -------------------------------------------
                 */
                if( parent:: _is_array( $community ) ){

                    foreach( $community as $key => $value ){

                        /**
                         *  Insert Term to get Term ID
                         *  --------------------------
                         */
                        wp_insert_term( 

                            /**
                             *  1. Term Name
                             *  ------------
                             */
                            esc_attr( $value[ 'title' ] ),

                            /**
                             *  2. Taxonomy Name
                             *  ----------------
                             */
                            esc_attr( $taxonomy_name )

                        );
                    }
                }

                /**
                 *  Color Setting Import in Taxonomy
                 *  --------------------------------
                 */
                $space_preferance       =       sdweddingdirectory_option( 'realwedding-space-preferance' );

                $taxonomy_name          =       esc_attr( 'real-wedding-space-preferance' );

                /**
                 *  Make sure color is not empty and it's array
                 *  -------------------------------------------
                 */
                if( parent:: _is_array( $space_preferance ) ){

                    foreach( $space_preferance as $key => $value ){

                        /**
                         *  Insert Term to get Term ID
                         *  --------------------------
                         */
                        wp_insert_term( 

                            /**
                             *  1. Term Name
                             *  ------------
                             */
                            esc_attr( $value[ 'title' ] ),

                            /**
                             *  2. Taxonomy Name
                             *  ----------------
                             */
                            esc_attr( $taxonomy_name )

                        );
                    }
                }

                /**
                 *  Color Setting Import in Taxonomy
                 *  --------------------------------
                 */
                $style                  =       sdweddingdirectory_option( 'realwedding-style' );

                $taxonomy_name          =       sanitize_html_class( 'real-wedding-style' );

                /**
                 *  Make sure color is not empty and it's array
                 *  -------------------------------------------
                 */
                if( parent:: _is_array( $style ) ){

                    foreach( $style as $key => $value ){

                        /**
                         *  Insert Term to get Term ID
                         *  --------------------------
                         */
                        extract( wp_insert_term( 

                            /**
                             *  1. Term Name
                             *  ------------
                             */
                            esc_attr( $value[ 'title' ] ),

                            /**
                             *  2. Taxonomy Name
                             *  ----------------
                             */
                            esc_attr( $taxonomy_name )

                        ) );

                        /**
                         *  Term ID
                         *  -------
                         */
                        update_term_meta( absint( $term_id ), sanitize_key( 'icon' ), esc_attr( $value[ 'icon' ] ) );
                    }
                }

                /**
                 *  Get Meta Value update in Taxonomy
                 *  ---------------------------------
                 */
                $query    = new WP_Query( array(

                    'post_type'         =>  esc_attr( 'real-wedding' ),

                    'post_status'       =>  esc_attr( 'publish' ),

                    'posts_per_page'    =>  -1,

                ) );

                /**
                 *  Have Post ?
                 *  -----------
                 */
                if ( $query->have_posts() ){

                    /**
                     *  Loop
                     *  ----
                     */
                    while ( $query->have_posts() ){  $query->the_post(); 

                        /**
                         *  Post ID
                         *  -------
                         */
                        $_post_id            =   absint( get_the_ID() );

                        /**
                         *  ---------------------
                         *  Key = Meta
                         *  ---------------------
                         *  Value = Taxonomy Slug
                         *  ---------------------
                         */
                        $update_taxonomy    =   [

                            'realwedding-color'                 =>      esc_attr( 'real-wedding-color' ),

                            'realwedding-style'                 =>      esc_attr( 'real-wedding-style' ),

                            'realwedding_space_preferance'      =>      esc_attr( 'real-wedding-space-preferance' ),

                            'realwedding_cultures'              =>      esc_attr( 'real-wedding-community' ),

                            'realwedding_season'                =>      esc_attr( 'real-wedding-season' ),
                        ];

                        /**
                         *  Update Taxonomy From Meta
                         *  -------------------------
                         */
                        foreach( $update_taxonomy as $key => $value ){

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
                                absint( get_term_by( 'name', get_post_meta( $_post_id, sanitize_key( $key ), true ), $value )->term_id ),

                                /**
                                 *  3. Taxonomy Name ( SLUG )
                                 *  -------------------------
                                 */
                                esc_attr( $value )
                            );
                        }
                    }
                }

                /**
                 *  Reset Query
                 *  -----------
                 */
                if ( isset( $query ) ) {

                    wp_reset_postdata();
                }

                /**
                 *  Social Migratrion Task DONE
                 *  ---------------------------
                 */
                update_option( $_option_name, absint( '1' ) );

                /**
                 *  Status
                 *  ------
                 */
                die( json_encode( [

                    'message'   =>  esc_attr__( 'Migration Done!', 'sdweddingdirectory-real-wedding' ),

                ] ) );
            }

            /**
             *  Error
             *  -----
             */
            else{

                /**
                 *  Status
                 *  ------
                 */
                die( json_encode( [

                    'message'   =>  esc_attr__( 'Error!', 'sdweddingdirectory-real-wedding' )

                ] ) );
            }
        }
    }

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Couple_Real_Wedding_Migration_Version_123:: get_instance();
}