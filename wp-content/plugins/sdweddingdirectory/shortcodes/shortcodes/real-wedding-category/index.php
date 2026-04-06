<?php
/**
 *  --------------------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Real Wedding Category ]
 *  --------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Real_Wedding_Category' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  --------------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Real Wedding Category ]
     *  --------------------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Real_Wedding_Category extends SDWeddingDirectory_Shortcode {

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
         *  ShortCode Atts
         *  --------------
         */
        public static function default_args(){

            return      [
                            'style'             =>      absint( '1' ),

                            'post_ids'          =>      ''
                        ];
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
            return      apply_filters( 'sdweddingdirectory/real-wedding-category/counter-widget', absint( '1' ) );
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
                                                sprintf( esc_attr__( '%1$s Real Weddings', 'sdweddingdirectory-shortcodes' ), 

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
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  1. Load Real Wedding Category
             *  -----------------------------
             */
            add_shortcode( 'sdweddingdirectory_real_wedding_category', [ $this, 'sdweddingdirectory_real_wedding_category' ] );

            /**
             *  SDWeddingDirectory - ShortCode Info
             *  ---------------------------
             */
            add_filter( 'sdweddingdirectory_shortcode_list', function( $args = [] ){

                /**
                 *  Get Plugin Information
                 *  ----------------------
                 */
                return  array_merge( $args, [

                            'sdweddingdirectory_real_wedding_category'    =>    sprintf( '[sdweddingdirectory_real_wedding_category %1$s][/sdweddingdirectory_real_wedding_category]',

                                                                            parent:: _shortcode_atts( self:: default_args() )
                                                                        )
                        ] );
            } );

            /**
             *  SDWeddingDirectory - Image Size
             *  -----------------------
             */
            add_filter( 'sdweddingdirectory/image-size', function( $args = [] ){

                return  array_merge( $args, array(

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_550x610' ),

                                'name'          =>      esc_attr__( 'Real Wedding Category', 'sdweddingdirectory-shortcodes' ),

                                'width'         =>      absint( '550' ),

                                'height'        =>      absint( '610' )
                            ]

                        ) );
            } );

            /**
             *  SDWeddingDirectory - Placeholder Filter
             *  -------------------------------
             */
            add_filter( 'sdweddingdirectory/placeholder/shortcode', function( $args = [] ){

                return  array_merge( $args, array(

                            'real-wedding-category'  =>  esc_url(  plugin_dir_url( __FILE__ ) . 'images/' . 'real-wedding-category.jpg' ),

                        ) );
            } );
        }

        /**
         *  Real Wedding Category Load
         *  --------------------------
         */
        public static function sdweddingdirectory_real_wedding_category( $atts, $content = '' ){

            /**
             *  Extract Attr Data
             *  -----------------
             */
            extract( shortcode_atts( self:: default_args(), $atts, esc_attr( __FUNCTION__ ) ) );

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
             *  Real Wedding Category IDs One by one load
             *  ------------------------------------------
             */
            foreach( parent:: _coma_to_array( $post_ids ) as $term_id ){

                /**
                 *  @credit - https://developer.wordpress.org/reference/functions/get_term_by/#user-contributed-notes
                 *  -------------------------------------------------------------------------------------------------
                 */
                $obj        =   get_term_by(

                                    esc_attr( 'id' ), 

                                    absint( $term_id ),

                                    sanitize_key( 'real-wedding-category' )
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
                    $_media_link    =       apply_filters( 'sdweddingdirectory/term/image', [

                                                'term_id'       =>  absint( $term_id ),

                                                'taxonomy'      =>  sanitize_key( 'real-wedding-category' ),

                                                'image_size'    =>  esc_attr( 'sdweddingdirectory_img_550x610' ),

                                                'default_img'   =>  parent:: placeholder( 'real-wedding-category' )

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

                                        <div class="mt-auto w-100 text-center">

                                            <div class="catlinks">

                                                <h3 class="term-name">

                                                    <a href="%1$s">%3$s</a>

                                                </h3>

                                                %4$s

                                            </div>

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
                            '',

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
                            esc_attr__( 'Real Wedding Category', 'sdweddingdirectory-shortcodes' )
                    );

                    /**
                     *  Have Style ?
                     *  ------------
                     */
                    $collection     .=  parent:: _shortcode_style_end( $style );
                }
            }

            /**
             *  Real Wedding Category Load
             *  ---------------------------
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
                extract( wp_parse_args( $args, self:: default_args() ) );

                /**
                 *  Venue Location
                 *  ----------------
                 */
                return  do_shortcode(

                            sprintf( '[sdweddingdirectory_real_wedding_category style="%1$s" post_ids="%2$s"][/sdweddingdirectory_real_wedding_category]',

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
     *  --------------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Real Wedding Category ]
     *  --------------------------------------------------
     */
    SDWeddingDirectory_Shortcode_Real_Wedding_Category::get_instance();
}