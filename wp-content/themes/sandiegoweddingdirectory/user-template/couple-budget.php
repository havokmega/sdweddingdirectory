<?php
/**
 * Template Name: Couple Budget
 *
 * Sub-page: /couple-dashboard/budget/
 * Spec s13: top summary + category sidebar + table.
 */

if ( ! is_user_logged_in() ) { wp_redirect( home_url( '/' ) ); exit; }

$user    = wp_get_current_user();
$post_id = get_user_meta( $user->ID, 'sdwd_post_id', true );
if ( ! $post_id ) { wp_redirect( home_url( '/' ) ); exit; }

$budget_total = (float) get_user_meta( $user->ID, 'sdwd_budget_total', true );
$budget_items = get_user_meta( $user->ID, 'sdwd_budget_items', true );
if ( ! is_array( $budget_items ) ) { $budget_items = []; }

get_header();
?>

<div class="container">
    <div class="cd-wrap cd-wrap--single">
        <?php
        get_template_part( 'template-parts/couple-dashboard/couple-dashboard-s1-sidebar', null, [ 'active' => 'budget' ] );
        ?>
        <main class="cd-main">
            <?php
            get_template_part( 'template-parts/couple-budget/couple-budget-shell', null, [
                'budget_total' => $budget_total,
                'budget_items' => $budget_items,
            ] );
            ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>
