<?php
/**
 * Template Name: Couple Seating Chart
 *
 * Sub-page: /couple-dashboard/seating-chart/
 * Spec s18: top controls + table list left + seating canvas right (drag/drop).
 * MVP shell — drag/drop interaction wired in a follow-up pass.
 */

if ( ! is_user_logged_in() ) { wp_redirect( home_url( '/' ) ); exit; }

$user    = wp_get_current_user();
$post_id = get_user_meta( $user->ID, 'sdwd_post_id', true );
if ( ! $post_id ) { wp_redirect( home_url( '/' ) ); exit; }

$tables = get_user_meta( $user->ID, 'sdwd_seating_tables', true );
$guests = get_user_meta( $user->ID, 'sdwd_guests', true );
if ( ! is_array( $tables ) ) { $tables = []; }
if ( ! is_array( $guests ) ) { $guests = []; }

get_header();
?>

<div class="container">
    <div class="cd-wrap cd-wrap--single">
        <?php
        get_template_part( 'template-parts/couple-dashboard/couple-dashboard-s1-sidebar', null, [ 'active' => 'seating-chart' ] );
        ?>
        <main class="cd-main">
            <?php
            get_template_part( 'template-parts/couple-seating-chart/couple-seating-chart-shell', null, [
                'tables' => $tables,
                'guests' => $guests,
            ] );
            ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>
