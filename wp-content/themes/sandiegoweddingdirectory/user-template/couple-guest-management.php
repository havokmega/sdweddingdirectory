<?php
/**
 * Template Name: Couple Guest Management
 *
 * Sub-page: /couple-dashboard/guest-management/
 * Spec s15: overview state (event tabs + table) and per-event state.
 */

if ( ! is_user_logged_in() ) { wp_redirect( home_url( '/' ) ); exit; }

$user    = wp_get_current_user();
$post_id = get_user_meta( $user->ID, 'sdwd_post_id', true );
if ( ! $post_id ) { wp_redirect( home_url( '/' ) ); exit; }

$active_event = isset( $_GET['event'] ) ? sanitize_key( $_GET['event'] ) : 'overview';

$guests = get_user_meta( $user->ID, 'sdwd_guests', true );
if ( ! is_array( $guests ) ) { $guests = []; }

get_header();
?>

<div class="container">
    <div class="cd-wrap cd-wrap--single">
        <?php
        get_template_part( 'template-parts/couple-dashboard/couple-dashboard-s1-sidebar', null, [ 'active' => 'guest-management' ] );
        ?>
        <main class="cd-main">
            <?php
            get_template_part( 'template-parts/couple-guest-management/couple-guest-management-shell', null, [
                'active_event' => $active_event,
                'guests'       => $guests,
            ] );
            ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>
