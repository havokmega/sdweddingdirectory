<?php
/**
 * Template Name: Couple Profile
 *
 * Sub-page: /couple-dashboard/profile/
 * Spec: couple-profile-s1-shell with vertical tabs (Profile / Wedding Info /
 * Password / Social) on the left, content on the right.
 */

if ( ! is_user_logged_in() ) { wp_redirect( home_url( '/' ) ); exit; }

$user    = wp_get_current_user();
$post_id = get_user_meta( $user->ID, 'sdwd_post_id', true );
if ( ! $post_id ) { wp_redirect( home_url( '/' ) ); exit; }

$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'profile';

get_header();
?>

<div class="container">
    <div class="cd-wrap cd-wrap--single">
        <?php
        get_template_part( 'template-parts/couple-dashboard/couple-dashboard-s1-sidebar', null, [ 'active' => 'profile' ] );
        ?>
        <main class="cd-main">
            <?php
            get_template_part( 'template-parts/couple-profile/couple-profile-shell', null, [
                'active_tab' => $active_tab,
                'user'       => $user,
                'post_id'    => $post_id,
            ] );
            ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>
