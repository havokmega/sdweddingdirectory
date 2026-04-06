<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Services' ) && class_exists( 'SDWeddingDirectory_Venue_Singular_Left_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Services extends SDWeddingDirectory_Venue_Singular_Left_Side_Widget {

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
            add_action( 'sdweddingdirectory/venue/singular/left-side/widget', [ $this, 'widget' ], absint( '12' ), absint( '1' ) );
        }

        /**
         *  Venue / Left Widget / Facility Available
         *  ------------------------------------------
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

                                        'category_tax'      =>      esc_attr( 'venue-type' ),

                                        'location_tax'      =>      esc_attr( 'venue-location' ),

                                        'post_id'           =>      absint( '0' ),

                                        'layout'            =>      absint( '1' ),

                                        'term_id'           =>      absint( '0' ),

                                        'icon'              =>      esc_attr( 'fa fa-list' ),

                                        'card_info'         =>      true,

                                        'handling'          =>      ''

                                    ] );

                /**
                 *  Extract the data
                 *  ----------------
                 */
                extract( $args );

                /**
                 *  Make sure post id not emtpy!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Have Term ID ?
                 *  --------------
                 */
                $term_id           =        apply_filters( 'sdweddingdirectory/post/term-parent', [

                                                'post_id'   =>  absint( $post_id ),

                                                'taxonomy'  =>  esc_attr( 'venue-type' )

                                            ] );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( ! empty( $post_id ) && ! empty( $term_id ) ){

                    /**
                     *  Highlight loop
                     *  --------------
                     */
                    foreach( apply_filters( 'sdweddingdirectory/dynamic-acf-group-box', [] ) as $group_key => $group_value ){

                        /**
                         *  Extract
                         *  -------
                         */
                        extract( $group_value );

                        /**
                         *  Enable Section
                         *  --------------
                         */
                        $enable_section   =     sdwd_get_term_field(

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
                        $enable_widget   =      sdwd_get_term_field(

                                                    /**
                                                     *  1. Term Key
                                                     *  -----------
                                                     */
                                                    'info_widget_' . $slug,

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
                        if( $enable_section && $enable_widget ){

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
                                     *  Heading
                                     *  -------
                                     */
                                    $heading            =   ! empty( $_title ) ? esc_attr( $_title ) : esc_attr( $name );

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

                                        foreach( $meta_value as $post_meta_key => $post_meta_value ){

                                            $term_key_name[ $post_meta_key ]    =   $terms_data[ $post_meta_key ];
                                        }
                                    }

                                    /**
                                     *  Make sure venue term meta exists
                                     *  ----------------------------------
                                     */
                                    if( parent:: _is_array( $term_key_name ) ){

                                        /**
                                         *  Layout 1
                                         *  --------
                                         */
                                        if( $layout == absint( '1' ) ){

                                            $handling   =       '';

                                            $handling   .=      '<div class="card-shadow-body pb-1">';

                                            $handling   .=      '<div class="row row-cols-1 row-cols-md-3 row-cols-sm-2">';

                                            if( parent:: _is_array( $term_key_name ) ){

                                                foreach ( $term_key_name as $_meta_key => $_term_name ){

                                                    $handling   .=
                                                    
                                                    sprintf(    '<div class="col">

                                                                    <p><i class="fa fa-check"></i> %1$s</p>

                                                                </div>',

                                                                /**
                                                                 *  1. List of Service Available
                                                                 *  ----------------------------
                                                                 */
                                                                esc_attr( $_term_name )
                                                    );
                                                }
                                            }

                                            $handling   .=      '</div>';

                                            $handling   .=      '</div>';


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
                                                            sanitize_key( $slug ),

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
                                                            $handling
                                                );
                                            }

                                            /**
                                             *  Direct Output
                                             *  -------------
                                             */
                                            else{

                                                print   $handling;
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
                                            printf('<a href="#section_%1$s" class="%2$s"><i class="%3$s"></i> %4$s</a>',

                                                    /**
                                                     *  Tab name
                                                     *  --------
                                                     */
                                                    esc_attr( $slug ),

                                                    /**
                                                     *  Default Active
                                                     *  --------------
                                                     */
                                                    '',

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



                                            $handling   =       '';

                                            $handling   .=      '<div class="card-shadow-body pb-1">';

                                            $handling   .=      '<div class="row row-cols-1 row-cols-md-3 row-cols-sm-2">';

                                            if( parent:: _is_array( $term_key_name ) ){

                                                foreach ( $term_key_name as $_meta_key => $_term_name ){

                                                    $handling   .=
                                                    
                                                    sprintf(    '<div class="col">

                                                                    <p><i class="fa fa-check"></i> %1$s</p>

                                                                </div>',

                                                                /**
                                                                 *  1. List of Service Available
                                                                 *  ----------------------------
                                                                 */
                                                                esc_attr( $_term_name )
                                                    );
                                                }
                                            }

                                            $handling   .=      '</div>';

                                            $handling   .=      '</div>';


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
                                                $handling
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Venue_Singular_Left_Side_Widget_Services:: get_instance();
}
