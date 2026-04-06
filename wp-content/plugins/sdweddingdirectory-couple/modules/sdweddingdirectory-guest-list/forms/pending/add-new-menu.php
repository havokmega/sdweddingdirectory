<?php
/**
 *  SDWeddingDirectory Couple Add New Group Form
 *  ------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Guest_List_Add_Menu' ) && class_exists( 'SDWeddingDirectory_Guest_List_Database' ) ){

    /**
     *  SDWeddingDirectory Couple Add New Group Form
     *  ------------------------------------
     */
    class SDWeddingDirectory_Guest_List_Add_Menu extends SDWeddingDirectory_Guest_List_Database{

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
             *  1. Add New Budget Category Task Form
             *  ------------------------------------
             */            
            add_action( 'wp_footer', [$this, 'sidebar_panel'] );
        }

        /**
         *  1. Load Create Todo Form
         *  ------------------------
         */
        public static function sidebar_panel(){

            ?>

            <div id="sdweddingdirectory_guest_menu_sidepanel" class="sliding-panel">

                <div class="card-shadow-header mb-0">
                    <div class="dashboard-head">

                        <h3><?php esc_attr_e( 'Add Menu', 'sdweddingdirectory-guest-list' ); ?></h3>

                    </div>
                </div>

                <div class="card-shadow-body">

                    <form id="sdweddingdirectory_guest_add_menu_form" method="post" autocomplete="off" >

                        <div class="row"><?php self:: load_form(); ?></div>

                    </form>

                </div>

            </div>

            <?php
        }

        /**
         *  1. Load Create Todo Form
         *  ------------------------
         */
        public static function load_form(){

            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <label class="control-label" for="%1$s">%2$s</label>
                            <input autocomplete="off" id="%1$s" name="%1$s" type="text" placeholder="%3$s" class="form-control" required="">
                        </div>
                    </div>',

                    // 1
                    esc_attr( 'add_new_guest_menu' ),

                    // 2
                    esc_attr__( 'Add Menu', 'sdweddingdirectory-guest-list' ),

                    // 3
                    esc_attr__( 'Add Menu', 'sdweddingdirectory-guest-list' ),
            );

            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <button type="submit" id="add_new_guest_menu_btn" class="btn btn-default">%1$s</button>
                            %2$s
                        </div>
                    </div>',

                    // 1
                    esc_attr__( 'Add Menu', 'sdweddingdirectory-guest-list' ),

                    // 2
                    wp_nonce_field( 'add_new_guest_menu_security', 'add_new_guest_menu_security', true, false )
            );
        }

    } /* class end **/

    /**
     *  Kicking this off by calling 'get_instance()' method
     *  ---------------------------------------------------
     */
    SDWeddingDirectory_Guest_List_Add_Menu:: get_instance();
}