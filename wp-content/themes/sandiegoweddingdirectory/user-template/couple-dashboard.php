<?php
/**
 * Template Name: Couple Dashboard
 *
 * Orchestrator. Pulls data from SDWD Core/Couple plugin meta and hands it
 * to template parts in /template-parts/couple-dashboard/. Rendering only —
 * no business logic or HTML layout belongs in this file.
 */

if ( ! is_user_logged_in() ) {
    wp_redirect( home_url( '/' ) );
    exit;
}

$user    = wp_get_current_user();
$post_id = get_user_meta( $user->ID, 'sdwd_post_id', true );

if ( ! $post_id ) {
    wp_redirect( home_url( '/' ) );
    exit;
}

// Couple-post meta (stored against the couple CPT).
$email        = get_post_meta( $post_id, 'sdwd_email', true );
$phone        = get_post_meta( $post_id, 'sdwd_phone', true );
$wedding_date = get_post_meta( $post_id, 'sdwd_wedding_date', true );

// User meta (planning-tool data lives on the user, per SDWD Couple plugin).
$wishlist     = get_user_meta( $user->ID, 'sdwd_wishlist', true );
$checklist    = get_user_meta( $user->ID, 'sdwd_checklist', true );
$budget_total = get_user_meta( $user->ID, 'sdwd_budget_total', true );
$budget_items = get_user_meta( $user->ID, 'sdwd_budget_items', true );

// Normalize.
if ( ! is_array( $wishlist ) )     { $wishlist     = []; }
if ( ! is_array( $checklist ) )    { $checklist    = []; }
if ( ! is_array( $budget_items ) ) { $budget_items = []; }

// Derived values for hero.
$checklist_total     = count( $checklist );
$checklist_completed = count( array_filter( $checklist, fn( $t ) => ! empty( $t['completed'] ) ) );

get_header();
?>

<div class="container">
    <div class="cd-wrap">

        <?php
        get_template_part( 'template-parts/couple-dashboard/couple-dashboard-sidebar-left', null, [
            'active' => 'dashboard',
        ] );

        get_template_part( 'template-parts/couple-dashboard/couple-dashboard-hero', null, [
            'first_name'          => $user->first_name,
            'partner_name'        => '', // Partner name not yet in data model.
            'wedding_date'        => $wedding_date,
            'checklist_total'     => $checklist_total,
            'checklist_completed' => $checklist_completed,
        ] );
        ?>

        <main class="cd-main">
            <?php
            get_template_part( 'template-parts/couple-dashboard/couple-dashboard-section-1-vendor-team', null, [
                'wishlist' => $wishlist,
            ] );

            get_template_part( 'template-parts/couple-dashboard/couple-dashboard-section-2-guest-list' );

            get_template_part( 'template-parts/couple-dashboard/couple-dashboard-section-3-wedding-details', null, [
                'first_name'   => $user->first_name,
                'last_name'    => $user->last_name,
                'email'        => $email,
                'phone'        => $phone,
                'wedding_date' => $wedding_date,
            ] );
            ?>
        </main>

        <aside class="cd-side">
            <?php
            get_template_part( 'template-parts/couple-dashboard/couple-dashboard-sidebar-right-1-website-link', null, [
                'website_url' => '', // Wedding website module not yet rebuilt.
            ] );

            get_template_part( 'template-parts/couple-dashboard/couple-dashboard-sidebar-right-2-task-list', null, [
                'checklist' => $checklist,
            ] );

            get_template_part( 'template-parts/couple-dashboard/couple-dashboard-sidebar-right-3-budget', null, [
                'budget_total' => $budget_total,
                'budget_items' => $budget_items,
            ] );

            get_template_part( 'template-parts/couple-dashboard/couple-dashboard-sidebar-right-4-rsvp' );
            ?>
        </aside>

    </div>
</div>

<?php get_footer(); ?>
