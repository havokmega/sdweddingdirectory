<?php
/**
 *  SDWeddingDirectory Couple Add New Group Form
 *  ------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Guest_List_Import' ) && class_exists( 'SDWeddingDirectory_Guest_List_Database' ) ){

	/**
	 *  SDWeddingDirectory Couple Add New Group Form
	 *  ------------------------------------
	 */
    class SDWeddingDirectory_Guest_List_Import extends SDWeddingDirectory_Guest_List_Database{

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

            <div id="sdweddingdirectory_guest_import_sidepanel" class="sliding-panel">

                <div class="card-shadow-header mb-0">
                    <div class="dashboard-head">

                        <h3><?php esc_attr_e( 'Import Guest', 'sdweddingdirectory-guest-list' ); ?></h3>

                    </div>
                </div>

                <div class="card-shadow-body">

                    <form id="sdweddingdirectory_guest_import_form" method="post" autocomplete="off"  enctype="multipart/form-data">

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
                            <input autocomplete="off" type="file" accept=".csv" id="%1$s">
                        </div>
                    </div>',

                    // 1
                    esc_attr( 'file' ),

                    // 2
                    esc_attr__( 'Import Guest List', 'sdweddingdirectory-guest-list' ),

                    // 3
                    esc_attr__( 'Select CSV File', 'sdweddingdirectory-guest-list' )
            );

            /**
             *  Add New Event Button
             *  --------------------
             */
            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <button type="submit" id="import_guest_list_button" class="btn btn-default">%1$s</button>
                            %2$s
                        </div>
                    </div>',

                    // 1
                    esc_attr__( 'Import Guest Data', 'sdweddingdirectory-guest-list' ),

                    // 2
                    wp_nonce_field( 'import_guest_security', 'import_guest_security', true, false )
            );
        }

    } /* class end **/

    /**
     *  Kicking this off by calling 'get_instance()' method
     *  ---------------------------------------------------
     */
    SDWeddingDirectory_Guest_List_Import:: get_instance();
}