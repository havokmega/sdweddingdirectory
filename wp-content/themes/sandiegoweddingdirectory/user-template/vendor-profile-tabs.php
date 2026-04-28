<?php
/**
 * Template Name: Vendor Profile (tabs)
 *
 * Sub-page: /vendor-dashboard/profile/
 * Spec s6: parent with 5 sub-tabs (Profile / Business / Password / Social / Filters).
 * Vendors do NOT have a full address. Venues use a separate template that adds it.
 */

if ( ! is_user_logged_in() ) { wp_redirect( home_url( '/' ) ); exit; }

$user    = wp_get_current_user();
$post_id = get_user_meta( $user->ID, 'sdwd_post_id', true );
if ( ! $post_id ) { wp_redirect( home_url( '/' ) ); exit; }

$is_venue = in_array( 'venue', (array) $user->roles, true );

$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'profile';

get_header();
?>

<div class="container">
    <div class="vd-wrap vd-wrap--single">
        <?php
        get_template_part( 'template-parts/vendor-dashboard/vendor-dashboard-s1-sidebar', null, [ 'active' => 'profile' ] );
        ?>
        <main class="vd-main">
            <?php
            get_template_part( 'template-parts/vendor-profile/vendor-profile-shell', null, [
                'active_tab' => $active_tab,
                'user'       => $user,
                'post_id'    => $post_id,
                'is_venue'   => $is_venue,
            ] );
            ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>
