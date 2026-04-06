<?php
/**
 *  SDWeddingDirectory - Core Filters
 *  -------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Core_Filters' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory - Core Filters
     *  -------------------------
     */
    class SDWeddingDirectory_Core_Filters extends SDWeddingDirectory_Config{

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
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once $file;
            }

            /**
             *  1. Removed Admin Bar
             *  --------------------
             *  2. Stop Access Profile
             *  ----------------------
             *  3. Removed Profile Menu
             *  -----------------------
             *  Article : https://developer.wordpress.org/reference/functions/show_admin_bar/#comment-content-5382
             *  ---------------------------------------------------------------------------------------------------
             */
            add_action( 'wp', [ $this, 'remove_admin_bar' ] );

            add_action( 'admin_init', [ $this, 'remove_admin_bar' ] );

            /**
             *  -----------------------------------------------
             *  Stop Accessing the Dashboard or Couple + Vendor
             *  -----------------------------------------------
             */
            add_action( 'admin_init', [ $this, 'stop_access_backend' ] );

            /**
             *  Add Menu
             *  --------
             */
            add_filter( 'sdweddingdirectory/nav-menus', [ $this, 'register_menu' ], absint( '1' ), absint( '20' ) );

            /**
             *  5. SDWeddingDirectory - Placeholder Filter
             *  ----------------------------------
             */
            add_filter( 'sdweddingdirectory/placeholder', function( $args = [] ){

                /**
                 *  Add Slider Placeholder
                 *  ----------------------
                 */
                return  array_merge(

                            /**
                             *  Have Args ?
                             *  -----------
                             */
                            $args,

                            /**
                             *  Merge New Args
                             *  --------------
                             */
                            array(

                                [

                                    'name'              =>  esc_attr__( 'Model Popup', 'sdweddingdirectory' ),

                                    'id'                =>  esc_attr( 'mode-popup' ),

                                    'placeholder'       =>  apply_filters( 'sdweddingdirectory/placeholder/modal-popup', [] )
                                ]
                            )
                        );
            } );

            /**
             *  Working Hours Filter
             *  --------------------
             */
            add_filter( 'sdweddingdirectory/24-hours/list', [ $this, 'get_hours_range' ], absint( '10' ), absint( '4' ) );

            /**
             *  Required argument in plugin data
             *  -------------------------------- 
             */
            add_filter( 'sdweddingdirectory/plugin-info-data', [ $this, 'convert_to_array' ], absint( '10' ), absint( '1' ) );

            /**
             * ---------------------------------------------------------------------------------------
             * @credit - https://spinupwp.com/hosting-wordpress-yourself-cron-email-automatic-backups/
             * ---------------------------------------------------------------------------------------
             * Creater Event Time : https://developer.wordpress.org/reference/functions/wp_get_schedules/#source
             * -------------------------------------------------------------------------------------------------
             *
             *  Once Hourly  =   HOUR_IN_SECONDS
             *  
             *  Twice Daily  =   HOUR_IN_SECONDS
             *  
             *  Once Daily   =   DAY_IN_SECONDS
             *  
             *  Once Weekly  =   WEEK_IN_SECONDS
             *
             *  ---------------------------
             *  Add SDWeddingDirectory - Cron Event
             *  ---------------------------
             */
            add_filter( 'cron_schedules', [ $this, 'sdweddingdirectory_cron' ], 10, 1 );

            /**
             *  Date Filter
             *  -----------
             */
            add_filter( 'ot_type_date_picker_date_format', function( $format, $field_id ){

                return  esc_attr( 'yy-mm-dd' );

            }, absint( '10' ), absint( '2' ) );

            /**
             *  Privacy Policy / Term and Condition
             *  -----------------------------------
             */
            add_filter( 'sdweddingdirectory/term_and_condition_note', [ $this, 'term_and_condition_note' ], absint( '10' ), absint( '1' ) );

            /**
             *  Media upload from link
             *  ----------------------
             */
            add_filter( 'sdweddingdirectory/media-upload/link', [ $this, 'upload_from_url' ], absint( '10' ), absint( '1' ) );

            /**
             *  Install SDWeddingDirectory - Plugin Update
             *  ----------------------------------
             */
            add_action( 'admin_init', [ $this, 'plugin_update_checker' ], absint( '100' ) );

            /**
             *  Load custom style in admin header
             *  ---------------------------------
             *  @credit - https://wordpress.stackexchange.com/questions/314539/override-load-styles-php-with-admin-screen-css#answers-header
             *  ----------------------------------------------------------------------------------------------------------------------------
             */
            add_action( 'admin_head', [ $this, 'plugin_information_style' ] );

            /**
             *  Have Update Plugin Link in Database ?
             *  -------------------------------------
             */
            add_filter( 'sdweddingdirectory/plugin/update', [ $this, 'plugin_update_json_file' ], absint( '10' ), absint( '1' ) );

            /**
             *  SDWeddingDirectory - Media Data
             *  -----------------------
             */
            if( ! has_filter( 'sdweddingdirectory/media-data' ) ){

                add_filter( 'sdweddingdirectory/media-data', [ $this, 'sdweddingdirectory_media' ], absint( '10' ), absint( '1' ) );
            }

            /**
             *  Get Image Size Data
             *  -------------------
             */
            add_filter( 'sdweddingdirectory/image-size-id/data', [ $this, 'get_image_data' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Get Image Link To Post ID
         *  -------------------------
         *  @link - https://gist.github.com/RadGH/966f8c756c5e142a5f489e86e751eacb
         *  ----------------------------------------------------------------------
         */
        public static function upload_from_url( $url, $title = null ) {

            /**
             *  Read Helper Files
             *  -----------------
             */
            require_once( ABSPATH . "/wp-load.php");
            require_once( ABSPATH . "/wp-admin/includes/image.php");
            require_once( ABSPATH . "/wp-admin/includes/file.php");
            require_once( ABSPATH . "/wp-admin/includes/media.php");
            
            // Download url to a temp file
            $tmp = download_url( $url );
            if ( is_wp_error( $tmp ) ) return false;
            
            // Get the filename and extension ("photo.png" => "photo", "png")
            $filename = pathinfo($url, PATHINFO_FILENAME);
            $extension = pathinfo($url, PATHINFO_EXTENSION);
            
            // An extension is required or else WordPress will reject the upload
            if ( ! $extension ) {
                // Look up mime type, example: "/photo.png" -> "image/png"
                $mime = mime_content_type( $tmp );
                $mime = is_string($mime) ? sanitize_mime_type( $mime ) : false;
                
                // Only allow certain mime types because mime types do not always end in a valid extension (see the .doc example below)
                $mime_extensions = array(
                    // mime_type         => extension (no period)
                    'text/plain'         => 'txt',
                    'text/csv'           => 'csv',
                    'application/msword' => 'doc',
                    'image/jpg'          => 'jpg',
                    'image/jpeg'         => 'jpeg',
                    'image/gif'          => 'gif',
                    'image/png'          => 'png',
                    'video/mp4'          => 'mp4',
                );
                
                if ( isset( $mime_extensions[$mime] ) ) {
                    // Use the mapped extension
                    $extension = $mime_extensions[$mime];
                }else{
                    // Could not identify extension
                    @unlink($tmp);
                    return false;
                }
            }
            
            // Upload by "sideloading": "the same way as an uploaded file is handled by media_handle_upload"
            $args = array(
                'name' => "$filename.$extension",
                'tmp_name' => $tmp,
            );
            
            // Do the upload
            $attachment_id = media_handle_sideload( $args, 0, $title);
            
            // Cleanup temp file
            // @unlink($tmp);
            
            // Error uploading
            if ( is_wp_error($attachment_id) ) return false;
            
            // Success, return attachment ID (int)
            return (int) $attachment_id;
        }

        /**
         *  Add WP Cron Task
         *  ----------------
         */
        public static function sdweddingdirectory_cron( $schedules ){

            $schedules['sdweddingdirectory_daily_cron'] = array(
                
                'interval' =>   absint( DAY_IN_SECONDS ),

                'display'  =>   'SDWeddingDirectory Daily Cron',
            );

            return $schedules;
        }

        /**
         *  Working Hours Filter
         *  --------------------
         */
        public static function get_hours_range( $start = 0, $end = 86400, $step = 3600, $format = 'g:i a' ){

            $times = array();

            foreach ( range( $start, $end, $step ) as $timestamp ) {

                $hour_mins = gmdate( 'H:i', $timestamp );

                if ( ! empty( $format ) )
                        $times[$hour_mins] = gmdate( $format, $timestamp );
                else $times[$hour_mins] = $hour_mins;
            }

            return $times;
        }

        /**
         *  Removed Vendor and Couple Role Top Admin Bar
         *  --------------------------------------------
         */
        public static function remove_admin_bar(){

            global $wp_admin_bar, $menu;

            if ( ! current_user_can('administrator') && ! is_admin() ) {

                show_admin_bar( false );
            }
        }

        /**
         *  -----------------------------------------------
         *  Stop Accessing the Dashboard or Couple + Vendor
         *  -----------------------------------------------
         */
        public static function stop_access_backend(){

            global $wp_admin_bar, $menu;

            /**
             *  ------------------------------------------------------
             *  Make sure this script not RUN while AJAX request start
             *  ------------------------------------------------------
             */
            if( ! wp_doing_ajax() ){

                /**
                 *  Is Vendor ?
                 *  -----------
                 */
                if( current_user_can( 'vendor' ) ) {

                    if ( is_user_logged_in() && parent::is_vendor() ) {

                        die( wp_redirect(

                            apply_filters( 'sdweddingdirectory/vendor-menu/page-link', esc_attr( 'vendor-profile' ) )

                        ) );
                    }
                }

                /**
                 *  Is Couple ?
                 *  -----------
                 */
                if( current_user_can( 'couple' ) ){

                    if ( is_user_logged_in() && parent::is_couple() ) {

                        die( wp_redirect(

                            apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'my-profile' ) )

                        ) );
                    }
                }
            }
        }

        /**
         *  Add Menu
         *  --------
         */
        public static function register_menu( $args = [] ){

            /**
             *  Return the list of Menu
             *  -----------------------
             */
            return      array_merge(  

                            /**
                             *  Have Menu ?
                             *  -----------
                             */
                            $args,

                            /**
                             *  1. Merge New Menu
                             *  -----------------
                             */
                            array(

                                /**
                                 *  Footer Location Menu
                                 *  --------------------
                                 */
                                'location-menu'             =>      esc_attr( 'Locations' ),

                                /**
                                 *  Footer Category Menu
                                 *  --------------------
                                 */
                                'category-menu'             =>      esc_attr( 'Categories' ),

                                /**
                                 *  Tiny Footer Menu
                                 *  ----------------
                                 */
                                'tiny-footer-menu'          =>      esc_attr( 'Tiny Footer Menu' ),
                            )
                        );
        }

        /**
         *  Required argument in plugin data
         *  -------------------------------- 
         */
        public static function convert_to_array( $args = [] ){

            $_convert   =   [];

            if( parent:: _is_array( $args ) ){

                foreach( $args as $key => $value ){

                    if( $key == esc_attr( 'Name' ) || $key == esc_attr( 'Version' ) || $key == esc_attr( 'TextDomain' ) || $key == esc_attr( 'AuthorName' ) ){

                        $_convert[ esc_attr( $key ) ]   =   ! empty( $value )   ? esc_attr( $value )    :   esc_attr( 'null' );
                    }
                }
            }

            return $_convert;
        }

        /**
         *  Term of use note
         *  ----------------
         */
        public static function term_and_condition_note( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'name'          =>      esc_attr__( 'Submit', 'sdweddingdirectory' ),

                    'before'        =>      '',

                    'after'         =>      '',

                    'collection'    =>      ''

                ] ) );

                /**
                 *  Have Before
                 *  -----------
                 */
                $collection         .=      $before;

                /**
                 *  Content
                 *  -------
                 */
                $collection         .=

                sprintf(   '<small class="form-text text-muted"> %1$s 

                                <span class="txt-orange">&apos;%2$s&apos;</span>,

                                <span>%3$s</span>  <a href="%7$s" target="_blank" class="text-underline">%4$s</a> 

                                <span>%5$s</span>  <a href="%8$s" target="_blank">%6$s</a>

                            </small>',

                        /**
                         *  1. Translation String
                         *  ---------------------
                         */
                        esc_attr__( 'By clicking', 'sdweddingdirectory' ),

                        /**
                         *  2. Translation String
                         *  ---------------------
                         */
                        esc_attr( $name ),

                        /**
                         *  3. Translation String
                         *  ---------------------
                         */
                        sprintf( esc_attr__( 'I agree to %1$s’s', 'sdweddingdirectory' ),

                            /**
                             *  1. Site Name
                             *  ------------
                             */
                            esc_attr( get_bloginfo( 'name' ) )
                        ),

                        /**
                         *  4. Translation String
                         *  ---------------------
                         */
                        esc_attr__( 'Privacy Policy', 'sdweddingdirectory' ),

                        /**
                         *  5. Translation String
                         *  ---------------------
                         */
                        esc_attr__( 'and', 'sdweddingdirectory' ),

                        /**
                         *  6. Translation String
                         *  ---------------------
                         */
                        esc_attr__( 'Terms of Use', 'sdweddingdirectory' ),

                        /**
                         *  7. Privacy Policy : Page Template Link
                         *  --------------------------------------
                         */
                        esc_url( apply_filters( 'sdweddingdirectory/template/link', esc_attr( 'privacy-policy.php' ) ) ),

                        /**
                         *  8. Terms of Use : Page Template Link
                         *  --------------------------------------
                         */
                        esc_url( apply_filters( 'sdweddingdirectory/template/link', esc_attr( 'terms-of-use.php' ) ) )
                );

                /**
                 *  Have After
                 *  ----------
                 */
                $collection         .=      $after;

                /**
                 *  Return
                 *  ------
                 */
                return              $collection;
            }
        }

        /**
         *  Install SDWeddingDirectory - Plugin Update
         *  ----------------------------------
         */
        public static function plugin_update_checker(){

            if( defined( 'SDWEDDINGDIRECTORY_SKIP_LICENSE' ) && SDWEDDINGDIRECTORY_SKIP_LICENSE ){

                return;
            }

            /**
             *  Plugin Update Checker
             *  ---------------------
             */
            $_data  =   apply_filters( 'sdweddingdirectory_plugin_update', [] );

            /**
             *  Make sure purchase code is enable to get an update from SDWeddingDirectory
             *  ------------------------------------------------------------------
             */
            if( parent:: _is_array( $_data ) && get_option( 'SDWeddingDirectory_Theme_Registration' ) !== '' ){

                /**
                 *  Plugin have an update handler 
                 *  -----------------------------
                 */
                require     SDWEDDINGDIRECTORY_DIR . '/plugin-update-checker-4.6/plugin-update-checker.php';

                /**
                 *  Each plugin check with update release ?
                 *  ---------------------------------------
                 */
                foreach ( $_data as $key => $value) {

                    Puc_v4_Factory::buildUpdateChecker(

                        /**
                         *  1. Read the json file for update
                         *  --------------------------------
                         */
                        esc_url( $value[ 'json' ] ),

                        /**
                         *  2. Full path to the main plugin file or functions.php
                         *  -----------------------------------------------------
                         */
                        $value[ 'path' ],

                        /**
                         *  3. Plugin Slug
                         *  --------------
                         */
                        esc_attr( $value[ 'slug' ] )
                    );
                }
            }
        }

        /**
         *  Load custom style in admin header
         *  ---------------------------------
         *  @credit - https://wordpress.stackexchange.com/questions/314539/override-load-styles-php-with-admin-screen-css#answers-header
         *  ----------------------------------------------------------------------------------------------------------------------------
         */
        public static function plugin_information_style(){

            ?><style>#plugin-information #section-description.section{display: inline-block;}</style><?php
        }

        /**
         *  Have Update Plugin Link in Database ?
         *  -------------------------------------
         */
        public static function plugin_update_json_file( $plugin_slug = '' ){

            /**
             *  Server Path
             *  -----------
             */
            $path     =   get_option( 'SDWeddingDirectory_Plugins_Update' );

            /**
             *  Make sure live site path with plugin slug match
             *  -----------------------------------------------
             */
            if( parent:: _have_data( $path ) && ! empty( $plugin_slug ) ){

                return  esc_url_raw( json_encode( $path ) . $plugin_slug . '/info.json' );
            }
        }

        /**
         *  -----------------------------------------------
         *  SDWeddingDirectory - Get Media Id to retrive image link
         *  -----------------------------------------------
         *  @credit - https://developer.wordpress.org/reference/functions/wp_get_attachment_image_src/
         *  ------------------------------------------------------------------------------------------
         */
        public static function sdweddingdirectory_media( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if(  parent:: _is_array( $args )  ){

                /**
                 *  Extract Args with Merge Default Args
                 *  ------------------------------------
                 */
                extract( wp_parse_args( $args, [

                    'media_id'      =>      absint( '0' ),

                    'image_size'    =>      esc_attr(  'sdweddingdirectory_img_600x470' ),

                    'get_data'      =>      esc_attr( 'url' ),

                    'default'       =>      ''

                ] ) );

                /**
                 *  Media is empty ?
                 *  ----------------
                 */
                if(  empty( $media_id )  ){

                    return      $default;
                }

                /**
                 *  Have Media Data ?
                 *  -----------------
                 */
                $_media_data    =   wp_get_attachment_image_src(

                                        /**
                                         *  1. Media ID
                                         *  -----------
                                         */
                                        absint( $media_id ),

                                        /**
                                         *  2. Media Size
                                         *  -------------
                                         */
                                        esc_attr( $image_size )
                                    );

                /**
                 *  Get Attechment meta data
                 *  ------------------------
                 */
                $media_meta     =   wp_get_attachment_metadata(

                                        /**
                                         *  1. Get the Media ID
                                         *  -------------------
                                         */
                                        absint( $media_id ),

                                        /**
                                         *  2. TRUE
                                         *  -------
                                         */
                                        true
                                    );

                /**
                 *  Media Have Array ?
                 *  ------------------
                 */
                if( parent:: _is_array( $_media_data ) || parent:: _is_array( $media_meta ) ){

                    /**
                     *  Return : Link
                     *  -------------
                     */
                    if(  $get_data == esc_attr( 'url' )  ){

                        return      esc_url( $_media_data[ absint( '0' ) ] );
                    }

                    /**
                     *  Return : Width 
                     *  --------------
                     */
                    if( $get_data == esc_attr( 'width' ) ){

                        return      absint(  $media_meta[ 'width' ] );
                    }

                    /**
                     *  Return : Height
                     *  ---------------
                     */
                    if( $get_data == esc_attr( 'height' ) ){

                        return      absint(  $media_meta[ 'height' ] );
                    }

                    /**
                     *  Return : Alt
                     *  ------------
                     */
                    if( $get_data == esc_attr( 'alt' ) ){

                        return      esc_attr( get_post_meta(

                                        /**
                                         *  1. Media Post Attachment ID
                                         *  ---------------------------
                                         */
                                        absint( $media_id ), 

                                        /**
                                         *  2. Meta Key
                                         *  -----------
                                         */
                                        sanitize_key( '_wp_attachment_image_alt' ), 

                                        /**
                                         *  3. TRUE
                                         *  -------
                                         */
                                        true

                                    ) );
                    }
                }
            }
        }

        /**
         *  Return Image Args
         *  -----------------
         */
        public static function get_image_data( $image_id = '' ){

            /**
             *  Have Image ID ?
             *  ---------------
             */
            if( empty( $image_id ) ){

                return;
            }

            /**
             *  Handler
             *  -------
             */
            extract( [

                'handler'               =>      [],

                'image_size_list'       =>      apply_filters( 'sdweddingdirectory/image-size', [] )

            ] );

            /**
             *  Image Size Available ?
             *  ----------------------
             */
            if( self:: _is_array( $image_size_list ) ){

                /**
                 *  Loop
                 *  ----
                 */
                foreach( $image_size_list as $key => $value ){

                    /**
                     *  Extract Args
                     *  ------------
                     */
                    extract( $value );

                    /**
                     *  Get Image Object
                     *  ----------------
                     */
                    if( $image_id == $id ){

                        $handler      =       $value;
                    }
                }
            }

            /**
             *  Handler
             *  -------
             */
            return      $handler;
        }
    }

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Core_Filters:: get_instance();
}
