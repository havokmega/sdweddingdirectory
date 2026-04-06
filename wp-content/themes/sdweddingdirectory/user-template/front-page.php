<?php
/**
 *   Template Name: Front-page Template
 *   ----------------------------------
 */

global $wp_query, $post;

get_header();

$featured_city_slides = [
    [ 'slug' => 'san-diego', 'name' => 'San Diego', 'image' => get_theme_file_uri( 'assets/images/locations/san-diego.jpg' ) ],
    [ 'slug' => 'la-jolla', 'name' => 'La Jolla', 'image' => get_theme_file_uri( 'assets/images/locations/la-jolla.jpg' ) ],
    [ 'slug' => 'coronado', 'name' => 'Coronado', 'image' => get_theme_file_uri( 'assets/images/locations/coronado.jpg' ) ],
    [ 'slug' => 'del-mar', 'name' => 'Del Mar', 'image' => get_theme_file_uri( 'assets/images/locations/del-mar.jpg' ) ],
    [ 'slug' => 'encinitas', 'name' => 'Encinitas', 'image' => get_theme_file_uri( 'assets/images/locations/encinitas.jpg' ) ],
    [ 'slug' => 'carlsbad', 'name' => 'Carlsbad', 'image' => get_theme_file_uri( 'assets/images/locations/carlsbad.jpg' ) ],
    [ 'slug' => 'oceanside', 'name' => 'Oceanside', 'image' => get_theme_file_uri( 'assets/images/locations/oceanside.jpg' ) ],
    [ 'slug' => 'solana-beach', 'name' => 'Solana Beach', 'image' => get_theme_file_uri( 'assets/images/locations/solana-beach.jpg' ) ],
    [ 'slug' => 'escondido', 'name' => 'Escondido', 'image' => get_theme_file_uri( 'assets/images/locations/escondido.jpg' ) ],
    [ 'slug' => 'chula-vista', 'name' => 'Chula Vista', 'image' => get_theme_file_uri( 'assets/images/locations/chula-vista.jpg' ) ],
    [ 'slug' => 'el-cajon', 'name' => 'El Cajon', 'image' => get_theme_file_uri( 'assets/images/locations/el-cajon.jpg' ) ],
    [ 'slug' => 'poway', 'name' => 'Poway', 'image' => get_theme_file_uri( 'assets/images/locations/poway.jpg' ) ],
    [ 'slug' => 'rancho-santa-fe', 'name' => 'Rancho Santa Fe', 'image' => get_theme_file_uri( 'assets/images/locations/rancho-santa-fe.jpg' ) ],
    [ 'slug' => 'imperial-beach', 'name' => 'Imperial Beach', 'image' => get_theme_file_uri( 'assets/images/locations/imperial-beach.jpg' ) ],
    [ 'slug' => 'san-marcos', 'name' => 'San Marcos', 'image' => get_theme_file_uri( 'assets/images/locations/san-marcos.jpg' ) ],
];

$venue_rows = [
    [
        [ 'label' => 'Banquet Halls', 'path' => '/venue-types/banquet-halls/' ],
        [ 'label' => 'Barns & Farms', 'path' => '/venue-types/barns-farms/' ],
        [ 'label' => 'Beaches', 'path' => '/venue-types/beaches/' ],
        [ 'label' => 'Boats', 'path' => '/venue-types/boats/' ],
        [ 'label' => 'Churches & Temples', 'path' => '/venue-types/churches-temples/' ],
        [ 'label' => 'Country Clubs', 'path' => '/venue-types/country-clubs/' ],
    ],
    [
        [ 'label' => 'Gardens', 'path' => '/venue-types/gardens/' ],
        [ 'label' => 'Historic Venues', 'path' => '/venue-types/historic-venues/' ],
        [ 'label' => 'Hotels', 'path' => '/venue-types/hotels/' ],
        [ 'label' => 'Mansions', 'path' => '/venue-types/mansions/' ],
        [ 'label' => 'Museums', 'path' => '/venue-types/museums/' ],
        [ 'label' => 'Outdoor', 'path' => '/venue-types/outdoor/' ],
    ],
    [
        [ 'label' => 'Parks', 'path' => '/venue-types/parks/' ],
        [ 'label' => 'Restaurants', 'path' => '/venue-types/restaurants/' ],
        [ 'label' => 'Rooftops & Lofts', 'path' => '/venue-types/rooftops-lofts/' ],
        [ 'label' => 'Waterfronts', 'path' => '/venue-types/waterfronts/' ],
        [ 'label' => 'Wineries & Breweries', 'path' => '/venue-types/wineries-breweries/' ],
    ],
];

$vendor_rows = [
    [
        [ 'label' => 'Bands', 'path' => '/vendors/bands/' ],
        [ 'label' => 'Cakes', 'path' => '/vendors/cakes/' ],
        [ 'label' => 'Catering', 'path' => '/vendors/catering/' ],
        [ 'label' => 'Ceremony Music', 'path' => '/vendors/ceremony-music/' ],
        [ 'label' => 'DJs', 'path' => '/vendors/djs/' ],
        [ 'label' => 'Dress & Attire', 'path' => '/vendors/dress-attire/' ],
        [ 'label' => 'Event Rentals', 'path' => '/vendors/event-rentals/' ],
    ],
    [
        [ 'label' => 'Favors & Gifts', 'path' => '/vendors/favors-gifts/' ],
        [ 'label' => 'Flowers', 'path' => '/vendors/flowers/' ],
        [ 'label' => 'Hair & Makeup', 'path' => '/vendors/hair-makeup/' ],
        [ 'label' => 'Invitations', 'path' => '/vendors/invitations/' ],
        [ 'label' => 'Jewelry', 'path' => '/vendors/jewelry/' ],
        [ 'label' => 'Lighting & Decor', 'path' => '/vendors/lighting-decor/' ],
        [ 'label' => 'Officiants', 'path' => '/vendors/officiants/' ],
    ],
    [
        [ 'label' => 'Photo Booths', 'path' => '/vendors/photo-booths/' ],
        [ 'label' => 'Photography', 'path' => '/vendors/photography/' ],
        [ 'label' => 'Transportation', 'path' => '/vendors/transportation/' ],
        [ 'label' => 'Travel Agents', 'path' => '/vendors/travel-agents/' ],
        [ 'label' => 'Videography', 'path' => '/vendors/videography/' ],
        [ 'label' => 'Wedding Planners', 'path' => '/vendors/wedding-planners/' ],
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

$GLOBALS['featured_city_slides'] = $featured_city_slides;
$GLOBALS['venue_rows'] = $venue_rows;
$GLOBALS['vendor_rows'] = $vendor_rows;
$GLOBALS['city_terms'] = $city_terms;
?>

<div class="sdwd-home-sections">

    <!-- HOME SECTION: Hero Search Banner -->
    <?php get_template_part('template-parts/home/hero-search-banner'); ?>
    <!-- /HOME SECTION: Hero Search Banner -->

    <!-- HOME SECTION: San Diego Wedding Vendors -->
    <?php get_template_part('template-parts/home/sd-wedding-vendors'); ?>
    <!-- /HOME SECTION: San Diego Wedding Vendors -->

    <!-- HOME SECTION: Plan Your San Diego Wedding -->
    <?php get_template_part('template-parts/home/plan-your-sd-wedding'); ?>
    <!-- /HOME SECTION: Plan Your San Diego Wedding -->

    <!-- HOME SECTION: Real Weddings -->
    <?php get_template_part('template-parts/home/real-weddings'); ?>
    <!-- /HOME SECTION: Real Weddings -->

    <!-- HOME SECTION: Inspiration -->
    <?php get_template_part('template-parts/home/inspiration'); ?>
    <!-- /HOME SECTION: Inspiration -->

    <!-- HOME SECTION: Find Your Location -->
    <?php get_template_part('template-parts/home/find-your-location'); ?>
    <!-- /HOME SECTION: Find Your Location -->

    <!-- HOME SECTION: Search by Category -->
    <?php get_template_part('template-parts/home/search-by-category'); ?>
    <!-- /HOME SECTION: Search by Category -->

    <!-- HOME SECTION: Planning / Directory Value Columns -->
    <?php get_template_part('template-parts/home/value-columns'); ?>
    <!-- /HOME SECTION: Planning / Directory Value Columns -->

    <!-- HOME SECTION: Why SDWeddingDirectory -->
    <?php get_template_part('template-parts/home/why-sdweddingdirectory'); ?>
    <!-- /HOME SECTION: Why SDWeddingDirectory -->

    <!-- HOME SECTION: Browse Wedding Venues by City -->
    <?php get_template_part('template-parts/home/venues-by-city'); ?>
    <!-- /HOME SECTION: Browse Wedding Venues by City -->
</div>

<?php
get_footer();
