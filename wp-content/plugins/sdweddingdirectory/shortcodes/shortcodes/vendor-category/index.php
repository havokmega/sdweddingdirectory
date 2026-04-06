<?php
/**
 *  --------------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Vendor Category ]
 *  --------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Vendor_Category' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  --------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Vendor Category ]
     *  --------------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Vendor_Category extends SDWeddingDirectory_Shortcode {

        /**
         *  Member - Var
         *  ------------
         */
        private static $instance;

        /**
         *  Instance
         *  --------
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
             *  1. Load Vendor Category
             *  -----------------------
             */
            add_shortcode( 'sdweddingdirectory_vendor_category', [ $this, 'sdweddingdirectory_vendor_category' ] );

            /**
             *  3. ShortCode : Filters
             *  ----------------------
             */
            self:: shortcode_filters();
        }

        /**
         *  Have Filter ?
         *  -------------
         */
        public static function found_post(){

            /**
             *  Display When Counter Is
             *  -----------------------
             */
            return      apply_filters( 'sdweddingdirectory/vendor-category/counter-widget', absint( '1' ) );
        }

        /**
         *  Display Counter Badge When ?
         *  ----------------------------
         */
        public static function display_record_found( $obj = [] ){

            /**
             *  Have Counter ?
             *  --------------
             */
            if( parent:: _is_array( $obj ) ){

                /**
                 *  Counter Is Max ?
                 *  ----------------
                 */
                extract( $obj );

                /**
                 *   Is True ?
                 *   ---------
                 */
                if( $count >= self:: found_post() && ! empty( $count ) ){

                    /**
                     *  Return Counter
                     *  --------------
                     */
                    return          sprintf(   '<span class="term-counter">

                                                    <a href="%1$s">%2$s</a>

                                                </span>',

                                                /**
                                                 *  1. Term Link
                                                 *  ------------
                                                 */
                                                esc_url(    get_term_link(  absint( $term_id )  )   ),

                                                /** 
                                                 *  2. Badge
                                                 *  --------
                                                 */
                                                sprintf( esc_attr__( '%1$s Vendors', 'sdweddingdirectory-shortcodes' ), 

                                                    /**
                                                     *  1. Count Used Term in Post
                                                     *  --------------------------
                                                     */
                                                    absint( $count )
                                                )
                                    );
                }
            }
        }

        /**
         *  Have Filters ?
         *  --------------
         */
        public static function shortcode_filters(){

            /**
             *  1. SDWeddingDirectory ShortCode Filter
             *  ------------------------------
             */
            add_filter( 'sdweddingdirectory_shortcode_list', function( $args = [] ){

                /**
                 *  Get Plugin Information
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

                                /**
                                 *  Vendor Category
                                 *  ----------------
                                 */
                                'sdweddingdirectory_vendor_category'  =>

                                '[sdweddingdirectory_vendor_category style="1" post_ids="68,110,69,66,67"][/sdweddingdirectory_vendor_category]'
                            )
                        );
            } );

            /**
             *  2. SDWeddingDirectory - Default Setting
             *  -------------------------------
             */
            add_filter( 'sdweddingdirectory/shortcode/vendor-category/attr', function(){

                /**
                 *  Add Slider Placeholder
                 *  ----------------------
                 */
                return  array(

                            /**
                             *  Collection of Post / Term IDs
                             *  -----------------------------
                             */
                            'post_ids'               =>  '',

                            /**
                             *  Defind [ Owl Carousel Class ( item )  / Grid Column Class ( col ) ]
                             *  -------------------------------------------------------------------
                             */
                            'style'            =>  absint( '1' ),
                        );
            } );

            /**
             *  4. SDWeddingDirectory - Venue Location Taxonomy Image Size ?
             *  ------------------------------------------------------
             */
            add_filter( 'sdweddingdirectory/image-size', function( $args = [] ){

                return  array_merge( 

                            $args,

                            array(

                                [
                                    'id'            =>      esc_attr( 'sdweddingdirectory_img_550x610' ),

                                    'name'          =>      esc_attr__( 'Vendor Category', 'sdweddingdirectory-shortcodes' ),

                                    'width'         =>      absint( '550' ),

                                    'height'        =>      absint( '610' )
                                ]
                            )
                        );
            } );

            /**
             *  5. SDWeddingDirectory - Placeholder Filter
             *  ----------------------------------
             */
            add_filter( 'sdweddingdirectory/placeholder/shortcode', function( $args = [] ){

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

                                'sdweddingdirectory-vendor-category'  =>  esc_url(  plugin_dir_url( __FILE__ ) . 'images/' . 'vendor-category-placeholder.jpg' ),
                            )
                        );
            } );
        }

        /**
         *  Vendor Category Load
         *  --------------------
         */
        public static function sdweddingdirectory_vendor_category( $atts, $content = '' ){

            /**
             *  Extract Attr Data
             *  -----------------
             */
            extract( shortcode_atts(

                /**
                 *  Default Args
                 *  ------------
                 */
                apply_filters( 'sdweddingdirectory/shortcode/vendor-category/attr', [] ),

                /**
                 *  Get Args
                 *  --------
                 */
                $atts,

                /**
                 *  ShortCode Name
                 *  --------------
                 */
                esc_attr( __FUNCTION__ )

            ) );

            /**
             *  Make sure have IDs
             *  ------------------
             */
            if( empty( $post_ids ) ){

                return;
            }

            /**
             *  Have Collection ?
             *  -----------------
             */
            $collection     =       '';

            /**
             *  Vendor Category IDs One by one load
             *  ------------------------------------
             */
            foreach( parent:: _coma_to_array( $post_ids ) as $term_id ){

                /**
                 *  @credit - https://developer.wordpress.org/reference/functions/get_term_by/#user-contributed-notes
                 *  -------------------------------------------------------------------------------------------------
                 */
                $obj        =   get_term_by(

                                    esc_attr( 'id' ), 

                                    absint( $term_id ), 

                                    sanitize_key( 'vendor-category' )
                                );

                /**
                 *  Have Object ID
                 *  --------------
                 */
                if( ! empty( $obj ) ){

                    /**
                     *  Media Data
                     *  ----------
                     */
                    $_media_link            =       apply_filters( 'sdweddingdirectory/term/image', [

                                                        'term_id'       =>  absint( $term_id ),

                                                        'taxonomy'      =>  sanitize_key( 'vendor-category' ),

                                                        'image_size'    =>  esc_attr( 'sdweddingdirectory_img_550x610' ),

                                                        'default_img'   =>  parent:: placeholder( 'sdweddingdirectory-vendor-category' )

                                                    ] );

                    /**
                     *  Have Style ?
                     *  ------------
                     */
                    $collection     .=  parent:: _shortcode_style_start( $style );

                    /**
                     *  Carousel Data
                     *  -------------
                     */
                    $collection     .=

                    sprintf( '<div class="popular-categories">

                                <a href="%1$s"><img src="%5$s" alt="%6$s %7$s %3$s" /></a>

                                <div class="content-wrap">

                                    <div class="content">

                                        <div class="mt-auto d-flex align-items-center w-100 justify-content-between">

                                            <div class="catlinks">

                                                <h3 class="term-name">

                                                    <a href="%1$s">%3$s</a>

                                                </h3>

                                                %4$s

                                            </div>

                                            <a href="%1$s" class="icon"><i class="%2$s"></i></a>

                                        </div>

                                    </div>

                                </div>

                            </div>',

                            /**
                             *  1. Term Name
                             *  ------------
                             */
                            esc_url(

                                /**
                                 *  Term Link
                                 *  ---------
                                 */
                                get_term_link( 

                                    absint( $obj->term_id )
                                )
                            ),

                            /**
                             *  2. Term Icon
                             *  ------------
                             */
                            apply_filters( 'sdweddingdirectory/term/icon', [

                                'term_id'       =>      absint( $obj->term_id ),

                                'taxonomy'      =>      sanitize_key( 'vendor-category' )

                            ] ),

                            /**
                             *  3. Term Name
                             *  ------------
                             */
                            esc_attr( $obj->name ),

                            /**
                             *  4. Translation Ready String
                             *  ---------------------------
                             */
                            self:: display_record_found( (array) $obj ),

                            /**
                             *  5. Get Image
                             *  ------------
                             */
                            esc_url( $_media_link ),
                            
                            /**
                             *  6. Blog Info
                             *  ------------
                             */
                            esc_attr( get_bloginfo( 'name' ) ),

                            /**
                             *  7. Image Alt : Translation Ready String
                             *  ---------------------------------------
                             */
                            esc_attr__( 'Vendor Category', 'sdweddingdirectory-shortcodes' )
                    );

                    /**
                     *  Have Style ?
                     *  ------------
                     */
                    $collection     .=  parent:: _shortcode_style_end( $style );
                }
            }

            /**
             *  Vendor Category Load
             *  ---------------------
             */
            return      $collection;
        }

        /**
         *  Page Builder *Value* pass here to print features
         *  ------------------------------------------------
         */
        public static function page_builder( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Setting with Merge Empty Fields
                 *  ---------------------------------------
                 */
                extract(

                    wp_parse_args(

                        $args,

                        apply_filters( 'sdweddingdirectory/shortcode/vendor-category/attr', [] )
                    )
                );

                /**
                 *  Venue Location
                 *  ----------------
                 */
                return  do_shortcode(

                            sprintf( '[sdweddingdirectory_vendor_category style="%1$s" post_ids="%2$s"][/sdweddingdirectory_vendor_category]',

                                /**
                                 *  3. Layout
                                 *  ---------
                                 */
                                absint( $style ),

                                /**
                                 *  4. Category IDs
                                 *  ---------------
                                 */
                                esc_attr( $post_ids ),
                            )
                        );
            }
        }

    } // end class

    /**
     *  --------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Vendor Category ]
     *  --------------------------------------------
     */
    SDWeddingDirectory_Shortcode_Vendor_Category::get_instance();
}