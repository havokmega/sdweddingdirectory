<?php
/**
 * Homepage Template
 */

get_header();

// --- Data setup ---

$featured_city_slides = [
    [ 'slug' => 'san-diego',       'name' => 'San Diego',       'image' => get_theme_file_uri( 'assets/images/locations/san-diego.jpg' ) ],
    [ 'slug' => 'la-jolla',        'name' => 'La Jolla',        'image' => get_theme_file_uri( 'assets/images/locations/la-jolla.jpg' ) ],
    [ 'slug' => 'carlsbad',        'name' => 'Carlsbad',        'image' => get_theme_file_uri( 'assets/images/locations/carlsbad.jpg' ) ],
    [ 'slug' => 'escondido',       'name' => 'Escondido',       'image' => get_theme_file_uri( 'assets/images/locations/escondido.jpg' ) ],
    [ 'slug' => 'fallbrook',       'name' => 'Fallbrook',       'image' => get_theme_file_uri( 'assets/images/locations/fallbrook.jpg' ) ],
    [ 'slug' => 'coronado',        'name' => 'Coronado',        'image' => get_theme_file_uri( 'assets/images/locations/coronado.jpg' ) ],
    [ 'slug' => 'oceanside',       'name' => 'Oceanside',       'image' => get_theme_file_uri( 'assets/images/locations/oceanside.jpg' ) ],
    [ 'slug' => 'el-cajon',        'name' => 'El Cajon',        'image' => get_theme_file_uri( 'assets/images/locations/el-cajon.jpg' ) ],
    [ 'slug' => 'san-marcos',      'name' => 'San Marcos',      'image' => get_theme_file_uri( 'assets/images/locations/san-marcos.jpg' ) ],
    [ 'slug' => 'vista',           'name' => 'Vista',           'image' => get_theme_file_uri( 'assets/images/locations/vista.jpg' ) ],
    [ 'slug' => 'del-mar',         'name' => 'Del Mar',         'image' => get_theme_file_uri( 'assets/images/locations/del-mar.jpg' ) ],
    [ 'slug' => 'chula-vista',     'name' => 'Chula Vista',     'image' => get_theme_file_uri( 'assets/images/locations/chula-vista.jpg' ) ],
    [ 'slug' => 'alpine',          'name' => 'Alpine',          'image' => get_theme_file_uri( 'assets/images/locations/alpine.jpg' ) ],
    [ 'slug' => 'bonita',          'name' => 'Bonita',          'image' => get_theme_file_uri( 'assets/images/locations/bonita.jpg' ) ],
    [ 'slug' => 'rancho-santa-fe', 'name' => 'Rancho Santa Fe', 'image' => get_theme_file_uri( 'assets/images/locations/rancho-santa-fe.jpg' ) ],
    [ 'slug' => 'solana-beach',    'name' => 'Solana Beach',    'image' => get_theme_file_uri( 'assets/images/locations/solana-beach.jpg' ) ],
    [ 'slug' => 'poway',           'name' => 'Poway',           'image' => get_theme_file_uri( 'assets/images/locations/poway.jpg' ) ],
    [ 'slug' => 'julian',          'name' => 'Julian',          'image' => get_theme_file_uri( 'assets/images/locations/julian.jpg' ) ],
    [ 'slug' => 'jamul',           'name' => 'Jamul',           'image' => get_theme_file_uri( 'assets/images/locations/jamul.jpg' ) ],
    [ 'slug' => 'ramona',          'name' => 'Ramona',          'image' => get_theme_file_uri( 'assets/images/locations/ramona.jpg' ) ],
];

$venue_rows = [
    [
        [ 'label' => 'Banquet Halls',         'path' => '/venue-types/banquet-halls/' ],
        [ 'label' => 'Barns & Farms',         'path' => '/venue-types/barns-farms/' ],
        [ 'label' => 'Beaches',               'path' => '/venue-types/beaches/' ],
        [ 'label' => 'Boats',                 'path' => '/venue-types/boats/' ],
        [ 'label' => 'Churches & Temples',    'path' => '/venue-types/churches-temples/' ],
        [ 'label' => 'Country Clubs',         'path' => '/venue-types/country-clubs/' ],
    ],
    [
        [ 'label' => 'Gardens',               'path' => '/venue-types/gardens/' ],
        [ 'label' => 'Historic Venues',       'path' => '/venue-types/historic-venues/' ],
        [ 'label' => 'Hotels',                'path' => '/venue-types/hotels/' ],
        [ 'label' => 'Mansions',              'path' => '/venue-types/mansions/' ],
        [ 'label' => 'Museums',               'path' => '/venue-types/museums/' ],
        [ 'label' => 'Outdoor',               'path' => '/venue-types/outdoor/' ],
    ],
    [
        [ 'label' => 'Parks',                 'path' => '/venue-types/parks/' ],
        [ 'label' => 'Restaurants',           'path' => '/venue-types/restaurants/' ],
        [ 'label' => 'Rooftops & Lofts',      'path' => '/venue-types/rooftops-lofts/' ],
        [ 'label' => 'Waterfronts',           'path' => '/venue-types/waterfronts/' ],
        [ 'label' => 'Wineries & Breweries',  'path' => '/venue-types/wineries-breweries/' ],
    ],
];

$vendor_rows = [
    [
        [ 'label' => 'Bands',             'path' => '/vendors/bands/' ],
        [ 'label' => 'Cakes',             'path' => '/vendors/cakes/' ],
        [ 'label' => 'Catering',          'path' => '/vendors/catering/' ],
        [ 'label' => 'Ceremony Music',    'path' => '/vendors/ceremony-music/' ],
        [ 'label' => 'DJs',               'path' => '/vendors/djs/' ],
        [ 'label' => 'Dress & Attire',    'path' => '/vendors/dress-attire/' ],
        [ 'label' => 'Event Rentals',     'path' => '/vendors/event-rentals/' ],
    ],
    [
        [ 'label' => 'Favors & Gifts',    'path' => '/vendors/favors-gifts/' ],
        [ 'label' => 'Flowers',           'path' => '/vendors/flowers/' ],
        [ 'label' => 'Hair & Makeup',     'path' => '/vendors/hair-makeup/' ],
        [ 'label' => 'Invitations',       'path' => '/vendors/invitations/' ],
        [ 'label' => 'Jewelry',           'path' => '/vendors/jewelry/' ],
        [ 'label' => 'Lighting & Decor',  'path' => '/vendors/lighting-decor/' ],
        [ 'label' => 'Officiants',        'path' => '/vendors/officiants/' ],
    ],
    [
        [ 'label' => 'Photo Booths',      'path' => '/vendors/photo-booths/' ],
        [ 'label' => 'Photography',       'path' => '/vendors/photography/' ],
        [ 'label' => 'Transportation',    'path' => '/vendors/transportation/' ],
        [ 'label' => 'Travel Agents',     'path' => '/vendors/travel-agents/' ],
        [ 'label' => 'Videography',       'path' => '/vendors/videography/' ],
        [ 'label' => 'Wedding Planners',  'path' => '/vendors/wedding-planners/' ],
    ],
];

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

// Venue type terms for search dropdown
$venue_types = get_terms( [
    'taxonomy'   => 'venue-type',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
] );
if ( is_wp_error( $venue_types ) ) {
    $venue_types = [];
}

// Location terms for search dropdown
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

// Random hero banner
$banner_images = glob( get_template_directory() . '/assets/images/banners/home-hero-random-*.jpg' );
$banner_image  = ! empty( $banner_images )
    ? get_template_directory_uri() . '/assets/images/banners/' . basename( $banner_images[ array_rand( $banner_images ) ] )
    : get_template_directory_uri() . '/assets/images/banners/home-hero-random-1.jpg';
?>

<div class="home-sections">

    <!-- SECTION 1: Hero Search Banner -->
    <section class="hero">
        <div class="hero__media" style="--hero-image: url('<?php echo esc_url( $banner_image ); ?>');" aria-hidden="true"></div>

        <div class="hero__panel">
            <div class="container hero__inner">
                <div class="hero__content">
                    <h1 class="hero__title"><?php esc_html_e( 'Find the Perfect Wedding Vendor', 'sandiegoweddingdirectory' ); ?></h1>
                    <p class="hero__subtitle"><?php esc_html_e( 'Find San Diego wedding vendors with reviews, pricing, and availability.', 'sandiegoweddingdirectory' ); ?></p>

                    <div class="hero__search">
                        <div class="hero__toggle">
                            <span class="hero__toggle-label"><?php esc_html_e( 'Choose what you want to search for:', 'sandiegoweddingdirectory' ); ?></span>

                            <label class="hero__toggle-option hero__toggle-option--active">
                                <input type="radio" name="sd_search_mode" value="venues" checked>
                                <span class="hero__toggle-radio" aria-hidden="true"></span>
                                <span class="hero__toggle-text"><?php esc_html_e( 'Venues', 'sandiegoweddingdirectory' ); ?></span>
                            </label>

                            <label class="hero__toggle-option">
                                <input type="radio" name="sd_search_mode" value="vendors">
                                <span class="hero__toggle-radio" aria-hidden="true"></span>
                                <span class="hero__toggle-text"><?php esc_html_e( 'Vendors', 'sandiegoweddingdirectory' ); ?></span>
                            </label>
                        </div>

                        <form class="hero__form" action="<?php echo esc_url( home_url( '/venues/' ) ); ?>" method="get"
                            data-venue-action="<?php echo esc_url( home_url( '/venues/' ) ); ?>"
                            data-vendor-action="<?php echo esc_url( home_url( '/vendors/' ) ); ?>">

                            <div class="hero__form-fields hero__venue-fields">
                                <div class="hero__field hero__field--type hero__field--dropdown">
                                    <span class="hero__field-icon icon-search" aria-hidden="true"></span>
                                    <button class="hero__dropdown-trigger" type="button" aria-expanded="false" aria-haspopup="true" data-dropdown="venue-type">
                                        <span class="hero__dropdown-text"><?php esc_html_e( 'Search by type', 'sandiegoweddingdirectory' ); ?></span>
                                        <span class="hero__dropdown-arrow icon-chevron-down" aria-hidden="true"></span>
                                    </button>
                                    <input type="hidden" name="cat_id" value="">
                                    <div class="hero__dropdown-panel" data-panel="venue-type" hidden>
                                        <div class="hero__dropdown-grid">
                                            <?php foreach ( $venue_types as $vtype ) : ?>
                                                <button class="hero__dropdown-item" type="button" data-value="<?php echo esc_attr( $vtype->term_id ); ?>">
                                                    <?php echo esc_html( $vtype->name ); ?>
                                                </button>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="hero__field hero__field--location hero__field--dropdown">
                                    <span class="hero__field-prefix" aria-hidden="true"><?php esc_html_e( 'in', 'sandiegoweddingdirectory' ); ?></span>
                                    <button class="hero__dropdown-trigger" type="button" aria-expanded="false" aria-haspopup="true" data-dropdown="venue-location">
                                        <span class="hero__dropdown-text"><?php esc_html_e( 'Location', 'sandiegoweddingdirectory' ); ?></span>
                                        <span class="hero__dropdown-arrow icon-chevron-down" aria-hidden="true"></span>
                                    </button>
                                    <input type="hidden" name="location" value="">
                                    <div class="hero__dropdown-panel" data-panel="venue-location" hidden>
                                        <div class="hero__dropdown-grid">
                                            <?php foreach ( $location_terms as $loc ) : ?>
                                                <button class="hero__dropdown-item" type="button" data-value="<?php echo esc_attr( $loc->slug ); ?>">
                                                    <?php echo esc_html( $loc->name ); ?>
                                                </button>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="hero__form-fields hero__vendor-fields" hidden>
                                <div class="hero__field hero__field--type hero__field--dropdown">
                                    <span class="hero__field-icon icon-search" aria-hidden="true"></span>
                                    <button class="hero__dropdown-trigger" type="button" aria-expanded="false" aria-haspopup="true" data-dropdown="vendor-category">
                                        <span class="hero__dropdown-text"><?php esc_html_e( 'Search by category', 'sandiegoweddingdirectory' ); ?></span>
                                        <span class="hero__dropdown-arrow icon-chevron-down" aria-hidden="true"></span>
                                    </button>
                                    <input type="hidden" name="cat_id" value="">
                                    <div class="hero__dropdown-panel hero__dropdown-panel--mega" data-panel="vendor-category" hidden>
                                        <div class="hero__dropdown-grid hero__dropdown-grid--icons">
                                            <?php
                                            // Slug → icon-font class. Best-guess for obvious fits; placeholder for the rest (founder will swap).
                                            $vendor_category_icons = [
                                                'photography'    => 'icon-camera-alt',
                                                'videography'    => 'icon-videographer',
                                                'djs'            => 'icon-music',
                                                'bands'          => 'icon-music',
                                                'ceremony-music' => 'icon-music',
                                                'flowers'        => 'icon-flowers',
                                                'catering'       => 'icon-wine',
                                                'cakes'          => 'icon-cake',
                                                'officiants'     => 'icon-church',
                                                'hair-makeup'    => 'icon-fashion',
                                                'dress-attire'   => 'icon-bridal-wear',
                                                'event-rentals'  => 'icon-four-side-table-1',
                                                'photo-booths'   => 'icon-camera',
                                                'invitations'    => 'icon-heart-envelope',
                                                'transportation' => 'icon-bus',
                                                'travel-agents'  => 'icon-birde',
                                                'wedding-planners' => 'icon-checklist',
                                                // Placeholders — replace post-build:
                                                'lighting-decor' => 'icon-bell',
                                                'jewelry'        => 'icon-ballon-heart',
                                                'favors-gifts'   => 'icon-ballon-heart',
                                            ];

                                            $vendor_cats = get_terms( [
                                                'taxonomy'   => 'vendor-category',
                                                'hide_empty' => false,
                                                'orderby'    => 'name',
                                                'order'      => 'ASC',
                                                'exclude'    => [ 140 ], // stray 'Venues' term in vendor-category taxonomy
                                            ] );
                                            if ( ! is_wp_error( $vendor_cats ) ) :
                                                foreach ( $vendor_cats as $vcat ) :
                                                    $icon_class = $vendor_category_icons[ $vcat->slug ] ?? 'icon-vendor-manager';
                                                    ?>
                                                    <button class="hero__dropdown-item hero__dropdown-item--with-icon" type="button" data-value="<?php echo esc_attr( $vcat->term_id ); ?>" data-slug="<?php echo esc_attr( $vcat->slug ); ?>">
                                                        <span class="hero__dropdown-item-icon <?php echo esc_attr( $icon_class ); ?>" aria-hidden="true"></span>
                                                        <span class="hero__dropdown-item-label"><?php echo esc_html( $vcat->name ); ?></span>
                                                    </button>
                                                <?php endforeach;
                                            endif;
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="hero__field hero__field--location hero__field--dropdown">
                                    <span class="hero__field-prefix" aria-hidden="true"><?php esc_html_e( 'in', 'sandiegoweddingdirectory' ); ?></span>
                                    <button class="hero__dropdown-trigger" type="button" aria-expanded="false" aria-haspopup="true" data-dropdown="vendor-location">
                                        <span class="hero__dropdown-text"><?php esc_html_e( 'Location', 'sandiegoweddingdirectory' ); ?></span>
                                        <span class="hero__dropdown-arrow icon-chevron-down" aria-hidden="true"></span>
                                    </button>
                                    <input type="hidden" name="location" value="">
                                    <div class="hero__dropdown-panel" data-panel="vendor-location" hidden>
                                        <div class="hero__dropdown-grid">
                                            <?php foreach ( $location_terms as $loc ) : ?>
                                                <button class="hero__dropdown-item" type="button" data-value="<?php echo esc_attr( $loc->slug ); ?>">
                                                    <?php echo esc_html( $loc->name ); ?>
                                                </button>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn--primary hero__submit" type="submit">
                                <?php esc_html_e( 'Search Now', 'sandiegoweddingdirectory' ); ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: San Diego Wedding Vendors -->
    <section class="home-vendors section">
        <div class="container">
            <?php
            get_template_part( 'template-parts/components/section-title', null, [
                'heading' => __( 'San Diego Wedding Vendors', 'sandiegoweddingdirectory' ),
                'desc'    => __( 'Quickly connect with the industry\'s top wedding professionals', 'sandiegoweddingdirectory' ),
            ] );
            ?>

            <div class="home-vendors__featured grid grid--4col">
                <?php
                $featured_cats = [
                    [
                        'card_class' => 'home-vendors__card--venues',
                        'image'      => 'categories/venues.png',
                        'icon'       => 'icons/categories/venues.png',
                        'title'      => __( 'Wedding Venues', 'sandiegoweddingdirectory' ),
                        'desc'       => __( 'Tour standout local reception spaces and lock in the perfect place to celebrate.', 'sandiegoweddingdirectory' ),
                        'url'        => home_url( '/venues/' ),
                    ],
                    [
                        'card_class' => 'home-vendors__card--photography',
                        'image'      => 'categories/photographers.png',
                        'icon'       => 'icons/categories/photographers.png',
                        'title'      => __( 'Wedding Photographers', 'sandiegoweddingdirectory' ),
                        'desc'       => __( 'Compare local photographers and portfolios to find the right fit for your day.', 'sandiegoweddingdirectory' ),
                        'url'        => home_url( '/vendors/photography/' ),
                    ],
                    [
                        'card_class' => 'home-vendors__card--catering',
                        'image'      => 'categories/caterers.png',
                        'icon'       => 'icons/categories/caterers.png',
                        'title'      => __( 'Wedding Caterers', 'sandiegoweddingdirectory' ),
                        'desc'       => __( 'Discover caterers, bartenders, and chefs who can build a menu your guests will remember.', 'sandiegoweddingdirectory' ),
                        'url'        => home_url( '/vendors/catering/' ),
                    ],
                    [
                        'card_class' => 'home-vendors__card--attire',
                        'image'      => 'categories/attire.png',
                        'icon'       => 'icons/categories/attire.png',
                        'title'      => __( 'Wedding Attire', 'sandiegoweddingdirectory' ),
                        'desc'       => __( 'Browse nearby bridal boutiques and shops to find a wedding look that feels like you.', 'sandiegoweddingdirectory' ),
                        'url'        => home_url( '/vendors/dress-attire/' ),
                    ],
                ];

                foreach ( $featured_cats as $cat ) :
                    ?>
                    <a class="home-vendors__card <?php echo esc_attr( $cat['card_class'] ); ?>" href="<?php echo esc_url( $cat['url'] ); ?>">
                        <div class="home-vendors__card-media">
                            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/' . $cat['image'] ); ?>" alt="<?php echo esc_attr( $cat['title'] ); ?>">
                        </div>
                        <div class="home-vendors__card-body">
                            <span class="home-vendors__card-badge" aria-hidden="true">
                                <span class="home-vendors__card-badge-inner">
                                    <img class="home-vendors__card-badge-icon" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/' . $cat['icon'] ); ?>" alt="">
                                </span>
                            </span>
                            <h3 class="home-vendors__card-title"><?php echo esc_html( $cat['title'] ); ?></h3>
                            <p class="home-vendors__card-desc"><?php echo esc_html( $cat['desc'] ); ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="home-vendors__buttons">
                <?php
                $vendor_buttons = [
                    [ 'label' => 'DJs',            'path' => '/vendors/djs/' ],
                    [ 'label' => 'Photography',    'path' => '/vendors/photography/' ],
                    [ 'label' => 'Flowers',        'path' => '/vendors/flowers/' ],
                    [ 'label' => 'Hair & Makeup',  'path' => '/vendors/hair-makeup/' ],
                    [ 'label' => 'Videography',    'path' => '/vendors/videography/' ],
                    [ 'label' => 'Officiants',     'path' => '/vendors/officiants/' ],
                    [ 'label' => 'Event Rentals',  'path' => '/vendors/event-rentals/' ],
                ];
                foreach ( $vendor_buttons as $vb ) :
                    ?>
                    <a class="btn btn--outline home-vendors__button" href="<?php echo esc_url( home_url( $vb['path'] ) ); ?>"><?php echo esc_html( $vb['label'] ); ?></a>
                <?php endforeach; ?>
                <a class="btn btn--outline home-vendors__button" href="<?php echo esc_url( home_url( '/vendors/' ) ); ?>"><?php esc_html_e( 'Show All', 'sandiegoweddingdirectory' ); ?></a>
            </div>
        </div>
    </section>

    <!-- SECTION 3: Plan Your San Diego Wedding -->
    <section class="home-planning section">
        <div class="container">
            <?php
            get_template_part( 'template-parts/components/section-title', null, [
                'heading' => __( 'Plan Your San Diego Wedding', 'sandiegoweddingdirectory' ),
                'desc'    => __( 'Free, easy-to-use wedding planning tools', 'sandiegoweddingdirectory' ),
            ] );
            ?>

            <div class="home-planning__cards grid grid--2col">
                <article class="home-planning__card">
                    <div class="home-planning__card-content">
                        <h3 class="home-planning__card-title"><?php esc_html_e( 'Free Wedding Website', 'sandiegoweddingdirectory' ); ?></h3>
                        <p class="home-planning__card-desc"><?php esc_html_e( 'Share your story, collect RSVPs, and keep guests informed in one place.', 'sandiegoweddingdirectory' ); ?></p>
                        <a class="home-planning__card-link" href="<?php echo esc_url( home_url( '/dashboard/wedding-website/' ) ); ?>"><?php esc_html_e( 'Start your website', 'sandiegoweddingdirectory' ); ?></a>
                    </div>
                    <div class="home-planning__card-icon">
                        <img loading="lazy" decoding="async" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/icons/planning/wedding-website.png' ); ?>" alt="<?php esc_attr_e( 'Wedding Website icon', 'sandiegoweddingdirectory' ); ?>" width="48" height="48">
                    </div>
                </article>
                <article class="home-planning__card">
                    <div class="home-planning__card-content">
                        <h3 class="home-planning__card-title"><?php esc_html_e( 'Planning Tools', 'sandiegoweddingdirectory' ); ?></h3>
                        <p class="home-planning__card-desc"><?php esc_html_e( 'Stay organized with checklists, budgets, and guest management tools.', 'sandiegoweddingdirectory' ); ?></p>
                        <a class="home-planning__card-link" href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>"><?php esc_html_e( 'Explore planning tools', 'sandiegoweddingdirectory' ); ?></a>
                    </div>
                    <div class="home-planning__card-icon">
                        <img loading="lazy" decoding="async" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/icons/planning/checklist.png' ); ?>" alt="<?php esc_attr_e( 'Planning Tools icon', 'sandiegoweddingdirectory' ); ?>" width="48" height="48">
                    </div>
                </article>
            </div>

            <div class="home-planning__extra">
                <span class="home-planning__registry-text"><?php esc_html_e( 'Search for a couples registry or website', 'sandiegoweddingdirectory' ); ?></span>
            </div>
        </div>
    </section>

    <!-- SECTION 4: Real Weddings -->
    <section class="home-realweddings section">
        <div class="container">
            <?php
            get_template_part( 'template-parts/components/section-title', null, [
                'heading' => __( 'Real Weddings', 'sandiegoweddingdirectory' ),
                'desc'    => __( 'Find inspiration from real San Diego couples', 'sandiegoweddingdirectory' ),
                'align'   => 'left',
            ] );

            $rw_base_url = get_template_directory_uri() . '/assets/images/real-wedding';
            $rw_link     = home_url( '/real-weddings/' );

            $rw_cards = [
                [
                    'main'   => 'rw_sarah-michael-1.png',
                    'thumbs' => [ 'rw_sarah-michael-2.png', 'rw_sarah-michael-3.png', 'rw_sarah-michael-4.png', 'rw_sarah-michael-1.png' ],
                    'name'   => 'Sarah & Michael',
                ],
                [
                    'main'   => 'rw_jessica-david-1.png',
                    'thumbs' => [ 'rw_jessica-david-2.png', 'rw_jessica-david-3.png', 'rw_jessica-david-4.png', 'rw_jessica-david-1.png' ],
                    'name'   => 'Jessica & David',
                ],
                [
                    'main'   => 'rw_emily-james-1.png',
                    'thumbs' => [ 'rw_emily-james-2.png', 'rw_emily-james-3.png', 'rw_emily-james-4.png', 'rw_emily-james-1.png' ],
                    'name'   => 'Emily & James',
                ],
                [
                    'main'   => 'rw_amanda-ryan-1.png',
                    'thumbs' => [ 'rw_amanda-ryan-2.png', 'rw_amanda-ryan-3.png', 'rw_amanda-ryan-4.png', 'rw_amanda-ryan-1.png' ],
                    'name'   => 'Amanda & Ryan',
                ],
            ];
            ?>
                <div class="home-realweddings__grid">
                    <?php foreach ( $rw_cards as $rw_card ) : ?>
                        <article class="home-realweddings__card">
                            <a class="home-realweddings__card-link" href="<?php echo esc_url( $rw_link ); ?>">
                                <div class="home-realweddings__frame">
                                    <div class="home-realweddings__image-wrap">
                                        <img class="home-realweddings__image" src="<?php echo esc_url( $rw_base_url . '/' . $rw_card['main'] ); ?>" alt="<?php echo esc_attr( $rw_card['name'] ); ?>" loading="lazy">
                                    </div>
                                    <div class="home-realweddings__thumbs" aria-hidden="true">
                                        <?php foreach ( $rw_card['thumbs'] as $rw_thumb_index => $rw_thumb_file ) :
                                            $rw_thumb_css = 3 === $rw_thumb_index
                                                ? 'home-realweddings__thumb home-realweddings__thumb--more'
                                                : 'home-realweddings__thumb';
                                        ?>
                                            <span class="<?php echo esc_attr( $rw_thumb_css ); ?>">
                                                <img class="home-realweddings__thumb-image" src="<?php echo esc_url( $rw_base_url . '/' . $rw_thumb_file ); ?>" alt="" loading="lazy">
                                                <?php if ( 3 === $rw_thumb_index ) : ?>
                                                    <span class="home-realweddings__thumb-overlay">
                                                        <span><?php esc_html_e( 'Load', 'sandiegoweddingdirectory' ); ?></span>
                                                        <span><?php esc_html_e( 'More', 'sandiegoweddingdirectory' ); ?></span>
                                                    </span>
                                                <?php endif; ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <h3 class="home-realweddings__name"><?php echo esc_html( $rw_card['name'] ); ?></h3>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
                <div class="home-realweddings__cta">
                    <a class="btn btn--outline home-realweddings__cta-button" href="<?php echo esc_url( $rw_link ); ?>">
                        <span><?php esc_html_e( 'See All Real Weddings', 'sandiegoweddingdirectory' ); ?></span>
                        <span class="home-cta__icon" aria-hidden="true">&rarr;</span>
                    </a>
                </div>
        </div>
    </section>

    <!-- SECTION 5: Inspiration -->
    <section class="home-inspiration section">
        <div class="container">
            <?php
            get_template_part( 'template-parts/components/section-title', null, [
                'heading' => __( 'Inspiration', 'sandiegoweddingdirectory' ),
                'desc'    => __( 'Tips, advice, and ideas for your perfect wedding', 'sandiegoweddingdirectory' ),
                'align'   => 'left',
            ] );

            $blog_categories = [
                [ 'slug' => 'wedding-planning-how-to', 'title' => 'Planning', 'image' => 'blog/wedding-planning-how-to.jpg' ],
                [ 'slug' => 'wedding-ceremony',        'title' => 'Ceremony',        'image' => 'blog/wedding-ceremony.jpg' ],
                [ 'slug' => 'wedding-reception',       'title' => 'Reception',       'image' => 'blog/wedding-reception.jpg' ],
                [ 'slug' => 'wedding-services',        'title' => 'Services',        'image' => 'blog/wedding-services.jpg' ],
                [ 'slug' => 'wedding-fashion',         'title' => 'Fashion',         'image' => 'blog/wedding-fashion.jpg' ],
                [ 'slug' => 'hair-makeup',             'title' => 'Hair & Makeup',   'image' => 'blog/hair-makeup.jpg' ],
            ];

            $featured_articles = [
                [
                    'path'     => '/the-ultimate-honeymoon-advice-guide-for-modern-couples/',
                    'image'    => 'blog/honeymoon-advice.jpg',
                    'alt'      => 'Honeymoon Advice Guide',
                    'category' => 'WEDDING HONEYMOON ADVICE',
                    'title'    => 'The Ultimate Honeymoon Advice Guide for Modern Couples',
                ],
                [
                    'path'     => '/the-ultimate-wedding-budget-guide-how-to-plan-and-spend-smart/',
                    'image'    => 'blog/budget.jpg',
                    'alt'      => 'Wedding Budget Guide',
                    'category' => 'WEDDING BUDGET',
                    'title'    => 'The Ultimate Wedding Budget Guide: How to Plan and Spend Smart',
                ],
                [
                    'path'     => '/san-diego-wedding-legal-paperwork-guide-what-you-actually-need-to-get-married/',
                    'image'    => 'blog/legal-paperwork.jpg',
                    'alt'      => 'Legal Paperwork Guide',
                    'category' => 'LEGAL PAPERWORK',
                    'title'    => 'San Diego Wedding Legal Paperwork Guide: What You Actually Need to Get Married',
                ],
                [
                    'path'     => '/smart-wedding-tips-every-san-diego-couple-should-know/',
                    'image'    => 'blog/trends-and-tips.jpg',
                    'alt'      => 'Smart Wedding Tips',
                    'category' => 'WEDDING TRENDS & TIPS',
                    'title'    => 'Smart Wedding Tips Every San Diego Couple Should Know',
                ],
            ];
            ?>
            <div class="home-inspiration__categories">
                <?php foreach ( $blog_categories as $bcat ) : ?>
                    <a class="home-inspiration__category" href="<?php echo esc_url( home_url( '/wedding-inspiration/' . $bcat['slug'] . '/' ) ); ?>">
                        <span class="home-inspiration__category-media">
                            <img
                                class="home-inspiration__category-image"
                                src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/' . $bcat['image'] ); ?>"
                                alt="<?php echo esc_attr( $bcat['title'] ); ?>"
                                loading="lazy"
                            >
                        </span>
                        <span class="home-inspiration__category-label"><?php echo esc_html( $bcat['title'] ); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="home-inspiration__articles">
                <?php foreach ( $featured_articles as $article ) : ?>
                    <a class="home-inspiration__article" href="<?php echo esc_url( home_url( $article['path'] ) ); ?>">
                        <span class="home-inspiration__article-media">
                            <img
                                class="home-inspiration__article-image"
                                src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/' . $article['image'] ); ?>"
                                alt="<?php echo esc_attr( $article['alt'] ); ?>"
                                loading="lazy"
                            >
                        </span>
                        <span class="home-inspiration__article-category"><?php echo esc_html( $article['category'] ); ?></span>
                        <h3 class="home-inspiration__article-title"><?php echo esc_html( $article['title'] ); ?></h3>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="home-inspiration__cta">
                <a class="btn btn--outline home-inspiration__button" href="<?php echo esc_url( home_url( '/wedding-inspiration/' ) ); ?>">
                    <span><?php esc_html_e( 'Browse all articles', 'sandiegoweddingdirectory' ); ?></span>
                    <span class="home-cta__icon" aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
    </section>

    <!-- SECTION 6: Find Your Location -->
    <section class="home-locations section">
        <div class="container">
            <?php
            $home_location_slides = [
                [ 'slug' => 'san-diego',       'name' => 'San Diego',       'image' => get_template_directory_uri() . '/assets/images/locations/san-diego.jpg' ],
                [ 'slug' => 'la-jolla',        'name' => 'La Jolla',        'image' => get_template_directory_uri() . '/assets/images/locations/la-jolla.jpg' ],
                [ 'slug' => 'coronado',        'name' => 'Coronado',        'image' => get_template_directory_uri() . '/assets/images/locations/coronado.jpg' ],
                [ 'slug' => 'del-mar',         'name' => 'Del Mar',         'image' => get_template_directory_uri() . '/assets/images/locations/del-mar.jpg' ],
                [ 'slug' => 'encinitas',       'name' => 'Encinitas',       'image' => get_template_directory_uri() . '/assets/images/locations/encinitas.jpg' ],
                [ 'slug' => 'carlsbad',        'name' => 'Carlsbad',        'image' => get_template_directory_uri() . '/assets/images/locations/carlsbad.jpg' ],
                [ 'slug' => 'oceanside',       'name' => 'Oceanside',       'image' => get_template_directory_uri() . '/assets/images/locations/oceanside.jpg' ],
                [ 'slug' => 'solana-beach',    'name' => 'Solana Beach',    'image' => get_template_directory_uri() . '/assets/images/locations/solana-beach.jpg' ],
                [ 'slug' => 'rancho-santa-fe', 'name' => 'Rancho Santa Fe', 'image' => get_template_directory_uri() . '/assets/images/locations/rancho-santa-fe.jpg' ],
                [ 'slug' => 'la-mesa',         'name' => 'La Mesa',         'image' => get_template_directory_uri() . '/assets/images/locations/la-mesa.jpg' ],
                [ 'slug' => 'san-marcos',      'name' => 'San Marcos',      'image' => get_template_directory_uri() . '/assets/images/locations/san-marcos.jpg' ],
                [ 'slug' => 'chula-vista',     'name' => 'Chula Vista',     'image' => get_template_directory_uri() . '/assets/images/locations/chula-vista.jpg' ],
            ];
            get_template_part( 'template-parts/components/section-title', null, [
                'heading' => __( 'Find Your Location', 'sandiegoweddingdirectory' ),
                'desc'    => __( 'Discover wedding venues and vendors across San Diego County', 'sandiegoweddingdirectory' ),
                'align'   => 'left',
            ] );
            ?>

            <div class="home-locations__carousel" data-carousel="cities">
                <button class="carousel-arrow carousel-arrow--prev home-locations__arrow" type="button" data-carousel-prev aria-label="<?php esc_attr_e( 'Previous locations', 'sandiegoweddingdirectory' ); ?>">
                    <span class="icon-chevron-left carousel-arrow__icon" aria-hidden="true"></span>
                </button>

                <div class="home-locations__viewport">
                    <div class="home-locations__track">
                        <?php foreach ( $home_location_slides as $city ) : ?>
                            <a class="home-locations__slide" href="<?php echo esc_url( home_url( '/venues/?location=' . $city['slug'] ) ); ?>">
                                <span class="home-locations__image">
                                    <img loading="lazy" decoding="async" src="<?php echo esc_url( $city['image'] ); ?>" alt="<?php echo esc_attr( $city['name'] ); ?>">
                                </span>
                                <span class="home-locations__name"><?php echo esc_html( $city['name'] ); ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button class="carousel-arrow carousel-arrow--next home-locations__arrow" type="button" data-carousel-next aria-label="<?php esc_attr_e( 'Next locations', 'sandiegoweddingdirectory' ); ?>">
                    <span class="icon-chevron-left carousel-arrow__icon" aria-hidden="true"></span>
                </button>
            </div>

            <div class="home-locations__cta">
                <a class="btn btn--outline home-locations__cta-button" href="<?php echo esc_url( home_url( '/venues/' ) ); ?>">
                    <span><?php esc_html_e( 'See All Cities', 'sandiegoweddingdirectory' ); ?></span>
                    <span class="home-cta__icon" aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
    </section>

    <!-- SECTION 7: Search by Category -->
    <section class="home-search-cat section">
        <div class="container">
            <?php
            get_template_part( 'template-parts/components/section-title', null, [
                'heading' => __( 'Search by category to find the perfect wedding team', 'sandiegoweddingdirectory' ),
            ] );

            get_template_part( 'template-parts/components/inline-link-grid', null, [
                'heading' => __( 'San Diego Wedding Venues', 'sandiegoweddingdirectory' ),
                'rows'    => $venue_rows,
            ] );

            get_template_part( 'template-parts/components/inline-link-grid', null, [
                'heading' => __( 'San Diego Wedding Vendors', 'sandiegoweddingdirectory' ),
                'rows'    => $vendor_rows,
            ] );
            ?>
        </div>
    </section>

    <!-- SECTION 8: 4-Column Value Text / Accordion -->
    <section class="home-value-cols section">
        <div class="container">
            <?php
            $value_items = [
                [
                    'title' => __( 'San Diego Wedding Vendors, All in One Place', 'sandiegoweddingdirectory' ),
                    'text'  => __( 'SDWeddingDirectory is built only for San Diego couples. That means every vendor you find here actually serves San Diego weddings, so you are not wasting time sorting through out-of-area venues.', 'sandiegoweddingdirectory' ),
                    'links' => [
                        [ 'label' => __( 'Wedding Venues', 'sandiegoweddingdirectory' ),        'url' => '/venues/' ],
                        [ 'label' => __( 'Wedding Photographers', 'sandiegoweddingdirectory' ), 'url' => '/vendors/photography/' ],
                        [ 'label' => __( 'Wedding DJs', 'sandiegoweddingdirectory' ),           'url' => '/vendors/djs/' ],
                        [ 'label' => __( 'Wedding Planners', 'sandiegoweddingdirectory' ),      'url' => '/vendors/wedding-planners/' ],
                    ],
                ],
                [
                    'title' => __( 'Simple Planning Tools That Keep You On Track', 'sandiegoweddingdirectory' ),
                    'text'  => __( 'Use our planning tools to stay organized from the first idea to the final send-off. Build your checklist, save favorites, and keep your wedding decisions in one place.', 'sandiegoweddingdirectory' ),
                    'links' => [
                        [ 'label' => __( 'Wedding Websites', 'sandiegoweddingdirectory' ),  'url' => '/dashboard/wedding-website/' ],
                        [ 'label' => __( 'Wedding Checklist', 'sandiegoweddingdirectory' ), 'url' => '/dashboard/' ],
                        [ 'label' => __( 'Wedding Budget', 'sandiegoweddingdirectory' ),    'url' => '/dashboard/' ],
                        [ 'label' => __( 'Wedding Guest List', 'sandiegoweddingdirectory' ),'url' => '/dashboard/' ],
                    ],
                ],
                [
                    'title' => __( 'Ideas and Inspiration You Can Actually Use', 'sandiegoweddingdirectory' ),
                    'text'  => __( 'Explore local-friendly wedding ideas, guides, and tips to help you make choices faster. From timelines and etiquette to music and design, we cover the stuff couples really need.', 'sandiegoweddingdirectory' ),
                    'links' => [
                        [ 'label' => __( 'Wedding Ideas', 'sandiegoweddingdirectory' ),      'url' => '/wedding-inspiration/' ],
                        [ 'label' => __( 'Real Weddings', 'sandiegoweddingdirectory' ),      'url' => '/real-weddings/' ],
                        [ 'label' => __( 'Wedding Dresses', 'sandiegoweddingdirectory' ),    'url' => '/vendors/dress-attire/' ],
                        [ 'label' => __( 'Wedding Hairstyles', 'sandiegoweddingdirectory' ), 'url' => '/vendors/hair-makeup/' ],
                    ],
                ],
                [
                    'title' => __( 'Help and Answers When You Need Them', 'sandiegoweddingdirectory' ),
                    'text'  => __( 'Find quick answers in our FAQ, learn what SDWeddingDirectory is about, and reach out anytime if you need help using the site or connecting with vendors and venues.', 'sandiegoweddingdirectory' ),
                    'links' => [
                        [ 'label' => __( 'About Us', 'sandiegoweddingdirectory' ), 'url' => '/about/' ],
                        [ 'label' => __( 'Contact', 'sandiegoweddingdirectory' ),  'url' => '/contact/' ],
                        [ 'label' => __( 'FAQs', 'sandiegoweddingdirectory' ),     'url' => '/faqs/' ],
                    ],
                ],
            ];
            ?>

            <!-- Desktop: 4-column grid -->
            <div class="home-value-cols__grid grid grid--4col">
                <?php foreach ( $value_items as $item ) : ?>
                <div class="home-value-cols__col">
                    <h5 class="home-value-cols__title"><?php echo esc_html( $item['title'] ); ?></h5>
                    <p class="home-value-cols__text"><?php echo esc_html( $item['text'] ); ?></p>
                    <ul class="home-value-cols__links">
                        <?php foreach ( $item['links'] as $link ) : ?>
                        <li><a class="home-value-cols__link" href="<?php echo esc_url( home_url( $link['url'] ) ); ?>"><?php echo esc_html( $link['label'] ); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Mobile: accordion -->
            <div class="home-value-accordion faq-accordion">
                <?php foreach ( $value_items as $i => $item ) : ?>
                <div class="faq-accordion__item">
                    <button class="faq-accordion__question" aria-expanded="false">
                        <?php echo esc_html( $item['title'] ); ?>
                    </button>
                    <div class="faq-accordion__answer" data-visible="false">
                        <p><?php echo esc_html( $item['text'] ); ?></p>
                        <ul class="home-value-cols__links">
                            <?php foreach ( $item['links'] as $link ) : ?>
                            <li><a class="home-value-cols__link" href="<?php echo esc_url( home_url( $link['url'] ) ); ?>"><?php echo esc_html( $link['label'] ); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- SECTION 9: Why SDWeddingDirectory -->
    <section class="home-why section">
        <div class="container">
            <h2 class="home-why__title"><?php esc_html_e( 'Why SDWeddingDirectory?', 'sandiegoweddingdirectory' ); ?></h2>
            <div class="home-why__content">
                <p><?php esc_html_e( 'SDWeddingDirectory brings San Diego couples and local pros together in one place, so you can compare vendors, check availability, and request pricing without bouncing between a dozen sites. Find options that fit your style and your budget, whether you are booking a venue or lining up every detail across the county.', 'sandiegoweddingdirectory' ); ?></p>
                <p><?php esc_html_e( 'You also get free planning tools that keep everything organized from day one, including customizable wedding checklists and a personalized wedding site you can share with guests. Track tasks, manage all the moving pieces, and keep your plans clear as your date gets closer.', 'sandiegoweddingdirectory' ); ?></p>
                <p><?php esc_html_e( 'Need ideas while you plan? Our editorial content and real-wedding inspiration help you narrow your options and make confident decisions on everything from florals and desserts to photography and wedding venues. SDWeddingDirectory is built to help you go from "we are engaged" to "we did it" with less stress and better options.', 'sandiegoweddingdirectory' ); ?></p>
            </div>
        </div>
    </section>

    <!-- SECTION 10: Browse Wedding Venues by City -->
    <section class="home-venues-city section">
        <div class="container">
            <h2 class="home-venues-city__title"><?php esc_html_e( 'Browse Wedding Venues by City', 'sandiegoweddingdirectory' ); ?></h2>

            <?php if ( ! empty( $city_terms ) ) : ?>
                <div class="home-venues-city__grid">
                    <?php foreach ( $city_terms as $city ) : ?>
                        <a class="home-venues-city__link" href="<?php echo esc_url( home_url( '/venues/?location=' . $city->slug ) ); ?>">
                            <?php echo esc_html( $city->name . ' Wedding Venues' ); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

</div>

<?php
get_footer();
