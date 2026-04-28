<?php
/**
 * Template Name: Couple My Reviews
 *
 * Sub-page: /couple-dashboard/reviews/
 * Spec s16: vendor list left + review editor right.
 */

if ( ! is_user_logged_in() ) { wp_redirect( home_url( '/' ) ); exit; }

$user    = wp_get_current_user();
$post_id = get_user_meta( $user->ID, 'sdwd_post_id', true );
if ( ! $post_id ) { wp_redirect( home_url( '/' ) ); exit; }

$selected_vendor = isset( $_GET['vendor'] ) ? (int) $_GET['vendor'] : 0;

get_header();
?>

<div class="container">
    <div class="cd-wrap cd-wrap--single">
        <?php
        get_template_part( 'template-parts/couple-dashboard/couple-dashboard-s1-sidebar', null, [ 'active' => 'reviews' ] );
        ?>
        <main class="cd-main">
            <?php
            get_template_part( 'template-parts/couple-reviews/couple-reviews-shell', null, [
                'selected_vendor' => $selected_vendor,
                'user_id'         => $user->ID,
            ] );
            ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>
