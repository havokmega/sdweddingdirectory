<?php
/**
 *  ---------------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Venue Location ]
 *  ---------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Venue_Location' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  ---------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Venue Location ]
     *  ---------------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Venue_Location extends SDWeddingDirectory_Shortcode {

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

                            'layout'        =>      '1', 

                            'style'         =>      '1'
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
            return      apply_filters( 'sdweddingdirectory/venue-location/counter-widget', absint( '1' ) );
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

                $term_obj = get_term( absint( $term_id ), sanitize_key( 'venue-location' ) );

                $term_link = ! empty( $term_obj ) && ! is_wp_error( $term_obj )

                            ? add_query_arg( [ 'location' => sanitize_title( $term_obj->slug ) ], home_url( '/venues/' ) )

                            : get_term_link( absint( $term_id ) );

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
                                                esc_url( $term_link ),

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
             *  ShortCode - [sdweddingdirectory_venue_location]
             *  -----------------------------------------
             */
            add_shortcode( 'sdweddingdirectory_venue_location', [ $this, 'sdweddingdirectory_venue_location' ] );

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

                            'sdweddingdirectory_venue_location'  =>   sprintf( '[sdweddingdirectory_venue_location %1$s][/sdweddingdirectory_venue_location]', 

                                                                    parent:: _shortcode_atts( self:: default_args() )
                                                                )
                        ] );
            } );

            /**
             *  Image Size ?
             *  ------------
             */
            add_filter( 'sdweddingdirectory/image-size', function( $args = [] ){

                return  array_merge( $args, array(

                            [
                                'id'            =>      esc_attr( 'sdweddingdirectory_img_550x460' ),

                                'name'          =>      esc_attr__( 'Venue Location', 'sdweddingdirectory-shortcodes' ),

                                'width'         =>      absint( '550' ),

                                'height'        =>      absint( '460' )
                            ],

                        ) );
            } );

            /**
             *  SDWeddingDirectory - Placeholder Filter
             *  -------------------------------
             */
            add_filter( 'sdweddingdirectory/placeholder/shortcode', function( $args = [] ){

                return  array_merge(  $args, [

                            'venue-location'  =>  esc_url(  plugin_dir_url( __FILE__ ) . 'images/' . 'venue-location.jpg' ),

                        ] );
            } );
        }

        /**
         *  Venue Category Icon Load
         *  --------------------------
         */
        public static function sdweddingdirectory_venue_location( $atts, $conten = '' ){

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
                $obj    =       get_term_by(

                                    esc_attr( 'id' ), 

                                    absint( $term_id ),

                                    sanitize_key( 'venue-location' )
                                );

                /**
                 *  Make sure object not empty!
                 *  ---------------------------
                 */
                if( ! empty( $obj ) ){

                    $location_link = add_query_arg(

                                        [ 'location' => sanitize_title( $obj->slug ) ],

                                        home_url( '/venues/' )
                                    );

                    /**
                     *  Media Data
                     *  ----------
                     */
                    $_get_media_link    =       apply_filters( 'sdweddingdirectory/term/image', [

                                                    'term_id'       =>  absint( $term_id ),

                                                    'taxonomy'      =>  esc_attr( 'venue-location' ),

                                                    'image_size'    =>  esc_attr( 'sdweddingdirectory_img_550x460' ),

                                                    'default_img'   =>  parent:: placeholder( 'venue-location' )

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
                                                            esc_url( $location_link ),

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
                                                            esc_attr__( 'Venue Location Taxonomy', 'sdweddingdirectory-shortcodes' )
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

                                                                    <div class="mt-auto term-info">

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
                                                            esc_url( $location_link ),

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
                                                            esc_attr__( 'Venue Location Taxonomy', 'sdweddingdirectory-shortcodes' )
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
                 *  Venue Location
                 *  ----------------
                 */
                return  do_shortcode(

                            sprintf( '[sdweddingdirectory_venue_location layout="%1$s" post_ids="%2$s" style="%3$s"][/sdweddingdirectory_venue_location]',

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
     *  ---------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Venue Location ]
     *  ---------------------------------------------
     */
    SDWeddingDirectory_Shortcode_Venue_Location::get_instance();
}
