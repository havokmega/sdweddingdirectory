<?php
/**
 *  SDWeddingDirectory Search Venue - Select Region
 *  -----------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Search_Result_Filter_Widget_Select_Region' ) && class_exists( 'SDWeddingDirectory_Search_Result_Helper' ) ) {

    /**
     *  SDWeddingDirectory Search Venue - Select Region
     *  -----------------------------------------
     */
    class SDWeddingDirectory_Search_Result_Filter_Widget_Select_Region extends SDWeddingDirectory_Search_Result_Helper {

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
            add_action( 'sdweddingdirectory/find-venue/filter-widget', [ $this, 'widget' ], absint( '40' ), absint( '1' ) );

            /**
             *  2. Have Active Filters ?
             *  ------------------------
             */
            add_filter( 'sdweddingdirectory/find-venue/active-filters', [ $this, 'active_filter' ], absint( '40' ), absint( '1' ) );
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

        			/**
        			 *  State ID
        			 *  --------
        			 */
        			'_state_id'						=>		parent:: _state_id(),

	                'group_id'	      				=> 		esc_attr( parent:: _rand() ),

	                'filter_type'					=>		self:: filter_type(),

					'layout'						=>		absint( '1' ),

	    			'category_taxonomy'				=>		esc_attr( 'venue-type' ),

					'location_taxonomy'				=>		esc_attr( 'venue-location' ),

					/**
					 *  Region Widget Args
					 *  ------------------
					 */
					'region_collapse'	     		=>		esc_attr( 'region_id' ),

					'region_widget_label'			=>		esc_attr__( 'Region', 'sdweddingdirectory' ),

					'region_placeholder'			=>		esc_attr__( 'Select Region', 'sdweddingdirectory' ),

					'region_selected' 				=>		! empty( parent:: _region_id() ) 

															?	[ parent:: _region_id() ]

															: 	false,

					'region_input_label'			=>		esc_attr__( 'All local groups', 'sdweddingdirectory' ),

					/**
					 *  City Widget Args
					 *  ----------------
					 */
					'city_collapse'	     			=>		esc_attr( 'city_id' ),

					'city_widget_label'				=>		esc_attr__( 'City / Town', 'sdweddingdirectory' ),

					'city_placeholder'				=>		esc_attr__( 'Select City / Town', 'sdweddingdirectory' ),

					'city_selected' 				=>		! empty( parent:: _city_id() )

															?	[ parent:: _city_id() ]

															: 	false,

					'city_input_label'				=>		esc_attr__( 'All Cities', 'sdweddingdirectory' ),

        		] ) );

	            /**
	             *  State ID Mandatory for Continues this widget
	             *  --------------------------------------------
	             */
	            if( empty( $_state_id ) ){

	                return;
	            }

	        	/**
	        	 *  Sorted By
	        	 *  ---------
	        	 */
	        	$_region_list 	= 		apply_filters( 'sdweddingdirectory/term-id/child-data', [

											'term_id'			=>	$_state_id,

											'__return'			=>	'group'

										] );
	        	/**
	        	 *  Have Data ?
	        	 *  -----------
	        	 */
	            if( ! parent:: _is_array( $_region_list ) ){

	            	return;
	            }

	            /**
	             *  Handler
	             *  -------
	             */
	            $_handaler 			=	'';

	            /**
	             *  Tab Content
	             *  -----------
	             */
	            $_tab_content 		=	'<div class="tab-content city-data-tab">';

	            /**
	             *  Have Data ?
	             *  -----------
	             */
	            if( parent:: _is_array( $_region_list ) ){

            		/**
            		 *  Collection
            		 *  ----------
            		 */
                    $_handaler 	.=

                    sprintf( 	'<a href="javascript:" class="list-group-item list-group-item-action _default_region_ %3$s" 

                    				data-location-id="0" data-collection="%4$s" data-location-name="%2$s"><span class="name">%2$s</span></a>',

                                /**
                                 *  1. Get Name
                                 *  -----------
                                 */
                                absint( '0' ),

                                /**
                                 *  2. Get ID ( KEY )
                                 *  -----------------
                                 */
                                esc_attr( $region_input_label ),

                                /**
                                 *  3. Have Region Selected ?
                                 *  -------------------------
                                 */
                                ! 	parent:: _is_array( $region_selected )

                                ?	sanitize_html_class( 'active' )

                                :	'',

                                /**
                                 *  4. Default Collection
                                 *  ---------------------
                                 */
                                apply_filters( 'sdweddingdirectory/term-id/json', [

                                	'term_info'		=>		false,

                                	'term_id'		=>		! empty( $_state_id ) ? $_state_id : absint( '0' )

                                ] )
                    );

	            	/**
	            	 *  Collection
	            	 *  ----------
	            	 */
	            	foreach( $_region_list as $_region_id => $region_data ){

	            		/**
	            		 *  Extract 
	            		 *  -------
	            		 */
	            		extract( $region_data );

	            		/**
	            		 *  Have Data ?
	            		 *  -----------
	            		 */
	            		$post_count 		=	apply_filters( 'sdweddingdirectory/term_exists', [

												    'category_id'       =>      $cat_id,

												    'location_id'       =>      $_region_id,

												    'counter'           =>      true

												] );

	            		/**
	            		 *  Make sure at least 1 data found
	            		 *  -------------------------------
	            		 */
	            		if( $post_count >= absint( '1' ) ){

		            		/**
		            		 *  Collection
		            		 *  ----------
		            		 */
		                    $_handaler  .=

		                    sprintf( 	'<a class="list-group-item list-group-item-action %3$s _select_region_" href="#%5$s"

		                    				data-bs-toggle="pill" data-bs-target="#%5$s"

      										role="tab" aria-controls="%5$s" aria-selected="%6$s"

      										data-collection="%7$s"

		                    				data-location-id="%1$s" data-location-name="%2$s">

		                    				<span class="name">%2$s</span> <span class="count float-end">%4$s</span>

		                    			</a>',

		                                /**
		                                 *  1. Get Name
		                                 *  -----------
		                                 */
		                                absint( $_region_id ),

		                                /**
		                                 *  2. Get ID ( KEY )
		                                 *  -----------------
		                                 */
		                                esc_attr( $name ),

		                                /**
		                                 *  3. Is Selected
		                                 *  --------------
		                                 */
		                                parent:: _is_array( $region_selected ) && in_array( esc_attr( $_region_id ), $region_selected )

		                                ?   esc_attr( 'active' )

		                                :   '',

		                                /**
		                                 *  4. Counter
		                                 *  ----------
		                                 */
		                                absint( $post_count ),

		                                /**
		                                 *  5. ID
		                                 *  -----
		                                 */
		                                sanitize_key( $slug ),

		                                /**
		                                 *  6. Is Active ?
		                                 *  --------------
		                                 */
		                                parent:: _is_array( $region_selected ) && in_array( esc_attr( $_region_id ), $region_selected )

		                                ?   esc_attr( 'true' )

		                                :   esc_attr( 'false' ),

		                                /**
		                                 *  7. Term ID wise Collection
		                                 *  --------------------------
		                                 */
		                                apply_filters( 'sdweddingdirectory/term-id/json', [

		                                	'term_info'		=>		false,

		                                	'term_id'		=>		$_region_id

		                                ] )
		                    );

		            		/**
		            		 *  Tab Content
		            		 *  -----------
		            		 */
	            			$_tab_content 	.=	sprintf( '<div class="tab-pane fade %1$s" id="%2$s" role="tabpanel" aria-labelledby="%2$s-tab">',

						            				/**
						            				 *  1. Is Active ?
						            				 *  --------------
						            				 */
						            				parent:: _is_array( $region_selected ) && in_array( esc_attr( $_region_id ), $region_selected )

						                            ?   esc_attr( 'show active' )

						                            :   '',

						                            /**
						                             *  2. ID
						                             *  -----
						                             */
						                            sanitize_key( $slug )
						            			);

		            		/**
		            		 *  State Have City Data ?
		            		 *  ----------------------
		            		 */
		            		$_city_list 	=	apply_filters( 'sdweddingdirectory/term-id/child-data', [

													'term_id'			=>	$_region_id,

													'__return'			=>	'group'

												] );


	            			/**
	            			 *  Have Region Data ?
	            			 *  ------------------
	            			 */
		            		if( parent:: _is_array( $_city_list ) ){

			            		/**
			            		 *  Collection
			            		 *  ----------
			            		 */
			                    $_tab_content 	.=

			                    sprintf( 	'<a href="javascript:" class="list-group-item list-group-item-action _default_city_ %3$s" 

			                    				data-location-id="0" data-collection="%4$s" data-location-name="%2$s"><span class="name">%2$s</span></a>',

			                                /**
			                                 *  1. Get Name
			                                 *  -----------
			                                 */
			                                absint( '0' ),

			                                /**
			                                 *  2. Get ID ( KEY )
			                                 *  -----------------
			                                 */
			                                esc_attr( $city_input_label ),

			                                /**
			                                 *  3. Is Selected
			                                 *  --------------
			                                 */
			                                ! 	parent:: _is_array( $city_selected )

			                                ?	sanitize_html_class( 'active' )

			                                :	'',

			                                /**
			                                 *  4. Default Collection
			                                 *  ---------------------
			                                 */
											apply_filters( 'sdweddingdirectory/term-id/json', [

										    	'term_info'		=>		false,

										    	'term_id'		=>		! empty( $region_id ) ? $region_id : absint( '0' )

										    ] )
			                    );

				            	/**
				            	 *  Loop For Region Child
				            	 *  ---------------------
				            	 */
		            			foreach( $_city_list as $_city_id => $_city_data ){

				            		/**
				            		 *  Extract 
				            		 *  -------
				            		 */
				            		extract( $_city_data );

				            		/**
				            		 *  Have Data ?
				            		 *  -----------
				            		 */
				            		$total_element 		=	apply_filters( 'sdweddingdirectory/term_exists', [

															    'category_id'       =>      $cat_id,

															    'location_id'       =>      $_city_id,

															    'counter'           =>      true

															] );

				            		/**
				            		 *  Make sure at least 1 data found
				            		 *  -------------------------------
				            		 */
				            		if( $total_element >= absint( '1' ) ){

					            		/**
					            		 *  Collection
					            		 *  ----------
					            		 */
					                    $_tab_content 	.=

					                    sprintf( 	'<a class="list-group-item list-group-item-action _select_city_ %3$s" href="#%5$s"

					                    				data-bs-toggle="pill" data-bs-target="#%5$s"

			      										role="tab" aria-controls="%5$s" aria-selected="%6$s"

			      										data-collection="%7$s"

					                    				data-location-id="%1$s" data-location-name="%2$s">

					                    				<span class="name">%2$s</span> <span class="count float-end">%4$s</span>

					                    			</a>',

					                                /**
					                                 *  1. Get Name
					                                 *  -----------
					                                 */
					                                absint( $_city_id ),

					                                /**
					                                 *  2. Get ID ( KEY )
					                                 *  -----------------
					                                 */
					                                esc_attr( $name ),

					                                /**
					                                 *  3. Is Selected
					                                 *  --------------
					                                 */
					                                parent:: _is_array( $city_selected ) && in_array( esc_attr( $_city_id ), $city_selected )

					                                ?   esc_attr( 'active' )

					                                :   '',

					                                /**
					                                 *  4. Counter
					                                 *  ----------
					                                 */
					                                absint( $total_element ),

					                                /**
					                                 *  5. ID
					                                 *  -----
					                                 */
					                                sanitize_key( $slug ),

					                                /**
					                                 *  6. Is Active ?
					                                 *  --------------
					                                 */
					                                parent:: _is_array( $city_selected ) && in_array( esc_attr( $_city_id ), $city_selected )

					                                ?   esc_attr( 'true' )

					                                :   esc_attr( 'false' ),

					                                /**
					                                 *  7. Term ID wise Collection
					                                 *  --------------------------
					                                 */
					                                apply_filters( 'sdweddingdirectory/term-id/json', [

					                                	'term_info'		=>		false,

					                                	'term_id'		=>		$_city_id

					                                ] )
					                    );
				            		}
		            				 
		            			}
		            		}


	            			$_tab_content 	.= 	'</div>';
	            		}
	            	}
	            }

	            /**
	             *  Tab Content
	             *  -----------
	             */
	            $_tab_content 		.=	'</div>';

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

							        <div class="select-item-section venue-filter-section">

							            <div class="head">

							                <strong>%1$s</strong>

							                <a class="toggle" href="#%2$s" role="button" aria-expanded="true" aria-controls="%2$s"

							                	data-bs-toggle="collapse" data-bs-target="#%2$s">

							                    <i class="fa fa-angle-down"></i>

							                </a>

							            </div>

							            <div class="collapse show" id="%2$s">

							                <div class="select-item-data venue-filter-data" data-handler="%2$s" data-type="select-item">

							                	<div class="row row-cols-1">

								                	<div class="col-md-12">

								                		<input 	type="text"  class="form-control input-collapse input-search-data mb-3" 

								                				id="%2$s-input"

								                				value="%5$s"

								                				data-search-id="#input-search-%2$s"

								                				data-search-target="a"

								                				data-target="#input-collapse-%2$s"

								                				placeholder="%6$s"  />

								                		<div class="input-collapse-box mb-3" id="%2$s-target-element">

									                		<div class="collapse mt-3 animate__animated animate__fadeIn %8$s" id="input-collapse-%2$s">

									                			<div class="list-group" id="input-search-%2$s" role="tablist" aria-orientation="vertical">%3$s</div>

									                		</div>

								                		</div>

								                	</div>

							                	</div>

							                    <input type="hidden" id="%2$s-input-data"  name="%2$s-input-data"  value="%4$s"  />

							                </div>

							            </div>

							        </div>

							    </div>

							</div>',

							/**
							 *  1. Translation Ready String
							 *  ---------------------------
							 */
							esc_attr( $region_widget_label ),

							/**
							 *  2. Have Before ?
							 *  ----------------
							 */
							esc_attr( $region_collapse ),

							/**
							 *  3. Checkbox Data
							 *  ----------------
							 */
							$_handaler,

							/**
							 *  4. Have default checked data ?
							 *  ------------------------------
							 */
							parent:: _is_array( $region_selected )

	                        ?   $region_selected[ absint( '0' ) ] 

	                        :   '',

	                        /**
	                         *  5. Name of Selected Term
	                         *  ------------------------
	                         */
	                        parent:: _is_array( $region_selected )

	                        ?	apply_filters( 'sdweddingdirectory/term/name', [

	                        		'term_id'		=>		$region_selected[ absint( '0' ) ],

	                        		'taxonomy'		=>		$location_taxonomy

	                        	] )

	                        :   esc_attr( $region_input_label ),

	                        /**
	                         *  6. Placeholder
	                         *  --------------
	                         */
	                        esc_attr( $region_placeholder ),

	                        /**
	                         *  7. Randome ID
	                         *  -------------
	                         */
	                        esc_attr( parent:: _rand() ),

	                        /**
	                         *  8. Have data ?
	                         *  --------------
	                         */
	                        parent:: _is_array( $region_selected )

	                        ?	sanitize_html_class( 'show' )

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

							        <div class="select-item-section venue-filter-section">

							            <div class="head">

							                <strong>%1$s</strong>

							                <a class="toggle" href="#%2$s" role="button" aria-expanded="true" aria-controls="%2$s"

							                	data-bs-toggle="collapse" data-bs-target="#%2$s">

							                    <i class="fa fa-angle-down"></i>

							                </a>

							            </div>

							            <div class="collapse show" id="%2$s">

							                <div class="select-item-data venue-filter-data" data-handler="%2$s" data-type="select-item">

							                	<div class="row row-cols-1">

								                	<div class="col-md-12">

								                		<input 	type="text"  class="form-control input-collapse input-search-data" 

								                				id="%2$s-input"

								                				value="%5$s"

								                				data-search-id="#input-search-%2$s"

								                				data-search-target="a"

								                				data-target="#input-collapse-%2$s"

								                				placeholder="%6$s"  />

								                		<div class="input-collapse-box" id="%2$s-target-element">

									                		<div class="collapse mt-3 animate__animated animate__fadeIn %8$s" id="input-collapse-%2$s">

									                			<div class="list-group" id="input-search-%2$s">%3$s</div>

									                		</div>

								                		</div>

								                	</div>

							                	</div>

							                    <input type="hidden" id="%2$s-input-data"  name="%2$s-input-data"  value="%4$s"  />

							                </div>

							            </div>

							        </div>

							    </div>

							</div>',

							/**
							 *  1. Translation Ready String
							 *  ---------------------------
							 */
							esc_attr( $region_widget_label ),

							/**
							 *  2. Have Before ?
							 *  ----------------
							 */
							esc_attr( $region_collapse ),

							/**
							 *  3. Checkbox Data
							 *  ----------------
							 */
							$_handaler,

							/**
							 *  4. Have default checked data ?
							 *  ------------------------------
							 */
							parent:: _is_array( $region_selected )

	                        ?   $region_selected[ absint( '0' ) ] 

	                        :   '',

	                        /**
	                         *  5. Name of Selected Term
	                         *  ------------------------
	                         */
	                        parent:: _is_array( $region_selected )

	                        ?	apply_filters( 'sdweddingdirectory/term/name', [

	                        		'term_id'		=>		$region_selected[ absint( '0' ) ],

	                        		'taxonomy'		=>		$location_taxonomy

	                        	] )

	                        :   esc_attr( $region_input_label ),

	                        /**
	                         *  6. Placeholder
	                         *  --------------
	                         */
	                        esc_attr( $region_placeholder ),

	                        /**
	                         *  7. Randome ID
	                         *  -------------
	                         */
	                        esc_attr( parent:: _rand() ),

	                        /**
	                         *  8. Have data ?
	                         *  --------------
	                         */
	                        parent:: _is_array( $region_selected )

	                        ?	sanitize_html_class( 'show' )

	                        :   ''
					);


		            /**
		             *  Load Checkbox
		             *  -------------
		             */
		            printf(	'<div class="col-12">

							    <div class="filter-widget-layout-two" id="%2$s-filter-section">

							        <div class="select-item-section venue-filter-section">

							            <div class="head">

							                <strong>%1$s</strong>

							                <a class="toggle" href="#%2$s" role="button" aria-expanded="true" aria-controls="%2$s"

							                	data-bs-toggle="collapse" data-bs-target="#%2$s">

							                    <i class="fa fa-angle-down"></i>

							                </a>

							            </div>

							            <div class="collapse show" id="%2$s">

							                <div class="select-item-data venue-filter-data enable-filter-tag" data-handler="%2$s" data-type="select-item">

							                	<div class="row row-cols-1">

								                	<div class="col-md-12">

								                		<input 	type="text"  class="form-control input-collapse input-search-data" 

								                				id="%2$s-input"

								                				value="%5$s"

								                				data-search-id="#input-search-%2$s"

								                				data-search-target="a"

								                				data-target="#input-collapse-%2$s"

								                				placeholder="%6$s"  />

								                		<div class="input-collapse-box" id="%2$s-target-element">

									                		<div class="collapse mt-3 animate__animated animate__fadeIn %8$s" id="input-collapse-%2$s">

									                			<div class="list-group" id="input-search-%2$s">%3$s</div>

									                		</div>

								                		</div>

								                	</div>

							                	</div>

							                    <input type="hidden" id="%2$s-input-data"  name="%2$s-input-data"  value="%4$s"  />

							                </div>

							            </div>

							        </div>

							    </div>

							</div>',

							/**
							 *  1. Translation Ready String
							 *  ---------------------------
							 */
							esc_attr( $city_widget_label ),

							/**
							 *  2. Have Before ?
							 *  ----------------
							 */
							esc_attr( $city_collapse ),

							/**
							 *  3. Checkbox Data
							 *  ----------------
							 */
							$_tab_content,

							/**
							 *  4. Have default checked data ?
							 *  ------------------------------
							 */
							parent:: _is_array( $city_selected )

	                        ?   $city_selected[ absint( '0' ) ] 

	                        :   '',

	                        /**
	                         *  5. Name of Selected Term
	                         *  ------------------------
	                         */
	                        parent:: _is_array( $city_selected )

	                        ?	apply_filters( 'sdweddingdirectory/term/name', [

	                        		'term_id'		=>		$city_selected[ absint( '0' ) ],

	                        		'taxonomy'		=>		$location_taxonomy

	                        	] )

	                        :   '',

	                        /**
	                         *  6. Placeholder
	                         *  --------------
	                         */
	                        esc_attr( $city_placeholder ),

	                        /**
	                         *  7. Randome ID
	                         *  -------------
	                         */
	                        esc_attr( parent:: _rand() ),

	                        /**
	                         *  8. Have data ?
	                         *  --------------
	                         */
	                        parent:: _is_array( $city_selected ) || parent:: _is_array( $region_selected )

	                        ?	sanitize_html_class( 'show' )

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
    		 *  Extract
    		 *  -------
    		 */
    		extract( wp_parse_args( parent:: _query_args(), [

				'location_taxonomy'		=>		esc_attr( 'venue-location' ),

    		] ) );

        	/**
        	 *  Handler
        	 *  -------
        	 */
        	$_handaler 		=	[];

        	/**
        	 *  Create Array of collection
        	 *  --------------------------
        	 */
            $_query_data		= 		parent:: _city_id();

        	/**
        	 *  Sorted By
        	 *  ---------
        	 */
        	$options_list 		=		apply_filters( 'sdweddingdirectory/term-id/child-data', [

											'term_id'			=>	parent:: _region_id(),

											'__return'			=>	'group'

										] );

            $filter_type		=		self:: filter_type();

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $options_list ) && parent:: _is_array( $_query_data ) ){

            	/**
            	 *  Term Exists ?
            	 *  -------------
            	 */
            	if ( array_key_exists( $_query_data[ 0 ] , $options_list ) ) {

	            	$_handaler[] 	=

	                sprintf( '<a    href="javascript:" id="%6$s" class="%5$s"

	                                data-handler="%1$s" 

	                                data-value="%2$s"

	                                data-type="select-item"

	                                >%3$s %4$s</a>',

	                    /**
	                     *  1. Query String
	                     *  ---------------
	                     */
	                    esc_attr( 'city_id' ),

	                    /**
	                     *  2. Location ID
	                     *  --------------
	                     */
	                    esc_attr( $_query_data[ 0 ] ),

	                    /**
	                     *  3. Location Name
	                     *  ----------------
	                     */
	                    esc_attr( $options_list[ $_query_data[ 0 ] ][ 'name' ] ),

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
	                    esc_attr( parent:: _rand() )
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
         *  Filter Type
         *  -----------
         */
        public static function filter_type(){

        	return  	esc_attr( 'select-item' );
        }
    }

    /**
     *  Search Result Helper
     *  --------------------
     */
    SDWeddingDirectory_Search_Result_Filter_Widget_Select_Region::get_instance();
}