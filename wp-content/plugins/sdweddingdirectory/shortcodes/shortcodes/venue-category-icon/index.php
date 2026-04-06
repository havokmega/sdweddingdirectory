<?php
/**
 *  --------------------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Venue Category Icon ]
 *  --------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Venue_Category_Icon' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  --------------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Venue Category Icon ]
     *  --------------------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Venue_Category_Icon extends SDWeddingDirectory_Shortcode {

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

            return  self::$instance;
        }

        /**
         *  ShortCode Atts
         *  --------------
         */
        public static function default_args(){

            return      [
                            'layout'    =>      '1', 

                            'class'     =>      '', 

                            'ids'       =>      '',

                            'id'        =>      ''
                        ];
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Load Venue Category Icon
             *  --------------------------
             */
            add_shortcode( 'sdweddingdirectory_venue_category_icon', [ $this, 'sdweddingdirectory_venue_category_icon' ] );

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

                            'sdweddingdirectory_venue_category_icon'  =>  sprintf( '[sdweddingdirectory_venue_category_icon %1$s][/sdweddingdirectory_venue_category_icon]', 

                                                                        parent:: _shortcode_atts( self:: default_args() )
                                                                    )
                        ] );
            } );
        }

        /**
         *  Venue Category Icon Load
         *  --------------------------
         */
        public static function sdweddingdirectory_venue_category_icon( $atts, $content = '' ){

            /**
             *  Extract Attr Data
             *  -----------------
             */
            extract( shortcode_atts( self:: default_args(), $atts, esc_attr( __FUNCTION__ ) ) );

            /**
             *  Handler
             *  -------
             */
            $_get_terms     =   '';

            /**
             *  Have IDS ?
             *  ----------
             */
            if( parent:: _have_data( $ids ) && parent:: _is_array( preg_split ("/\,/", $ids ) ) ) {

                /**
                 *  Venue Category IDs One by one load
                 *  ------------------------------------
                 */
                foreach( preg_split ("/\,/", $ids ) as $venue_category_id ){

                    /**
                     *  @credit - https://developer.wordpress.org/reference/functions/get_term_by/#user-contributed-notes
                     *  -------------------------------------------------------------------------------------------------
                     */
                    $_get_terms_data    =   get_term_by(

                                                esc_attr( 'id' ),

                                                absint( $venue_category_id ),

                                                sanitize_key( 'venue-type' )
                                            );

                    /**
                     *  Fallback: check vendor-category taxonomy
                     *  ----------------------------------------
                     */
                    if( ! parent:: _is_object( $_get_terms_data ) || ! isset( $_get_terms_data->term_id ) ){

                        $_get_terms_data    =   get_term_by(

                                                    esc_attr( 'id' ),

                                                    absint( $venue_category_id ),

                                                    sanitize_key( 'vendor-category' )
                                                );
                    }

                    /**
                     *  Have Object ID
                     *  --------------
                     */
                    if( parent:: _is_object( $_get_terms_data ) && isset( $_get_terms_data->term_id ) ){

                        /**
                         *  Get layout one
                         *  --------------
                         */
                        $_get_terms     .=

                        sprintf( '<a href="%1$s"><i class="%2$s"></i> %3$s %4$s</a>',

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

                                        absint( $_get_terms_data->term_id )
                                    )
                                ),

                                /**
                                 *  2. Term Icon
                                 *  ------------
                                 */
                                apply_filters( 'sdweddingdirectory/term/icon', [  'term_id'   =>   absint( $_get_terms_data->term_id ), 'taxonomy' => $_get_terms_data->taxonomy  ] ),

                                /**
                                 *  3. Term Name
                                 *  ------------
                                 */
                                $layout == absint( '1' )

                                ?   esc_attr( $_get_terms_data->name )

                                :   '',

                                /**
                                 *  4. Have Counter ?
                                 *  -----------------
                                 */
                                $layout == absint( '3' )

                                ?   sprintf( '<span class="badge badge-pill bg-light ms-2">%1$s</span>',

                                        absint( $_get_terms_data->count )
                                    )

                                :   ''
                        );
                    }
                }
            }

            /**
             *  Return Category Icon
             *  --------------------
             */
            return      $_get_terms;
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

                            sprintf( '[sdweddingdirectory_venue_category_icon layout="%1$s" ids="%2$s" class="%3$s" id="%4$s"][/sdweddingdirectory_venue_category_icon]',

                                /**
                                 *  1. Layout
                                 *  ---------
                                 */
                                absint( $layout ),

                                /**
                                 *  2. Id
                                 *  -----
                                 */
                                esc_attr( $ids ),

                                /**
                                 *  4. Have Class ?
                                 *  ---------------
                                 */
                                esc_attr( $class ),

                                /**
                                 *  5. Have ID ?
                                 *  ------------
                                 */
                                esc_attr( $id )
                            )
                        );
            }
        }

    } // end class

    /**
     *  --------------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Venue Category Icon ]
     *  --------------------------------------------------
     */
    SDWeddingDirectory_Shortcode_Venue_Category_Icon::get_instance();
}