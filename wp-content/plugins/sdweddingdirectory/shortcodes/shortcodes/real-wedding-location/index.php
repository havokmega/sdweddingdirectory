<?php
/**
 *  --------------------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Real Wedding Location ]
 *  --------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Real_Wedding_Location' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  --------------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Real Wedding Location ]
     *  --------------------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Real_Wedding_Location extends SDWeddingDirectory_Shortcode {

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

                            'post_ids'          =>      '',

                            'layout'            =>      absint( '1' )
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
            return      apply_filters( 'sdweddingdirectory/real-wedding-location/counter-widget', absint( '1' ) );
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
                    return          sprintf(    '<span class="term-counter">

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
             *  1. Load Vendor Category Icon Section
             *  -------------------------------------
             */
            add_shortcode( 'sdweddingdirectory_realwedding_location', [ $this, 'sdweddingdirectory_realwedding_location' ] );

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

                            'sdweddingdirectory_realwedding_location'   =>  sprintf( '[sdweddingdirectory_realwedding_location %1$s][/sdweddingdirectory_realwedding_location]',

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
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_550x460' ),

                                'name'          =>      esc_attr__( 'Real Wedding Location', 'sdweddingdirectory-shortcodes' ),

                                'width'         =>      absint( '550' ),

                                'height'        =>      absint( '460' )
                            ]

                        ) );
            } );

            /**
             *  SDWeddingDirectory - Placeholder Filter
             *  -------------------------------
             */
            add_filter( 'sdweddingdirectory/placeholder/shortcode', function( $args = [] ){

                return  array_merge( $args, array(

                            'real-wedding-location'  =>  esc_url(  plugin_dir_url( __FILE__ ) . 'images/' . 'real-wedding-location.jpg' ),

                        ) );
            } );
        }

        /**
         *  Vendor Category Icon Load
         *  --------------------------
         */
        public static function sdweddingdirectory_realwedding_location( $atts, $conten = '' ){

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
             *  Vendor Category IDs One by one load
             *  ------------------------------------
             */
            foreach( parent:: _coma_to_array( $post_ids ) as $term_id ){

                /**
                 *  @credit - https://developer.wordpress.org/reference/functions/get_term_by/#user-contributed-notes
                 *  -------------------------------------------------------------------------------------------------
                 */
                $obj    =       get_term_by(

                                    esc_attr( 'id' ), 

                                    absint( $term_id ),

                                    sanitize_key( 'real-wedding-location' )
                                );

                /**
                 *  Make sure object not empty!
                 *  ---------------------------
                 */
                if( ! empty( $obj ) ){

                    /**
                     *  Media Data
                     *  ----------
                     */
                    $_get_media_link        =       apply_filters( 'sdweddingdirectory/term/image', [

                                                        'term_id'       =>  absint( $term_id ),

                                                        'taxonomy'      =>  sanitize_key( 'real-wedding-location' ),

                                                        'image_size'    =>  esc_attr( 'sdweddingdirectory_img_550x460' ),

                                                        'default_img'   =>  parent:: placeholder( 'real-wedding-location' )

                                                    ] );

                    /**
                     *  Have Style ?
                     *  ------------
                     */
                    $collection         .=      parent:: _shortcode_style_start( $style );

                    /**
                     *  Layout : 1
                     *  ----------
                     */
                    if( $layout == absint( '1' ) ){

                        /**
                         *  Collection
                         *  ----------
                         */
                        $collection     .=      sprintf(   '<div class="popular-locations">

                                                                <div class="overlay-box">

                                                                    <div class="term-info">

                                                                        <h3 class="term-name">

                                                                            <a href="%1$s">%2$s</a>

                                                                        </h3>

                                                                        %3$s

                                                                    </div>

                                                                    <a href="%1$s" class="iconlink"><i class="fa fa-angle-right"></i></a>

                                                                </div>

                                                                <img class="w-100" src="%4$s" alt="%5$s %6$s %2$s" />

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
                                                             *  2. Term Name
                                                             *  ------------
                                                             */
                                                            esc_attr( $obj->name ),

                                                            /**
                                                             *  3. Term Count
                                                             *  -------------
                                                             */
                                                            self:: display_record_found( (array) $obj ),

                                                            /**
                                                             *  4. Get Image
                                                             *  ------------
                                                             */
                                                            esc_url( $_get_media_link ),
                                                            
                                                            /**
                                                             *  5. Blog Info
                                                             *  ------------
                                                             */
                                                            esc_attr( get_bloginfo( 'name' ) ),

                                                            /**
                                                             *  6. Image Alt : Translation Ready String
                                                             *  ---------------------------------------
                                                             */
                                                            esc_attr__( 'Real Wedding Location Taxonomy', 'sdweddingdirectory-shortcodes' )
                                                );
                    }

                    /**
                     *  Layout : 2
                     *  ----------
                     */
                    elseif( $layout == absint( '2' ) ){

                        /**
                         *  Collection
                         *  ----------
                         */
                        $collection     .=      sprintf(   '<div class="popular-locations-alternate">

                                                                <div class="overlay-box">

                                                                    <div class="mt-auto">

                                                                        <h3 class="term-name">

                                                                            <a href="%1$s">%2$s</a>

                                                                        </h3>

                                                                        %3$s

                                                                    </div>

                                                                </div>

                                                                <img class="w-100" src="%4$s" alt="%5$s %6$s %2$s" />

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
                                                             *  2. Term Name
                                                             *  ------------
                                                             */
                                                            esc_attr( $obj->name ),

                                                            /**
                                                             *  3. Term Count
                                                             *  -------------
                                                             */
                                                            self:: display_record_found( (array) $obj ),

                                                            /**
                                                             *  4. Get Image
                                                             *  ------------
                                                             */
                                                            esc_url( $_get_media_link ),
                                                            
                                                            /**
                                                             *  5. Blog Info
                                                             *  ------------
                                                             */
                                                            esc_attr( get_bloginfo( 'name' ) ),

                                                            /**
                                                             *  6. Image Alt : Translation Ready String
                                                             *  ---------------------------------------
                                                             */
                                                            esc_attr__( 'Real Wedding Location Taxonomy', 'sdweddingdirectory-shortcodes' )
                                                );
                    }

                    /**
                     *  Have Style ?
                     *  ------------
                     */
                    $collection     .=      parent:: _shortcode_style_end( $style );
                }
            }

            /**
             *  Return Data
             *  -----------
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
                 *  Real Wedding Location
                 *  ----------------
                 */
                return  do_shortcode(

                            sprintf( '[sdweddingdirectory_realwedding_location layout="%1$s" post_ids="%2$s" style="%3$s"][/sdweddingdirectory_realwedding_location]',

                                /**
                                 *  1. Layout
                                 *  ---------
                                 */
                                absint( $layout ),

                                /**
                                 *  2. Id
                                 *  -----
                                 */
                                esc_attr( $post_ids ),

                                /**
                                 *  3. Style
                                 *  --------
                                 */
                                $style
                            )
                        );
            }
        }

    } // end class

    /**
     *  --------------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Real Wedding Location ]
     *  --------------------------------------------------
     */
    SDWeddingDirectory_Shortcode_Real_Wedding_Location::get_instance();
}