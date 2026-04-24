<?php
/**
 * Template Name: Vendor Dashboard
 *
 * Landing page for the vendor dashboard. Calls template parts in
 * /template-parts/vendor-dashboard/. Rendering only — no business logic.
 */

if ( ! is_user_logged_in() ) {
    wp_redirect( home_url( '/' ) );
    exit;
}

$user    = wp_get_current_user();
$post_id = get_user_meta( $user->ID, 'sdwd_post_id', true );

if ( ! $post_id ) {
    wp_redirect( home_url( '/' ) );
    exit;
}

$post    = get_post( $post_id );
$company = get_post_meta( $post_id, 'sdwd_company_name', true );
if ( empty( $company ) ) {
    $company = $post ? $post->post_title : '';
}
$profile_url  = $post ? get_permalink( $post ) : '';
$current_slug = $post ? $post->post_name : '';
$base_url     = home_url( '/vendor/' );

// Profile completeness — simple score across 7 core fields.
$fields_to_check = [
    'sdwd_company_name', 'sdwd_email', 'sdwd_phone',
    'sdwd_company_website', 'sdwd_hours', 'sdwd_pricing', 'sdwd_social',
];
$filled = 0;
foreach ( $fields_to_check as $k ) {
    $v = get_post_meta( $post_id, $k, true );
    if ( ! empty( $v ) ) { $filled++; }
}
if ( ! empty( $post->post_content ) ) { $filled++; }
$profile_complete = (int) round( ( $filled / ( count( $fields_to_check ) + 1 ) ) * 100 );

// Quote requests list — wire to plugin data when the module lands.
$quotes = [];

get_header();
?>

<div class="container">
    <div class="vd-wrap">

        <?php
        get_template_part( 'template-parts/vendor-dashboard/vendor-dashboard-sidebar-left', null, [
            'active' => 'dashboard',
        ] );

        get_template_part( 'template-parts/vendor-dashboard/vendor-dashboard-hero', null, [
            'company_name'     => $company,
            'profile_complete' => $profile_complete,
            'profile_url'      => $profile_url,
        ] );
        ?>

        <main class="vd-main">
            <?php
            get_template_part( 'template-parts/vendor-dashboard/vendor-dashboard-section-1-quote-requests', null, [
                'quotes' => $quotes,
            ] );

            get_template_part( 'template-parts/vendor-dashboard/vendor-dashboard-section-2-url-slug', null, [
                'current_slug' => $current_slug,
                'profile_url'  => $profile_url,
                'base_url'     => $base_url,
            ] );
            ?>
        </main>

        <aside class="vd-side">
            <?php
            get_template_part( 'template-parts/vendor-dashboard/vendor-dashboard-sidebar-right-1-profile-link', null, [
                'profile_url'      => $profile_url,
                'profile_complete' => $profile_complete,
            ] );

            get_template_part( 'template-parts/vendor-dashboard/vendor-dashboard-sidebar-right-2-tips' );

            get_template_part( 'template-parts/vendor-dashboard/vendor-dashboard-sidebar-right-3-support' );
            ?>
        </aside>

    </div>
</div>

<?php get_footer(); ?>
