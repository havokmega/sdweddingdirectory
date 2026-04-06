<?php
/**
 *  SDWeddingDirectory Search Venue Helper
 *  --------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Search_Result_Filter_Widget_Pricing' ) && class_exists( 'SDWeddingDirectory_Search_Result_Helper' ) ){
    if( ! apply_filters( 'sdsdweddingdirectoryectory/enable_pricing_filter', true ) ){
        return;
    }

    /**
     *  SDWeddingDirectory Search Venue Helper
     *  --------------------------------
     */
    class SDWeddingDirectory_Search_Result_Filter_Widget_Pricing extends SDWeddingDirectory_Search_Result_Helper{

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
             *  1. Sub Categoy Widget
             *  ---------------------
             */
            add_action( 'sdweddingdirectory/find-venue/filter-widget', [ $this, 'widget' ], absint( '30' ), absint( '1' ) );

            /**
             *  2. Have Active Filters ?
             *  ------------------------
             */
            add_filter( 'sdweddingdirectory/find-venue/active-filters', [ $this, 'active_filter' ], absint( '30' ), absint( '1' ) );
        }

        /**
         *  1. Sub Categoy Widget
         *  ---------------------
         */
        public static function widget( $args = [] ){

        	/**
        	 *  Have Args ?
        	 *  -----------
        	 */
        	if( parent:: _is_array( $args ) ){

        		/**
        		 *  Extract
        		 *  -------
        		 */
        		extract( wp_parse_args( $args, [

                    'group_id'              =>    esc_attr( parent:: _rand() ),

                    'selected'              =>    parent:: _query_string( self:: widget_name() ),

                    'collapse'              =>    esc_attr( self:: widget_name() ),

                    'layout'                =>    absint( '1' ),


                    /**
                     *  View More - Helper
                     *  ------------------
                     */
                    'view_more_limit'       =>    absint( '8' ),

                    'view_more_counter'     =>    absint( '0' ),

                    'view_more'             =>    parent:: _filter_view_more(),

        		] ) );

	            /**
	             *  Make sure category id not empty!
	             *  --------------------------------
	             */
	            if( empty( $cat_id ) && ! parent:: is_venues_search_page() ){

	                return;
	            }

                /**
                 *  Have Label ?
                 *  ------------
                 */
                $_checkbox_data     =   self:: options();

                /**
                 *  Make sure it's enable to continues
                 *  ----------------------------------
                 */
                if( ! self:: is_enable() || ! parent:: _is_array( $_checkbox_data ) ){

                    return;
                }

	            /**
	             *  Term Name
	             *  ---------
	             */
	            $term				= 	esc_attr( 'venue-type' );

                /**
                 *  Have Label ?
                 *  ------------
                 */
                $_have_lable        =   esc_attr__( 'Pricing', 'sdweddingdirectory' );

                /**
                 *  Min and Max
                 *  -----------
                 */
                $min                =   parent:: _is_array( $_checkbox_data ) 

                                    ?   absint( min( array_column( $_checkbox_data, 'min' ) ) )

                                    :   '';

                $max                =   parent:: _is_array( $_checkbox_data ) 

                                    ?   absint( max( array_column( $_checkbox_data, 'max' ) ) )

                                    :   '';

                /**
                 *  Checked Collection
                 *  ------------------
                 */
                $_new_collection    =   [];

                /**
                 *  Have Selected Value ?
                 *  ---------------------
                 */
                if( parent:: _is_array( $selected ) ){

                    foreach( $selected as $key => $value ){

                        $i = explode( ',', str_replace( [ '[', ']' ], '', str_replace( [ '-' ], ',', $value ) ) );

                        foreach( $i as $_key => $_value ){

                            $_new_collection[]      =   $_value;
                        }
                    } 
                }

                /**
                 *  Handler
                 *  -------
                 */
                $_handaler          =   '';

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _is_array( $_checkbox_data ) ){

                    /**
                     *  Collection
                     *  ----------
                     */
                    foreach( $_checkbox_data as $key => $value ){

                        /**
                         *  Extract
                         *  -------
                         */
                        extract( $value );

                        /**
                         *  Collection
                         *  ----------
                         */
                        $_handaler  .=

                        sprintf(    '<div class="col %6$s">

                                        <div class="mb-3">

                                            <input autocomplete="off" type="checkbox" class="form-check-input" 

                                                value="[%1$s-%2$s]" id="%3$s_%2$s" data-min="%1$s" data-max="%2$s" %5$s />

                                            <label class="form-check-label" for="%3$s_%2$s">%4$s</label>

                                        </div>

                                    </div>',

                                    /**
                                     *  1. Get Name
                                     *  -----------
                                     */
                                    absint( $min ),

                                    /**
                                     *  2. Get ID ( KEY )
                                     *  -----------------
                                     */
                                    absint( $max ),

                                    /**
                                     *  3. Group ID
                                     *  -----------
                                     */
                                    esc_attr( $group_id ),

                                    /**
                                     *  4. Label
                                     *  --------
                                     */
                                    $label,

                                    /**
                                     *  5. Is Selected ?
                                     *  ----------------
                                     */
                                    parent:: _is_array( $selected ) && 

                                    in_array( 

                                        sprintf( '[%1$s-%2$s]', absint( $min ), absint( $max ) ),

                                        $selected
                                    )

                                    ?   esc_attr( 'checked' )

                                    :   '',

                                    /**
                                     *  6. Make sure it's layout 2 + limit set to hide data
                                     *  ---------------------------------------------------
                                     */
                                    absint( $view_more_counter ) >= absint( $view_more_limit ) && $layout == absint( '2' )

                                    ?   sanitize_html_class( 'd-none' )

                                    :   ''
                        );

                        /**
                         *  Add View More Button
                         *  --------------------
                         */
                        if( absint( $view_more_counter ) >= absint( $view_more_limit ) && $layout == absint( '2' ) && end( $_checkbox_data ) == $value ){

                            /**
                             *  Show more
                             *  ---------
                             */
                            $_handaler  .=      $view_more;
                        }

                        /**
                         *  Counter+
                         *  --------
                         */
                        $view_more_counter++;
                    }
                }

                /**
                 *  Layout One
                 *  ----------
                 */
                if( $layout == absint( '1' ) ){

                    /**
                     *  Load Checkbox
                     *  -------------
                     */
                    printf( '<div class="col-12">

                                <div class="find-venue-widget mt-2 collapse" id="%2$s-filter-section">

                                    <div class="checkbox-section venue-filter-section">

                                        <div class="head">

                                            <strong>%1$s</strong>

                                            <a class="toggle" href="#%2$s" role="button" aria-expanded="true" aria-controls="%2$s"

                                                data-bs-toggle="collapse" data-bs-target="#%2$s">

                                                <i class="fa fa-angle-down"></i>

                                            </a>

                                        </div>

                                        <div class="collapse show" id="%2$s">

                                            <div class="checkbox-data venue-filter-data" data-handler="%2$s" data-type="range">

                                                <div class="row row-cols-xl-4 row-cols-lg-3 row-cols-sm-2 row-cols-1">%3$s</div>

                                                <input id="%2$s-translation" name="%2$s-translation" value="%7$s" type="hidden"  />

                                                <input id="%2$s-input-min" name="%2$s-input-min" value="%5$s" autocomplete="off" type="hidden" />

                                                <input id="%2$s-input-max" name="%2$s-input-max" value="%6$s" autocomplete="off" type="hidden" />

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>',

                            /**
                             *  1. Translation Ready String
                             *  ---------------------------
                             */
                            esc_attr( $_have_lable ),

                            /**
                             *  2. Have Before ?
                             *  ----------------
                             */
                            esc_attr( $collapse ),

                            /**
                             *  3. Checkbox Data
                             *  ----------------
                             */
                            $_handaler,

                            /**
                             *  4. Translation Ready String
                             *  ---------------------------
                             */
                            esc_attr__( 'Pricing', 'sdweddingdirectory' ),

                            /**
                             *  5. Have Active Filter to Check Min Seating Capacity ?
                             *  -----------------------------------------------------
                             */
                            parent:: _is_array( $_new_collection )

                            ?    min( $_new_collection )

                            :   '',

                            /**
                             *  6. Have Active Filter to Check Max Seating Capacity ?
                             *  -----------------------------------------------------
                             */
                            parent:: _is_array( $_new_collection )

                            ?   max( $_new_collection )

                            :   '',

                            /**
                             *  7. Translation Ready String
                             *  ---------------------------
                             */
                            ''
                    );
                }

                /**
                 *  Layout Two
                 *  ----------
                 */
                if( $layout == absint( '2' ) ){

                    /**
                     *  Load Checkbox
                     *  -------------
                     */
                    printf( '<div class="col-12">

                                <div class="filter-widget-layout-two" id="%2$s-filter-section">

                                    <div class="checkbox-section venue-filter-section">

                                        <div class="head">

                                            <strong>%1$s</strong>

                                            <a class="toggle" href="#%2$s" role="button" aria-expanded="true" aria-controls="%2$s"

                                                data-bs-toggle="collapse" data-bs-target="#%2$s">

                                                <i class="fa fa-angle-down"></i>

                                            </a>

                                        </div>

                                        <div class="collapse show" id="%2$s">

                                            <div class="checkbox-data venue-filter-data" data-handler="%2$s" data-type="range">

                                                <div class="row row-cols-1">%3$s</div>

                                                <input id="%2$s-translation" name="%2$s-translation" value="%7$s" type="hidden"  />

                                                <input id="%2$s-input-min" name="%2$s-input-min" value="%5$s" autocomplete="off" type="hidden" />

                                                <input id="%2$s-input-max" name="%2$s-input-max" value="%6$s" autocomplete="off" type="hidden" />

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>',

                            /**
                             *  1. Translation Ready String
                             *  ---------------------------
                             */
                            esc_attr( $_have_lable ),

                            /**
                             *  2. Have Before ?
                             *  ----------------
                             */
                            esc_attr( $collapse ),

                            /**
                             *  3. Checkbox Data
                             *  ----------------
                             */
                            $_handaler,

                            /**
                             *  4. Translation Ready String
                             *  ---------------------------
                             */
                            esc_attr__( 'Pricing', 'sdweddingdirectory' ),

                            /**
                             *  5. Have Active Filter to Check Min Seating Capacity ?
                             *  -----------------------------------------------------
                             */
                            parent:: _is_array( $_new_collection )

                            ?    min( $_new_collection )

                            :   '',

                            /**
                             *  6. Have Active Filter to Check Max Seating Capacity ?
                             *  -----------------------------------------------------
                             */
                            parent:: _is_array( $_new_collection )

                            ?   max( $_new_collection )

                            :   '',

                            /**
                             *  7. Translation Ready String
                             *  ---------------------------
                             */
                            ''
                    );
                }
        	}
        }

        /**
         *  2. Have Active Filters ?
         *  ------------------------
         */
        public static function active_filter( $args = [] ){

            /**
             *  Make sure it's enable to continues
             *  ----------------------------------
             */
            if( ! self:: is_enable() ){

                return;
            }

            /**
             *  Handler
             *  -------
             */
            $_handaler          =   [];

            /**
             *  Create Array of collection
             *  --------------------------
             */
            $_data              =   parent:: _query_string( self:: widget_name() );

            /**
             *  Have Label ?
             *  ------------
             */
            $_checkbox_data     =   self:: options();

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $_data ) ){

                foreach( $_data as $key => $value ){

                    /**
                     *  Handler
                     *  -------
                     */
                    $_handaler[]    =

                    sprintf( '<a    href="javascript:" id="%7$s" class="%6$s"

                                    data-handler="%1$s" 

                                    data-value="%2$s" 

                                    data-type="range"

                                    >%3$s %4$s %5$s</a>',

                        /**
                         *  1. Query String
                         *  ---------------
                         */
                        esc_attr( self:: widget_name() ),

                        /**
                         *  2. Location ID
                         *  --------------
                         */
                        esc_attr( $value ),

                        /**
                         *  3. Capacity Range
                         *  -----------------
                         */
                        esc_attr( parent:: _range_label( $_checkbox_data, $value ) ),

                        /**
                         *  4. Translation Ready String
                         *  ---------------------------
                         */
                        '',

                        /**
                         *  5. Remove Icon
                         *  --------------
                         */
                        parent:: _remove_filter_icon(),

                        /**
                         *  6. Remove Class
                         *  ---------------
                         */
                        parent:: _remove_filter_class(),

                        /**
                         *  7. ID
                         *  -----
                         */
                        parent:: _rand()
                    );
                }
            }

            /**
             *  Merge Data
             *  ----------
             */
            if( parent:: _is_array( $_handaler ) ){

                return      array_merge( $args, $_handaler );
            }

            else{

                return      $args;
            }
        }

        /**
         *  Widget Name
         *  -----------
         */
        public static function widget_name(){

            return      esc_attr( 'price-filter' );
        }

        /**
         *  Category Enable this features ?
         *  -------------------------------
         */
        public static function is_enable(){

            $cat_id = absint( parent:: _cat_id() );

            if( ! empty( $cat_id ) ){

                return sdwd_get_term_field(

                            sanitize_key( 'pricing_available' ),

                            $cat_id
                        );
            }

            if( ! parent:: is_venues_search_page() ){

                return false;
            }

            return true;
        }

        /**
         *  Category Options ?
         *  ------------------
         */
        public static function options(){

            $options = [];

            $cat_id = absint( parent:: _cat_id() );

            if( ! empty( $cat_id ) ){

                $_have_data = sdwd_get_term_repeater(

                                sanitize_key( 'pricing_options' ),

                                $cat_id,

                                [ 'label', 'min', 'max' ]
                            );

                return parent:: _is_array( $_have_data ) ? $_have_data : [];
            }

            if( ! parent:: is_venues_search_page() ){

                return [];
            }

            $terms = get_terms( [

                'taxonomy'      =>  esc_attr( 'venue-type' ),

                'hide_empty'    =>  false,

                'fields'        =>  esc_attr( 'ids' )
            ] );

            if( parent:: _is_array( $terms ) ){

                foreach( $terms as $term_id ){

                    $_have_data = sdwd_get_term_repeater(

                                    sanitize_key( 'pricing_options' ),

                                    $term_id,

                                    [ 'label', 'min', 'max' ]
                                );

                    if( parent:: _is_array( $_have_data ) ){

                        foreach( $_have_data as $row ){

                            if( ! isset( $row['min'] ) || ! isset( $row['max'] ) ){

                                continue;
                            }

                            $key = sprintf( '%1$s-%2$s', absint( $row['min'] ), absint( $row['max'] ) );

                            $options[ $key ] = [

                                'min'   => absint( $row['min'] ),

                                'max'   => absint( $row['max'] ),

                                'label' => isset( $row['label'] ) ? $row['label'] : sprintf( '%1$s - %2$s', absint( $row['min'] ), absint( $row['max'] ) ),
                            ];
                        }
                    }
                }
            }

            if( parent:: _is_array( $options ) ){

                return array_values( $options );
            }

            $fallback = [

                [
                    'min'   => absint( '1' ),
                    'max'   => absint( '2500' ),
                    'label' => sanitize_text_field( '$1 - $2,500' ),
                ],
                [
                    'min'   => absint( '2501' ),
                    'max'   => absint( '5000' ),
                    'label' => sanitize_text_field( '$2,501 - $5,000' ),
                ],
                [
                    'min'   => absint( '5001' ),
                    'max'   => absint( '10000' ),
                    'label' => sanitize_text_field( '$5,001 - $10,000' ),
                ],
                [
                    'min'   => absint( '10001' ),
                    'max'   => absint( '1000000' ),
                    'label' => sanitize_text_field( '$10,001+' ),
                ],
            ];

            return $fallback;

        }
    }

    /**
     *  Search Result Helper
     *  --------------------
     */
    SDWeddingDirectory_Search_Result_Filter_Widget_Pricing::get_instance();
}
