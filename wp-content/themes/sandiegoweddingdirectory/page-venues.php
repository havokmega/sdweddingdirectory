<?php
/**
 * Template Name: Venues
 * Page Template: Venues Landing
 *
 * Marketing/discovery page for wedding venues.
 * NOT a search results page — that is handled by archive-venue.php and taxonomy templates.
 */

get_header();

// --- Data setup ---

$venue_types = get_terms( [
	'taxonomy'   => 'venue-type',
	'hide_empty' => true,
	'orderby'    => 'name',
	'order'      => 'ASC',
] );
if ( is_wp_error( $venue_types ) ) {
	$venue_types = [];
}

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

$city_terms = get_terms( [
	'taxonomy'   => 'venue-location',
	'hide_empty' => false,
	'parent'     => 0,
	'orderby'    => 'name',
	'order'      => 'ASC',
] );
if ( is_wp_error( $city_terms ) ) {
	$city_terms = [];
}

// Carousel city slides — images from assets/images/locations/
$carousel_cities = [
	[ 'slug' => 'san-diego',       'name' => 'San Diego' ],
	[ 'slug' => 'la-jolla',        'name' => 'La Jolla' ],
	[ 'slug' => 'carlsbad',        'name' => 'Carlsbad' ],
	[ 'slug' => 'coronado',        'name' => 'Coronado' ],
	[ 'slug' => 'escondido',       'name' => 'Escondido' ],
	[ 'slug' => 'fallbrook',       'name' => 'Fallbrook' ],
	[ 'slug' => 'del-mar',         'name' => 'Del Mar' ],
	[ 'slug' => 'oceanside',       'name' => 'Oceanside' ],
	[ 'slug' => 'el-cajon',        'name' => 'El Cajon' ],
	[ 'slug' => 'chula-vista',     'name' => 'Chula Vista' ],
	[ 'slug' => 'encinitas',       'name' => 'Encinitas' ],
	[ 'slug' => 'rancho-santa-fe', 'name' => 'Rancho Santa Fe' ],
	[ 'slug' => 'san-marcos',      'name' => 'San Marcos' ],
	[ 'slug' => 'solana-beach',    'name' => 'Solana Beach' ],
	[ 'slug' => 'poway',           'name' => 'Poway' ],
];

// Alternating city popout + card row data (5 cities, each with 4 venues)
$city_sections = [
	[
		'slug'    => 'san-diego',
		'name'    => 'San Diego',
		'heading' => __( 'Wedding Venues in San Diego', 'sdweddingdirectory' ),
		'desc'    => __( 'Explore the best wedding venues San Diego has to offer — from waterfront estates to urban rooftops.', 'sdweddingdirectory' ),
	],
	[
		'slug'    => 'carlsbad',
		'name'    => 'Carlsbad',
		'heading' => __( 'Wedding Venues in Carlsbad', 'sdweddingdirectory' ),
		'desc'    => __( 'Discover stunning coastal wedding venues in Carlsbad, each with scenic ocean views and resort-style elegance.', 'sdweddingdirectory' ),
	],
	[
		'slug'    => 'la-jolla',
		'name'    => 'La Jolla',
		'heading' => __( 'Wedding Venues in La Jolla', 'sdweddingdirectory' ),
		'desc'    => __( 'Find beautiful wedding venues in La Jolla with breathtaking ocean views, lush gardens, and upscale charm.', 'sdweddingdirectory' ),
	],
	[
		'slug'    => 'fallbrook',
		'name'    => 'Fallbrook',
		'heading' => __( 'Wedding Venues in Fallbrook', 'sdweddingdirectory' ),
		'desc'    => __( 'Explore rustic wedding venues in Fallbrook surrounded by rolling hills, vineyards, and country charm.', 'sdweddingdirectory' ),
	],
	[
		'slug'    => 'escondido',
		'name'    => 'Escondido',
		'heading' => __( 'Wedding Venues in Escondido', 'sdweddingdirectory' ),
		'desc'    => __( 'Discover unique wedding venues in Escondido — from vineyard estates and gardens to ranch-style properties.', 'sdweddingdirectory' ),
	],
];

// Venue type link rows (same as homepage)
$venue_rows = [
	[
		[ 'label' => 'Banquet Halls',         'path' => '/venue-types/banquet-halls/' ],
		[ 'label' => 'Barns & Farms',         'path' => '/venue-types/barns-farms/' ],
		[ 'label' => 'Beaches',               'path' => '/venue-types/beaches/' ],
		[ 'label' => 'Boats',                 'path' => '/venue-types/boats/' ],
		[ 'label' => 'Churches & Temples',    'path' => '/venue-types/churches-temples/' ],
		[ 'label' => 'Country Clubs',         'path' => '/venue-types/country-clubs/' ],
		[ 'label' => 'Gardens',               'path' => '/venue-types/gardens/' ],
	],
	[
		[ 'label' => 'Historic Venues',       'path' => '/venue-types/historic-venues/' ],
		[ 'label' => 'Hotels',                'path' => '/venue-types/hotels/' ],
		[ 'label' => 'Mansions',              'path' => '/venue-types/mansions/' ],
		[ 'label' => 'Museums',               'path' => '/venue-types/museums/' ],
		[ 'label' => 'Outdoor',               'path' => '/venue-types/outdoor/' ],
		[ 'label' => 'Parks',                 'path' => '/venue-types/parks/' ],
		[ 'label' => 'Restaurants',           'path' => '/venue-types/restaurants/' ],
	],
	[
		[ 'label' => 'Rooftops & Lofts',      'path' => '/venue-types/rooftops-lofts/' ],
		[ 'label' => 'Waterfronts',           'path' => '/venue-types/waterfronts/' ],
		[ 'label' => 'Wineries & Breweries',  'path' => '/venue-types/wineries-breweries/' ],
	],
];

// Vendor category link rows (same as homepage)
$vendor_rows = [
	[
		[ 'label' => 'Bands',             'path' => '/vendors/bands/' ],
		[ 'label' => 'Cakes',             'path' => '/vendors/cakes/' ],
		[ 'label' => 'Catering',          'path' => '/vendors/catering/' ],
		[ 'label' => 'DJs',               'path' => '/vendors/djs/' ],
		[ 'label' => 'Florists',          'path' => '/vendors/flowers/' ],
		[ 'label' => 'Officiants',        'path' => '/vendors/officiants/' ],
		[ 'label' => 'Photo Booths',      'path' => '/vendors/photo-booths/' ],
	],
	[
		[ 'label' => 'Photographers',     'path' => '/vendors/photography/' ],
		[ 'label' => 'Planners',          'path' => '/vendors/wedding-planners/' ],
		[ 'label' => 'Rentals',           'path' => '/vendors/event-rentals/' ],
		[ 'label' => 'Stationers',        'path' => '/vendors/invitations/' ],
		[ 'label' => 'Transportation',    'path' => '/vendors/transportation/' ],
		[ 'label' => 'Videographers',     'path' => '/vendors/videography/' ],
		[ 'label' => 'Wedding Cakes',     'path' => '/vendors/cakes/' ],
	],
	[
		[ 'label' => 'Wedding Decor',     'path' => '/vendors/lighting-decor/' ],
		[ 'label' => 'Wedding Favors',    'path' => '/vendors/favors-gifts/' ],
		[ 'label' => 'Wedding Invitations', 'path' => '/vendors/invitations/' ],
		[ 'label' => 'Wedding Jewelers',  'path' => '/vendors/jewelry/' ],
		[ 'label' => 'Wedding Planners',  'path' => '/vendors/wedding-planners/' ],
	],
];

// SEO text columns for Section 13
$seo_columns = [
	[
		'title' => __( 'Picking a Wedding Venue', 'sdweddingdirectory' ),
		'text'  => __( 'Start with your guest count, budget, and overall vision. San Diego offers everything from intimate cliff-top ceremonies to large-scale ballroom receptions. Narrowing down what matters most — location, capacity, or vibe — will help you find the right match faster.', 'sdweddingdirectory' ),
	],
	[
		'title' => __( 'Outdoor Wedding Venue Spaces', 'sdweddingdirectory' ),
		'text'  => __( "San Diego's year-round sunshine makes it one of the best cities in the country for outdoor weddings. From ocean-view terraces and vineyard estates to botanical gardens and ranch-style properties, there's no shortage of beautiful open-air settings.", 'sdweddingdirectory' ),
	],
	[
		'title' => __( 'Indoor Wedding Spaces', 'sdweddingdirectory' ),
		'text'  => __( 'For couples who want a polished, weather-proof setting, San Diego has a wide range of indoor venues. Think historic ballrooms, boutique hotels, modern lofts, and elegant event spaces — many with the flexibility to host both your ceremony and reception under one roof.', 'sdweddingdirectory' ),
	],
];

?>

<div class="venues-landing">

	<!-- SECTION 1: Compact Search Hero -->
	<section class="search-hero" style="--search-hero-image: url('<?php echo esc_url( get_template_directory_uri() . '/assets/images/banners/venues-search.png' ); ?>');">
		<div class="container search-hero__inner">
			<?php
			get_template_part( 'template-parts/components/breadcrumbs', null, [
				'items' => [
					[ 'label' => __( 'Weddings', 'sdweddingdirectory' ), 'url' => home_url( '/' ) ],
					[ 'label' => __( 'Wedding Venues', 'sdweddingdirectory' ) ],
				],
			] );
			?>
			<h1 class="search-hero__title"><?php esc_html_e( 'Wedding Venues', 'sdweddingdirectory' ); ?></h1>

			<form class="search-hero__form" action="<?php echo esc_url( home_url( '/venues/' ) ); ?>" method="get">
				<div class="search-hero__field search-hero__field--type">
					<span class="search-hero__icon icon-search" aria-hidden="true"></span>
					<select class="search-hero__select" name="cat_id" aria-label="<?php esc_attr_e( 'Venue type', 'sdweddingdirectory' ); ?>">
						<option value=""><?php esc_html_e( 'Wedding Venues', 'sdweddingdirectory' ); ?></option>
						<?php foreach ( $venue_types as $vtype ) : ?>
							<option value="<?php echo esc_attr( $vtype->term_id ); ?>"><?php echo esc_html( $vtype->name ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="search-hero__field search-hero__field--location">
					<span class="search-hero__icon icon-map-marker" aria-hidden="true"></span>
					<select class="search-hero__select" name="location" aria-label="<?php esc_attr_e( 'Venue location', 'sdweddingdirectory' ); ?>">
						<option value=""><?php esc_html_e( 'Location', 'sdweddingdirectory' ); ?></option>
						<?php foreach ( $location_terms as $loc ) : ?>
							<option value="<?php echo esc_attr( $loc->slug ); ?>"><?php echo esc_html( $loc->name ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<button class="btn btn--cta search-hero__submit" type="submit">
					<?php esc_html_e( 'Search', 'sdweddingdirectory' ); ?>
				</button>
			</form>
		</div>
	</section>

	<!-- SECTION 2: Venues by Area (circular carousel) -->
	<section class="home-locations section venues-discovery">
		<div class="container">
			<?php
			get_template_part( 'template-parts/components/section-title', null, [
				'heading' => __( 'Venues by Area', 'sdweddingdirectory' ),
				'desc'    => __( 'Explore wedding venues across San Diego County by city.', 'sdweddingdirectory' ),
			] );

			// Build slide data with image URIs
			$location_slides = [];
			foreach ( $carousel_cities as $city ) {
				$location_slides[] = [
					'slug'  => $city['slug'],
					'name'  => $city['name'],
					'image' => get_theme_file_uri( 'assets/images/locations/' . $city['slug'] . '.jpg' ),
				];
			}
			$location_groups = array_chunk( $location_slides, 6 );
			?>

			<div class="home-locations__carousel" data-carousel="cities">
				<div class="home-locations__viewport">
					<div class="home-locations__track">
						<?php foreach ( $location_groups as $group_index => $city_group ) : ?>
							<?php
							$panel_number = $group_index + 1;
							$panel_prev   = 0 === $group_index ? count( $location_groups ) : $group_index;
							$panel_next   = count( $location_groups ) === $panel_number ? 1 : $panel_number + 1;
							?>
							<div class="home-locations__panel" id="<?php echo esc_attr( 'venues-locations-page-' . $panel_number ); ?>">
								<div class="home-locations__panel-grid">
									<?php foreach ( $city_group as $city ) : ?>
										<?php
										$city_term = get_term_by( 'slug', $city['slug'], 'venue-location' );
										$city_count = $city_term ? $city_term->count : 0;
										?>
										<a class="home-locations__slide" href="<?php echo esc_url( home_url( '/venues/?location=' . $city['slug'] ) ); ?>">
											<span class="home-locations__image">
												<img loading="lazy" decoding="async" src="<?php echo esc_url( $city['image'] ); ?>" alt="<?php echo esc_attr( $city['name'] ); ?>">
											</span>
											<span class="home-locations__name"><?php echo esc_html( $city['name'] ); ?></span>
											<?php if ( $city_count > 0 ) : ?>
												<span class="home-locations__count"><?php echo esc_html( sprintf( _n( '%s venue', '%s venues', $city_count, 'sdweddingdirectory' ), number_format_i18n( $city_count ) ) ); ?></span>
											<?php endif; ?>
										</a>
									<?php endforeach; ?>
								</div>

								<?php if ( count( $location_groups ) > 1 ) : ?>
									<div class="home-locations__controls">
										<a class="carousel-arrow carousel-arrow--prev home-locations__arrow home-locations__arrow--prev" href="<?php echo esc_url( '#venues-locations-page-' . $panel_prev ); ?>">
											<span class="icon-chevron-left carousel-arrow__icon" aria-hidden="true"></span>
											<span class="screen-reader-text"><?php esc_html_e( 'Previous locations', 'sdweddingdirectory' ); ?></span>
										</a>
										<a class="carousel-arrow carousel-arrow--next home-locations__arrow home-locations__arrow--next" href="<?php echo esc_url( '#venues-locations-page-' . $panel_next ); ?>">
											<span class="icon-chevron-left carousel-arrow__icon" aria-hidden="true"></span>
											<span class="screen-reader-text"><?php esc_html_e( 'Next locations', 'sdweddingdirectory' ); ?></span>
										</a>
									</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- San Diego popout -->
	<section class="section venues-city-popout">
		<div class="container">
			<?php
			get_template_part( 'template-parts/components/venue-popout', null, [
				'heading'   => __( 'San Diego Wedding Venues', 'sdweddingdirectory' ),
				'desc'      => __( 'SDWeddingDirectory can help you find the perfect San Diego wedding venue.', 'sdweddingdirectory' ),
				'image'     => get_theme_file_uri( 'assets/images/pages/venues-pop-out.png' ),
				'image_alt' => __( 'San Diego Wedding Venues', 'sdweddingdirectory' ),
			] );
			?>
		</div>
	</section>

	<!-- Venue card rows per city -->
	<?php foreach ( $city_sections as $index => $city_data ) :
		$city_slug = $city_data['slug'];
		$city_name = $city_data['name'];

		// Query 4 venues from this city
		$city_venue_query = new WP_Query( [
			'post_type'      => 'venue',
			'post_status'    => 'publish',
			'posts_per_page' => 4,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'tax_query'      => [
				[
					'taxonomy' => 'venue-location',
					'field'    => 'slug',
					'terms'    => [ $city_slug ],
				],
			],
		] );
		?>

		<!-- Venue cards: <?php echo esc_html( $city_name ); ?> -->
		<?php if ( $city_venue_query->have_posts() ) : ?>
			<section class="section venues-city-cards">
				<div class="container">
					<h2 class="venues-city-cards__heading">
						<?php
						echo esc_html(
							sprintf(
								/* translators: %s: city name */
								__( 'Venues in %s', 'sdweddingdirectory' ),
								$city_name
							)
						);
						?>
					</h2>

					<div class="grid grid--4col">
						<?php
						while ( $city_venue_query->have_posts() ) :
							$city_venue_query->the_post();
							get_template_part( 'template-parts/components/venue-card', null, [
								'post_id'    => get_the_ID(),
								'image_size' => 'medium',
							] );
						endwhile;
						wp_reset_postdata();
						?>
					</div>

					<div class="venues-city-cards__cta">
						<a class="btn btn--outline" href="<?php echo esc_url( home_url( '/venues/?location=' . $city_slug ) ); ?>">
							<?php
							echo esc_html(
								sprintf(
									/* translators: %s: city name */
									__( 'See all %s venues', 'sdweddingdirectory' ),
									$city_name
								)
							);
							?>
						</a>
					</div>
				</div>
			</section>
		<?php endif; ?>

	<?php endforeach; ?>

	<!-- SECTION 13: SEO text columns -->
	<section class="section venues-seo">
		<div class="container">
			<?php
			get_template_part( 'template-parts/components/section-title', null, [
				'heading' => __( 'San Diego Wedding Venues', 'sdweddingdirectory' ),
				'desc'    => __( 'Beachfront views, garden settings, modern ballrooms, and historic estates. Whatever your style or budget, San Diego has a venue that fits.', 'sdweddingdirectory' ),
			] );
			?>

			<div class="venues-seo__columns">
				<?php foreach ( $seo_columns as $col ) : ?>
					<div class="venues-seo__col">
						<h3 class="venues-seo__col-title"><?php echo esc_html( $col['title'] ); ?></h3>
						<p class="venues-seo__col-text"><?php echo esc_html( $col['text'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- SECTION 14: Venue type buttons -->
	<section class="section venues-type-buttons">
		<div class="container">
			<?php
			get_template_part( 'template-parts/components/section-title', null, [
				'heading' => __( 'Find Your San Diego Wedding Venue by Type', 'sdweddingdirectory' ),
			] );
			?>
			<div class="venues-button-row">
				<?php foreach ( $venue_types as $type_term ) : ?>
					<a class="btn btn--outline" href="<?php echo esc_url( get_term_link( $type_term ) ); ?>">
						<?php echo esc_html( $type_term->name ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- SECTION 15: Browse San Diego Wedding Venues by City -->
	<?php if ( ! empty( $city_terms ) ) : ?>
		<section class="section venues-city-browse">
			<div class="container">
				<h2 class="venues-city-browse__title"><?php esc_html_e( 'Browse San Diego Wedding Venues', 'sdweddingdirectory' ); ?></h2>
				<div class="venues-city-browse__grid">
					<?php foreach ( $city_terms as $city ) : ?>
						<a class="venues-city-browse__link" href="<?php echo esc_url( home_url( '/venues/?location=' . $city->slug ) ); ?>">
							<?php echo esc_html( $city->name . ' Wedding Venues' ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<!-- SECTION 16: San Diego Wedding Vendors (vendor link grid) -->
	<section class="section venues-vendor-links">
		<div class="container">
			<?php
			get_template_part( 'template-parts/components/inline-link-grid', null, [
				'heading' => __( 'San Diego Wedding Vendors', 'sdweddingdirectory' ),
				'rows'    => $vendor_rows,
			] );
			?>
		</div>
	</section>

</div>

<?php
get_footer();
