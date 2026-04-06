<?php
/**
 *  SDWeddingDirectory - Gutenberg Support
 *  ------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Gutenberg_Support' ) && class_exists( 'SDWeddingDirectory_Theme_Scripts' ) ){

    /**
     *  SDWeddingDirectory - Gutenberg Support
     *  ------------------------------
     */
    class SDWeddingDirectory_Gutenberg_Support extends SDWeddingDirectory_Theme_Scripts {

        /**
         *  Member Variable
         *  ---------------
         *  @var instance
         *  -------------
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
             *  1. Gutenberg Color Filter
             *  -------------------------
             */
            add_filter( 'sdweddingdirectory/gutenberg/colors', [ $this, 'color_palette' ] );

            /**
             *  2. Gutenberg Font Size Filter
             *  -----------------------------
             */
            add_filter( 'sdweddingdirectory/gutenberg/font-size', [ $this, 'font_size_list' ] );

            /**
             *  3. Inline Style Added for Gutenberg
             *  -----------------------------------
             */
            add_filter( 'sdweddingdirectory/inline-style', function( $args = [] ){

                return  array_merge( $args, array(

                            'gutenberg-style'   =>  preg_replace('/\s+/', ' ', self:: gutenberg_inline_style()  )

                        ) );
            } );

            /**
             *  2. After Setup Theme
             *  --------------------
             */
            add_action( 'after_setup_theme', function(){

                /**
                 *  1. Block Style
                 *  --------------
                 *  @link - https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#default-block-styles
                 *  -------------------------------------------------------------------------------------------------------------
                 */
                add_theme_support( 'wp-block-styles' );

                /**
                 *  2. Align Wide Support
                 *  ---------------------
                 *  @link - https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#wide-alignment
                 *  -------------------------------------------------------------------------------------------------------
                 */
                add_theme_support( 'align-wide' );

                /**
                 *  3. SDWeddingDirectory - Editor Style Loaded
                 *  ------------------------------------
                 *  @credit - https://richtabor.com/add-wordpress-theme-styles-to-gutenberg/
                 *  ------------------------------------------------------------------------
                 */
                add_theme_support( 'editor-styles' );

                /**
                 *  4. Enqueue editor styles
                 *  ------------------------
                 */
                add_action( 'enqueue_block_editor_assets', [ $this, 'sdweddingdirectory_editory_styles' ] );

                /**
                 *  5. Load Editor Font Family
                 *  --------------------------
                 */
                add_editor_style( esc_url_raw( parent:: sdweddingdirectory_google_fonts() ) );

                /**
                 *  6. Editor Color
                 *  ---------------
                 *  Editor Color Palette
                 *  --------------------
                 *  @credit - https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#block-color-palettes
                 *  ---------------------------------------------------------------------------------------------------------------
                 */
                add_theme_support(

                    /**
                     *  Editor Color Palette
                     *  --------------------
                     */
                    esc_attr( 'editor-color-palette' ),

                    /**
                     *  Add SDWeddingDirectory - Color Palette
                     *  ------------------------------
                     */
                    apply_filters( 'sdweddingdirectory/gutenberg/colors', [] )
                );

                /**
                 *  7. Editor Font Size
                 *  -------------------
                 *  Editor Font Size
                 *  ----------------
                 *  @credit - https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#block-font-sizes
                 *  -----------------------------------------------------------------------------------------------------------
                 */
                add_theme_support(

                    /**
                     *  Editor Font Size
                     *  ----------------
                     */
                    esc_attr( 'editor-font-sizes' ),

                    /**
                     *  Add SDWeddingDirectory - Font Size
                     *  --------------------------
                     */
                    apply_filters( 'sdweddingdirectory/gutenberg/font-size', [] )
                );

                /**
                 *  8. Responsive Embeds
                 *  --------------------
                 *  @credit - https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
                 *  ----------------------------------------------------------------------------------------------------------------------
                 */
                add_theme_support( 'responsive-embeds' );

                /**
                 *  9. Custom Spacing
                 *  -----------------
                 *  @credit - https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#spacing-control
                 *  ----------------------------------------------------------------------------------------------------------
                 */
                add_theme_support( 'custom-spacing' );

            } );
        }

        /**
         *  Gutenberg - Support Color Palette
         *  ---------------------------------
         */
        public static function color_palette( $args = [] ){

            return  array_merge(  

                        /**
                         *  1. Have Color
                         *  -------------
                         */
                        $args,

                        /**
                         *  2. Merge New Color
                         *  ------------------
                         */
                        array(

                            array(

                                'name'  =>  esc_attr__( 'SDWeddingDirectory - Default Color', 'sdweddingdirectory' ),

                                'slug'  =>  esc_attr( 'sdweddingdirectory-default-color' ),

                                'color' =>  sanitize_hex_color( '#f48f00' ),
                            ),
                            array(

                                'name'  =>  esc_attr__( 'SDWeddingDirectory - Primary Color', 'sdweddingdirectory' ),

                                'slug'  =>  esc_attr( 'sdweddingdirectory-primary-color' ),

                                'color' =>  sanitize_hex_color( '#00aeaf' ),
                            ),
                            array(

                                'name'  =>  esc_attr__( 'SDWeddingDirectory - Black Color', 'sdweddingdirectory' ),

                                'slug'  =>  esc_attr( 'sdweddingdirectory-black-color' ),

                                'color' =>  sanitize_hex_color( '#000000' ),
                            ),
                            array(

                                'name'  =>  esc_attr__( 'SDWeddingDirectory - Dark Gray Color', 'sdweddingdirectory' ),

                                'slug'  =>  esc_attr( 'sdweddingdirectory-dark-gray-color' ),

                                'color' =>  sanitize_hex_color( '#302d34' ),
                            ),
                            array(

                                'name'  =>  esc_attr__( 'SDWeddingDirectory - Black Variant Color', 'sdweddingdirectory' ),

                                'slug'  =>  esc_attr( 'sdweddingdirectory-black-variant-color' ),

                                'color' => '#252428',
                            ),
                            array(

                                'name'  =>  esc_attr__( 'SDWeddingDirectory - Teal Dark Color', 'sdweddingdirectory' ),

                                'slug'  =>  esc_attr( 'sdweddingdirectory-teal-dark-color' ),

                                'color' =>  sanitize_hex_color( '#005b5c' ),
                            ),
                            array(

                                'name'  =>  esc_attr__( 'SDWeddingDirectory - Light Gray Color', 'sdweddingdirectory' ),

                                'slug'  =>  esc_attr( 'sdweddingdirectory-light-gray-color' ),

                                'color' =>  sanitize_hex_color( '#f8f8f9' ),
                            ),
                        )
                    );
        }

        /**
         *  Get Gutenberg Style
         *  -------------------
         */
        public static function gutenberg_inline_style(){

            $_style     =   '';

            /**
             *  Update Editor Color Style
             *  -------------------------
             */
            if( parent:: _is_array( apply_filters( 'sdweddingdirectory/gutenberg/colors', [] ) ) ){

                foreach( apply_filters( 'sdweddingdirectory/gutenberg/colors', [] ) as $key => $value ){

                    $_style     .=

                    sprintf(

                        '.has-%1$s-color{ color: %2$s } .has-%1$s-background-color{ background-color: %2$s }',

                        sanitize_key( $value[ 'slug' ] ),
                        
                        sanitize_hex_color( $value[ 'color' ] )
                    );
                }
            }

            /**
             *  Update Editor Font Size
             *  -----------------------
             */
            if( parent:: _is_array( apply_filters( 'sdweddingdirectory/gutenberg/font-size', [] ) ) ){

                foreach( apply_filters( 'sdweddingdirectory/gutenberg/font-size', [] ) as $key => $value ){

                    $_style     .=

                    sprintf(

                        '.has-%1$s-font-size{ font-size: %2$spx; }',

                        sanitize_key( $value[ 'slug' ] ),
                        
                        absint( $value[ 'size' ] )
                    );
                }
            }

            return $_style;
        }

        /**
         *  Editor Style
         *  ------------
         */
        public static function sdweddingdirectory_editory_styles(){

            /**
             *  Get List of Icon Library used in SDWeddingDirectory!
             *  --------------------------------------------
             */
            $install_font_family   =    array_merge(

                                            /**
                                             *  1. Collection for fonts
                                             *  -----------------------
                                             */
                                            apply_filters( 'sdweddingdirectory_icon_library', [] ),

                                            /**
                                             *  2. Update Editor StyleSheet path
                                             *  --------------------------------
                                             */
                                            [ 
                                                'editor-style'  =>  esc_url(

                                                                        trailingslashit( SDWEDDINGDIRECTORY_THEME_DIR ) . 'assets/css/editor-style.css'
                                                                    )
                                            ] 
                                        );


            /**
             *  Have Font Family Member ?
             *  -------------------------
             */
            if( parent:: _is_array( $install_font_family ) ){

                /**
                 *  Load one by one icons family
                 *  ----------------------------
                 */
                foreach( $install_font_family as $key => $value ){

                    /**
                     *  Add Stylesheet and Fonts
                     *  ------------------------
                     */
                    wp_enqueue_style(

                        /**
                         *  Stylsheet Name
                         *  --------------
                         */
                        esc_attr( 'sdweddingdirectory-editor-' . $key ),

                        /**
                         *  Link
                         *  ----
                         */
                        esc_url( $value ),

                        /**
                         *  Dependecy
                         *  ---------
                         */
                        array( 'wp-edit-blocks' ),

                        /**
                         *  Version
                         *  -------
                         */
                        esc_attr( wp_get_theme()->get( 'Version' ) ),

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
         *  Gutenberg - Support Font Size
         *  -----------------------------
         */
        public static function font_size_list( $args = [] ){

            return  array_merge(

                        /**
                         *  1. Have Font
                         *  ------------
                         */
                        $args,

                        /**
                         *  2. Merge New Font
                         *  -----------------
                         */
                        array(

                            array(

                                'name' =>   esc_attr__( 'Heading H1', 'sdweddingdirectory' ),
                                'size' =>   absint( '40' ),
                                'slug' =>   esc_attr( 'sdweddingdirectory-heading-1' )
                            ),
                            array(

                                'name' =>   esc_attr__( 'Heading H2', 'sdweddingdirectory' ),
                                'size' =>   absint( '23' ),
                                'slug' =>   esc_attr( 'sdweddingdirectory-heading-2' )
                            ),
                            array(

                                'name' =>   esc_attr__( 'Heading H3', 'sdweddingdirectory' ),
                                'size' =>   absint( '20' ),
                                'slug' =>   esc_attr( 'sdweddingdirectory-heading-3' )
                            ),
                            array(

                                'name' =>   esc_attr__( 'Heading H4', 'sdweddingdirectory' ),
                                'size' =>   absint( '18' ),
                                'slug' =>   esc_attr( 'sdweddingdirectory-heading-4' )
                            ),
                            array(

                                'name' =>   esc_attr__( 'Heading H5', 'sdweddingdirectory' ),
                                'size' =>   absint( '16' ),
                                'slug' =>   esc_attr( 'sdweddingdirectory-heading-5' )
                            ),
                            array(

                                'name' =>   esc_attr__( 'Heading H6', 'sdweddingdirectory' ),
                                'size' =>   absint( '14' ),
                                'slug' =>   esc_attr( 'sdweddingdirectory-heading-6' )
                            ),
                            array(

                                'name' =>   esc_attr__( 'Paragraph P', 'sdweddingdirectory' ),
                                'size' =>   absint( '15' ),
                                'slug' =>   esc_attr( 'sdweddingdirectory-heading-p' )
                            ),
                        )
                    );
        }
    }

    /**
     *  SDWeddingDirectory - Gutenberg Support
     *  ------------------------------
     */
    SDWeddingDirectory_Gutenberg_Support::get_instance();
}
