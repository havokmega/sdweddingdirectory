<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_Guest_List_Default_Setting_Filters' ) && class_exists( 'SDWeddingDirectory_Guest_List_Filters' ) ){

    /**
     *  Filter & Hooks
     *  --------------
     */
    class SDWeddingDirectory_Guest_List_Default_Setting_Filters extends SDWeddingDirectory_Guest_List_Filters{

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
             *  -------------------------------------------------
             *  Default Guest List Data Insert in All Couple Post
             *  -------------------------------------------------
             *  !important : This action overwrite the all couple data to import default setting.
             *  ---------------------------------------------------------------------------------
             */
            // add_action( 'init', [ $this,'sdweddingdirectory_default_guest_list_data' ] );
        }

        /**
         *  Couple Update with default guest list data via setting page using
         *  -----------------------------------------------------------------
         */
        public static function sdweddingdirectory_default_guest_list_data(){

            /**
             *  Post Query
             *  ----------
             */
            $query      =       new WP_Query( array(

                                    'post_type'         =>  esc_attr( 'couple' ),

                                    'post_status'       =>  esc_attr( 'publish' ),

                                    'posts_per_page'    =>  -1,

                                ) );

            /**
             *  Have Post ?
             *  -----------
             */
            if( $query->have_posts() ){

                /**
                 *  One By One Update Post
                 *  ----------------------
                 */
                while ( $query->have_posts() ){    $query->the_post();

                    /**
                     *  Post ID
                     *  -------
                     */
                    $post_id        =       absint( get_the_ID() );

                    /**
                     *  Import Event
                     *  ------------
                     */
                    parent:: default_guest_list_event_data( [  'post_id'   =>  $post_id   ] );

                    /**
                     *  Import Group
                     *  ------------
                     */
                    parent:: default_guest_list_group_data( [  'post_id'   =>  $post_id   ] );

                    /**
                     *  Import Menu
                     *  -----------
                     */
                    parent:: default_guest_list_menu_data( [   'post_id'   =>  $post_id   ] );
                }

                /**
                 *  Reset : Guest List Data Update Query
                 *  ------------------------------------
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
    SDWeddingDirectory_Guest_List_Default_Setting_Filters:: get_instance();
}