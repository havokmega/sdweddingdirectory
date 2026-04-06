<?php
/**
 *  SDWeddingDirectory - Post Page Template ( index.php )
 *  ---------------------------------------------
 */
global $wp_query, $post, $page;

$is_inspiration_blog_index = is_home() && ! is_front_page();

if ( $is_inspiration_blog_index && class_exists( 'SDWeddingDirectory_Page_Header_Banner' ) ) {
	remove_action(
		'sdweddingdirectory/page-header-banner',
		[ SDWeddingDirectory_Page_Header_Banner::get_instance(), 'sdweddingdirectory_default_page_header' ],
		absint( '10' )
	);
}

/**
 *  Current Page
 *  ------------
 */
$paged 	= 	get_query_var( 'paged' ) 

		? 	absint( get_query_var( 'paged' ) ) 

		: 	absint( '1' );

/**
 *  Container Start
 *  ---------------
 */
do_action( 'sdweddingdirectory_main_container' );

if ( $is_inspiration_blog_index ) {

	$inspiration_link_items = [];
	$menu_locations         = get_nav_menu_locations();

	if ( ! empty( $menu_locations['primary-menu'] ) ) {

		$menu_items = wp_get_nav_menu_items( absint( $menu_locations['primary-menu'] ) );
		$links_id   = absint( '0' );

		if ( is_array( $menu_items ) && ! empty( $menu_items ) ) {

			foreach ( $menu_items as $menu_item ) {
				$classes = is_array( $menu_item->classes ) ? $menu_item->classes : [];

				if ( in_array( 'sd-mega-group', $classes, true ) && in_array( 'sd-mega-links', $classes, true ) ) {
					$links_id = absint( $menu_item->ID );
					break;
				}
			}

			if ( ! empty( $links_id ) ) {
				foreach ( $menu_items as $menu_item ) {
					if ( absint( $menu_item->menu_item_parent ) !== $links_id ) {
						continue;
					}

					$slug = '';
					$path = wp_parse_url( $menu_item->url, PHP_URL_PATH );

					if ( is_string( $path ) && $path !== '' ) {
						$path_parts = array_values( array_filter( explode( '/', trim( $path, '/' ) ) ) );
						$slug       = ! empty( $path_parts ) ? sanitize_title( end( $path_parts ) ) : '';
					}

					$inspiration_link_items[] = [
						'title'      => $menu_item->title,
						'url'        => $menu_item->url,
						'slug'       => $slug,
						'menu_order' => absint( $menu_item->menu_order ),
					];
				}
			}
		}
	}

	if ( empty( $inspiration_link_items ) ) {
		$inspiration_link_items = [
			[ 'title' => esc_html__( 'Wedding Planning How To', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-inspiration/wedding-planning-how-to/' ), 'slug' => 'wedding-planning-how-to', 'menu_order' => 1 ],
			[ 'title' => esc_html__( 'Wedding Ceremony', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-inspiration/wedding-ceremony/' ), 'slug' => 'wedding-ceremony', 'menu_order' => 2 ],
			[ 'title' => esc_html__( 'Wedding Reception', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-inspiration/wedding-reception/' ), 'slug' => 'wedding-reception', 'menu_order' => 3 ],
			[ 'title' => esc_html__( 'Wedding Services', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-inspiration/wedding-services/' ), 'slug' => 'wedding-services', 'menu_order' => 4 ],
			[ 'title' => esc_html__( 'Wedding Fashion', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-inspiration/wedding-fashion/' ), 'slug' => 'wedding-fashion', 'menu_order' => 5 ],
			[ 'title' => esc_html__( 'Hair & Makeup', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-inspiration/hair-makeup/' ), 'slug' => 'hair-makeup', 'menu_order' => 6 ],
			[ 'title' => esc_html__( 'Destination Weddings', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-inspiration/destination-weddings/' ), 'slug' => 'destination-weddings', 'menu_order' => 7 ],
			[ 'title' => esc_html__( 'Married Life', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-inspiration/married-life/' ), 'slug' => 'married-life', 'menu_order' => 8 ],
			[ 'title' => esc_html__( 'Events & Parties', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-inspiration/events-parties/' ), 'slug' => 'events-parties', 'menu_order' => 9 ],
			[ 'title' => esc_html__( 'Family & Friends', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-inspiration/family-friends/' ), 'slug' => 'family-friends', 'menu_order' => 10 ],
		];
	}

	usort( $inspiration_link_items, function( $left, $right ) {
		return absint( $left['menu_order'] ) <=> absint( $right['menu_order'] );
	} );

	$inspiration_link_items = array_slice( $inspiration_link_items, 0, 10 );

	$image_map = [
		'wedding-planning-how-to' => 'wedding-planning-how-to.jpg',
		'wedding-ceremony'        => 'wedding-ceremony.jpg',
		'wedding-reception'       => 'wedding-reception.jpg',
		'wedding-services'        => 'wedding-services.jpg',
		'wedding-fashion'         => 'wedding-fashion.jpg',
		'hair-makeup'             => 'hair-makeup.jpg',
		'destination-weddings'    => 'honeymoon-advice.jpg',
		'married-life'            => 'trends-and-tips.jpg',
		'events-parties'          => 'legal-paperwork.jpg',
		'family-friends'          => 'budget.jpg',
	];

	$fallback_images = [
		'wedding-planning-how-to.jpg',
		'wedding-ceremony.jpg',
		'wedding-reception.jpg',
		'wedding-services.jpg',
		'wedding-fashion.jpg',
		'hair-makeup.jpg',
		'honeymoon-advice.jpg',
		'trends-and-tips.jpg',
		'legal-paperwork.jpg',
		'budget.jpg',
	];
	?>
	<section class="sd-inspiration-index-intro" aria-labelledby="sd-inspiration-index-title">
		<nav class="sdwd-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'sdweddingdirectory' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Wedding', 'sdweddingdirectory' ); ?></a>
			<span class="sdwd-breadcrumb-sep" aria-hidden="true"> / </span>
			<span><?php esc_html_e( 'Wedding Inspiration', 'sdweddingdirectory' ); ?></span>
		</nav>

		<h1 id="sd-inspiration-index-title" class="sd-inspiration-index-title"><?php esc_html_e( 'Wedding Inspiration', 'sdweddingdirectory' ); ?></h1>

		<p class="sd-inspiration-index-copy">
			<?php esc_html_e( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'sdweddingdirectory' ); ?>
		</p>

		<form class="sd-inspiration-index-search" method="get" action="<?php echo esc_url( home_url( '/wedding-inspiration/' ) ); ?>" role="search">
			<div class="sd-inspiration-index-search-input-wrap">
				<span class="sd-inspiration-index-search-icon" aria-hidden="true"><i class="fa fa-search"></i></span>
				<label class="screen-reader-text" for="sd-inspiration-article-search"><?php esc_html_e( 'Search SDWeddingDirectory Articles', 'sdweddingdirectory' ); ?></label>
				<input
					id="sd-inspiration-article-search"
					class="sd-inspiration-index-search-input"
					type="search"
					name="s"
					value="<?php echo esc_attr( get_search_query() ); ?>"
					placeholder="<?php esc_attr_e( 'Search SDWeddingDirectory Articles', 'sdweddingdirectory' ); ?>"
				/>
				<input type="hidden" name="post_type" value="post" />
			</div>
			<button type="submit" class="sd-inspiration-index-search-button"><?php esc_html_e( 'Search', 'sdweddingdirectory' ); ?></button>
		</form>

		<div class="sd-inspiration-index-categories">
			<?php foreach ( $inspiration_link_items as $index => $item ) : ?>
				<?php
					$image_file = isset( $image_map[ $item['slug'] ] ) ? $image_map[ $item['slug'] ] : $fallback_images[ $index % count( $fallback_images ) ];
					$image_url  = get_theme_file_uri( 'assets/images/blog/' . $image_file );
				?>
				<a class="sd-inspiration-index-category" href="<?php echo esc_url( $item['url'] ); ?>">
					<span class="sd-inspiration-index-category-media">
						<img loading="lazy" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" />
					</span>
					<span class="sd-inspiration-index-category-label"><?php echo esc_html( $item['title'] ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="sd-inspiration-grid-5-section">
		<?php get_template_part( 'template-parts/blog/blog-grid-5' ); ?>
	</section>

	<section class="sd-inspiration-list-with-sidebar-section">
		<?php get_template_part( 'template-parts/blog/blog-list-with-sidebar' ); ?>
	</section>
	<?php
}

if ( ! $is_inspiration_blog_index ) {

	/**
	 *  Have Post ?
	 *  -----------
	 */
	if ( have_posts() ){

		/**
		 *  Load Post One by One
		 *  --------------------
		 */
	   	while ( have_posts() ) {  the_post();

	   		/**
	   		 *  Load Article
	   		 *  ------------
	   		 */
	   		do_action( 'sdweddingdirectory_article', array(

				'layout'	=>	absint( '1' ),

				'post_id'	=>	absint( get_the_ID() )

			) );
		}

		/**
		 *  Reset WP Query
		 *  --------------
		 */
		if( isset( $wp_query ) ){

			wp_reset_postdata();	
		}

	    /**
	     *  Create Pagination
	     *  -----------------
	     */
	    print       apply_filters( 'sdweddingdirectory/pagination', [

	                    'numpages'      =>  absint( $wp_query->max_num_pages ),

	                    'paged'         =>  absint( $paged )

	                ] );

	}else{

		/**
		 *  Article Not Found!
		 *  ------------------
		 */
		do_action( 'sdweddingdirectory_empty_article' );
	}
}

/**
 *  Container End
 *  -------------
 */
do_action( 'sdweddingdirectory_main_container_end' ); ?>
