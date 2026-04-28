<?php
/**
 * Template Name: Vendor Business Hours
 *
 * Sub-page: /vendor-dashboard/hours/
 * Spec s3: per-day open/closed/24h with open and close times.
 */

if ( ! is_user_logged_in() ) { wp_redirect( home_url( '/' ) ); exit; }

$user    = wp_get_current_user();
$post_id = get_user_meta( $user->ID, 'sdwd_post_id', true );
if ( ! $post_id ) { wp_redirect( home_url( '/' ) ); exit; }

$hours = get_post_meta( $post_id, 'sdwd_hours', true );
if ( ! is_array( $hours ) ) { $hours = []; }

get_header();
?>

<div class="container">
    <div class="vd-wrap vd-wrap--single">
        <?php
        get_template_part( 'template-parts/vendor-dashboard/vendor-dashboard-s1-sidebar', null, [ 'active' => 'hours' ] );
        ?>
        <main class="vd-main">
            <?php
            get_template_part( 'template-parts/vendor-hours/vendor-hours-shell', null, [
                'hours'   => $hours,
                'post_id' => $post_id,
            ] );
            ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>
