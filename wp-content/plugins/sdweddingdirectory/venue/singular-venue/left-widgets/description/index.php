<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Description' ) && class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Description extends SDWeddingDirectory_Venue_Singular_Left_Side_Widget {

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
             *  1. Venue Section Left Widget [ Description ]
             *  ----------------------------------------------
             */
            add_action( 'sdweddingdirectory/venue/singular/left-side/widget', [ $this, 'widget' ], absint( '10' ), absint( '1' ) );

            /**
             *  Highlight - Sub Category
             *  ------------------------
             */
            add_filter( 'sdweddingdirectory/venue/highlight', [ $this, 'highlight_sub_category' ], absint( '10' ), absint( '2' ) );

            /**
             *  Highlight - Sub Category
             *  ------------------------
             */
            add_filter( 'sdweddingdirectory/venue/highlight', [ $this, 'highlight_term_group_box' ], absint( '15' ), absint( '2' ) );

            /**
             *  Highlight - Price
             *  -----------------
             */
            add_filter( 'sdweddingdirectory/venue/highlight', [ $this, 'highlight_price' ], absint( '40' ), absint( '2' ) );

            /**
             *  Highlight - Venue Rental Fees
             *  -----------------------------
             */
            add_filter( 'sdweddingdirectory/venue/highlight', [ $this, 'highlight_venue_rental_fees' ], absint( '50' ), absint( '2' ) );

            /**
             *  Seating Capacity ?
             *  ------------------
             */
            add_filter( 'sdweddingdirectory/venue/highlight', [ $this, 'highlight_seating_capacity' ], absint( '60' ), absint( '2' ) );
        }

        /**
         *  1. Venue Section Left Widget [ Description ]
         *  ----------------------------------------------
         */
        public static function widget( $args = [] ){

            /**
             *  Is array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Merge Args
                 *  ----------
                 */
                $args       =       wp_parse_args( $args, [

                                        'post_id'               =>      absint( '0' ),

                                        'active_tab'            =>      true,

                                        'id'                    =>      sanitize_title( 'vendor_about' ),

                                        'icon'                  =>      'fa fa-file-text',

                                        'heading'               =>      esc_attr__( 'About this venue', 'sdweddingdirectory-venue' ),

                                        'card_info'             =>      true,

                                        'handler'               =>      ''

                                    ] );

                /**
                 *  Extract the data
                 *  ----------------
                 */
                extract( $args );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Get Content ?
                 *  -------------
                 */
                $post               =       get_post( absint( $post_id ) );

                $the_content        =       apply_filters('the_content', $post->post_content );

                if( empty( $the_content ) ){

                    $the_content = sprintf(
                        esc_attr__( 'Welcome to %1$s', 'sdweddingdirectory-venue' ),
                        esc_attr( get_the_title( absint( $post_id ) ) )
                    );
                }

                /**
                 *  Make sure have data ?
                 *  ---------------------
                 */
                if( ! empty( $the_content ) ){

                    /**
                     *  Layout 1
                     *  --------
                     */
                    if( $layout == absint( '1' ) ){

                        /**
                         *  Card Body
                         *  ---------
                         */
                        $handler    .=

                        sprintf( '<div class="card-shadow-body clearfix">%1$s <div class="sd-vendor-about-readmore-wrap">%2$s</div></div>',

                            /**
                             *  1. Have Hightlight ?
                             *  --------------------
                             */
                            self:: venue_highlight( $args ),

                            /**
                             *  2. Content
                             *  ----------
                             */
                            $the_content
                        );

                        /**
                         *  Card Info ?
                         *  -----------
                         */
                        if( $card_info ){

                            printf(     '<div class="card-shadow position-relative">
                                                
                                            <a id="section_%1$s" class="anchor-fake"></a>

                                            <div class="card-shadow-header">

                                                <h3><i class="%2$s"></i> %3$s</h3>

                                            </div>

                                            %4$s

                                        </div>',

                                        /**
                                         *  1. Tab name
                                         *  -----------
                                         */
                                        sanitize_key( $id ),

                                        /**
                                         *  2. Tab Icon
                                         *  -----------
                                         */
                                        $icon,

                                        /**
                                         *  3. Heading 
                                         *  ----------
                                         */
                                        esc_attr( $heading ),

                                        /**
                                         *  4. Output
                                         *  ---------
                                         */
                                        $handler
                                );
                        }

                        /**
                         *  Direct Output
                         *  -------------
                         */
                        else{

                            print       $handler;
                        }
                    }

                    /**
                     *  Layout 2
                     *  --------
                     */
                    if( $layout == absint( '2' ) ){

                        /**
                         *  Tab Overview
                         *  ------------
                         */
                        printf( '<a href="#section_%1$s" class="%2$s"><i class="%3$s"></i> %4$s</a>',

                                /**
                                 *  Tab name
                                 *  --------
                                 */
                                esc_attr( $id ),

                                /**
                                 *  Default Active
                                 *  --------------
                                 */
                                $active_tab   ?   sanitize_html_class( 'active' )  :  '',

                                /**
                                 *  Tab Icon
                                 *  --------
                                 */
                                $icon,

                                /**
                                 *  Tab Title
                                 *  ---------
                                 */
                                $heading
                        );
                    }

                    /**
                     *  Layout 3 - Tabing Style
                     *  -----------------------
                     */
                    if( $layout == absint( '3' ) ){

                        ob_start();

                        /**
                         *  List of slider tab icon
                         *  -----------------------
                         */
                        call_user_func( [ __CLASS__, __FUNCTION__ ], [

                            'post_id'       =>      absint( $post_id ),

                            'layout'        =>      absint( '1' ),

                            'card_info'     =>      false

                        ] );

                        $data   =   ob_get_contents();

                        ob_end_clean();

                        /**
                         *  Tab layout
                         *  ----------
                         */
                        printf( '[sdweddingdirectory_tab icon="%1$s" title="%2$s"]%3$s[/sdweddingdirectory_tab]', 

                            /**
                             *  Tab Icon
                             *  --------
                             */
                            $icon,

                            /**
                             *  Tab Title
                             *  ---------
                             */
                            $heading,

                            /**
                             *  Card Body
                             *  ---------
                             */
                            $data
                        );
                    }
                }
            }
        }

        /** 
         *  Have Hightlight
         *  ---------------
         */
        public static function venue_highlight( $args = [] ){

            /**
             *  Is array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract the data
                 *  ----------------
                 */
                extract( wp_parse_args( $args, [

                    'category_tax'      =>      esc_attr( 'venue-type' ),

                    'location_tax'      =>      esc_attr( 'venue-location' ),

                    'layout'            =>      absint( '1' ),

                    'post_id'           =>      absint( '0' ),

                    'collection'        =>      ''

                ] ) );

                /**
                 *  Highlight Content
                 *  -----------------
                 */
                $_highlight_data    =   apply_filters( 'sdweddingdirectory/venue/highlight',

                                            /**
                                             *  Collection Start
                                             *  ----------------
                                             */
                                            [],

                                            /**
                                             *  Arguments
                                             *  ---------
                                             */
                                            [
                                                'category_tax'      =>      $category_tax,

                                                'location_tax'      =>      $location_tax,

                                                'layout'            =>      absint( '1' ),

                                                'post_id'           =>      absint( $post_id ),

                                                'term_id'           =>      apply_filters( 'sdweddingdirectory/post/term-parent', [

                                                                                'post_id'   =>  absint( $post_id ),

                                                                                'taxonomy'  =>  esc_attr( 'venue-type' )

                                                                            ] )
                                            ]
                                        );
                /**
                 *  Have Highlight
                 *  --------------
                 */
                if( parent:: _is_array( $_highlight_data ) ){

                    /**
                     *  Heading
                     *  -------
                     */
                    $collection    .=   sprintf( '<div class="hightlight-section-singular"><h4 class="text-orange">%1$s</h4>',

                                                /**
                                                 *  Have Category Child ?
                                                 *  ---------------------
                                                 */
                                                esc_attr__( 'Highlights', 'sdweddingdirectory-venue' )
                                        );

                    /**
                     *  Loop
                     *  ----
                     */
                    foreach( $_highlight_data as $key => $value ){

                        /**
                         *  Extract Args
                         *  ------------
                         */
                        extract( $value );

                        /**
                         *  Data
                         *  ----
                         */
                        $collection    .=

                        sprintf(    '<div class="highlight-venue">

                                        <small>%1$s</small>

                                        <p class="show-read-more" data-word="60" data-read-more-string="%3$s">%2$s</p>

                                    </div>',

                                    /**
                                     *  1. Title
                                     *  --------
                                     */
                                    esc_attr( $title ),

                                    /**
                                     *  2. Data
                                     *  -------
                                     */
                                    $data,

                                    /**
                                     *  3. Translation Ready String
                                     *  ---------------------------
                                     */
                                    esc_attr__( 'View More', 'sdweddingdirectory-venue' )
                        );
                    }

                    $collection    .=      '</div>';
                }

                /**
                 *  Return Highlight data
                 *  ---------------------
                 */
                return          $collection;
            }
        }

        /**
         *  Highlight - Sub Category
         *  ------------------------
         */
        public static function highlight_sub_category( $collection = [], $args = [] ){

            /**
             *  Handler
             *  -------
             */
            $handler        =       [];

            /**
             *  Is array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract the data
                 *  ----------------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'           =>      absint( '0' ),

                    'layout'            =>      absint( '1' ),

                    'term_id'           =>      absint( '0' ),

                    'category_tax'      =>      esc_attr( 'venue-type' ),

                    'location_tax'      =>      esc_attr( 'venue-location' ),

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( ! empty( $post_id ) && ! empty( $term_id ) ){

                    /**
                     *  Have Child Terms ?
                     *  ------------------
                     */
                    $_have_data     =   wp_list_pluck(

                                            /**
                                             *  List of Collection
                                             *  ------------------
                                             */
                                            wp_get_post_terms( absint( $post_id ), $category_tax, [

                                                'child_of'  =>  absint( $term_id ),
                                                
                                                'parent'    =>  absint( $term_id )

                                            ] ), 

                                            /** 
                                             *  Target
                                             *  ------
                                             */
                                            esc_attr( 'name' )
                                        );

                    /**
                     *  Have Child Category ?
                     *  ---------------------
                     */
                    if( parent:: _is_array( $_have_data ) ){

                        /**
                         *  Have Sub Category ?
                         *  -------------------
                         */
                        $_title         =   sdwd_get_term_field(

                                                /**
                                                 *  1. Term Key
                                                 *  -----------
                                                 */
                                                'venue_category_sub_category_label',

                                                /**
                                                 *  2. Term ID
                                                 *  ----------
                                                 */
                                                $term_id
                                            );
                        /**
                         *  Handler
                         *  -------
                         */
                        $handler[ 'sub_category' ]      =   array(

                                                                'title'     =>      parent:: _have_data( $_title )

                                                                                    ?   esc_attr( $_title )

                                                                                    :   esc_attr__( 'Sub Categories', 'sdweddingdirectory-venue' ),

                                                                'data'      =>      join( ', ', $_have_data )
                                                            );
                    }
                }
            }

            /**
             *  Return Collection
             *  -----------------
             */
            return      array_merge( $collection, $handler );
        }

        /**
         *  Highlight - Term Group Box
         *  --------------------------
         */
        public static function highlight_term_group_box( $collection = [], $args = [] ){

            /**
             *  Handler
             *  -------
             */
            $handler        =       [];

            /**
             *  Is array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract the data
                 *  ----------------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'           =>      absint( '0' ),

                    'layout'            =>      absint( '1' ),

                    'term_id'           =>      absint( '0' ),

                    'category_tax'      =>      esc_attr( 'venue-type' ),

                    'location_tax'      =>      esc_attr( 'venue-location' ),

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( ! empty( $post_id ) && ! empty( $term_id ) ){

                    /**
                     *  Highlight loop
                     *  --------------
                     */
                    foreach( apply_filters( 'sdweddingdirectory/dynamic-acf-group-box', [] ) as $key => $value ){

                        /**
                         *  Extract
                         *  -------
                         */
                        extract( $value );

                        /**
                         *  Enable Section
                         *  --------------
                         */
                        $enable_section   =   sdwd_get_term_field(

                                                    /**
                                                     *  1. Term Key
                                                     *  -----------
                                                     */
                                                    'enable_' . $slug,

                                                    /**
                                                     *  2. Term ID
                                                     *  ----------
                                                     */
                                                    $term_id
                                                );

                        /**
                         *  Database Values
                         *  ---------------
                         */
                        $enable_highlight   =   sdwd_get_term_field(

                                                    /**
                                                     *  1. Term Key
                                                     *  -----------
                                                     */
                                                    'highlight_' . $slug,

                                                    /**
                                                     *  2. Term ID
                                                     *  ----------
                                                     */
                                                    $term_id
                                                );

                        /**
                         *  Make sure highlight enable
                         *  --------------------------
                         */
                        if( $enable_section && $enable_highlight ){

                            /**
                             *  Database Values
                             *  ---------------
                             */
                            $get_values     =   sdwd_get_term_repeater(

                                                    /**
                                                     *  1. Term Key
                                                     *  -----------
                                                     */
                                                    sanitize_key( $slug ),

                                                    /**
                                                     *  2. Term ID
                                                     *  ----------
                                                     */
                                                    $term_id
                                                );

                            /**
                             *  Make sure backend have data ?
                             *  -----------------------------
                             */
                            if( parent:: _is_array( $get_values ) ){

                                /**
                                 *  Meta Value
                                 *  ----------
                                 */
                                $meta_value         =   get_post_meta( $post_id, sanitize_key( $slug ), true );

                                /**
                                 *  Only Key and Value Filter
                                 *  -------------------------
                                 */
                                $terms_data         =   array_column( $get_values , $slug );

                                /**
                                 *  Have Category Service ?
                                 *  -----------------------
                                 */
                                if( parent:: _is_array( $meta_value ) && parent:: _is_array( $terms_data ) ){

                                    /**
                                     *  Have Sub Category ?
                                     *  -------------------
                                     */
                                    $_title             =   sdwd_get_term_field(

                                                                /**
                                                                 *  1. Term Key
                                                                 *  -----------
                                                                 */
                                                                'label_' . $slug,

                                                                /**
                                                                 *  2. Term ID
                                                                 *  ----------
                                                                 */
                                                                $term_id
                                                            );

                                    /**
                                     *  Collection of Term Key Name
                                     *  ---------------------------
                                     */
                                    $term_key_name          =   [];
                                    
                                    /**
                                     *  Make sure meta value exists 
                                     *  ---------------------------
                                     */
                                    if( parent:: _is_array( $meta_value ) ){

                                        foreach( $meta_value as $key => $value ){

                                            $term_key_name[]    =   $terms_data[ $key ];
                                        }
                                    }

                                    /**
                                     *  Get Amenities
                                     *  -------------
                                     */
                                    $handler[ $slug ]   =   array(

                                                                'title'     =>  !   empty( $_title )    

                                                                                ?   esc_attr( $_title )  

                                                                                :   esc_attr( $name ),

                                                                'data'      =>  join( ', ', $term_key_name )
                                                            );
                                }
                            }
                        }
                    }
                }
            }

            /**
             *  Return Collection
             *  -----------------
             */
            return      array_merge( $collection, $handler );
        }

        /**
         *  Highlight - Price
         *  -----------------
         */
        public static function highlight_price( $collection = [], $args = [] ){

            /**
             *  Handler
             *  -------
             */
            $handler        =       [];

            /**
             *  Is array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract the data
                 *  ----------------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'           =>      absint( '0' ),

                    'layout'            =>      absint( '1' ),

                    'term_id'           =>      absint( '0' ),

                    'category_tax'      =>      esc_attr( 'venue-type' ),

                    'location_tax'      =>      esc_attr( 'venue-location' ),

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( ! empty( $post_id ) && ! empty( $term_id ) ){

                    /**
                     *  Meta Value
                     *  ----------
                     */
                    $meta_value         =   get_post_meta( $post_id, sanitize_key( 'venue_min_price' ), true );

                    /**
                     *  Have Category Service ?
                     *  -----------------------
                     */
                    if( ! empty( $meta_value ) ){

                        /**
                         *  Get Amenities
                         *  -------------
                         */
                        $handler[ 'price' ]     =   array(

                                                        'title'     =>  esc_attr__( 'Package Starting Price', 'sdweddingdirectory-venue' ),

                                                        'data'      =>  esc_attr( sdweddingdirectory_pricing_possition( $meta_value ) )
                                                    );
                    }
                }
            }

            /**
             *  Return Collection
             *  -----------------
             */
            return      array_merge( $collection, $handler );
        }

        /**
         *  Highlight - Venue Rental Fees
         *  -----------------------------
         */
        public static function highlight_venue_rental_fees( $collection = [], $args = [] ){

            /**
             *  Handler
             *  -------
             */
            $handler        =       [];

            /**
             *  Is array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract the data
                 *  ----------------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'           =>      absint( '0' ),

                    'layout'            =>      absint( '1' ),

                    'term_id'           =>      absint( '0' ),

                    'category_tax'      =>      esc_attr( 'venue-type' ),

                    'location_tax'      =>      esc_attr( 'venue-location' ),

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( ! empty( $post_id ) && ! empty( $term_id ) ){

                    /**
                     *  Meta Value
                     *  ----------
                     */
                    $meta_value         =   get_post_meta( $post_id, sanitize_key( 'venue_facilities' ), true );

                    /**
                     *  Have Category Service ?
                     *  -----------------------
                     */
                    if( parent:: _is_array( $meta_value ) ){

                        /**
                         *  Get Amenities
                         *  -------------
                         */
                        $handler[ 'venue_rental_fees' ]     =   [

                            'title'     =>  esc_attr__( 'Venue Rental Fees', 'sdweddingdirectory-venue' ),

                            'data'      =>  sprintf( esc_attr__( 'Starting Price : %1$s', 'sdweddingdirectory-venue' ),

                                                /**
                                                 *  1. Minimum Price
                                                 *  ----------------
                                                 */
                                                esc_attr( sdweddingdirectory_pricing_possition( 

                                                    min( array_column( $meta_value, 'facilities_price' ) )

                                                ) )    
                                            )
                        ];
                    }
                }
            }

            /**
             *  Return Collection
             *  -----------------
             */
            return      array_merge( $collection, $handler );
        }

        /**
         *  Highlight - Seating Capacity
         *  ----------------------------
         */
        public static function highlight_seating_capacity( $collection = [], $args = [] ){

            /**
             *  Handler
             *  -------
             */
            $handler        =       [];

            /**
             *  Is array ?
             *  ----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract the data
                 *  ----------------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'           =>      absint( '0' ),

                    'layout'            =>      absint( '1' ),

                    'term_id'           =>      absint( '0' ),

                    'category_tax'      =>      esc_attr( 'venue-type' ),

                    'location_tax'      =>      esc_attr( 'venue-location' ),

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( ! empty( $post_id ) && ! empty( $term_id ) ){

                    /**
                     *  Have Seating Capacity ?
                     *  -----------------------
                     */
                    $enable_capacity    =   apply_filters( 'sdweddingdirectory/capacity-enable', [

                                                'term_id'       =>      absint( $term_id )

                                            ] );


                    /**
                     *  Make sure this venue enable seating capacity ?
                     *  ------------------------------------------------
                     */
                    if( $enable_capacity ){

                        /**
                         *  Meta Value
                         *  ----------
                         */
                        $meta_value         =   get_post_meta( $post_id, sanitize_key( 'venue_seat_capacity' ), true );

                        /**
                         *  Have Capacity ?
                         *  ---------------
                         */
                        if( ! empty( $meta_value ) ){

                            /**
                             *  Seating Capacity
                             *  ----------------
                             */
                            $handler[ 'seating_capacity' ]     =   [

                                'title'     =>  esc_attr__( 'Seating Capacity', 'sdweddingdirectory-venue' ),

                                'data'      =>  sprintf( esc_attr__( 'Up to %1$s guests', 'sdweddingdirectory-venue' ),

                                                    /**
                                                     *  1. Seating Capacity Number
                                                     *  --------------------------
                                                     */
                                                    esc_attr( $meta_value )
                                                )
                            ];
                        }
                    }
                }
            }

            /**
             *  Return Collection
             *  -----------------
             */
            return      array_merge( $collection, $handler );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Description:: get_instance();
}
