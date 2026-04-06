<?php
/**
 *  SDWeddingDirectory Search Venue Helper
 *  --------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Search_Result_Filter_Widget_Sub_Category' ) && class_exists( 'SDWeddingDirectory_Search_Result_Helper' ) ){

    /**
     *  SDWeddingDirectory Search Venue Helper
     *  --------------------------------
     */
    class SDWeddingDirectory_Search_Result_Filter_Widget_Sub_Category extends SDWeddingDirectory_Search_Result_Helper{

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
            add_action( 'sdweddingdirectory/find-venue/filter-widget', [ $this, 'widget' ], absint( '11' ), absint( '1' ) );

            /**
             *  2. Have Active Filters ?
             *  ------------------------
             */
            add_filter( 'sdweddingdirectory/find-venue/active-filters', [ $this, 'active_filter' ], absint( '11' ), absint( '1' ) );
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

                    'group_id'	      		=> 		esc_attr( parent:: _rand() ),

                    'selected'	      		=>		parent:: _query_string( self:: widget_name() ),

                    'collapse'	      		=>		esc_attr( self:: widget_name() ),

                    'layout'	      		=>    	absint( '1' ),

                    /**
                     *  View More - Helper
                     *  ------------------
                     */
                    'view_more_limit'       =>    absint( '8' ),

                    'view_more_counter'     =>    absint( '0' ),

                    'view_more'             =>    parent:: _filter_view_more(),

        		] ) );

	            /**
	             *  Term Name
	             *  ---------
	             */
	            $term			= 	esc_attr( 'venue-type' );

	            if( ! empty( $cat_id ) ){

	                /**
	                 *  Have Label ?
	                 *  ------------
	                 */
	                $_have_lable    =   sdwd_get_term_field(

	                                        /**
	                                         *  1. Term Key
	                                         *  -----------
	                                         */
	                                        sanitize_key( 'venue_category_sub_category_label' ),

	                                        /**
	                                         *  2. Term ID
	                                         *  ----------
	                                         */
	                                        $cat_id
	                                    );

	                /**
	                 *  Checkbox Data
	                 *  -------------
	                 */
	                $_checkbox_data 	=	SDWeddingDirectory_Taxonomy:: get_taxonomy_child(

											    esc_attr( $term ),

											    absint( $cat_id ),

											    true
											);
	            }

	            else{

	                $_have_lable    =   esc_attr__( 'Venue Type', 'sdweddingdirectory' );

	                $_checkbox_data =   SDWeddingDirectory_Taxonomy:: get_taxonomy_parent( esc_attr( $term ) );
	            }

	            /**
	             *  Handler
	             *  -------
	             */
	            $_handaler 			=	'';

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
	            		 *  Collection
	            		 *  ----------
	            		 */
	                    $_handaler  .=

	                    sprintf( 	'<div class="col %5$s">

									    <div class="mb-3">

									        <input autocomplete="off" type="checkbox" class="form-check-input" value="%2$s" id="%3$s_%2$s" %4$s />

									        <label class="form-check-label" for="%3$s_%2$s">%1$s</label>

									    </div>

									</div>',

	                                /**
	                                 *  1. Get Name
	                                 *  -----------
	                                 */
	                                esc_attr( $value ),

	                                /**
	                                 *  2. Get ID ( KEY )
	                                 *  -----------------
	                                 */
	                                absint( $key ),

	                                /**
	                                 *  3. Group ID
	                                 *  -----------
	                                 */
	                                esc_attr( $group_id ),

	                                /**
	                                 *  4. Is Checked
	                                 *  -------------
	                                 */
	                                parent:: _is_array( $selected ) && in_array( absint( $key ), $selected )

	                                ?   esc_attr( 'checked' )

	                                :   '',

	                                /**
	                                 *  5. Make sure it's layout 2 + limit set to hide data
	                                 *  ---------------------------------------------------
	                                 */
	                                absint( $view_more_counter ) >= absint( $view_more_limit ) && $layout == absint( '2' )

	                                ?	sanitize_html_class( 'd-none' )

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
		            printf(	'<div class="col-12">

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

							                <div class="checkbox-data venue-filter-data" data-handler="%2$s" data-type="string">

							                	<div class="row row-cols-xl-4 row-cols-lg-3 row-cols-sm-2 row-cols-1">%3$s</div>

							                    <input type="hidden" id="%2$s-input-data" name="%2$s-input-data" value="%4$s">

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
							 *  4. Have default checked data ?
							 *  ------------------------------
							 */
							parent:: _is_array( $selected )

	                        ?   implode( ',', $selected )

	                        :   ''
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
		            printf(	'<div class="col-12">

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

							                <div class="checkbox-data venue-filter-data" data-handler="%2$s" data-type="string">

							                	<div class="row row-cols-xl-1 row-cols-lg-1 row-cols-sm-1 row-cols-1">%3$s</div>

							                    <input type="hidden" id="%2$s-input-data" name="%2$s-input-data" value="%4$s">

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
							 *  4. Have default checked data ?
							 *  ------------------------------
							 */
							parent:: _is_array( $selected )

	                        ?   implode( ',', $selected )

	                        :   ''
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
        	 *  Handler
        	 *  -------
        	 */
        	$_handaler 	=	[];

        	/**
        	 *  Create Array of collection
        	 *  --------------------------
        	 */
            $_data 	= 	parent:: _query_string( self:: widget_name() );

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $_data ) ){

                foreach( $_data as $key => $value ){

                	$_handaler[] 	=

                    sprintf( '<a    href="javascript:"  id="%6$s" class="%5$s"

                                    data-handler="%1$s" 

                                    data-value="%2$s" 

                                    data-type="string"

                                    >%3$s %4$s</a>',

                        /**
                         *  1. Query String
                         *  ---------------
                         */
                        esc_attr( self:: widget_name() ),

                        /**
                         *  2. Location ID
                         *  --------------
                         */
                        absint( $value ),

                        /**
                         *  3. Location Name
                         *  ----------------
                         */
                        esc_attr( get_term(

                            $value, 

                            esc_attr( 'venue-type' ) 

                        )->name ),

                        /**
                         *  4. Remove Icon
                         *  --------------
                         */
                        parent:: _remove_filter_icon(),

                        /**
                         *  5. Remove Class
                         *  ---------------
                         */
                        parent:: _remove_filter_class(),

                        /**
                         *  6. ID
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

	            return  	array_merge( $args, $_handaler );
            }

            else{

            	return  	$args;
            }
        }

        /**
         *  Widget Name
         *  -----------
         */
        public static function widget_name(){

        	return 		esc_attr( 'sub-category' );
        }
    }

    /**
     *  Search Result Helper
     *  --------------------
     */
    SDWeddingDirectory_Search_Result_Filter_Widget_Sub_Category::get_instance();
}
