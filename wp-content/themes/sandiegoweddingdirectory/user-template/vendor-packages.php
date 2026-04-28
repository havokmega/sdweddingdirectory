<?php
/**
 * Template Name: Vendor Pricing Packages
 *
 * Sub-page: /vendor-dashboard/packages/
 * Spec s2: vendor creates packages shown on profile.
 */

if ( ! is_user_logged_in() ) { wp_redirect( home_url( '/' ) ); exit; }

$user    = wp_get_current_user();
$post_id = get_user_meta( $user->ID, 'sdwd_post_id', true );
if ( ! $post_id ) { wp_redirect( home_url( '/' ) ); exit; }

$packages = get_post_meta( $post_id, 'sdwd_packages', true );
if ( ! is_array( $packages ) ) { $packages = []; }

get_header();
?>

<div class="container">
    <div class="vd-wrap vd-wrap--single">
        <?php
        get_template_part( 'template-parts/vendor-dashboard/vendor-dashboard-s1-sidebar', null, [ 'active' => 'packages' ] );
        ?>
        <main class="vd-main">
            <?php
            get_template_part( 'template-parts/vendor-packages/vendor-packages-shell', null, [
                'packages' => $packages,
                'post_id'  => $post_id,
            ] );
            ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>
