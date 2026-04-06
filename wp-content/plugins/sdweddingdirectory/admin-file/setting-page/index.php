<?php
/**
 *  SDWeddingDirectory Setting Page
 *  -----------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Setting_Page' ) && class_exists( 'SDWeddingDirectory_Admin_Settings' ) ){
   
    /**
     *  SDWeddingDirectory Setting Page
     *  -----------------------
     */
    class SDWeddingDirectory_Setting_Page extends SDWeddingDirectory_Admin_Settings {

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
             *  1. Load Script
             *  --------------
             */
            add_action( 'admin_enqueue_scripts', [$this, 'sdweddingdirectory_script'] );

            /**
             *  2. Global Script
             *  ----------------
             */
            add_action( 'admin_head', function(){

                ?>
                <style>

                    #adminmenu  .toplevel_page_sdweddingdirectory .wp-menu-image img{ 
                        padding-top: 3px; height: 30px; width: 26px; 
                    }

                    #adminmenu .toplevel_page_sdweddingdirectory .wp-menu-name{
                        white-space: nowrap;
                        font-size: 12px;
                        line-height: 1.2;
                    }

                </style>
                <?php

            } );

            /**
             *  2. Create Menu
             *  --------------
             */
            // Removed - OptionTree admin menu no longer needed
            // add_action( 'admin_menu', [$this, 'create_admin_menu'], absint( '10' ) );

            /**
             *  Wp Admin Menu
             *  -------------
             */
            // Removed - OptionTree admin menu no longer needed
            // add_filter( 'ot_theme_options_parent_slug', [ $this, 'sdweddingdirectory_setting_page' ] );

            // Removed - OptionTree admin menu no longer needed
            // add_filter( 'ot_theme_options_page_title', [$this, 'sdweddingdirectory_setting_option_page_title' ] );

            // Removed - OptionTree admin menu no longer needed
            // add_filter( 'ot_theme_options_menu_title', [$this, 'sdweddingdirectory_setting_option_menu_title' ] );

            // Removed - OptionTree admin menu no longer needed
            // add_filter( 'ot_theme_options_menu_slug', [$this, 'sdweddingdirectory_setting_page_menu_slug' ] );

            add_filter( 'sdweddingdirectory_license_page', [$this, 'sdweddingdirectory_setting_page' ] );

            /**
             *  Is AJAX Start ?
             *  ---------------
             */
            if ( wp_doing_ajax() ) {

                require_once 'ajax.php';
            }

            /**
             *  Tabs Files
             *  ----------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '*/index.php' ) as $_file ) {
           
                require_once $_file;
            }

            /**
             *  7. Hosting Configuration
             *  ------------------------
             */
            // add_filter( 'sdweddingdirectory/setting-page/tabs', function( $args = [] ){

            //     return      array_merge( $args, [

            //                     'hosting'   =>  array(

            //                         'tab'       =>  esc_attr__( 'Hosting', 'sdweddingdirectory' ),

            //                         'active'    =>  ( isset( $_GET[ 'tab' ] ) && $_GET[ 'tab' ] == esc_attr( 'hosting' )  )

            //                                             ?   true 

            //                                             :   false,

            //                         'callback'  =>  [ __CLASS__, 'hosting_configuration' ]
            //                     ),

            //                 ] );

            // }, absint( '70' ) );

            /**
             *  8. Product Documentation
             *  ------------------------
             */
            // add_filter( 'sdweddingdirectory/setting-page/tabs', function( $args = [] ){

            //     return      array_merge( $args, [

            //                     'documentation'   =>  array(

            //                         'tab'       =>  esc_attr__( 'Documentation', 'sdweddingdirectory' ),

            //                         'active'    =>  ( isset( $_GET[ 'tab' ] ) && $_GET[ 'tab' ] == esc_attr( 'documentation' )  )

            //                                             ?   true 

            //                                             :   false,

            //                         'callback'  =>  [ __CLASS__, 'documentation' ]
            //                     ),

            //                 ] );

            // }, absint( '80' ) );

        }

        /**
         *  Page Load Condition
         *  -------------------
         */
        public static function setting_page_tab_active( $tab_id = '' ){

            /**
             *  Tab ID
             *  ------
             */
            if( is_admin() && isset( $_GET[ 'tab' ] ) && $_GET[ 'tab' ] == $tab_id ){

                return      true;
            }

            else{

                return      false;
            }
        }

       /**
        *  Create SDWeddingDirectory - Menu
        *  ------------------------
        *  @link https://github.com/awesomemotive/one-click-demo-import/issues/53#issuecomment-253258096
        *  ---------------------------------------------------------------------------------------------
        */
        public function create_admin_menu(){

            // Removed - OptionTree admin menu no longer needed
            // add_menu_page(
            //
            //     esc_attr__( 'WeddingDirectory', 'sdweddingdirectory' ),
            //
            //     esc_attr__( 'WeddingDirectory', 'sdweddingdirectory' ),
            //
            //     'manage_options',
            //
            //     self:: sdweddingdirectory_setting_page(),
            //
            //     [$this, 'sdweddingdirectory_create_admin_page'],
            //
            //     esc_url( SDWEDDINGDIRECTORY_IMAGES . 'sdweddingdirectory.svg' ),
            //
            //     absint( '40' )
            // );
        }

        public function sdweddingdirectory_setting_page(){

            return  esc_attr( 'sdweddingdirectory' );
        }

        public function sdweddingdirectory_setting_option_page_title(){

            return  esc_attr__( 'Setting Options', 'sdweddingdirectory' );
        }
        public function sdweddingdirectory_setting_option_menu_title(){ 

            return  esc_attr__( 'Setting Options', 'sdweddingdirectory' );
        }

        public function sdweddingdirectory_setting_page_menu_slug(){

            return  esc_attr( 'sdweddingdirectory-setting-page' );
        }

        /**
         *  1. Load Script
         *  --------------
         */
        public static function sdweddingdirectory_script( $hook ){

            if( $hook !== 'toplevel_page_sdweddingdirectory' ){

                return;
            }

            /**
             *  Load Style
             *  ----------
             */
            wp_enqueue_style( 

                /**
                 *  1. File Name
                 *  ------------
                 */
                esc_attr( sanitize_title( __CLASS__ ) ),

                /**
                 *  2. File Path
                 *  ------------
                 */
                esc_url(   plugin_dir_url( __FILE__ )   .   'style.css'   ),

                /**
                 *  3. Have Dependancy ?
                 *  --------------------
                 */
                [ ],

                /**
                 *  4. Bootstrap - Library Version
                 *  ------------------------------
                 */
                esc_attr( parent:: _file_version(   plugin_dir_path( __FILE__ )   .   'style.css' )     ),

                /**
                 *  5. Load All Media
                 *  -----------------
                 */
                esc_attr( 'all' )
            );
        }

        /**
         *  Create Tabs
         *  -----------
         */
        public static function sdweddingdirectory_create_admin_page(){

            // Removed - OptionTree admin menu no longer needed
            return;

            ?>

            <div class="sdweddingdirectory-page-settings">

                <h1><?php esc_html_e( 'Welcome to SDWeddingDirectory', 'sdweddingdirectory' ); ?></h1>

                <p class="about-text"><?php esc_html_e( 'SDWeddingDirectory - Directory & Venue WordPress Theme', 'sdweddingdirectory' ); ?></p>

                <?php

                   /**
                    *  Tabs Handler
                    *  ------------
                    */
                    $_tab   = '';

                    $_data  =   apply_filters( 'sdweddingdirectory/setting-page/tabs', [] );

                    /**
                     *  Have Data ?
                     *  -----------
                     */
                    if( parent:: _is_array( $_data ) ){

                        /**
                         *  Load Tabs
                         *  ---------
                         */
                        foreach( $_data as $key => $value ){

                            /**
                             *  Extract
                             *  -------
                             */
                            extract( $value );

                            $_tab .=

                            sprintf( '<a href="admin.php?page=sdweddingdirectory&tab=%1$s" class="nav-tab %2$s">%3$s</a>',

                                /**
                                 *  1. Tab Slug
                                 *  -----------
                                 */
                                esc_attr( $key ),

                                /**
                                 *  2. Active Tab
                                 *  -------------
                                 */
                                $active == true

                                ?   sanitize_html_class( 'nav-tab-active' ) 

                                :   '',

                                /**
                                 *  3. Tab Name
                                 *  -----------
                                 */
                                esc_attr( $tab )
                            );
                        }

                        printf( '<h2 class="nav-tab-wrapper">%1$s</h2>', $_tab );

                        /**
                         *  Call Member to Load HTML
                         *  ------------------------
                         */
                        if( parent:: _is_array( $_data ) ){

                            foreach( $_data as $key => $value ){

                                /**
                                 *  Extract
                                 *  -------
                                 */
                                extract( $value );

                                /**
                                 *  Active Tab Content Load
                                 *  -----------------------
                                 */
                                if( $active == true ){

                                    call_user_func( $callback );
                                }
                            }
                        }
                    }
            ?>
            </div><?php
        }

        /**
         *  ---------------
         *  Helpful article 
         *  ---------------
         *  https://stackoverflow.com/questions/6035850/is-there-a-way-to-force-phpinfo-to-output-its-stuff-without-formatting-just-l#answer-32160684
         *  -----------------------------------------------------------------------------------------------------------------------------------------
         */
        public static function hosting_configuration(){

            ob_start(); phpinfo(INFO_MODULES); $s = ob_get_contents(); ob_end_clean();
            $s = wp_strip_all_tags($s, '<h2><th><td>');
            $s = preg_replace('/<th[^>]*>([^<]+)<\/th>/', '<info>\1</info>', $s  );
            $s = preg_replace('/<td[^>]*>([^<]+)<\/td>/', '<info>\1</info>', $s  );
            $t = preg_split('/(<h2[^>]*>[^<]+<\/h2>)/', $s, -1, PREG_SPLIT_DELIM_CAPTURE);
            $r = []; $count = count($t);
            $p1 = '<info>([^<]+)<\/info>';
            $p2 = '/'.$p1.'\s*'.$p1.'\s*'.$p1.'/';
            $p3 = '/'.$p1.'\s*'.$p1.'/';
            for ($i = 1; $i < $count; $i++) {
                if (preg_match('/<h2[^>]*>([^<]+)<\/h2>/', $t[$i], $matchs)) {
                    $name = trim($matchs[1]);
                    $vals = explode("\n", $t[$i + 1]);
                    foreach ($vals AS $val) {
                        if (preg_match($p2, $val, $matchs)) { // 3cols
                            $r[$name][trim($matchs[1])] = array(trim($matchs[2]), trim($matchs[3]));
                        } elseif (preg_match($p3, $val, $matchs)) { // 2cols
                            $r[$name][trim($matchs[1])] = trim($matchs[2]);
                        }
                    }
                }
            }

            $_sdweddingdirectory_product_requirement    =   [

                [

                    'name'          =>  esc_attr__( 'PHP version', 'sdweddingdirectory' ),

                    'sdweddingdirectory'    =>  esc_attr( '7.4' ),

                    'your_hosting'  =>  $r['HTTP Headers Information']['X-Powered-By']
                ],

                [

                    'name'          =>  esc_attr__( 'PHP Memory Limit', 'sdweddingdirectory' ),

                    'sdweddingdirectory'    =>  esc_attr( '768M' ),

                    'your_hosting'  =>  [

                                            'Local Value'    =>  $r['Core']['memory_limit'][0],

                                            'Master Value'   =>  $r['Core']['memory_limit'][1]
                                        ]
                ],

                [

                    'name'          =>  esc_attr__( 'Max Execution Time', 'sdweddingdirectory' ),

                    'sdweddingdirectory'    =>  esc_attr( '200' ),

                    'your_hosting'  =>  [

                                            'Local Value'    =>  $r['Core']['max_execution_time'][0],

                                            'Master Value'   =>  $r['Core']['max_execution_time'][1]
                                        ]
                ],

                [

                    'name'          =>  esc_attr__( 'Max Input Time', 'sdweddingdirectory' ),

                    'sdweddingdirectory'    =>  esc_attr( '200' ),

                    'your_hosting'  =>  [

                                            'Local Value'    =>  $r['Core']['max_input_time'][0],

                                            'Master Value'   =>  $r['Core']['max_input_time'][1]
                                        ]
                ],

                [

                    'name'          =>  esc_attr__( 'Upload Max Filesize', 'sdweddingdirectory' ),

                    'sdweddingdirectory'    =>  esc_attr( '200M' ),

                    'your_hosting'  =>  [

                                            'Local Value'    =>  $r['Core']['upload_max_filesize'][0],

                                            'Master Value'   =>  $r['Core']['upload_max_filesize'][1]
                                        ]
                ],
            ];


            _print_r( $_sdweddingdirectory_product_requirement );



            $_table     =   [];

            $_table     =   [

                'name'      =>  esc_attr__( 'Server environment', 'sdweddingdirectory' ),

                'content'   =>  'Server environment data'
            ];

            $_table     =   [

                'name'      =>  esc_attr__( 'SDWeddingDirectory Plugins', 'sdweddingdirectory' ),

                'content'   =>  'SDWeddingDirectory Plugins environment data'
            ];


            /**
             *  Have Table ?
             *  ------------
             */
            if( parent:: _is_array( $_table ) ){

                foreach ( $_table as $key => $value ){

                    /**
                     *  Extract Args
                     *  ------------
                     */
                    extract( $value );
                    
                    ?>
                    <table class="wc_status_table widefat" cellspacing="0" id="status">
                        <thead>
                            <tr>
                            <?php

                                printf( '<th colspan="3" data-export-label="WordPress Environment"><h2>%1$s</h2></th>', 

                                    /**
                                     *  1. Table Name
                                     *  -------------
                                     */
                                    esc_attr( $name )
                                );

                            ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php

                            /**
                             *  Content
                             *  -------
                             */
                            if( parent:: _is_array( $content ) ){

                                foreach( $content as $_key => $_value ){

                                    printf('<tr>
                                                <td data-export-label="WordPress address (URL)">%1$s</td>
                                                <td class="help"><span class="woocommerce-help-tip"></span></td>
                                                <td>%2$s</td>
                                            </tr>', 

                                            /**
                                             *  1. Key
                                             *  ------
                                             */
                                            esc_attr( $_key ),

                                            /**
                                             *  2. Value
                                             *  --------
                                             */
                                            $_value
                                    );
                                }
                            }
                        ?>
                        </tbody>

                    </table>
                    <?php
                }
            }
        }

        public static function documentation(){

            print 'Coming Soon';
        }
    }

    /**
     *  SDWeddingDirectory Setting Page
     *  -----------------------
     */
    SDWeddingDirectory_Setting_Page:: get_instance();
}
