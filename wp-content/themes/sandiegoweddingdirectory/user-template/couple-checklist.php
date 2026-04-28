<?php
/**
 * Template Name: Couple Checklist
 *
 * Sub-page: /couple-dashboard/checklist/
 * Spec s12: progress bar + grouped tasks + task rows.
 */

if ( ! is_user_logged_in() ) { wp_redirect( home_url( '/' ) ); exit; }

$user    = wp_get_current_user();
$post_id = get_user_meta( $user->ID, 'sdwd_post_id', true );
if ( ! $post_id ) { wp_redirect( home_url( '/' ) ); exit; }

$checklist = get_user_meta( $user->ID, 'sdwd_checklist', true );
if ( ! is_array( $checklist ) ) { $checklist = []; }

get_header();
?>

<div class="container">
    <div class="cd-wrap cd-wrap--single">
        <?php
        get_template_part( 'template-parts/couple-dashboard/couple-dashboard-s1-sidebar', null, [ 'active' => 'checklist' ] );
        ?>
        <main class="cd-main">
            <?php
            get_template_part( 'template-parts/couple-checklist/couple-checklist-shell', null, [
                'checklist' => $checklist,
            ] );
            ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>
