<?php
/**
 *  SDWeddingDirectory Search Venue Helper
 *  --------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Search_Result_Filter_Widget_Active_Filters' ) && class_exists( 'SDWeddingDirectory_Search_Result_Helper' ) ){

    /**
     *  SDWeddingDirectory Search Venue Helper
     *  --------------------------------
     */
    class SDWeddingDirectory_Search_Result_Filter_Widget_Active_Filters extends SDWeddingDirectory_Search_Result_Helper{

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
            add_action( 'sdweddingdirectory/find-venue/filter-widget', [ $this, 'widget' ], absint( '10' ), absint( '1' ) );
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

                    'group_id'	      	=> 		esc_attr( parent:: _rand() ),

                    'collapse'	      	=>		esc_attr( 'active-filters' ),

                    'layout'	      	=>    	absint( '1' ),

                    'lable'    			=>   	esc_attr__( 'Active Filters', 'sdweddingdirectory' ),

                    'filters' 			=>		apply_filters( 'sdweddingdirectory/find-venue/active-filters', [] ),

        		] ) );

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

							    <div class="find-venue-widget mt-2 collapse %5$s" id="%2$s-section">

							        <div class="venue-filter-section">

							            <div class="head">

							                <strong>%1$s <a href="javascript:" class="btn btn-link-primary p-0 ms-3 clear_active_filters">%3$s</a></strong>

							                <a class="toggle" href="#%2$s" role="button" aria-expanded="true" aria-controls="%2$s"

							                	data-bs-toggle="collapse" data-bs-target="#%2$s">

							                	<i class="fa fa-angle-down"></i>

							                </a>

							            </div>

							            <div class="collapse show" id="%2$s">

							                <div class="venue-filter-data pb-2">

							                	<div class="d-grid gap-2 d-md-block active-filters-widget">%4$s</div>

							                </div>

							            </div>

							        </div>

							    </div>

							</div>',

							/**
							 *  1. Translation Ready String
							 *  ---------------------------
							 */
							esc_attr( $lable ),

							/**
							 *  2. Have Before ?
							 *  ----------------
							 */
							esc_attr( $collapse ),

							/**
							 *  3. Checkbox Data
							 *  ----------------
							 */
							esc_attr__( 'Clear all', 'sdweddingdirectory' ),

							/**
							 *  4. Have default checked data ?
							 *  ------------------------------
							 */
							parent:: _is_array( $filters )

	                        ?   implode( '', $filters )

	                        :   '',

	                        /**
							 *  5. Have Filters ?
							 *  -----------------
							 */
							! parent:: _is_array( $filters )

	                        ?   sanitize_html_class( 'd-none' )

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

							    <div class="filter-widget-layout-two %5$s" id="%2$s-section">

							        <div class="venue-filter-section">

							            <div class="head">

							                <strong>%1$s <a href="javascript:" class="btn btn-link-primary p-0 ms-3 clear_active_filters">%3$s</a></strong>

							                <a class="toggle" href="#%2$s" role="button" aria-expanded="true" aria-controls="%2$s"

							                	data-bs-toggle="collapse" data-bs-target="#%2$s">

							                	<i class="fa fa-angle-down"></i>

							                </a>

							            </div>

							            <div class="collapse show" id="%2$s">

							                <div class="venue-filter-data pb-2">

							                	<div class="d-grid gap-2 d-md-block active-filters-widget">%4$s</div>

							                </div>

							            </div>

							        </div>

							    </div>

							</div>',

							/**
							 *  1. Translation Ready String
							 *  ---------------------------
							 */
							esc_attr( $lable ),

							/**
							 *  2. Have Before ?
							 *  ----------------
							 */
							esc_attr( $collapse ),

							/**
							 *  3. Checkbox Data
							 *  ----------------
							 */
							esc_attr__( 'Clear all', 'sdweddingdirectory' ),

							/**
							 *  4. Have default checked data ?
							 *  ------------------------------
							 */
							parent:: _is_array( $filters )

	                        ?   implode( '', $filters )

	                        :   '',

	                        /**
							 *  5. Have Filters ?
							 *  -----------------
							 */
							! parent:: _is_array( $filters )

	                        ?   sanitize_html_class( 'd-none' )

	                        :   ''
					);
	            }
        	}
        }
    }

    /**
     *  Search Result Helper
     *  --------------------
     */
    SDWeddingDirectory_Search_Result_Filter_Widget_Active_Filters::get_instance();
}