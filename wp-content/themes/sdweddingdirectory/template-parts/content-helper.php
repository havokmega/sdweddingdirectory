<?php
/**
 *  SDWeddingDirectory - Blog
 *  -----------------
 */
if( ! class_exists( 'SDWeddingDirectory_Blog_Helper' ) && class_exists( 'SDWeddingDirectory' ) ){

	/**
	 *  SDWeddingDirectory - Blog
	 *  -----------------
	 */
	class SDWeddingDirectory_Blog_Helper extends SDWeddingDirectory {

	    /**
	     * Member Variable
	     * ---------------
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

		public function __construct(){

			/**
			 *  Blog Post Category
			 *  ------------------
			 */
			add_filter( 'sdweddingdirectory/post-category', [ $this, 'post_category' ], absint( '10' ), absint( '1' ) );

			/**
			 *  Blog Post Tags
			 *  --------------
			 */
			add_action( 'sdweddingdirectory/blog/details', [ $this, 'sdweddingdirectory_blog_tags' ], absint( '10' ), absint( '1' ) );

			/**
			 *  Single Post Link
			 *  ----------------
			 */
			add_action( 'sdweddingdirectory/blog/details', [ $this, 'sdweddingdirectory_single_post_link' ], absint( '20' ), absint( '1' ) );
		}

		/**
		 *  Elementor Page Builder now editing mode ?
		 *  -----------------------------------------
		 *  @credit - https://github.com/elementor/elementor/issues/4491
		 *  ------------------------------------------------------------
		 */
		public static function is_elementor_edit_mode(){
			return false;

		}

		/** 
		 * 	SDWeddingDirectory - The Excerpt for word counting in the_content
		 * 	---------------------------------------------------------
		 */
		public static function SDWeddingDirectory_getwords( $text, $limit ) {
				 
			$array = explode("", $text, $limit+1);

			if (count($array) > $limit){

				unset($array[$limit]);
			}

			return esc_attr(   implode( "", $array )  );
		}

		/**
		 *  SDWeddingDirectory - Post Format Load Condition
		 *  ---------------------------------------
		 */
		public static function post_load_condition( $post_id = '0' ){

			if( empty( $post_id ) ) {

				return false;
			}

			$_have_post_thumbnail	=	get_post_meta( absint( $post_id ), sanitize_key( '_thumbnail_id' ), true );

    		$_have_post_format 		=	self:: post_formate( array(  

											'post_id'		=>		absint( $post_id ),

											'image_size'	=>		esc_attr( 'sdweddingdirectory_img_600x470' )

										) );

    		$_sdweddingdirectory_posts 		=	in_array(

				                			/**
				                			 *  Have Key ?
				                			 *  ----------
				                			 */
				                			esc_attr( get_post_format( $post_id ) ),

				                			/**
				                			 *  Check in list 
				                			 *  -------------
				                			 */
				                			array(

				                				esc_attr( 'image' ), 

				                				esc_attr( 'gallery' ), 

				                				esc_attr( 'video' ), 

				                				esc_attr( 'audio' )
				                			)
				                		);

    		/**
    		 *  Have Post Thumbnails ?
    		 *  ----------------------
    		 */
    		if( $_have_post_thumbnail ){

    			return 	true;
    		}

    		/**
    		 *  If is post format supported ?
    		 *  -----------------------------
    		 */
    		return 	$_have_post_format &&  $_sdweddingdirectory_posts;
		}

		/** 
		 * 	SDWeddingDirectory - The Excerpt for Character counting in the_content
		 * 	--------------------------------------------------------------
		 */
		public static function sdweddingdirectory_getcharcut(  $text,  $limit  ){
			 
			$char_cut	=	substr(	

								wp_strip_all_tags(		stripslashes(	$text 	)	),

								absint( '0' ),

								$limit
							);

			if(	strlen($text)	<=	$limit	){

				return  $char_cut; 

			}else{

				return esc_attr( $char_cut );
			}
		}

		/**
		 *  Tags : Blog Detail Page
		 *  -----------------------
		 */
		public static function sdweddingdirectory_blog_tags( $args = [] ){

			/**
			 *  Have Args ?
			 *  -----------
			 */
			if( parent:: _is_array( $args ) ){

				/**
				 *  Extract Args
				 *  ------------
				 */
				extract( wp_parse_args( $args, [

					'post_id'		=>		absint( '0' ),

				] ) );

				/**
				 *  Make sure it's not emtpy!
				 *  -------------------------
				 */
				if( empty( $post_id ) ){

					return;
				}

				/**
				 *  SDWeddingDirectory - Post Have Tags ?
				 *  -----------------------------
				 */
				if( has_tag() ){

					/**
					 *  SDWeddingDirectory - Load the Tags
					 *  --------------------------
					 *  @credit - https://developer.wordpress.org/reference/functions/the_tags/
					 *  -----------------------------------------------------------------------
					 */
					the_tags( 

						/**
						 *  1. Before Start Tags
						 *  --------------------
						 */
						'<div class="sdweddingdirectory-section-post-tags tag-wrap">

							<div class="post-tags"> 

								<i class="fa fa-tags"></i> 

								<div>',


						/**
						 *  2. Is a Seperator each tags when loaded such as ","
						 *  ----------------------------------------------------
						 */
						'', 

						/**
						 *  3. When Tags end then close the div structure
						 *  ---------------------------------------------
						 */
							   '</div>

							</div>

						</div>'
					);
				}
			}
		}

		/**
		 *  Post Title
		 *  ----------
		 */
		public static function have_title( $post_id = '0' ){

			if( empty( $post_id ) ){

				return;
			}

			$_have_title  	=	esc_attr( get_the_title( absint( $post_id ) ) );

			$_condition_1 	=	$_have_title !== '' && $_have_title !== NULL && ! empty( $_have_title );

			$_condition_2 	=	self:: is_elementor_edit_mode() || ! is_single();

			/**
			 *  Have Post Title
			 *  ---------------
			 */
			if( $_condition_1 && $_condition_2 ){

				return

				sprintf(   '<h3 class="blog-title" itemprop="headline">

								<a href="%1$s" class="post-title" rel="bookmark">%2$s</a>

							</h3>',

							/**
							 *  1. Post Singular Page
							 *  ---------------------
							 */
							esc_url( get_the_permalink( absint( $post_id ) ) ),

							/**
							 *  2. Post Title
							 *  -------------
							 *  @credit - https://developer.wordpress.org/reference/functions/get_the_title/#highlighter_463314
							 *  ------------------------------------------------------------------------------------------------
							 */
							wp_kses_post( get_the_title( absint( $post_id ) ) )
				);
			}
		}

		/**
		 *  Post Have Content ?
		 *  -------------------
		 */
		public static function have_content( $post_id = 0 ){

			/**
			 *  Post ID not Empty!
			 *  ------------------
			 */
			if( empty( $post_id ) ){

				return;
			}

			/**
			 *  Have Post Content ?
			 *  -------------------
			 */
			$_have_content 	=	apply_filters(

									/**
									 *  Filter Name
									 *  -----------
									 */
									esc_attr( 'the_content' ), 

									/**
									 *  ---------------
									 *  Get The Content
									 *  ---------------
									 *  @article - https://developer.wordpress.org/reference/functions/get_the_content/
									 *  -------------------------------------------------------------------------------
									 */
									get_the_content(

									 	null, 

									 	false,

									 	/**
									 	 *  Post ID
									 	 *  -------
									 	 */
									 	absint( $post_id ) 
									)
								);

			/**
			 *  Make sure post have content ?
			 *  -----------------------------
			 */
			if( parent:: _have_data( $_have_content ) ){

				return 	true;

			}else{

				return 	false;
			}
		}

		/**
		 *  Post Content
		 *  ------------
		 */
		public static function article_content( $post_id = '0' ){

			/**
			 *  Post ID not Empty!
			 *  ------------------
			 */
			if( empty( $post_id ) ){

				return;
			}

			/**
			 *  Have Post Content ?
			 *  -------------------
			 */
			if( self:: have_content( absint( $post_id ) ) ){

				/**
				 *  If Is Singular page ?
				 *  ---------------------
				 */
				if( is_single() && ! self:: is_elementor_edit_mode() ){

					return

					sprintf( '<div class="entry-content">%1$s</div> %2$s',

						/**
						 *  Get the content
						 *  ---------------
						 */
						apply_filters( 

							/**
							 *  Filter Name
							 *  -----------
							 */
							esc_attr( 'the_content' ), 

							/**
							 *  ---------------
							 *  Get The Content
							 *  ---------------
							 *  @article - https://developer.wordpress.org/reference/functions/get_the_content/
							 *  -------------------------------------------------------------------------------
							 */
							get_the_content(

							 	null, 

							 	false,

							 	/**
							 	 *  Post ID
							 	 *  -------
							 	 */
							 	absint( $post_id ) 
							)
						),

						/**
						 *  2. Have More Link ?
						 *  -------------------
						 */
						self::link_pagination()
					);

				}else{

					/**
					 *  Expert Content
					 *  --------------
					 */
					return 		self:: expert_content( absint( $post_id ) );
				}
			}
		}

		/**
		 *  Expert Content
		 *  --------------
		 */
		public static function expert_content( $post_id = '0', $word = '25' ){

			if( empty( $post_id ) ){

				return;
			}

			$_have_content  	=	get_the_excerpt( absint( $post_id ) );

			if( empty(  $_have_content  ) ){

				return;
			}

			$_content_length 	= 	absint( $word );

			$excerpt = explode(' ', $_have_content, $_content_length );

			if ( count($excerpt) >= $_content_length ) {

				array_pop($excerpt);

				$excerpt = 	implode( " ", $excerpt ) . ' ...';

			} else {

				$excerpt = 	implode(" ",$excerpt);
			}

			$excerpt 	= 	preg_replace( '`[[^]]*]`','',$excerpt  );

			/**
			 *  Return Expert Content
			 *  ---------------------
			 */
			return 		apply_filters( 'expert_content', 

							sprintf( '<div class="entry-content" itemprop="description"><p>%1$s</p></div>', 

								$excerpt 
							) 
						);
		}

		/**
		 *  Post Tag
		 *  --------
		 */
		public static function post_tags(){

			if( has_tag() ){

				?>
					<div class="sdweddingdirectory-theme-tags">
						<?php the_tags( '<div class="widget-tags"> <h5>'. esc_attr__( 'Tags:', 'sdweddingdirectory' ) .'</h5> ', '', '</div>' ); ?>
					</div>

				<?php
			}
		}

		/**
		 *  Date Formate
		 *  ------------
		 */
		public static function date_formate( $post_id = '0' ){

			if( empty( $post_id ) ){

				return;
			}

			$archive_year  		= 	esc_attr( get_the_time( 'Y', absint( $post_id ) ) );

			$archive_month	 	= 	esc_attr( get_the_time( 'm', absint( $post_id ) ) );

			$archive_day   		= 	esc_attr( get_the_time( 'd', absint( $post_id ) ) );

			return

			sprintf(   '<span class="meta-date">

							<a href="%3$s"> %1$s </a>

							<meta itemprop="datePublished" content="%1$s" />

							<meta itemprop="dateModified" content="%2$s" />

						</span>',

					/**
					 *  1. Post Date
					 *  ------------
					 */
					esc_attr( get_the_date( get_option( 'date_format' ), absint( $post_id ) ) ),

					/**
					 *  2. Post Modification Date
					 *  -------------------------
					 */
					esc_attr( get_the_modified_date( get_option( 'date_format' ), absint( $post_id ) ) ),

					/**
					 *  3. Date link
					 *  ------------
					 */
					esc_url( get_day_link( $archive_year, $archive_month, $archive_day ) )
			);	
		}

		public static function sdweddingdirectory_entry_content_markup_layout_two(){

			?>

				<div class="blog-wrap-home">

				    <div class="post-content">

				        <?php echo self:: post_formate( 'sdweddingdirectory_img_360x480' ); ?>

				        <div class="home-content">
				            
				            <?php echo self:: date_formate(); ?>

				            <div class="mt-auto">

				                <?php

				                	/**
				                	 *  Category
				                	 *  --------
				                	 */
				                	print 	self:: post_category( [

				                				'post_id'			=>		absint( get_the_ID() ),

				                			] );

									if( get_the_title() != '' && ! is_single() && ! is_page() && ! is_singular() ){

										?>

											<h3 class="blog-title" itemprop="headline">

												<a href="<?php the_permalink(); ?>" class="post-title" rel="bookmark"><?php the_title(); ?></a>

											</h3>

										<?php
									}

				                ?>
				                
				                <?php 

				                	self::article_content(); 


									if( ! is_single() ){

										self::read_more( '2' );
									}

				                ?>

				            </div>                     
				        </div>                   
				        <!-- Post Blog Content -->
				    </div>                            
				</div>
			
			<?php
		}

		/**
		 *  Result Not Found
		 *  ----------------
		 */
		public static function sdweddingdirectory_empty_article_markup(){

			/**
			 *  SDWeddingDirectory - Article Not Found
			 *  ------------------------------
			 */
			get_template_part(

				/**
				 *  1. Slug
				 *  -------
				 */
				esc_attr( 'template-parts/no' ), 

				/**
				 *  2. Name
				 *  -------
				 */
				esc_attr( 'result' ) 
			);
		}

		/**
		 *  Post Class
		 *  ----------
		 */
		public static function sdweddingdirectory_post_class( $args = [] ){

			global $post, $wp_query;

			/**
			 *  Extract Args
			 *  ------------
			 */
			extract(

				wp_parse_args(

					$args,

					array(

						'layout' => absint( '1' )
					)
				)
			);

			$classes = [];

			/**
			 *  Common prefix class
			 *  -------------------
			 */
			$classes[] = 	sanitize_html_class( 'sdweddingdirectory-blog-style-' . absint( $layout ) );

			/**
			 *  Common prefix class
			 *  -------------------
			 */
			$classes[] = 	sanitize_html_class( 'sdweddingdirectory-section-post-content' );

			/**
			 *  Is Sticky Post ?
			 *  ----------------
			 */
			if( is_sticky() ){

				$classes[] = 	sanitize_html_class( 'post-sticky' );
			}

			/**
			 *  Is Post Show Page ?
			 *  -------------------
			 */
			if( is_home() || is_search() || is_archive() ){

				$classes[] = 	sanitize_html_class( 'post-block' );
			}

			/**
			 *  Is Singular Page ?
			 *  ------------------
			 */
			if( is_single() ){

				$classes[] = 	sanitize_html_class( 'sdweddingdirectory-singular-page' );
			}

			return esc_attr( join( ' ', array_map( 'sanitize_html_class', $classes ) ) );
		}

		/**
		 *  Post Pagination link
		 *  --------------------
		 */
		public static function link_pagination(){

			return 
		 
		    wp_link_pages( array(

				'before'           => 	'<ul class="pagination justify-content-center">',

				'after'            => 	'</ul>',

				'next_or_number'   => 	esc_attr( 'number' ),

				'nextpagelink'     => 	esc_attr__( 'Next page', 'sdweddingdirectory'),

				'previouspagelink' => 	esc_attr__( 'Previous page', 'sdweddingdirectory' ),

				'pagelink'         => 	esc_attr( '%' ),

				'echo'             => 	absint( '0' )

			) );
		}

		/**
		 *  Post Category 
		 *  -------------
		 */
		public static function post_category( $args = [] ){

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

					'post_id'		=>		absint( '0' ),

					'before_div'	=>		'<span class="post-category">',

					'after_div'		=>		'</span>',

					'separator'		=>		' ',

					'class'			=>		'',

					'output'		=>		'',

				] ) );
			}

			/**
			 *  Make sure post category not empty!
			 *  ----------------------------------
			 */
			if( empty( $post_id ) ){

				return;
			}

			/**
			 *  Get Category
			 *  ------------
			 */
			$categories 	= 	get_the_category( absint( $post_id ) );

			/**
			 *  Have Category ?
			 *  ---------------
			 */
			if( ! empty( $categories ) && parent:: _is_array( $categories ) ){
				
				/**
				 *  Data
				 *  ----
				 */
			    foreach( $categories as $category ) {

			        $output .= 

			        sprintf( '<a href="%1$s" title="%2$s" class="%5$s">%3$s</a> %4$s', 

			        	/**
			        	 *  1. Category Link
			        	 *  ----------------
			        	 */
			        	esc_url( get_category_link( $category->term_id ) ),

			        	/**
			        	 *  2. Anchore Title
			        	 *  ----------------
			        	 */
						esc_attr( sprintf( __( 'View all posts in %s', 'sdweddingdirectory' ), $category->name ) ),

						/**
						 *  3. Category Name
						 *  ----------------
						 */
						esc_attr( $category->name ),

						/**
						 *  4. Separator when category one by one load
						 *  ------------------------------------------
						 */
						esc_attr( $separator ),

						/**
						 *  5. Class
						 *  --------
						 */
						$class
			        );
			    }
			}

			/**
			 *  If Have Category ?
			 *  ------------------
			 */
			if( ! empty( $output ) ){

				/**
				 *  Return
				 *  ------
				 */
				return		$before_div . trim( $output, $separator ) . $after_div;
			}
		}

		/**
		 *  Edit Post
		 *  ---------
		 */
		public static function sdweddingdirectory_content_edit_post(){

			global 	$post, $wp_query;

			printf( '<span class="edit_post"><a href="%1$s">%2$s</a></span>',

				// 1
				esc_url( get_edit_post_link( get_the_ID() ) ),

				// 2
				esc_attr__( 'Edit', 'sdweddingdirectory' )
			);
		}


		/**
		 *   Post Comment Meta
		 * @return [type] [description]
		 */
		public static function sdweddingdirectory_content_comment_e(){

			echo self:: sdweddingdirectory_content_comment();
		}

		/**
		 *  Post Comment
		 *  ------------
		 */
		public static function sdweddingdirectory_content_comment(){

			global $post, $wp_query;

			if ( comments_open() ){

				printf('<span class="meta-comments text-primary"><a href="%1$s" class="meta-link"><i class="fas fa-comments"></i> %3$s</a></span>', 

					// 1
					get_comments_link(),

					// 2
					( get_comments_number() == 1 ? ( get_comments_number() ) : ( get_comments_number() ) ),

					// 3
					esc_attr__( 'Comment', 'sdweddingdirectory' )
				);
			}
		}

		/**
		 *  Post By
		 *  -------
		 */

		public static function post_by(){

			global $post, $wp_query;

			printf( '<span class="meta-posted-by" itemprop="author" itemscope itemtype="https://schema.org/Person">
						<i class="fas fa-user-circle"></i>
						<a href="%1$s">
							<span itemprop="name">%2$s</span>
						</a>
					</span>',

					// 1
					get_author_posts_url( get_the_author_meta('ID') ),

					// 2
					ucwords( get_the_author() ),

					// 3
					esc_attr__('BY', 'sdweddingdirectory' )
			);
		}

		/**
		 *  Post is Sticky
		 *  --------------
		 */
		public static function is_sticky_post( $post_id = '0' ){

			if( empty( $post_id ) ){

				return;
			}

			if( is_sticky( $post_id ) ){

				return 		'<div class="sticky-icon"></div>';

				return   	'<span class="sticky-post"><i class="fa fa-bookmark-o"></i></span>';
			}
		}

		/**
		 *  -------------------
		 *  Get Media Resources
		 *  -------------------
		 *  @credit - https://developer.wordpress.org/reference/functions/wp_get_attachment_metadata/
		 *  -----------------------------------------------------------------------------------------
		 */
		public static function get_image_meta( $args = [] ){

			/**
			 *  Have Args ?
			 *  -----------
			 */
			if(  parent:: _is_array( $args )  ){

				/**
				 *  Extract Args with Merge Default Args
				 *  ------------------------------------
				 */
				extract( wp_parse_args( $args, array(

					'media_id'		=>		absint( '0' ),

					'get_data'		=>		esc_attr( 'alt' ),

				) ) );

				/**
				 *  Media is empty ?
				 *  ----------------
				 */
				if(  empty( $media_id )  ){

					return;
				}

				/**
				 *  Get Attechment meta data
				 *  ------------------------
				 */
				$media_meta 	=		wp_get_attachment_metadata(

												/**
												 *  1. Get the Media ID
												 *  -------------------
												 */
												absint( $media_id ),

												/**
												 *  2. TRUE
												 *  -------
												 */
												true
											);

				/**
				 *  Return : Media Meta Data
				 *  ------------------------
				 */
				if(  parent:: _is_array( $media_meta )  ){

					/**
					 *  Return : Width
					 *  --------------
					 */
					if( $get_data == esc_attr( 'width' ) ){

						
					}

					/**
					 *  Return : Height
					 *  ---------------
					 */
					if( $get_data == esc_attr( 'height' ) ){

						return 		absint(  $media_meta[ 'height' ] );
					}

					/**
					 *  Return : Alt
					 *  ------------
					 */
					if( $get_data == esc_attr( 'alt' ) ){

						return 		esc_attr( 	get_post_meta( 

										/**
										 *  1. Media Post Attachment ID
										 *  ---------------------------
										 */
										absint( $media_id ), 

										/**
										 *  2. Meta Key
										 *  -----------
										 */
										sanitize_key( '_wp_attachment_image_alt' ), 

										/**
										 *  3. TRUE
										 *  -------
										 */
										true

									) );
					}
				}
			}
		}

		/**
		 *  -----------------------------------------------
		 *  SDWeddingDirectory - Get Media Id to retrive image link
		 *  -----------------------------------------------
		 *  @credit - https://developer.wordpress.org/reference/functions/wp_get_attachment_image_src/
		 *  ------------------------------------------------------------------------------------------
		 */
		public static function sdweddingdirectory_media( $args = [] ){

			/**
			 *  Have Args ?
			 *  -----------
			 */
			if(  parent:: _is_array( $args )  ){

				/**
				 *  Extract Args with Merge Default Args
				 *  ------------------------------------
				 */
				extract( wp_parse_args( $args, [

					'media_id'		=>		absint( '0' ),

					'image_size'	=>		esc_attr(  'sdweddingdirectory_img_600x470' ),

					'get_data'		=>		esc_attr( 'url' ),

				] ) );

				/**
				 *  Media is empty ?
				 *  ----------------
				 */
				if(  empty( $media_id )  ){

					return;
				}

				/**
				 *  Have Media Data ?
				 *  -----------------
				 */
				$_media_data 	=	wp_get_attachment_image_src(

									    /**
									     *  1. Media ID
									     *  -----------
									     */
									    absint( $media_id ),

									    /**
									     *  2. Media Size
									     *  -------------
									     */
									    esc_attr( $image_size )
									);

				/**
				 *  Get Attechment meta data
				 *  ------------------------
				 */
				$media_meta		=	wp_get_attachment_metadata(

										/**
										 *  1. Get the Media ID
										 *  -------------------
										 */
										absint( $media_id ),

										/**
										 *  2. TRUE
										 *  -------
										 */
										true
									);

				/**
				 *  Media Have Array ?
				 *  ------------------
				 */
				if( parent:: _is_array( $_media_data ) || parent:: _is_array( $media_meta ) ){

					/**
					 *  Return : Link
					 *  -------------
					 */
					if(  $get_data == esc_attr( 'url' )  ){

						return  	esc_url( $_media_data[ absint( '0' ) ] );
					}

					/**
					 *  Return : Width 
					 *  --------------
					 */
					if( $get_data == esc_attr( 'width' ) ){

						return 		absint(  $media_meta[ 'width' ] );
					}

					/**
					 *  Return : Height
					 *  ---------------
					 */
					if( $get_data == esc_attr( 'height' ) ){

						return 		absint(  $media_meta[ 'height' ] );
					}

					/**
					 *  Return : Alt
					 *  ------------
					 */
					if( $get_data == esc_attr( 'alt' ) ){

						return 		esc_attr( get_post_meta(

										/**
										 *  1. Media Post Attachment ID
										 *  ---------------------------
										 */
										absint( $media_id ), 

										/**
										 *  2. Meta Key
										 *  -----------
										 */
										sanitize_key( '_wp_attachment_image_alt' ), 

										/**
										 *  3. TRUE
										 *  -------
										 */
										true

									) );
					}
				}
			}
		}

		/**
		 *  Get Image Full Data
		 *  -------------------
		 */
		public static function sdweddingdirectory_image( $args = [] ){

			/**
			 *  Have Args ?
			 *  -----------
			 */
			if(  parent:: _is_array( $args )  ){

				$args 	=	wp_parse_args( $args, array(

								'media_id'		=>		absint( '0' ),

								'image_size'	=>		esc_attr(  'sdweddingdirectory_img_600x470' ),

								'image_class'	=>		array(  'img-fluid', 'rounded', 'wp-post-image' )

							) );

				/**
				 *  Extract Args with Merge Default Args
				 *  ------------------------------------
				 */
				extract( $args );

				/**
				 *  Media is empty ?
				 *  ----------------
				 */
				if(  empty( $media_id )  ){

					return;
				}

				/**
				 *  Return Image Body
				 *  -----------------
				 */
				return 

				sprintf( '<img src="%1$s" alt="%2$s" height="%3$s" width="%4$s" class="%5$s" loading="lazy" itemprop="url" />',

					/**
					 *  1. Image src
					 *  ------------
					 */
					self:: sdweddingdirectory_media( array(

						'media_id'		=>		absint(  $media_id  ),

						'image_size'	=>		esc_attr( $image_size ),

						'get_data'		=>		esc_attr( 'url' ),

					) ),

					/**
					 *  2. Image alt
					 *  ------------
					 */
					self:: sdweddingdirectory_media( array(

						'media_id'		=>		absint(  $media_id  ),

						'get_data'		=>		esc_attr( 'alt' )

					) ),

					/**
					 *  3. Image height
					 *  ---------------
					 */
					self:: sdweddingdirectory_media( array(

						'media_id'		=>		absint(  $media_id  ),

						'get_data'		=>		esc_attr( 'height' )

					) ),

					/**
					 *  4. Image width
					 *  ---------------
					 */
					self:: sdweddingdirectory_media( array(

						'media_id'		=>		absint(  $media_id  ),

						'get_data'		=>		esc_attr( 'width' )

					) ),

					/**
					 *  5. Image Class
					 *  --------------
					 */
					parent:: _is_array( $image_class )

					?	parent:: _class( $image_class )

					:	''
				);
			}
		}

		/**
		 *  Post Format : Image ?
		 *  ---------------------
		 */ 
		public static function is_image_post( $args = [] ){

			/**
			 *  Have Args ?
			 *  -----------
			 */
			if(  parent:: _is_array( $args )  ){

				$output 	= 		'';

				/**
				 *  Merge Default Args if not found
				 *  -------------------------------
				 */
				$args 		=		wp_parse_args(  $args,  array(

										'post_id'		=>		absint( '0' ),

										'image_size'	=>		esc_attr( 'sdweddingdirectory_img_600x470' ),

									)   );

				/**
				 *  Extract Args
				 *  ------------
				 */
				extract( $args );

				/**
				 *  If Post not empty!
				 *  ------------------
				 */
				if(  empty( $post_id )  ){

					return;
				}

				/**
				 *  Have Post Featured Image ?
				 *  --------------------------
				 */
				if (   has_post_thumbnail( $post_id )   ) {

					/**
					 *  1. Load Thumbnail
					 *  -----------------
					 */
					$_custom_image 		=		self:: sdweddingdirectory_image( array(

													'media_id'		=>		absint( 

																				/**
																				 *  1. Get Post Thumbnails ID
																				 *  -------------------------
																				 */
																				get_post_thumbnail_id( absint( $post_id ) )
																			),

													'image_size'	=>		esc_attr(  $image_size  )

												) );
					/**
					 *  Post Thumbnails
					 *  ---------------
					 */
					$_post_thumbnails 	=		get_the_post_thumbnail(

													/**
													 *  1. Post ID
													 *  ----------
													 */
													absint( $post_id ),

													/**
													 *  2. Image Size
													 *  -------------
													 */
													esc_attr(  $image_size  )
												);
					/**
					 *  Load HTML 
					 *  ---------
					 */
					$output 	.= 		sprintf( '<div class="post-img">

													<div class="sdweddingdirectory-post-formate" 

														itemprop="image" itemscope 

														itemtype="https://schema.org/ImageObject">'
										);
					
							/**
							 *  Is Singular Page ?
							 *  ------------------
							 */
							if( is_single() ){

								$output 		.=		self:: sdweddingdirectory_image( array(

															'media_id'		=>		absint( 

																						/**
																						 *  1. Get Post Thumbnails ID
																						 *  -------------------------
																						 */
																						get_post_thumbnail_id( absint( $post_id ) )
																					),

															'image_size'	=>		esc_attr(  $image_size  ),

															'image_class'	=>		array(

																						'img-fluid', 'rounded', 

																						'wp-post-image'
																					)
														) );
							}else{

								$output 		.=		sprintf(   '<a href="%1$s" title="%2$s"> %4$s %3$s </a>',

																	/**
																	 *  1. Get The Post Link
																	 *  --------------------
																	 */
																	esc_url( get_the_permalink( absint( $post_id ) ) ),

																	/**
																	 *  2. Anchore Title
																	 *  ----------------
																	 */
																	esc_attr( get_the_title( absint( $post_id ) ) ),

																	/**
																	 *  3. Load Thumbnail
																	 *  -----------------
																	 */
																	self:: sdweddingdirectory_image( array(

																		'media_id'		=>		absint( 

																									/**
																									 *  1. Get Post Thumbnails ID
																									 *  -------------------------
																									 */
																									get_post_thumbnail_id( absint( $post_id ) )
																								),

																		'image_size'	=>		esc_attr(  $image_size  ),

																		'image_class'	=>		array(

																									'img-fluid', 'rounded', 

																									'wp-post-image', 'w-100' 
																								)

																	) ),

																	/**
																	 *  4. Have Mouse Over Effect ?
																	 *  ---------------------------
																	 */
																	( $image_size == 	esc_attr( 'sdweddingdirectory_img_600x470' ) )

																	?	   '<div class="img-hover">

																				<i class="fa fa-plus"></i>

																			</div>'

																	: 		''
														);
							}

					/**
					 *  If Have Post Then End Div Added
					 *  -------------------------------
					 */
					$output 	.=		'</div></div>';

					/**
					 *  Return : Featured Image HTML Content
					 *  ------------------------------------
					 */
					return 		$output;
				}
			}
		}

		/**
		 *  Post Format : Gallery ?
		 *  -----------------------
		 */ 
		public static function is_gallery_post( $args = [] ){

			/**
			 *  Have Args ?
			 *  -----------
			 */
			if(  parent:: _is_array( $args )  ){

				$output 	= 		'';

				/**
				 *  Merge Default Args if not found
				 *  -------------------------------
				 */
				$args 		=		wp_parse_args(  $args,  array(

										'post_id'		=>		absint( '0' ),

										'image_size'	=>		esc_attr( 'sdweddingdirectory_img_600x470' ),

									)   );

				/**
				 *  Extract Args
				 *  ------------
				 */
				extract( $args );

				/**
				 *  If Post not empty!
				 *  ------------------
				 */
				if(  empty( $post_id )  ){

					return;
				}

				/**
				 *  Get Gallery Data ?
				 *  ------------------
				 */
				$_have_gallery 		=		esc_attr(  get_post_meta(

													/**
													 *  1. Post ID
													 *  ----------
													 */
													absint( $post_id ),

													/**
													 *  2. Meta Key
													 *  -----------
													 */
													sanitize_key( 'gallery_meta' ), 

													/**
													 *  3. TRUE
													 *  -------
													 */
													true
											)  );

				$_condition_1 		=		$_have_gallery !== '' && $_have_gallery !== NULL && ! empty( $_have_gallery );

				$_condition_2 		=		$_condition_1 && parent:: _is_array( preg_split ("/\,/", $_have_gallery ) );

				/**
				 *  Have Gallery ?
				 *  --------------
				 */
				if(  $_condition_1 && $_condition_2  ){

					/**
					 *  Carousel Start
					 *  --------------
					 */
					$output 	.=	'<div class="post-img post-type-slider mb-4">

										<div class="sdweddingdirectory-post-slider owl-carousel owl-theme">';

						/**
						 *  Is Singular Page ?
						 *  ------------------
						 */
						foreach ( preg_split ("/\,/", $_have_gallery ) as $media_id ) {

							$output 	.=

							sprintf(   '<!-- About Slider Images -->
	                                    <div class="item">

	                                        <div class="post-type-slider"> %3$s </div>

	                                    </div>
	                                    <!-- About Slider Images -->',

										/**
										 *  1. Post Singular Page link
										 *  --------------------------
										 */
										esc_url( get_the_permalink( absint( $post_id ) ) ),

										/**
										 *  2. Post Title
										 *  -------------
										 */
										esc_attr( get_the_title( absint( $post_id ) ) ),

										/**
										 *  3. Get Image
										 *  ------------
										 */
										self:: sdweddingdirectory_image( array(

											'media_id'		=>		absint( 

																		/**
																		 *  1. Media ID
																		 *  -----------
																		 */
																		$media_id
																	),

											'image_size'	=>		esc_attr(  $image_size  )

										) )
							);
						}

					/**
					 *  Carousel End
					 *  ------------
					 */
					$output 	.= 		'</div></div>';

					/**
					 *  Return Gallery HTML
					 *  -------------------
					 */
					return   	$output;
				}
			}
		}

		/**
		 *  Post Format : Video ?
		 *  ---------------------
		 */ 
		public static function is_video_post( $args = [] ){

			/**
			 *  Have Args ?
			 *  -----------
			 */
			if(  parent:: _is_array( $args )  ){

				$output 	= 		'';

				/**
				 *  Merge Default Args if not found
				 *  -------------------------------
				 */
				$args 		=		wp_parse_args(  $args,  array(

										'post_id'		=>		absint( '0' ),

									)   );
				/**
				 *  Extract Args
				 *  ------------
				 */
				extract( $args );

				/**
				 *  If Post not empty!
				 *  ------------------
				 */
				if(  empty( $post_id )  ){

					return;
				}

				/**
				 *  Have Video Meta Value ?
				 *  -----------------------
				 */
				$_have_video 	=	esc_attr( get_post_meta(

										/**
										 *  1. Post ID
										 *  ----------
										 */
										absint( $post_id ), 

										/**
										 *  2. Meta Key
										 *  -----------
										 */
										sanitize_key( 'video_meta' ), 

										/**
										 *  3. TRUE
										 *  -------
										 */
										true

									)  );

				/**
				 *  Have Video Post ?
				 *  -----------------
				 */
				if (  parent:: _have_data( $_have_video )  ) {

					$output 	.=		sprintf(   '<div class="sdweddingdirectory-post-formate ratio ratio-16x9 mb-4">

														%1$s

													</div>',

											/**
											 *  1. Load Video With embed
											 *  ------------------------
											 */
											self:: sdweddingdirectory_video_embed(

												wp_oembed_get( $_have_video )
											)
										);
					/**
					 *  Return : Video Post Format
					 *  --------------------------
					 */
					return  		$output;
				}
			}
		}

		/**
		 *  Post Format : Audio ?
		 *  ---------------------
		 */ 
		public static function is_audio_post( $args = [] ){

			/**
			 *  Have Args ?
			 *  -----------
			 */
			if(  parent:: _is_array( $args )  ){

				$output 	= 		'';

				/**
				 *  Merge Default Args if not found
				 *  -------------------------------
				 */
				$args 		=		wp_parse_args(  $args,  array(

										'post_id'		=>		absint( '0' ),

									)   );
				/**
				 *  Extract Args
				 *  ------------
				 */
				extract( $args );

				/**
				 *  If Post not empty!
				 *  ------------------
				 */
				if(  empty( $post_id )  ){

					return;
				}

				/**
				 *  Have Video Meta Value ?
				 *  -----------------------
				 */
				$_audio_video 	=	esc_attr( get_post_meta(

										/**
										 *  1. Post ID
										 *  ----------
										 */
										absint( $post_id ), 

										/**
										 *  2. Meta Key
										 *  -----------
										 */
										sanitize_key( 'audio_meta' ), 

										/**
										 *  3. TRUE
										 *  -------
										 */
										true

									)  );

				/**
				 *  Have Video Post ?
				 *  -----------------
				 */
				if (  parent:: _have_data( $_audio_video )  ) {

					$output 	.=		sprintf(   '<div class="sdweddingdirectory-post-formate ratio ratio-16x9 mb-4">

														%1$s

													</div>',

											/**
											 *  1. Load Video With embed
											 *  ------------------------
											 */
											self:: sdweddingdirectory_video_embed(

												wp_oembed_get( $_audio_video )
											)
										);
					/**
					 *  Return : Video Post Format
					 *  --------------------------
					 */
					return  		$output;
				}
			}
		}

		/**
		 *  Post Formate
		 *  ------------
		 */
		public static function post_formate( $args = [] ){

			/**
			 *  Is Array ?
			 *  ----------
			 */
			if( parent:: _is_array( $args ) ){

				$_output  	=		'';

				/**
				 *  Merge Default Args if not found
				 *  -------------------------------
				 */
				$args 		=		wp_parse_args(  $args,  array(

										'post_id'		=>		absint( '0' ),

										'image_size'	=>		esc_attr( 'sdweddingdirectory_img_600x470' ),

										'post_format'	=> 		esc_attr( get_post_format() )

									)   );

				/**
				 *  Extract Args
				 *  ------------
				 */
				extract( $args );

				/**
				 *  If Post not empty!
				 *  ------------------
				 */
				if(  empty( $post_id )  ){

					return;
				}

				/**
				 *  If post format not found to set default : image
				 *  -----------------------------------------------
				 */
				if( $post_format === false ) {

					$post_format 	= 	esc_attr( 'image' );
				}

				/**
				 *  Check With Format 
				 *  -----------------
				 */
				switch ( $post_format ) {

					/**
					 *  Is Image ?
					 *  ----------
					 */
					case 	esc_attr( 'image' ) :

							$_output 	.=		self:: is_image_post( $args );

					break;

					/**
					 *  Is Gallery ?
					 *  ------------
					 */
					case 	esc_attr( 'gallery' ):

							$_output 	.=		self:: is_gallery_post( $args );

					break;

					/**
					 *  Is Video Format ?
					 *  -----------------
					 */
					case 	esc_attr( 'video' )  :

							$_output 	.=		self:: is_video_post( $args );

					break;

					/**
					 *  Have Audio ?
					 *  ------------
					 */
					case 'audio':

							$_output 	.=		self:: is_audio_post( $args );

					break;

					/**
					 *  Default
					 *  -------
					 */
					default: 		  	

							$_output 	.=		self:: is_image_post( $args );

					break;
				}

				/**  
				 *   SDWeddingDirectory - Post Format Custom HTML Return
				 *   -------------------------------------------
				 */
				return 		apply_filters( 'post_formate', $_output );

			}
		}

		public static function sdweddingdirectory_video_embed( $embed_code ){

			$embed_code = str_replace('webkitallowfullscreen','',$embed_code);
			$embed_code = str_replace('mozallowfullscreen','',$embed_code);
			$embed_code = str_replace('frameborder="0"','',$embed_code);
			$embed_code = str_replace('frameborder="no"','',$embed_code);
			$embed_code = str_replace('scrolling="no"','',$embed_code);
			$embed_code = str_replace('&','&amp;',$embed_code);
			
			return $embed_code;
		}

		/**
		 *  ------------------------------------------------
		 *  SDWeddingDirectory - Next and Prev Link on Singular page
		 *  ------------------------------------------------
		 *  @credit - https://developer.wordpress.org/reference/functions/get_adjacent_post/
		 *  --------------------------------------------------------------------------------
		 */
		public static function sdweddingdirectory_single_post_link( $args = [] ){

			/**
			 *  Have Args ?
			 *  -----------
			 */
			if( parent:: _is_array( $args ) ){

				/**
				 *  Extract Args
				 *  ------------
				 */
				extract( wp_parse_args( $args, [

					'post_id'		=>		absint( '0' ),

				] ) );

				/**
				 *  Make sure it's not emtpy!
				 *  -------------------------
				 */
				if( empty( $post_id ) ){

					return;
				}

				/**
				 *  SDWeddingDirectory - Prev Post
				 *  ----------------------
				 */
				$sdweddingdirectory_prev_post 	= 	get_adjacent_post( false, '', true );

				/**
				 *  SDWeddingDirectory - Next Post
				 *  ----------------------
				 */
				$sdweddingdirectory_next_post 	= 	get_adjacent_post( false, '', false );

				/**
				 *  If Next or Prev post is available ?
				 *  -----------------------------------
				 */

				if( ! empty( $sdweddingdirectory_prev_post ) || ! empty( $sdweddingdirectory_next_post ) ) {

					/**
					 *  Load : Next | Prev Section
					 *  --------------------------
					 */
					$_get_prev_section 	= 	$_get_next_section 	= 	'';

		    	    /** 
		    	     *   SDWeddingDirectory - Previous Post Title + Link
		    	     *   ---------------------------------------
		    	     */
			        if( ! 	empty( $sdweddingdirectory_prev_post ) 	){

			        	$_get_prev_section 	=

						sprintf('	<div class="previous-post">

										<a href="%1$s" class="prev-link btn-default-link" title="%2$s">

											<small> %3$s </small>

										</a>

										<h3 class="prev-link-title">

											<a href="%1$s" title="%2$s" class="title"> %2$s </a>

										</h3>

									</div>',

								/**
								 *  1. Sanitize Link
								 *  ----------------
								 */
								esc_url( 

									/**
									 *  Get Permalink
									 *  -------------
									 */
									get_permalink( 

										/**
										 *  Post ID
										 *  -------
										 */
										absint( $sdweddingdirectory_prev_post->ID ) 
									)
								),

								/**
								 *  2. Sanitize Post Title
								 *  ----------------------
								 */
								esc_attr( 

									/**
									 *  Post Title
									 *  ----------
									 */
									get_the_title( 

										/**
										 *  Post ID
										 *  -------
										 */
										absint( $sdweddingdirectory_prev_post->ID ) 
									) 
								),

								/**
								 *  3. Translation Ready String
								 *  ---------------------------
								 */
								esc_attr__( 'Previous Post', 'sdweddingdirectory' )
						);
			        }

		    	    /** 
		    	     *   SDWeddingDirectory - Next Post Title + Link
		    	     *   -----------------------------------
		    	     */
			        if( ! 	empty( $sdweddingdirectory_next_post ) 	){

			        	$_get_next_section 	=

						sprintf('	<div class="next-post">

										<a href="%1$s" class="next-link btn-default-link text-end" title="%2$s">

											<small> %3$s </small>

										</a>

										<h3 class="next-link-title">

											<a href="%1$s" title="%2$s" class="title"> %2$s </a>

										</h3>

									</div>',

								/**
								 *  1. Sanitize Link
								 *  ----------------
								 */
								esc_url( 

									/**
									 *  Get Permalink
									 *  -------------
									 */
									get_permalink( 

										/**
										 *  Post ID
										 *  -------
										 */
										absint( $sdweddingdirectory_next_post->ID ) 
									)
								),

								/**
								 *  2. Sanitize Post Title
								 *  ----------------------
								 */
								esc_attr( 

									/**
									 *  Post Title
									 *  ----------
									 */
									get_the_title( 

										/**
										 *  Post ID
										 *  -------
										 */
										absint( $sdweddingdirectory_next_post->ID ) 
									) 
								),

								/**
								 *  3. Translation Ready String
								 *  ---------------------------
								 */
								esc_attr__( 'Next Post', 'sdweddingdirectory' )
						);
			        }

			        /**
			         *  Load : SDWeddingDirectory - Next & Prev Section
			         *  ---------------------------------------
			         */
			        printf( '<div class="sdweddingdirectory-section-post-next-prev post-linking %3$s %4$s">%1$s %2$s </div>',

			        		/**
			        		 *  1. Prev Section
			        		 *  ---------------
			        		 */
			        		$_get_prev_section,
			        		
			        		/**
			        		 *  2. Next Section
			        		 *  ---------------
			        		 */
			        		$_get_next_section,

			        		/**
			        		 *  3. Have Pre Post ?
			        		 *  ------------------
			        		 */
			        		isset( $_get_prev_section ) && empty( $_get_prev_section )

			        		?	sanitize_html_class( 'prev_post_empty' )

			        		:	'',

			        		/**
			        		 *  4. Have Next Post ?
			        		 *  -------------------
			        		 */
			        		isset( $_get_next_section ) && empty( $_get_next_section )

			        		?	sanitize_html_class( 'next_post_empty' )

			        		:	''
			    	);
				}
			}
		}

		/**
		 *  Read More Button
		 *  ----------------
		 */
		public static function read_more( $args = [] ){

			if( parent:: _is_array( $args ) ){

				$_args 		=	wp_parse_args( $args, array(

									'post_id'		=>		absint( '0' ),

									'layout'		=>		absint( '1' )

								) );
				/**
				 *  Extract Args
				 *  ------------
				 */
				extract(  $_args  );

				/**
				 *  Have Post ?
				 *  -----------
				 */
				if( empty( $post_id ) ){

					return;
				}

				/**
				 *  Translation Ready String
				 *  ------------------------
				 */
				$_read_more 	=	esc_attr__( 'Read More', 'sdweddingdirectory' );

				/**
				 *  layout 1
				 *  --------
				 */
				if( $layout == absint( '1' ) ){

					return

					sprintf( '	<div class="read-more">

									<a href="%1$s" class="btn btn-link %2$s"> %3$s </a>

								</div>', 

								/**
								 *  1. Link
								 *  -------
								 */
								esc_url( get_the_permalink( absint( $post_id ) ) ),

								/**
								 *  2. class
								 *  --------
								 */
								sanitize_html_class( 'btn-link-primary' ),

								/**
								 *  3. Read More
								 *  ------------
								 */
								esc_attr( $_read_more )
					);
				}

				/**
				 *  layout 2
				 *  --------
				 */
				if( $layout == absint( '2' ) ){

					return

					sprintf( '	<div class="read-more">

									<a href="%1$s" class="btn btn-link %2$s"> %3$s </a>

								</div>',

								/**
								 *  1. Link
								 *  -------
								 */
								esc_url( get_the_permalink( absint( $post_id ) ) ),

								/**
								 *  2. class
								 *  --------
								 */
								sanitize_html_class( 'btn-link-default' ),

								/**
								 *  3. Read More
								 *  ------------
								 */
								esc_attr( $_read_more )
					);
				}
			}
		}
	}

	/**
	 *  SDWeddingDirectory - Blog Post Helper
	 *  -----------------------------
	 */
	SDWeddingDirectory_Blog_Helper::get_instance();
}
