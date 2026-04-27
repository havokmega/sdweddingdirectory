<?php
/**
 * Template Name: Vendors
 * Page Template: Vendors Landing
 *
 * Marketing/discovery page for wedding vendors.
 * NOT a search results page — that is handled by archive-vendor.php and taxonomy templates.
 */

get_header();

// --- Data setup ---

$vendor_categories = sdwdv2_get_vendor_category_terms( [
	'hide_empty' => false,
	'orderby'    => 'name',
	'order'      => 'ASC',
] );

$location_terms = get_terms( [
	'taxonomy'   => 'venue-location',
	'hide_empty' => true,
	'parent'     => 0,
	'orderby'    => 'name',
	'order'      => 'ASC',
] );
if ( is_wp_error( $location_terms ) ) {
	$location_terms = [];
}

// Top categories for the carousel (sorted by count, top 12)
$carousel_categories = sdwdv2_get_vendor_category_terms( [
	'hide_empty' => true,
	'orderby'    => 'count',
	'order'      => 'DESC',
	'number'     => 12,
] );

// Featured category sections — show vendor cards grouped by category
$featured_category_slugs = [
	'photography',
	'djs',
	'catering',
	'flowers',
	'videography',
];

$featured_sections = [];
foreach ( $featured_category_slugs as $cat_slug ) {
	$term = get_term_by( 'slug', $cat_slug, 'vendor-category' );
	if ( $term instanceof WP_Term ) {
		$featured_sections[] = $term;
	}
}

// Vendor category link rows
$category_link_items = [];
foreach ( $vendor_categories as $cat ) {
	$category_link_items[] = [
		'label' => $cat->name,
		'url'   => sdwdv2_get_vendor_category_url( $cat ),
	];
}
$category_link_rows = array_chunk( $category_link_items, 5 );

// SEO text columns
$seo_columns = [
	[
		'title' => __( 'Finding Your Wedding Vendors', 'sandiegoweddingdirectory' ),
		'text'  => __( 'Start by booking the essentials — photographer, DJ, and caterer — then fill in the rest based on your vision and budget. San Diego has a deep pool of experienced wedding professionals ready to bring your day to life.', 'sandiegoweddingdirectory' ),
	],
	[
		'title' => __( 'How to Choose the Right Vendor', 'sandiegoweddingdirectory' ),
		'text'  => __( 'Read reviews, compare pricing, and check availability early. The best vendors book up fast, especially during peak wedding season. Reach out to several in each category to find the right fit for your style and budget.', 'sandiegoweddingdirectory' ),
	],
	[
		'title' => __( 'Working with Local Pros', 'sandiegoweddingdirectory' ),
		'text'  => __( 'San Diego wedding vendors know the local venues, the best light for photos, and the logistics that make events run smoothly. Hiring local means faster response times, lower travel costs, and professionals who understand the area.', 'sandiegoweddingdirectory' ),
	],
];

?>

<div class="vendors-landing">

	<!-- SECTION 1: Compact Search Hero -->
	<section class="search-hero" style="--search-hero-image: url('<?php echo esc_url( get_template_directory_uri() . '/assets/images/banners/vendors-search.png' ); ?>');">
		<div class="container search-hero__inner">
			<?php
			get_template_part( 'template-parts/components/breadcrumbs', null, [
				'items' => [
					[ 'label' => __( 'Weddings', 'sandiegoweddingdirectory' ), 'url' => home_url( '/' ) ],
					[ 'label' => __( 'Wedding Vendors', 'sandiegoweddingdirectory' ) ],
				],
			] );
			?>
			<h1 class="search-hero__title"><?php esc_html_e( 'Wedding Vendors', 'sandiegoweddingdirectory' ); ?></h1>

			<?php
			get_template_part( 'template-parts/components/hero-search', null, [
				'default_mode' => 'vendors',
			] );
			?>
		</div>
	</section>

	<!-- SECTION 2: Browse by Category (carousel) -->
	<?php if ( ! empty( $carousel_categories ) ) : ?>
		<section class="section vendors-discovery">
			<div class="container">
				<?php
				get_template_part( 'template-parts/components/section-title', null, [
					'heading' => __( 'Browse Wedding Vendors by Category', 'sandiegoweddingdirectory' ),
				] );
				?>

				<div class="vendors-carousel" data-scroll-carousel>
					<button class="carousel-arrow carousel-arrow--prev vendors-carousel__arrow hidden" data-scroll-prev type="button">
						<span class="icon-chevron-left carousel-arrow__icon" aria-hidden="true"></span>
						<span class="screen-reader-text"><?php esc_html_e( 'Previous categories', 'sandiegoweddingdirectory' ); ?></span>
					</button>

					<div class="vendors-carousel__track" data-scroll-track>
						<?php foreach ( $carousel_categories as $category ) : ?>
							<a class="vendors-category-card" href="<?php echo esc_url( sdwdv2_get_vendor_category_url( $category ) ); ?>">
								<span class="vendors-category-card__media">
									<img loading="lazy" decoding="async" src="<?php echo esc_url( sdwdv2_get_vendor_category_image_url( $category ) ); ?>" alt="<?php echo esc_attr( $category->name ); ?>">
								</span>
								<span class="vendors-category-card__body">
									<strong class="vendors-category-card__title"><?php echo esc_html( $category->name ); ?></strong>
									<span class="vendors-category-card__count">
										<?php
										echo esc_html(
											sprintf(
												_n( '%s vendor', '%s vendors', $category->count, 'sandiegoweddingdirectory' ),
												number_format_i18n( $category->count )
											)
										);
										?>
									</span>
								</span>
							</a>
						<?php endforeach; ?>
					</div>

					<button class="carousel-arrow carousel-arrow--next vendors-carousel__arrow" data-scroll-next type="button">
						<span class="icon-chevron-left carousel-arrow__icon" aria-hidden="true"></span>
						<span class="screen-reader-text"><?php esc_html_e( 'Next categories', 'sandiegoweddingdirectory' ); ?></span>
					</button>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	$popout_headings = [
		'photography' => __( 'San Diego Photographers', 'sandiegoweddingdirectory' ),
		'djs'         => __( 'San Diego DJs', 'sandiegoweddingdirectory' ),
		'catering'    => __( 'San Diego Caterers', 'sandiegoweddingdirectory' ),
		'flowers'     => __( 'San Diego Florists', 'sandiegoweddingdirectory' ),
		'videography' => __( 'San Diego Videographers', 'sandiegoweddingdirectory' ),
	];
	?>

	<!-- SECTION 3: Vendor Popout -->
	<?php $photography_term = get_term_by( 'slug', 'photography', 'vendor-category' ); ?>
	<section class="section vendors-popout-section">
		<div class="container">
			<?php
			get_template_part( 'template-parts/components/vendor-popout', null, [
				'heading'   => $popout_headings['photography'],
				'desc'      => __( 'SDWeddingDirectory connects you with trusted local wedding professionals across San Diego County.', 'sandiegoweddingdirectory' ),
				'image'     => get_theme_file_uri( 'assets/images/banners/vendors-search.png' ),
				'image_alt' => $popout_headings['photography'],
				'cta_text'  => __( 'See all', 'sandiegoweddingdirectory' ),
				'cta_url'   => $photography_term ? sdwdv2_get_vendor_category_url( $photography_term ) : home_url( '/vendors/' ),
			] );
			?>
		</div>
	</section>

	<!-- SECTION 4: Vendor cards per category -->
	<?php foreach ( $featured_sections as $cat_term ) :
		$cat_query = new WP_Query( [
			'post_type'      => 'vendor',
			'post_status'    => 'publish',
			'posts_per_page' => 4,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'tax_query'      => [
				[
					'taxonomy' => 'vendor-category',
					'field'    => 'term_id',
					'terms'    => [ $cat_term->term_id ],
				],
			],
		] );
		?>

		<?php if ( $cat_term->slug !== 'photography' && isset( $popout_headings[ $cat_term->slug ] ) ) : ?>
			<section class="section vendors-popout-section">
				<div class="container">
					<?php
					get_template_part( 'template-parts/components/vendor-popout', null, [
						'heading'   => $popout_headings[ $cat_term->slug ],
						'desc'      => __( 'SDWeddingDirectory connects you with trusted local wedding professionals across San Diego County.', 'sandiegoweddingdirectory' ),
						'image'     => get_theme_file_uri( 'assets/images/banners/vendors-search.png' ),
						'image_alt' => $popout_headings[ $cat_term->slug ],
						'cta_text'  => __( 'See all', 'sandiegoweddingdirectory' ),
						'cta_url'   => sdwdv2_get_vendor_category_url( $cat_term ),
					] );
					?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $cat_query->have_posts() ) : ?>
			<section class="section vendors-category-cards">
				<div class="container">
					<div class="grid grid--4col">
						<?php
						while ( $cat_query->have_posts() ) :
							$cat_query->the_post();
							get_template_part( 'template-parts/components/vendor-card', null, [
								'post_id'    => get_the_ID(),
								'image_size' => 'medium',
							] );
						endwhile;
						wp_reset_postdata();
						?>
					</div>

					<div class="vendors-category-cards__cta">
						<a class="btn btn--outline" href="<?php echo esc_url( sdwdv2_get_vendor_category_url( $cat_term ) ); ?>">
							<?php
							echo esc_html(
								sprintf(
									/* translators: %s: category name */
									__( 'See all %s', 'sandiegoweddingdirectory' ),
									$cat_term->name
								)
							);
							?>
						</a>
					</div>
				</div>
			</section>
		<?php endif; ?>

	<?php endforeach; ?>

	<!-- SECTION 5: SEO text columns -->
	<section class="section vendors-seo">
		<div class="container">
			<?php
			get_template_part( 'template-parts/components/section-title', null, [
				'heading' => __( 'San Diego Wedding Vendors', 'sandiegoweddingdirectory' ),
				'desc'    => __( 'From photographers and DJs to florists and planners — find every wedding professional you need in one place.', 'sandiegoweddingdirectory' ),
			] );
			?>

			<div class="vendors-seo__columns">
				<?php foreach ( $seo_columns as $col ) : ?>
					<div class="vendors-seo__col">
						<h3 class="vendors-seo__col-title"><?php echo esc_html( $col['title'] ); ?></h3>
						<p class="vendors-seo__col-text"><?php echo esc_html( $col['text'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- SECTION 6: Category button row -->
	<section class="section vendors-type-buttons">
		<div class="container">
			<?php
			get_template_part( 'template-parts/components/section-title', null, [
				'heading' => __( 'Every Vendor you need for your Wedding', 'sandiegoweddingdirectory' ),
			] );
			?>
			<div class="vendors-button-row">
				<?php foreach ( $vendor_categories as $cat ) : ?>
					<a class="btn btn--outline" href="<?php echo esc_url( sdwdv2_get_vendor_category_url( $cat ) ); ?>">
						<?php echo esc_html( $cat->name ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- SECTION 7: All Vendor Categories (link grid) -->
	<?php if ( ! empty( $category_link_rows ) ) : ?>
		<section class="section vendors-category-links">
			<div class="container">
				<?php
				get_template_part( 'template-parts/components/inline-link-grid', null, [
					'heading' => __( 'All Vendor Categories', 'sandiegoweddingdirectory' ),
					'rows'    => $category_link_rows,
				] );
				?>
			</div>
		</section>
	<?php endif; ?>

</div>

<?php
get_footer();
