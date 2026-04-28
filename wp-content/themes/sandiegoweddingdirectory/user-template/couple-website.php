<?php
/**
 * Template Name: Couple Wedding Website Editor
 *
 * Sub-page: /couple-dashboard/website/
 * Spec s17: vertical section tabs (Header / About Us / Our Story / Countdown /
 * RSVP / Groomsman / Bridesmaids / Thank You / Gallery / When & Where / Footer)
 * + live editable content + preview button + save bar.
 */

if ( ! is_user_logged_in() ) { wp_redirect( home_url( '/' ) ); exit; }

$user    = wp_get_current_user();
$post_id = get_user_meta( $user->ID, 'sdwd_post_id', true );
if ( ! $post_id ) { wp_redirect( home_url( '/' ) ); exit; }

$active_section = isset( $_GET['section'] ) ? sanitize_key( $_GET['section'] ) : 'header';

get_header();
?>

<div class="container">
    <div class="cd-wrap cd-wrap--single">
        <?php
        get_template_part( 'template-parts/couple-dashboard/couple-dashboard-s1-sidebar', null, [ 'active' => 'wedding-website' ] );
        ?>
        <main class="cd-main">
            <?php
            get_template_part( 'template-parts/couple-website/couple-website-shell', null, [
                'active_section' => $active_section,
                'post_id'        => $post_id,
            ] );
            ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>
