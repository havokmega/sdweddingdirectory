<?php
/**
 *  SDWeddingDirectory - Budget Page Filters
 *  --------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Budget_Default_Setting_Filters' ) && class_exists( 'SDWeddingDirectory_Budget_Filters' ) ){

    /**
     *  SDWeddingDirectory - Budget Page Filters
     *  --------------------------------
     */
    class SDWeddingDirectory_Budget_Default_Setting_Filters extends SDWeddingDirectory_Budget_Filters{

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
             *  ---------------------------------------------
             *  Default Budget Data Insert in All Couple Post
             *  --------------------------------------------------------------------------------------------------------------------
             *  !important : Do not enable this action to overwrite the all couple tools data with default setting provided by admin
             *  --------------------------------------------------------------------------------------------------------------------
             */
            // add_action( 'init', [ $this,'whole_couple_set_default_budget_data' ] );
        }

        /**
         *  This function is do whole site couple update with default budget list data
         *  --------------------------------------------------------------------------
         */
        public static function whole_couple_set_default_budget_data(){

            /**
             *  Query
             *  -----
             */
            $query      =   new WP_Query( [

                                'post_type'         =>    esc_attr( 'couple' ),

                                'post_status'       =>    esc_attr( 'publish' ),

                                'posts_per_page'    =>    -1,

                            ] );

            /**
             *  Have Post ?
             *  -----------
             */
            if( $query->have_posts() ){

                /**
                 *  One By One Post Assing
                 *  ----------------------
                 */
                while ( $query->have_posts() ){  $query->the_post();

                    /**
                     *  Update Default Budget Data
                     *  --------------------------
                     */
                    parent:: default_budget_data( [

                        'post_id'       =>      absint( get_the_ID() ),

                    ] );
                }

                /**
                 *  Reset : Budget Calculator Update Data Query
                 *  -------------------------------------------
                 */
                if( isset( $query ) ){

                    wp_reset_postdata();
                }
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Budget_Default_Setting_Filters:: get_instance();
}