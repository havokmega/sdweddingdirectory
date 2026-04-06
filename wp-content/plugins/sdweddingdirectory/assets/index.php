<?php
/**
 *  SDWeddingDirectory - Load [ Script && Styles ]
 *  --------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Scripts' ) && class_exists( 'SDWeddingDirectory_Config' ) ){
   
    /**
     *  SDWeddingDirectory - Load [ Script && Styles ]
     *  --------------------------------------
     */
    class SDWeddingDirectory_Scripts extends SDWeddingDirectory_Config{

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
             *  Register Modals
             *  ---------------
             */
            add_filter( 'sdweddingdirectory/localize_script', [ $this, 'register_modal' ], absint( '10' ), absint( '1' ) );

            /**
             *  1. SDWeddingDirectory - Load Script
             *  ---------------------------
             */
            add_action( 'wp_enqueue_scripts', [ $this, 'sdweddingdirectory_plugin_script' ], absint( '500' ) );

            /**
             *  2. SDWeddingDirectory - Icon Manager Filter
             *  -----------------------------------
             */
            add_filter( 'sdweddingdirectory_icon_library', [ $this, 'add_new_icon_library' ] );
        }

        /**
         *   Add New Font Library link
         *   -------------------------
         */
        public static function add_new_icon_library( $args = [] ){

            /**
             *  Return : Font collection
             *  ------------------------
             */
            return      array_merge(

                            /**
                             *  Have args ?
                             *  -----------
                             */
                            $args,

                            /**
                             *  Add New font
                             *  ------------
                             */
                            array(

                                'sdweddingdirectory-flaticon'   =>  esc_url( plugin_dir_url( __FILE__ ) . 'library/flaticon/flaticon.css' ),

                                'sdweddingdirectory-fontello'   =>  esc_url( plugin_dir_url( __FILE__ ) . 'library/fontello/css/fontello.css' )
                            )
                        );
        }

        /**
         *  ---------------------------
         *  1. SDWeddingDirectory - Load Script
         *  ---------------------------
         */
        public static function sdweddingdirectory_plugin_script(){

            global $post, $wp_query, $page;

            /**
             *  ---------------------------
             *  Slide Reveal jQuery Library
             *  ---------------------------
             */
            self:: slide_reveal_libary();

            /**
             *   ---------------------------
             *   SDWeddingDirectory - Editor Library
             *   ---------------------------
             */
            self:: summery_editor_library();

            /**
             *  -----------------------
             *  Magnific jQuery Library
             *  -----------------------
             */
            self:: magnific_popup_library();

            /**
             *  --------------
             *  Pricing Slider
             *  --------------
             */
            self:: sdweddingdirectory_range_slider();

            /**
             *  ---------------------
             *  Isotop jQuery Library
             *  ---------------------
             */
            self:: isotop_library();

            /**
             *  -----------------------
             *  Masonary jQuery Library
             *  -----------------------
             */
            self:: masonary_library();

            /**
             *   -------------------------------
             *   SDWeddingDirectory - Pagination Library
             *   -------------------------------
             */
            self:: pagination_library();

            /**
             *  ---------------------------
             *  DatePicker - jQuery Library
             *  ---------------------------
             */
            self:: date_picker_libaray();

            /**
             *  ----------------------------
             *  Flaticon - Icon Font Library
             *  ----------------------------
             */
            self:: flaticon_icon_library();

            /**
             *  ----------------------------
             *  Fontello - Icon Font Library
             *  ----------------------------
             */
            self:: fontello_icon_library();

            /**
             *  ----------------------------
             *  Toaster - Alert Show Library
             *  ----------------------------
             */
            self:: toastr_library();

            /**
             *  ----------------------------------------
             *  SDWeddingDirectory - Clipboard ( Click to Copy )
             *  ----------------------------------------
             */
            self:: clipboard_library();

            /**
             *  -----------------------
             *  Select 2 jQuery Library
             *  -----------------------
             */
            self::select2_library();

            /**
             *  -----------------------------
             *  SDWeddingDirectory - Pie Chart Script
             *  -----------------------------
             */
            self:: sdweddingdirectory_pie_chart();

            /**
             *  ----------------------
             *  SDWeddingDirectory - Animation
             *  ----------------------
             */
            self:: sdweddingdirectory_animation();

            /**
             *  --------------------------
             *  SDWeddingDirectory - Plugin Script
             *  --------------------------
             */
            self:: sdweddingdirectory_scripts();
        }

        /**
         *  ----------------------------------------
         *  SDWeddingDirectory - Clipboard ( Click to Copy )
         *  ----------------------------------------
         */
        public static function clipboard_library(){

            /**
             *  Load Library
             *  ------------
             */
            wp_enqueue_style( 

                /**
                 *  1. File Name
                 *  ------------
                 */
                esc_attr( 'clipboard' ),

                /**
                 *  Library Link
                 *  ------------
                 */
                esc_url( plugin_dir_url( __FILE__ ) . 'library/clipboard/clipboard.min.js' ),

                /**
                 *  Have Dependancy ?
                 *  -----------------
                 */
                [], 

                /**
                 *  Libaray Version ?
                 *  -----------------
                 */
                esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/clipboard/clipboard.min.js' ) ),

                /**
                 *  Load all Media
                 *  --------------
                 */
                esc_attr( 'all' )
            );

            /**
             *  Load Library
             *  ------------
             */
            wp_enqueue_script(

                /**
                 *  1. File Name
                 *  ------------
                 */
                esc_attr( 'clipboard' ),

                /**
                 *  Library Link
                 *  ------------
                 */
                esc_url( plugin_dir_url( __FILE__ ) . 'library/clipboard/clipboard.js' ), 

                /**
                 *  Have Dependancy ?
                 *  -----------------
                 */
                array('jquery'),

                /**
                 *  Libaray Version ?
                 *  -----------------
                 */
                esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/clipboard/clipboard.js' ) ),

                /**
                 *  Load all Media
                 *  --------------
                 */
                true
            );
        }

        /**
         *   ---------------------------
         *   SDWeddingDirectory - Isotop Library
         *   ---------------------------
         */
        public static function isotop_library(){

            /**
             *  Have Request ?
             *  --------------
             */
            if( parent:: _load_script( 'sdweddingdirectory/enable-script/isotope' ) ){

                /**
                 *  Load Library
                 *  ------------
                 */
                wp_enqueue_script(

                    /**
                     *  Library Name
                     *  ------------
                     */
                    esc_attr( 'isotope' ),

                    /**
                     *  Library Link
                     *  ------------
                     */
                    esc_url( plugin_dir_url( __FILE__ ) . 'library/isotope/isotope.pkgd.min.js' ),

                    /**
                     *  Have Dependancy ?
                     *  -----------------
                     */
                    array( 'jquery' ),

                    /**
                     *  Libaray Version ?
                     *  -----------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/isotope/isotope.pkgd.min.js' ) ),

                    /**
                     *  Load all Media
                     *  --------------
                     */
                    true
                );
            }
        }

        /**
         *   ---------------------------
         *   SDWeddingDirectory - Isotop Library
         *   ---------------------------
         */
        public static function masonary_library(){

            /**
             *  Have Request ?
             *  --------------
             */
            if( parent:: _load_script( 'sdweddingdirectory/enable-script/masonary' ) ){

                /**
                 *  Load Library
                 *  ------------
                 */
                wp_enqueue_script(

                    /**
                     *  Library Name
                     *  ------------
                     */
                    esc_attr( 'masonary' ),

                    /**
                     *  Library Link
                     *  ------------
                     */
                    esc_url( plugin_dir_url( __FILE__ ) . 'library/masonary/script.js' ),

                    /**
                     *  Have Dependancy ?
                     *  -----------------
                     */
                    array( 'jquery' ),

                    /**
                     *  Libaray Version ?
                     *  -----------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/masonary/script.js' ) ),

                    /**
                     *  Load all Media
                     *  --------------
                     */
                    true
                );
            }
        }

        /**
         *   -------------------------------
         *   SDWeddingDirectory - Pagination Library
         *   -------------------------------
         */
        public static function pagination_library(){

            /**
             *  Have Request ?
             *  --------------
             */
            if( parent:: _load_script( 'sdweddingdirectory/enable-script/pagination' ) ){

                /**
                 *  Load Library
                 *  ------------
                 */
                wp_enqueue_style(

                    /**
                     *  Library Name
                     *  ------------
                     */
                    esc_attr( 'pagination' ),

                    /**
                     *  Library Link
                     *  ------------
                     */
                    esc_url( plugin_dir_url( __FILE__ ) . 'library/pagination/pagination.css' ),

                    /**
                     *  Have Dependancy ?
                     *  -----------------
                     */
                    [], 

                    /**
                     *  Libaray Version ?
                     *  -----------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/pagination/pagination.css' ) ),

                    /**
                     *  Load all Media
                     *  --------------
                     */
                    esc_attr( 'all' )
                );

                /**
                 *  Load Library
                 *  ------------
                 */
                wp_enqueue_script( 

                    /**
                     *  Library Name
                     *  ------------
                     */
                    esc_attr( 'pagination' ),

                    /**
                     *  Library Link
                     *  ------------
                     */
                    esc_url( plugin_dir_url( __FILE__ ) . 'library/pagination/pagination.js' ),

                    /**
                     *  Have Dependancy ?
                     *  -----------------
                     */
                    array('jquery'), 

                    /**
                     *  Libaray Version ?
                     *  -----------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/pagination/pagination.js' ) ),

                    /**
                     *  Load all Media
                     *  --------------
                     */
                    true
                );
            }
        }

        /**
         *  ----------------------------
         *  Toaster - Alert Show Library
         *  ----------------------------
         *  @credit - https://codeseven.github.io/toastr/
         *  ---------------------------------------------
         */
        public static function toastr_library(){
            
            /**
             *  Load Library
             *  ------------
             */
            wp_enqueue_style( 

                /**
                 *  Library Name
                 *  ------------
                 */
                esc_attr( 'toastr' ),

                /**
                 *  Library Link
                 *  ------------
                 */
                esc_url( plugin_dir_url( __FILE__ ) . 'library/toastr/toastr.css' ),

                /**
                 *  Have Dependancy ?
                 *  -----------------
                 */
                array( 'bootstrap' ),

                /**
                 *  Libaray Version ?
                 *  -----------------
                 */
                esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/toastr/toastr.css' ) ),

                /**
                 *  Load all Media
                 *  --------------
                 */
                esc_attr( 'all' )
            );

            /**
             *  Load Library
             *  ------------
             */
            wp_enqueue_script( 

                /**
                 *  Library Name
                 *  ------------
                 */
                esc_attr( 'toastr' ), 

                /**
                 *  Library Link
                 *  ------------
                 */
                esc_url( plugin_dir_url( __FILE__ ) . 'library/toastr/toastr.js' ),

                /**
                 *  Have Dependancy ?
                 *  -----------------
                 */
                array('jquery'), 

                /**
                 *  Library Version ?
                 *  -----------------
                 */
                esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/toastr/toastr.js' ) ),

                /**
                 *  Load in Footer ?
                 *  ----------------
                 */
                true
            );
        }

        /**
         *   ---------------------------
         *   SDWeddingDirectory - Editor Library
         *   ---------------------------
         */
        public static function summery_editor_library(){

            /**
             *  Have Request ?
             *  --------------
             */
            if( parent:: _load_script( 'sdweddingdirectory/enable-script/summary-editor' ) ){

                /**
                 *  Load Library
                 *  ------------
                 */
                wp_enqueue_style(

                    /**
                     *  Library Name
                     *  ------------
                     */
                    esc_attr( 'summernote' ),

                    /**
                     *  Library Link
                     *  ------------
                     */
                    esc_url( plugin_dir_url( __FILE__ ) . 'library/summernote/summernote.css' ),

                    /**
                     *  Have Dependancy ?
                     *  -----------------
                     */
                    [],

                    /**
                     *  Library Version ?
                     *  -----------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/summernote/summernote.css' ) ),

                    /**
                     *  Media All ?
                     *  -----------
                     */
                    esc_attr( 'all' )
                );

                /**
                 *  Load Library
                 *  ------------
                 */
                wp_enqueue_script(

                    /**
                     *  Library Name
                     *  ------------
                     */
                    esc_attr( 'summernote' ),

                    /**
                     *  Library Link
                     *  ------------
                     */
                    esc_url( plugin_dir_url( __FILE__ ) . 'library/summernote/summernote.js' ),

                    /**
                     *  Have Dependancy ?
                     *  -----------------
                     */
                    array('jquery'), 

                    /**
                     *  Library Version ?
                     *  -----------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/summernote/summernote.js' ) ),

                    /**
                     *  Load in Footer ?
                     *  ----------------
                     */
                    true 
                );
            }
        }        

        /**
         *  --------------------------
         *  SDWeddingDirectory - Plugin Script
         *  --------------------------
         */
        public static function sdweddingdirectory_scripts(){

            /**
             *  Load Library
             *  ------------
             */
            wp_enqueue_script( 

                /**
                 *  File Name
                 *  ---------
                 */
                esc_attr( 'sdweddingdirectory-core-script' ),

                /**
                 *  File Link
                 *  ---------
                 */
                esc_url( plugin_dir_url( __FILE__ )     .   'script.js' ),

                /**
                 *  Have Dependancy ?
                 *  -----------------
                 */
                array( 'jquery' ),

                /**
                 *  File Version ?
                 *  --------------
                 */
                esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'script.js' ) ),

                /**
                 *  Load in Footer ?
                 *  ----------------
                 */
                false
            );

            /**
             *  SDWeddingDirectory - Localize Script
             *  ----------------------------
             */
            wp_localize_script(

                /**
                 *  Load After Script NAME
                 *  ----------------------
                 */
                esc_attr( 'sdweddingdirectory-core-script' ),

                /**
                 *  Localize Object
                 *  ---------------
                 */
                esc_attr( 'SDWEDDINGDIRECTORY_AJAX_OBJ' ),

                /**
                 *  Localize Object Data 
                 *  --------------------
                 */
                apply_filters( 'sdweddingdirectory/localize_script',

                    /**
                     *  Default Added This Args
                     *  -----------------------
                     */
                    array(

                        /**
                         *  WordPress AJAX File
                         *  -------------------
                         */
                        'ajaxurl'                       =>      admin_url( 'admin-ajax.php' ),

                        /**
                         *  Brand Name
                         *  ----------
                         */
                        'brand_name'                    =>      sanitize_title( get_bloginfo( 'name' ) ),

                        /**
                         *  SDWeddingDirectory - Currency Sign
                         *  --------------------------
                         */
                        'currency_sign'                 =>      sdweddingdirectory_currenty(),

                        /**
                         *  SDWeddingDirectory - Currency Position
                         *  ------------------------------
                         */
                        'currency_position'             =>      sdweddingdirectory_currency_possition(),

                        /**
                         *  SDWeddingDirectory - Loader Enable ?
                         *  ----------------------------
                         */
                        // OptionTree loader toggle removed - keep loader disabled per current site behavior.
                        'sdweddingdirectory_loader'             =>      false,

                        'sdweddingdirectory_map_latitude'       =>     parent:: _have_data( sdweddingdirectory_option( 'sdweddingdirectory_latitude' ) )

                                                                ?   esc_attr( sdweddingdirectory_option( 'sdweddingdirectory_latitude' ) )

                                                                :   esc_attr( '23.019469943904543' ),

                        'sdweddingdirectory_map_longitude'      =>     parent:: _have_data( sdweddingdirectory_option( 'sdweddingdirectory_longitude' ) )

                                                                ?   esc_attr( sdweddingdirectory_option( 'sdweddingdirectory_longitude' ) )

                                                                :   esc_attr( '72.5730813242451' ),

                        'sdweddingdirectory_map_zoom_level'     =>     parent:: _have_data( sdweddingdirectory_option( 'map_zoom_level' ) )

                                                                ?   absint( sdweddingdirectory_option( 'map_zoom_level' ) )

                                                                :   absint( '13' ),
                    )
                )
            );
        }

        /**
         *  Register Modals
         *  ---------------
         */
        public static function register_modal( $args = [] ){

            /**
             *  Register Modals
             *  ---------------
             */
            $_register_modal        =       apply_filters( 'sdweddingdirectory/model-popup', [] );

            /**
             *  Found Modals with Merge modal args
             *  ----------------------------------
             */
            if( parent:: _is_array( $_register_modal ) ){

                return      array_merge( $args, [

                                'popup_list'        =>      array_column(  $_register_modal,  esc_attr( 'modal_id' ), esc_attr( 'slug' )  )

                            ] );
            }

            /**
             *  Return Default Args
             *  -------------------
             */
            else{

                return      $args;
            }
        }

        /**
         *  ----------------------------
         *  Flaticon - Icon Font Library
         *  ----------------------------
         */
        public static function flaticon_icon_library(){

            /**
             *  Load Library
             *  ------------
             */
            wp_enqueue_style( 

                /**
                 *  Libaray Name
                 *  ------------
                 */
                esc_attr( 'sdweddingdirectory-flaticon' ), 

                /**
                 *  Library Link ?
                 *  --------------
                 */
                esc_url( plugin_dir_url( __FILE__ ) . 'library/flaticon/flaticon.css' ), 

                /**
                 *  Have Dependancy ?
                 *  -----------------
                 */
                [], 

                /**
                 *  Libaray Version ?
                 *  -----------------
                 */
                esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/flaticon/flaticon.css' ) ),

                /**
                 *  Load all Media
                 *  --------------
                 */
                esc_attr( 'all' )
            );
        }

        /**
         *  ----------------------------
         *  Fontello - Icon Font Library
         *  ----------------------------
         */
        public static function fontello_icon_library(){

            /**
             *  Load Library
             *  ------------
             */
            wp_enqueue_style( 

                /**
                 *  Libaray Name
                 *  ------------
                 */
                esc_attr( 'sdweddingdirectory-fontello' ),

                /**
                 *  Library Link ?
                 *  --------------
                 */
                esc_url( plugin_dir_url( __FILE__ ) . 'library/fontello/css/fontello.css' ), 

                /**
                 *  Have Dependancy ?
                 *  -----------------
                 */
                [], 

                /**
                 *  Libaray Version ?
                 *  -----------------
                 */
                esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/fontello/css/fontello.css' ) ),

                /**
                 *  Load all Media
                 *  --------------
                 */
                esc_attr( 'all' )
            );
        }

        /**
         *  -----------------------
         *  Magnific jQuery Library
         *  -----------------------
         */
        public static function magnific_popup_library(){

            /**
             *  Owl Carousel Load
             *  -----------------
             */
            if( parent:: _load_script( 'sdweddingdirectory/enable-script/magnific-popup' ) ){

                /**
                 *  Load Style
                 *  ----------
                 */
                wp_enqueue_style( 

                    /**
                     *  Library Name
                     *  ------------
                     */
                    esc_attr( 'magnific-popup' ),

                    /**
                     *  Library Link
                     *  ------------
                     */
                    esc_url( plugin_dir_url( __FILE__ ) . 'library/magnific-popup/magnific-popup.css' ),

                    /**
                     *  Have Dependancy ?
                     *  -----------------
                     */
                    [],

                    /**
                     *  Libaray Version ?
                     *  -----------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/magnific-popup/magnific-popup.css' ) ),

                    /**
                     *  Load All Media
                     *  --------------
                     */
                    esc_attr( 'all' )
                );

                /**
                 *  Load Script
                 *  -----------
                 */
                wp_enqueue_script( 

                    /**
                     *  Library Name
                     *  ------------
                     */
                    esc_attr( 'magnific-popup' ),

                    /**
                     *  Library Link
                     *  ------------
                     */
                    esc_url( plugin_dir_url( __FILE__ ) . 'library/magnific-popup/magnific-popup.min.js' ),

                    /**
                     *  Have Dependancy ?
                     *  -----------------
                     */
                    array( 'jquery' ),

                    /**
                     *  Libaray Version ?
                     *  -----------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/magnific-popup/magnific-popup.min.js' ) ),

                    /**
                     *  Load in Footer
                     *  --------------
                     */
                    true 
                );
            }
        }

        /**
         *  -----------------------
         *  Select 2 jQuery Library
         *  -----------------------
         */
        public static function select2_library(){

            /**
             *  Script Load
             *  -----------
             */
            wp_enqueue_style( 

                /**
                 *  Libaray Name
                 *  ------------
                 */
                esc_attr( 'select2' ),

                /**
                 *  Libary Link
                 *  -----------
                 */
                esc_url( plugin_dir_url( __FILE__ ) . 'library/select2/select2.min.css' ),

                /**
                 *  Have Dependancy ?
                 *  -----------------
                 */
                [], 

                /**
                 *  Libaray version
                 *  ---------------
                 */
                esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/select2/select2.min.css' ) ),

                /**
                 *  Load All Media
                 *  --------------
                 */
                'all' 
            );

            /**
             *  Script Load
             *  -----------
             */
            wp_enqueue_script(

                /**
                 *  Libaray Name
                 *  ------------
                 */
                esc_attr( 'select2' ),

                /**
                 *  Libary Link
                 *  -----------
                 */
                esc_url( plugin_dir_url( __FILE__ ) . 'library/select2/select2.min.js' ),

                /**
                 *  Have Dependancy ?
                 *  -----------------
                 */
                array( 'jquery' ), 

                /**
                 *  Libaray version
                 *  ---------------
                 */
                esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/select2/select2.min.js' ) ),

                /**
                 *  Load in Footer
                 *  --------------
                 */
                true 
            );
        }

        /**
         *  ---------------------------
         *  Slide Reveal jQuery Library
         *  ---------------------------
         */
        public static function slide_reveal_libary(){

            /**
             *  Owl Carousel Load
             *  -----------------
             */
            if( parent:: _load_script( 'sdweddingdirectory/enable-script/slide-reveal' ) ){

                /**
                 *  Load Library
                 *  ------------
                 */
                wp_enqueue_script(

                    /**
                     *  Libaray name
                     *  ------------
                     */
                    esc_attr( 'slide-reveal' ),

                    /**
                     *  Library Link
                     *  ------------
                     */
                    esc_url( plugin_dir_url( __FILE__ ) . 'library/slide-reveal/slide-reveal.min.js' ),

                    /**
                     *  Have Dependancy ?
                     *  -----------------
                     */
                    array( 'jquery' ),

                    /**
                     *  Script Version
                     *  --------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/slide-reveal/slide-reveal.min.js' ) ),

                    /**
                     *  All Media ?
                     *  -----------
                     */
                    true 
                );
            }
        }

        /**
         *  ---------------------------
         *  DatePicker - jQuery Library
         *  ---------------------------
         */
        public static function date_picker_libaray(){

            /**
             *  Owl Carousel Load
             *  -----------------
             */
            if( parent:: _load_script( 'sdweddingdirectory/enable-script/date-picker' ) ){

                /**
                 *  Load Style
                 *  ----------
                 */
                wp_enqueue_style( 

                    /**
                     *  Style name
                     *  ----------
                     */
                    esc_attr( 'sdweddingdirectory-datepicker' ), 

                    /**
                     *  Library Link
                     *  ------------
                     */
                    esc_url( plugin_dir_url( __FILE__ ) . 'library/datepicker/css/bootstrap-datepicker.css' ), 

                    /**
                     *  Have Dependancy ?
                     *  -----------------
                     */
                    [],

                    /**
                     *  Script Version
                     *  --------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/datepicker/css/bootstrap-datepicker.css' ) ),

                    /**
                     *  All Media ?
                     *  -----------
                     */
                    esc_attr( 'all' )
                );

                /**
                 *  Load Style
                 *  ----------
                 */
                wp_enqueue_script( 

                    /**
                     *  Script Name
                     *  -----------
                     */
                    esc_attr( 'sdweddingdirectory-datepicker' ), 

                    /**
                     *  Library Link
                     *  ------------
                     */
                    esc_url( plugin_dir_url( __FILE__ ) . 'library/datepicker/js/bootstrap-datepicker.js' ), 

                    /**
                     *  Have Dependancy ?
                     *  -----------------
                     */
                    array( 'jquery' ), 

                    /**
                     *  Script Version
                     *  --------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/datepicker/js/bootstrap-datepicker.js' ) ),

                    /**
                     *  Enable in Footer
                     *  ----------------
                     */
                    true 
                );
            }
        }

        /**
         *  --------------
         *  Pricing Slider
         *  --------------
         *  Working on : Venue Find Template as Pricing Filter
         *  ----------------------------------------------------
         *  https://www.npmjs.com/package/bootstrap-slider
         */
        public static function sdweddingdirectory_range_slider(){

            /**
             *  Owl Carousel Load
             *  -----------------
             */
            if( parent:: _load_script( 'sdweddingdirectory/enable-script/range-slider' ) ){

                /**
                 *  Load Style
                 *  ----------
                 */
                wp_enqueue_style(

                    /**
                     *  Style name
                     *  ----------
                     */
                    esc_attr( 'bootstrap-slider' ),

                    /**
                     *  Library Link
                     *  ------------
                     */
                    esc_url( plugin_dir_url( __FILE__ ) . 'library/bootstrap-slider/bootstrap-slider.min.css' ),

                    /**
                     *  Have Dependancy ?
                     *  -----------------
                     */
                    [],

                    /**
                     *  Script Version
                     *  --------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/bootstrap-slider/bootstrap-slider.min.css' ) ),

                    /**
                     *  All Media ?
                     *  -----------
                     */
                    esc_attr( 'all' )
                );

                /**
                 *  Load Script
                 *  -----------
                 */
                wp_enqueue_script( 

                    /**
                     *  Script Name
                     *  -----------
                     */
                    esc_attr( 'bootstrap-slider' ),

                    /**
                     *  Library Link
                     *  ------------
                     */
                    esc_url( plugin_dir_url( __FILE__ ) . 'library/bootstrap-slider/bootstrap-slider.min.js' ),

                    /**
                     *  Have Dependancy ?
                     *  -----------------
                     */
                    array( 'jquery' ), 

                    /**
                     *  Version
                     *  -------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/bootstrap-slider/bootstrap-slider.min.js' ) ),

                    /**
                     *  Enable in Footer
                     *  ----------------
                     */
                    true
                );
            }
        }

        /**
         *  --------------
         *  Pricing Slider
         *  --------------
         *  Working on : Venue Find Template as Pricing Filter
         *  ----------------------------------------------------
         */
        public static function sdweddingdirectory_pie_chart(){

            /**
             *  Owl Carousel Load
             *  -----------------
             */
            if( parent:: _load_script( 'sdweddingdirectory/enable-script/pie-chart' ) ){

                /**
                 *  Load Script
                 *  -----------
                 */
                wp_enqueue_script( 

                    /**
                     *  Script Name
                     *  -----------
                     */
                    esc_attr( 'sdweddingdirectory-apexcharts' ),

                    /**
                     *  Library Link
                     *  ------------
                     */
                    esc_url( plugin_dir_url( __FILE__ ) . 'library/apex-chart/apexcharts.js' ),

                    /**
                     *  Have Dependancy ?
                     *  -----------------
                     */
                    array( 'jquery' ), 

                    /**
                     *  Version
                     *  -------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/apex-chart/apexcharts.js' ) ),

                    /**
                     *  Enable in Footer
                     *  ----------------
                     */
                    true
                );
            }
        }

        /**
         *  --------------
         *  Animation CSS
         *  --------------
         *  If required to call filter
         *  --------------------------
         */
        public static function sdweddingdirectory_animation(){

            /**
             *  Owl Carousel Load
             *  -----------------
             */
            if( parent:: _load_script( 'sdweddingdirectory/enable-script/animation' ) ){

                /**
                 *  Load Script
                 *  -----------
                 */
                wp_enqueue_style( 

                    /**
                     *  Script Name
                     *  -----------
                     */
                    esc_attr( 'sdweddingdirectory-apexcharts' ),

                    /**
                     *  Library Link
                     *  ------------
                     */
                    esc_url( plugin_dir_url( __FILE__ ) . 'library/animation/style.css' ),

                    /**
                     *  Have Dependancy ?
                     *  -----------------
                     */
                    array( 'bootstrap' ),

                    /**
                     *  Version
                     *  -------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . 'library/animation/style.css' ) ),

                    /**
                     *  All Media
                     *  ---------
                     */
                    esc_attr( 'all' )
                );
            }
        }
    }

    /**
     *  SDWeddingDirectory - Script Object
     *  --------------------------
     */
    SDWeddingDirectory_Scripts:: get_instance();
}
