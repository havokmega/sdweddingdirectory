<?php
/**
 * Template Name: Couple Real Wedding Editor
 *
 * Sub-page: /couple-dashboard/real-wedding/
 * Spec s14: vertical tabs (About / Wedding Info / Hire Vendors) + content.
 */

if ( ! is_user_logged_in() ) { wp_redirect( home_url( '/' ) ); exit; }

$user    = wp_get_current_user();
$post_id = get_user_meta( $user->ID, 'sdwd_post_id', true );
if ( ! $post_id ) { wp_redirect( home_url( '/' ) ); exit; }

$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'about';

get_header();
?>

<div class="container">
    <div class="cd-wrap cd-wrap--single">
        <?php
        get_template_part( 'template-parts/couple-dashboard/couple-dashboard-s1-sidebar', null, [ 'active' => 'real-wedding' ] );
        ?>
        <main class="cd-main">
            <?php
            get_template_part( 'template-parts/couple-real-wedding/couple-real-wedding-shell', null, [
                'active_tab' => $active_tab,
                'post_id'    => $post_id,
            ] );
            ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>
