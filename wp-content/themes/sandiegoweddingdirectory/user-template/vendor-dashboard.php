<?php
/**
 * Template Name: Vendor Dashboard
 *
 * Thin wrapper for the vendor/venue dashboard.
 * Plugin renders all dashboard markup via action hook.
 */

if ( ! is_user_logged_in() ) {
    wp_redirect( wp_login_url( get_permalink() ) );
    exit;
}

get_header();
?>

<div class="dashboard-wrapper">
    <?php do_action( 'sdweddingdirectory/dashboard' ); ?>
</div>

<?php
get_footer();
