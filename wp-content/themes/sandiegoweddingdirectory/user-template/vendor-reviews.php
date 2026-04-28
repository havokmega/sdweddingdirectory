<?php
/**
 * Template Name: Vendor My Reviews
 *
 * Sub-page: /vendor-dashboard/reviews/
 * Spec s5: vendor sees reviews about themselves and can reply.
 */

if ( ! is_user_logged_in() ) { wp_redirect( home_url( '/' ) ); exit; }

$user    = wp_get_current_user();
$post_id = get_user_meta( $user->ID, 'sdwd_post_id', true );
if ( ! $post_id ) { wp_redirect( home_url( '/' ) ); exit; }

$reviews = get_comments( [
    'post_id' => $post_id,
    'type'    => 'review',
    'status'  => 'approve',
] );

get_header();
?>

<div class="container">
    <div class="vd-wrap vd-wrap--single">
        <?php
        get_template_part( 'template-parts/vendor-dashboard/vendor-dashboard-s1-sidebar', null, [ 'active' => 'reviews' ] );
        ?>
        <main class="vd-main">
            <?php
            get_template_part( 'template-parts/vendor-reviews/vendor-reviews-shell', null, [
                'reviews' => $reviews,
            ] );
            ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>
