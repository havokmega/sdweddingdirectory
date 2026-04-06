<?php
/**
 *  SDWeddingDirectory Search Venue - Sorted By
 *  -------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Search_Result_Filter_Widget_Sorted_By' ) && class_exists( 'SDWeddingDirectory_Search_Result_Helper' ) ){

    /**
     *  SDWeddingDirectory Search Venue - Sorted By
     *  -------------------------------------
     */
    class SDWeddingDirectory_Search_Result_Filter_Widget_Sorted_By extends SDWeddingDirectory_Search_Result_Helper{

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
            add_action( 'sdweddingdirectory/find-venue/filter-widget', [ $this, 'widget' ], absint( '50' ), absint( '1' ) );

            /**
             *  2. Have Active Filters ?
             *  ------------------------
             */
            add_filter( 'sdweddingdirectory/find-venue/active-filters', [ $this, 'active_filter' ], absint( '50' ), absint( '1' ) );
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

                    'group_id'	      => 	esc_attr( parent:: _rand() ),

                    'selected'	      =>	parent:: _query_string( self:: widget_name() ),

                    'collapse'	      =>	esc_attr( self:: widget_name() ),

                    'layout'	      =>    absint( '1' ),

        		] ) );

	            /**
	             *  Make sure category id not empty!
	             *  --------------------------------
	             */
	            if( empty( $cat_id ) ){

	                return;
	            }

	            /**
	             *  Have Label ?
	             *  ------------
	             */
	            $_enable_widget    	=   true;

	            /**
	             *  Make sure it's enable to continues
	             *  ----------------------------------
	             */
	            if( ! $_enable_widget ){

	            	return;
	            }

	            /**
	             *  Have Label ?
	             *  ------------
	             */
	            $_have_lable    	=   esc_attr__( 'Sort By', 'sdweddingdirectory' );

	            /**
	             *  Checkbox Data
	             *  -------------
	             */
	            $_checkbox_data 	=	self:: options();

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

	                    sprintf( 	'<option value="%2$s" data-string="%1$s" %3$s>%1$s</option>',

	                                /**
	                                 *  1. Get Name
	                                 *  -----------
	                                 */
	                                esc_attr( $value ),

	                                /**
	                                 *  2. Get ID ( KEY )
	                                 *  -----------------
	                                 */
	                                esc_attr( $key ),

	                                /**
	                                 *  3. Is Selected
	                                 *  --------------
	                                 */
	                                parent:: _is_array( $selected ) && in_array( esc_attr( $key ), $selected )

	                                ?   esc_attr( 'selected' )

	                                :   ''
	                    );
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

							        <div class="select-section venue-filter-section">

							            <div class="head">

							                <strong>%1$s</strong>

							                <a class="toggle" href="#%2$s" role="button" aria-expanded="true" aria-controls="%2$s"

							                	data-bs-toggle="collapse" data-bs-target="#%2$s">

							                    <i class="fa fa-angle-down"></i>

							                </a>

							            </div>

							            <div class="collapse show" id="%2$s">

							                <div class="select-data venue-filter-data" data-handler="%2$s" data-type="select">

							                	<div class="row row-cols-1">

							                		<select class="form-select form-light mb-3">%3$s</select>

							                	</div>

							                	<input id="%2$s-translation" name="%2$s-translation" value="%5$s" type="hidden" />

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

	                        :   '',

	                        /**
	                         *  5. Translation Ready String
	                         *  ---------------------------
	                         */
	                        esc_attr__( 'Sort By', 'sdweddingdirectory' )
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

							        <div class="select-section venue-filter-section">

							            <div class="head">

							                <strong>%1$s</strong>

							                <a class="toggle" href="#%2$s" role="button" aria-expanded="true" aria-controls="%2$s"

							                	data-bs-toggle="collapse" data-bs-target="#%2$s">

							                    <i class="fa fa-angle-down"></i>

							                </a>

							            </div>

							            <div class="collapse show" id="%2$s">

							                <div class="select-data venue-filter-data" data-handler="%2$s" data-type="select">

							                	<div class="row row-cols-1">

							                		<select class="form-select form-light">%3$s</select>

							                	</div>

							                	<input id="%2$s-translation" name="%2$s-translation" value="%5$s" type="hidden" />

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

	                        :   '',

	                        /**
	                         *  5. Translation Ready String
	                         *  ---------------------------
	                         */
	                        esc_attr__( 'Sort By', 'sdweddingdirectory' )
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
            $_query_data	= 	parent:: _query_string( self:: widget_name() );

            $options_list 	=	self:: options();

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $options_list ) && parent:: _is_array( $_query_data ) ){

            	$_handaler[] 	=

                sprintf( '<a    href="javascript:" id="%6$s" class="%5$s"

                                data-handler="%1$s" 

                                data-value="%2$s" 

                                data-type="select"

                                >%7$s : %3$s %4$s</a>',

                    /**
                     *  1. Query String
                     *  ---------------
                     */
                    esc_attr( self:: widget_name() ),

                    /**
                     *  2. Location ID
                     *  --------------
                     */
                    esc_attr( $_query_data[ 0 ] ),

                    /**
                     *  3. Location Name
                     *  ----------------
                     */
                    esc_attr( $options_list[ $_query_data[ 0 ] ] ),

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
                    parent:: _rand(),

                    /***
                     *  7. Translation Ready String
                     *  ---------------------------
                     */
                    esc_attr__( 'Sort By', 'sdweddingdirectory' )
                );
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

        	return 		esc_attr( 'sort-by' );
        }

        /**
         *  Options
         *  -------
         */
        public static function options(){

        	/**
        	 *  Sorted By
        	 *  ---------
        	 */
        	return 	[
					    ''                     	=>  esc_attr__( 'Sort By', 'sdweddingdirectory' ),

					    'top_rated'             =>  esc_attr__( 'Top Rated', 'sdweddingdirectory' ),

					    'most_viewed'           =>  esc_attr__( 'Most Viewed', 'sdweddingdirectory' ),

					    'highest_rating'        =>  esc_attr__( 'Highest Rating', 'sdweddingdirectory' ),

					    'lowest_rating'         =>  esc_attr__( 'Lowest Rating', 'sdweddingdirectory' ),

					    'highest_price'         =>  esc_attr__( 'Highest Price', 'sdweddingdirectory' ),

					    'lowest_price'          =>  esc_attr__( 'Lowest Price', 'sdweddingdirectory' ),
					];
        }
    }

    /**
     *  Search Result Helper
     *  --------------------
     */
    SDWeddingDirectory_Search_Result_Filter_Widget_Sorted_By::get_instance();
}