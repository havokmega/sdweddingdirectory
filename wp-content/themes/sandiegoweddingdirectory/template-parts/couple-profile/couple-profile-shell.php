<?php
/**
 * Couple Profile — Shell
 *
 * Vertical tabs (Profile / Wedding Info / Password / Social) on the left,
 * tab content on the right. Tab switching via ?tab= query.
 *
 * $args:
 *   - active_tab (string)
 *   - user (WP_User)
 *   - post_id (int)
 */

$active_tab = $args['active_tab'] ?? 'profile';
$user       = $args['user'] ?? wp_get_current_user();
$post_id    = (int) ( $args['post_id'] ?? 0 );

$tabs = [
    'profile'      => __( 'Profile',      'sandiegoweddingdirectory' ),
    'wedding-info' => __( 'Wedding Info', 'sandiegoweddingdirectory' ),
    'password'     => __( 'Password',     'sandiegoweddingdirectory' ),
    'social'       => __( 'Social',       'sandiegoweddingdirectory' ),
];

$base_url = home_url( '/couple-dashboard/profile/' );
?>

<div class="cd-card cd-profile">
    <div class="cd-card__head">
        <h1 class="cd-card__title"><?php esc_html_e( 'My Profile', 'sandiegoweddingdirectory' ); ?></h1>
    </div>

    <div class="cd-tabs cd-tabs--vertical">
        <nav class="cd-tabs__list" aria-label="<?php esc_attr_e( 'Profile sections', 'sandiegoweddingdirectory' ); ?>">
            <?php foreach ( $tabs as $slug => $label ) :
                $is_active = ( $slug === $active_tab );
                $url       = add_query_arg( 'tab', $slug, $base_url );
            ?>
                <a href="<?php echo esc_url( $url ); ?>" class="cd-tabs__tab<?php echo $is_active ? ' cd-tabs__tab--active' : ''; ?>">
                    <?php echo esc_html( $label ); ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="cd-tabs__content">
            <?php
            switch ( $active_tab ) {
                case 'wedding-info':
                    get_template_part( 'template-parts/couple-profile/couple-profile-s3-wedding-info', null, [ 'post_id' => $post_id ] );
                    break;
                case 'password':
                    get_template_part( 'template-parts/couple-profile/couple-profile-s4-password' );
                    break;
                case 'social':
                    get_template_part( 'template-parts/couple-profile/couple-profile-s5-social', null, [ 'user_id' => $user->ID ] );
                    break;
                case 'profile':
                default:
                    get_template_part( 'template-parts/couple-profile/couple-profile-s2-profile-form', null, [ 'user' => $user, 'post_id' => $post_id ] );
                    break;
            }
            ?>
        </div>
    </div>
</div>
