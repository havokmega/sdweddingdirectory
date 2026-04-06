<?php
/**
 *  ---------------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Venue Category ]
 *  ---------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Venue_Category' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  ---------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Venue Category ]
     *  ---------------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Venue_Category extends SDWeddingDirectory_Shortcode {

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
                            'post_ids'      =>      '',

                            'style'         =>      absint( '1' ),
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
            return      apply_filters( 'sdweddingdirectory/venue-type/counter-widget', absint( '1' ) );
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
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  ShortCode - [sdweddingdirectory_venue_category]
             *  -----------------------------------------
             */
            add_shortcode( 'sdweddingdirectory_venue_category', [ $this, 'sdweddingdirectory_venue_category' ] );

            /**
             *  Image Size ?
             *  ------------
             */
            add_filter( 'sdweddingdirectory/image-size', function( $args = [] ){

                return  array_merge( $args, array(

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_550x610' ),

                                'name'          =>      esc_attr__( 'Venue Category', 'sdweddingdirectory-shortcodes' ),

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

                return  array_merge(  $args, [

                            'venue-type'  =>  esc_url(  plugin_dir_url( __FILE__ ) . 'images/' . 'venue-type.jpg' ),

                        ] );
            } );

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

                            'sdweddingdirectory_venue_category'  =>   sprintf( '[sdweddingdirectory_venue_category %1$s][/sdweddingdirectory_venue_category]', 

                                                                    parent:: _shortcode_atts( self:: default_args() )
                                                                )
                        ] );
            } );
        }

        /**
         *  Venue Category Icon Load
         *  --------------------------
         */
        public static function sdweddingdirectory_venue_category( $atts, $content = '' ){

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
             *  Venue Category IDs One by one load
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

                                    sanitize_key( 'venue-type' )
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

                                                        'image_size'    =>  esc_attr( 'sdweddingdirectory_img_550x610' ),

                                                        'default_img'   =>  parent:: placeholder( 'venue-type' )

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
                            apply_filters( 'sdweddingdirectory/term/icon', [  'term_id'   =>   absint( $obj->term_id )  ] ),

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
                            esc_attr__( 'Venue Category', 'sdweddingdirectory-shortcodes' )
                    );

                    /**
                     *  Have Style ?
                     *  ------------
                     */
                    $collection     .=  parent:: _shortcode_style_end( $style );
                }
            }

            /**
             *  Venue Category Load
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
                extract( wp_parse_args( $args, self:: default_args() ) );

                /**
                 *  Venue Location
                 *  ----------------
                 */
                return  do_shortcode(

                            sprintf( '[sdweddingdirectory_venue_category style="%1$s" post_ids="%2$s"][/sdweddingdirectory_venue_category]',

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
     *  ---------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Venue Category ]
     *  ---------------------------------------------
     */
    SDWeddingDirectory_Shortcode_Venue_Category::get_instance();
}