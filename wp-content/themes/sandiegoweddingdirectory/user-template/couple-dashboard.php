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
$email                 = get_post_meta( $post_id, 'sdwd_email', true );
$phone                 = get_post_meta( $post_id, 'sdwd_phone', true );
$wedding_date          = get_post_meta( $post_id, 'sdwd_wedding_date', true );
$partner_first_name    = get_post_meta( $post_id, 'sdwd_partner_first_name', true );
$partner_last_name     = get_post_meta( $post_id, 'sdwd_partner_last_name', true );
$wedding_color         = get_post_meta( $post_id, 'sdwd_wedding_color', true );
$wedding_season        = get_post_meta( $post_id, 'sdwd_wedding_season', true );
$wedding_style         = get_post_meta( $post_id, 'sdwd_wedding_style', true );
$wedding_attire        = get_post_meta( $post_id, 'sdwd_wedding_attire', true );
$wedding_honeymoon     = get_post_meta( $post_id, 'sdwd_wedding_honeymoon', true );
$couple_photo_id       = (int) get_post_meta( $post_id, 'sdwd_couple_photo_id', true );
$partner_photo_id      = (int) get_post_meta( $post_id, 'sdwd_partner_photo_id', true );
$hero_photo_id         = (int) get_post_meta( $post_id, 'sdwd_hero_photo_id', true );

// User meta (planning-tool data lives on the user).
$wishlist     = get_user_meta( $user->ID, 'sdwd_wishlist', true );
$checklist    = get_user_meta( $user->ID, 'sdwd_checklist', true );
$budget_total = get_user_meta( $user->ID, 'sdwd_budget_total', true );
$budget_items = get_user_meta( $user->ID, 'sdwd_budget_items', true );
$guests       = get_user_meta( $user->ID, 'sdwd_guests', true );
$vendor_team  = get_user_meta( $user->ID, 'sdwd_vendor_team', true );

// Normalize.
if ( ! is_array( $wishlist ) )     { $wishlist     = []; }
if ( ! is_array( $checklist ) )    { $checklist    = []; }
if ( ! is_array( $budget_items ) ) { $budget_items = []; }
if ( ! is_array( $guests ) )       { $guests       = []; }
if ( ! is_array( $vendor_team ) )  { $vendor_team  = []; }

// Derived values for hero.
$checklist_total     = count( $checklist );
$checklist_completed = count( array_filter( $checklist, fn( $t ) => ! empty( $t['completed'] ) ) );

// Vendor stats.
$vendors_total = count( $vendor_team );
$vendors_hired = count( array_filter( $vendor_team, fn( $v ) => ! empty( $v['hired'] ) ) );

// Guest stats (wedding event only — primary headline number).
$wedding_guests   = array_filter( $guests, fn( $g ) => ( $g['event'] ?? '' ) === 'wedding' );
$guests_total     = count( $wedding_guests );
$guests_attending = count( array_filter( $wedding_guests, fn( $g ) => ( $g['status'] ?? '' ) === 'attended' ) );

get_header();
?>

<div class="container">
    <div class="cd-wrap">

        <?php
        get_template_part( 'template-parts/couple-dashboard/couple-dashboard-s1-sidebar', null, [
            'active' => 'dashboard',
        ] );

        get_template_part( 'template-parts/couple-dashboard/couple-dashboard-s2-hero', null, [
            'first_name'         => $user->first_name,
            'partner_first_name' => $partner_first_name,
            'wedding_date'       => $wedding_date,
            'couple_photo_id'    => $couple_photo_id,
            'partner_photo_id'   => $partner_photo_id,
            'hero_photo_id'      => $hero_photo_id,
            'vendors_hired'      => $vendors_hired,
            'vendors_total'      => $vendors_total,
            'tasks_completed'    => $checklist_completed,
            'tasks_total'        => $checklist_total,
            'guests_attending'   => $guests_attending,
            'guests_total'       => $guests_total,
        ] );
        ?>

        <main class="cd-main">
            <?php
            get_template_part( 'template-parts/couple-dashboard/couple-dashboard-s4-vendor-team', null, [
                'vendor_team'   => $vendor_team,
                'vendors_hired' => $vendors_hired,
                'vendors_total' => $vendors_total,
            ] );

            get_template_part( 'template-parts/couple-dashboard/couple-dashboard-s5-guest-overview', null, [
                'guests' => $guests,
            ] );

            get_template_part( 'template-parts/couple-dashboard/couple-dashboard-s6-wedding-details', null, [
                'color'     => $wedding_color,
                'season'    => $wedding_season,
                'style'     => $wedding_style,
                'attire'    => $wedding_attire,
                'honeymoon' => $wedding_honeymoon,
            ] );
            ?>
        </main>

        <aside class="cd-side">
            <?php
            get_template_part( 'template-parts/couple-dashboard/couple-dashboard-s7-sidebar-website', null, [
                'website_url' => '', // Wedding website module not yet rebuilt.
            ] );

            get_template_part( 'template-parts/couple-dashboard/couple-dashboard-s8-sidebar-tasks', null, [
                'checklist' => $checklist,
            ] );

            get_template_part( 'template-parts/couple-dashboard/couple-dashboard-s9-sidebar-budget', null, [
                'budget_total' => $budget_total,
                'budget_items' => $budget_items,
            ] );

            get_template_part( 'template-parts/couple-dashboard/couple-dashboard-s10-sidebar-rsvp' );
            ?>
        </aside>

    </div>
</div>

<?php get_footer(); ?>
