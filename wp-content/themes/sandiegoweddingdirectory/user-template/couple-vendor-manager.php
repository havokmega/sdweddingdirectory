<?php
/**
 * Template Name: Couple Vendor Manager
 *
 * Sub-page: /couple-dashboard/vendor-manager/
 * Spec s11: top horizontal tabs (favorites / overview / hired) + category sidebar +
 * content area.
 */

if ( ! is_user_logged_in() ) { wp_redirect( home_url( '/' ) ); exit; }

$user    = wp_get_current_user();
$post_id = get_user_meta( $user->ID, 'sdwd_post_id', true );
if ( ! $post_id ) { wp_redirect( home_url( '/' ) ); exit; }

$active_mode = isset( $_GET['mode'] ) ? sanitize_key( $_GET['mode'] ) : 'overview';

get_header();
?>

<div class="container">
    <div class="cd-wrap cd-wrap--single">
        <?php
        get_template_part( 'template-parts/couple-dashboard/couple-dashboard-s1-sidebar', null, [ 'active' => 'vendor-manager' ] );
        ?>
        <main class="cd-main">
            <?php
            get_template_part( 'template-parts/couple-vendor-manager/couple-vendor-manager-shell', null, [
                'active_mode' => $active_mode,
                'user'        => $user,
            ] );
            ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>
