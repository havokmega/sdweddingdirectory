<?php
/**
 * Template Name: Vendor Quote Requests
 *
 * Sub-page: /vendor-dashboard/quote-requests/
 * Spec s4: inbox of leads from couples.
 */

if ( ! is_user_logged_in() ) { wp_redirect( home_url( '/' ) ); exit; }

$user    = wp_get_current_user();
$post_id = get_user_meta( $user->ID, 'sdwd_post_id', true );
if ( ! $post_id ) { wp_redirect( home_url( '/' ) ); exit; }

$quotes = get_post_meta( $post_id, 'sdwd_quote_requests', true );
if ( ! is_array( $quotes ) ) { $quotes = []; }

get_header();
?>

<div class="container">
    <div class="vd-wrap vd-wrap--single">
        <?php
        get_template_part( 'template-parts/vendor-dashboard/vendor-dashboard-s1-sidebar', null, [ 'active' => 'quote-requests' ] );
        ?>
        <main class="vd-main">
            <?php
            get_template_part( 'template-parts/vendor-quote-requests/vendor-quote-requests-shell', null, [
                'quotes' => $quotes,
            ] );
            ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>
