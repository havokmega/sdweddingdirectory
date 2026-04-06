<?php
/**
 *  SDWeddingDirectory - Couple Wedding Website Right Widget Filter
 *  -------------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Wedding_Website_Right_Widget_Filter' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Filters' ) ){

    /**
     *  SDWeddingDirectory - Couple Wedding Website Right Widget Filter
     *  -------------------------------------------------------
     */
    class SDWeddingDirectory_Couple_Wedding_Website_Right_Widget_Filter extends SDWeddingDirectory_Couple_Website_Filters {

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
             *  Load one by one shortcode file
             *  ------------------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '*/index.php' ) as $file ) {
           
                require_once $file;
            }

            /**
             *  1. Dashboard / Left Side / Widget Start
             *  ---------------------------------------
             */
            add_action( 'sdweddingdirectory/couple/dashboard/widget/right-side', [ $this, 'widget' ], absint( '10' ) );
        }

        /**
         *  1. Dashboard / Left Side / Widget Start
         *  ---------------------------------------
         */
        public static function widget(){

            /**
             *  Create Var
             *  ----------
             */
            extract( [

                'rand_id'           =>        esc_attr( parent:: _rand() ),

                'website_post_id'   =>        absint( parent:: website_post_id() )

            ] );

            ?>
            <!-- Couple - Wedding Website Right Side Widget -->
            <div class="card-shadow">

                <div class="card-shadow-header">

                    <div class="dashboard-head">
                        
                        <h3><?php esc_attr_e( 'Wedding Website', 'sdweddingdirectory-couple-website' ); ?></h3>

                        <div class="links">
                        <?php

                            printf( '<a target="_blank" href="%1$s">%2$s <i class="fa fa-angle-right"></i></a>',

                                /**
                                 *  1. Todo page redirect link
                                 *  --------------------------
                                 */
                                esc_url( get_the_permalink( $website_post_id ) ),

                                /**
                                 *  2. Button Text string translation ready
                                 *  ---------------------------------------
                                 */
                                esc_attr__( 'View Website', 'sdweddingdirectory-couple-website' )
                            );

                        ?>
                        </div>

                    </div>
                    
                </div>

                <div class="card-shadow-body">

                    <div class="col-12">
                    <?php

                        /**
                         *  Message Display
                         *  ---------------
                         */
                        printf( '<p class="d-none text-success fw-bold" id="%1$s_message" data-success-message="%2$s"></p>',

                            /**
                             *  1. ID
                             *  -----
                             */
                            esc_attr( $rand_id ),

                            /**
                             *  4. Translation Ready String
                             *  ---------------------------
                             */
                            esc_attr__( 'link copied', 'sdweddingdirectory-couple-website' )
                        );
                    ?>
                    </div>

                    <div class="input-group mb-3 input_copy_group">
                    <?php

                        /**
                         *  Weblink Widget
                         *  --------------
                         */
                        printf( '<input autocomplete="off" type="text" class="form-control" id="%1$s_text" value="%2$s" readonly />

                                <div class="input-group-append d-grid">

                                    <button class="input-group-text btn btn-primary __copy_text__" 

                                            data-success-message="#%1$s_message" 

                                            data-clipboard-target="#%1$s_text">

                                                <i class="fa fa-clone" aria-hidden="true"></i>

                                    </button>

                                </div>',

                                /**
                                 *  1. ID
                                 *  -----
                                 */
                                esc_attr( $rand_id ),

                                /**
                                 *  2. Website link
                                 *  ---------------
                                 */
                                esc_url( get_the_permalink( $website_post_id ) )
                        );

                    ?>
                    </div>

                    <div class="col-12">

                        <div class="d-flex justify-content-between">
                        <?php

                            /**
                             *  Card Footer
                             *  -----------
                             */
                            printf( '<a href="%1$s" class="btn btn-outline-white btn-sm">%2$s</a>

                                    <a class="btn btn-outline-white btn-sm sdweddingdirectory-share-post-model" 

                                        data-post-id="%3$s" href="javascript:">

                                        <i class="fa fa-share-alt"></i>

                                        <span class="ms-1">%4$s</span>

                                    </a>',

                                    /**
                                     *  1. Edit Webslink 
                                     *  ----------------
                                     */
                                    esc_url( apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'couple-website' ) ) ),

                                    /**
                                     *  2. Translation Ready String
                                     *  ---------------------------
                                     */
                                    esc_attr__( 'Edit My Website', 'sdweddingdirectory-couple-website' ),

                                    /**
                                     *  3. Post ID
                                     *  ----------
                                     */
                                    $website_post_id,

                                    /**
                                     *  4. Translation Ready String
                                     *  ---------------------------
                                     */
                                    esc_attr__( 'Share', 'sdweddingdirectory-couple-website' )
                            );

                        ?>
                        </div>

                    </div>

                </div>

            </div>
                
            <!-- Couple - Wedding Website Right Side Widget -->
            <?php
        }

    } /* class end **/

    /**
     *  SDWeddingDirectory - Couple Wedding Website Right Widget Filter
     *  -------------------------------------------------------
     */
    SDWeddingDirectory_Couple_Wedding_Website_Right_Widget_Filter:: get_instance();
}