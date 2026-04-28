<?php
/**
 * Vendor Profile — Shell
 *
 * Spec s6: parent with 5 sub-tabs (Profile / Business / Password / Social / Filters).
 * Vendors do NOT have an address. Venues use the same shell with $is_venue=true,
 * which adds the address fields in the Business sub-template.
 *
 * $args:
 *   - active_tab (string)
 *   - user (WP_User)
 *   - post_id (int)
 *   - is_venue (bool)
 */

$active_tab = $args['active_tab'] ?? 'profile';
$user       = $args['user']       ?? wp_get_current_user();
$post_id    = (int) ( $args['post_id'] ?? 0 );
$is_venue   = ! empty( $args['is_venue'] );

$tabs = [
    'profile'  => __( 'Profile',          'sandiegoweddingdirectory' ),
    'business' => __( 'Business Profile', 'sandiegoweddingdirectory' ),
    'password' => __( 'Password',         'sandiegoweddingdirectory' ),
    'social'   => __( 'Social',           'sandiegoweddingdirectory' ),
    'filters'  => __( 'Filters',          'sandiegoweddingdirectory' ),
];

$base_url = home_url( ( $is_venue ? '/venue-dashboard' : '/vendor-dashboard' ) . '/profile/' );
?>

<div class="cd-card vd-profile">
    <div class="cd-card__head">
        <h1 class="cd-card__title"><?php esc_html_e( 'My Profile', 'sandiegoweddingdirectory' ); ?></h1>
    </div>

    <div class="cd-tabs cd-tabs--vertical">
        <nav class="cd-tabs__list">
            <?php foreach ( $tabs as $slug => $label ) :
                $url       = add_query_arg( 'tab', $slug, $base_url );
                $is_active = ( $slug === $active_tab );
            ?>
                <a href="<?php echo esc_url( $url ); ?>" class="cd-tabs__tab<?php echo $is_active ? ' cd-tabs__tab--active' : ''; ?>"><?php echo esc_html( $label ); ?></a>
            <?php endforeach; ?>
        </nav>

        <div class="cd-tabs__content">
            <?php
            switch ( $active_tab ) {
                case 'business':
                    get_template_part( 'template-parts/vendor-profile/vendor-profile-s6b-business', null, [ 'post_id' => $post_id, 'is_venue' => $is_venue ] );
                    break;
                case 'password':
                    get_template_part( 'template-parts/couple-profile/couple-profile-s4-password' );
                    break;
                case 'social':
                    get_template_part( 'template-parts/vendor-profile/vendor-profile-s6d-social', null, [ 'post_id' => $post_id ] );
                    break;
                case 'filters':
                    get_template_part( 'template-parts/vendor-profile/vendor-profile-s6e-filters', null, [ 'post_id' => $post_id, 'is_venue' => $is_venue ] );
                    break;
                case 'profile':
                default:
                    get_template_part( 'template-parts/vendor-profile/vendor-profile-s6a-profile', null, [ 'user' => $user, 'post_id' => $post_id ] );
                    break;
            }
            ?>
        </div>
    </div>
</div>
