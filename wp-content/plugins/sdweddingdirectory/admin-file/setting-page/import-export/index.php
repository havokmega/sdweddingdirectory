<?php
/**
 *  SDWeddingDirectory Setting Page
 *  -----------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Setting_Import_Export_Page' ) && class_exists( 'SDWeddingDirectory_Setting_Page' ) ){
   
    /**
     *  SDWeddingDirectory Setting Page
     *  -----------------------
     */
    class SDWeddingDirectory_Setting_Import_Export_Page extends SDWeddingDirectory_Setting_Page {

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
         *   Constructor
         *   -----------
         */
        public function __construct(){

            /**
             *  Theme Option Setting Import / Export
             *  ------------------------------------
             */
            // Removed - OptionTree admin menu no longer needed
            // add_filter( 'ot_register_pages_array', [ $this, 'import_export_filter' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Import / Export Setting Option
         *  ------------------------------
         */
        public static function import_export_filter( $args = [] ){

            // Create the filterable pages array
            $ot_register_pages_array =  array( 

              array(
                'id'              => 'settings',
                'parent_slug'     => 'sdweddingdirectory',
                'page_title'      => __( 'Import / Export', 'sdweddingdirectory' ),
                'menu_title'      => __( 'Import / Export', 'sdweddingdirectory' ),
                'capability'      => 'manage_options',
                'menu_slug'       => 'setting-import-export',
                'icon_url'        => null,
                'position'        => null,
                'updated_message' => __( 'Theme Options updated.', 'sdweddingdirectory' ),
                'reset_message'   => __( 'Theme Options reset.', 'sdweddingdirectory' ),
                'button_text'     => __( 'Save Settings', 'sdweddingdirectory' ),
                'show_buttons'    => false,
                'sections'        => array(
                  array(
                    'id'          => 'import',
                    'title'       => __( 'Import', 'sdweddingdirectory' )
                  ),
                  array(
                    'id'          => 'export',
                    'title'       => __( 'Export', 'sdweddingdirectory' )
                  ),
                ),
                'settings'        => array(
                  array(
                    'id'          => 'import_settings_text',
                    'label'       => __( 'Settings', 'sdweddingdirectory' ),
                    'type'        => 'import-settings',
                    'section'     => 'import'
                  ),
                  array(
                    'id'          => 'import_data_text',
                    'label'       => __( 'Theme Options', 'sdweddingdirectory' ),
                    'type'        => 'import-data',
                    'section'     => 'import'
                  ),
                  array(
                    'id'          => 'export_settings_text',
                    'label'       => __( 'Settings', 'sdweddingdirectory' ),
                    'type'        => 'export-settings',
                    'section'     => 'export'
                  ),
                  array(
                    'id'          => 'export_data_text',
                    'label'       => __( 'Theme Options', 'sdweddingdirectory' ),
                    'type'        => 'export-data',
                    'section'     => 'export'
                  ),
                )
              ),
            );

            return          $ot_register_pages_array;
        }
    }

    /**
     *  SDWeddingDirectory Setting Page
     *  -----------------------
     */
    SDWeddingDirectory_Setting_Import_Export_Page:: get_instance();
}
