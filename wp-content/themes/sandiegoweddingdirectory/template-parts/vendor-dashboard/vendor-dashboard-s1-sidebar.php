<?php
/**
 * Vendor Dashboard — Left sidebar navigation
 *
 * $args:
 *   - active (string) active slug
 */

$active = $args['active'] ?? 'dashboard';

// Role-aware base URL: venue users live at /venue-dashboard/, vendors at /vendor-dashboard/.
$current_user = wp_get_current_user();
$is_venue     = in_array( 'venue', (array) $current_user->roles, true );
$base         = $is_venue ? '/venue-dashboard/' : '/vendor-dashboard/';

$nav_items = [
    [ 'slug' => 'dashboard',      'label' => __( 'Dashboard',      'sandiegoweddingdirectory' ), 'url' => home_url( $base ),                     'icon' => 'icon-dashboard' ],
    [ 'slug' => 'profile',        'label' => __( 'My Profile',     'sandiegoweddingdirectory' ), 'url' => home_url( $base . 'profile/' ),        'icon' => 'icon-my-profile' ],
    [ 'slug' => 'packages',       'label' => __( 'Price Packages', 'sandiegoweddingdirectory' ), 'url' => home_url( $base . 'packages/' ),       'icon' => 'icon-tags' ],
    [ 'slug' => 'hours',          'label' => __( 'Business Hours', 'sandiegoweddingdirectory' ), 'url' => home_url( $base . 'hours/' ),          'icon' => 'icon-calendar-heart' ],
    [ 'slug' => 'quote-requests', 'label' => __( 'Quote Requests', 'sandiegoweddingdirectory' ), 'url' => home_url( $base . 'quote-requests/' ), 'icon' => 'icon-guestlist' ],
    [ 'slug' => 'reviews',        'label' => __( 'My Reviews',     'sandiegoweddingdirectory' ), 'url' => home_url( $base . 'reviews/' ),        'icon' => 'icon-reviews' ],
    [ 'slug' => 'logout',         'label' => __( 'Logout',         'sandiegoweddingdirectory' ), 'url' => wp_logout_url( home_url( '/' ) ),      'icon' => 'icon-logout' ],
];
?>

<nav class="vd-nav" aria-label="<?php esc_attr_e( 'Vendor dashboard navigation', 'sandiegoweddingdirectory' ); ?>">
    <ul class="vd-nav__list">
        <?php foreach ( $nav_items as $item ) :
            $is_active = ( $item['slug'] === $active );
            $class     = 'vd-nav__link' . ( $is_active ? ' vd-nav__link--active' : '' );
        ?>
            <li class="vd-nav__item">
                <a href="<?php echo esc_url( $item['url'] ); ?>" class="<?php echo esc_attr( $class ); ?>">
                    <span class="vd-nav__icon <?php echo esc_attr( $item['icon'] ); ?>" aria-hidden="true"></span>
                    <span class="vd-nav__label"><?php echo esc_html( $item['label'] ); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
