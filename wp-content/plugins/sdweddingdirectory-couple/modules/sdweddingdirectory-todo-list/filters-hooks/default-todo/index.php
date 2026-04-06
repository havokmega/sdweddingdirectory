<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_Todo_Default_Setting_Filters' ) && class_exists( 'SDWeddingDirectory_Todo_Filters' ) ){

    /**
     *  SDWeddingDirectory Todo Filter
     *  ----------------------
     */
    class SDWeddingDirectory_Todo_Default_Setting_Filters extends SDWeddingDirectory_Todo_Filters{

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
             *  -------------------------------------------
             *  Default Todo List Insert in All Couple Post
             *  ---------------------------------------------------------------------------------------------------------------
             *  !important : Do not enable this action after all couple todo data overwrite with default setting admin provided
             *  ---------------------------------------------------------------------------------------------------------------
             */
            // add_action( 'init', [ $this,'all_couple_post_update_default_todo_list' ] );
        }

        /**
         *  Couple Checklist Update in Post
         *  -------------------------------
         */
        public static function all_couple_post_update_default_todo_list(){

            /**
             *  Check List : WP_Query
             *  ---------------------
             */
            $query      =   new WP_Query( [

                                'post_type'         =>      esc_attr( 'couple' ),

                                'post_status'       =>      esc_attr( 'publish' ),

                                'posts_per_page'    =>      -1,

                            ] );

            /**
             *  Have Post ?
             *  -----------
             */
            if( $query->have_posts() ){

                /**
                 *  Post Update One By One
                 *  ----------------------
                 */
                while ( $query->have_posts() ){  $query->the_post();

                    /**
                     *  Update Default Todo
                     *  -------------------
                     */
                    parent:: default_checklist_data( [

                        'post_id'           =>      absint( get_the_ID() )

                    ] );
                }

                /**
                 *  Reset : WP_Query
                 *  ----------------
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
    SDWeddingDirectory_Todo_Default_Setting_Filters:: get_instance();
}