<?php
/**
 * Couple Dashboard — Left sidebar navigation
 *
 * Links to each sub-page of the couple dashboard. The currently active page
 * is marked with `cd-nav__link--active` based on the page slug passed in $args.
 */

$active = $args['active'] ?? 'dashboard';

$nav_items = [
    [ 'slug' => 'dashboard',        'label' => __( 'Dashboard',         'sandiegoweddingdirectory' ), 'url' => home_url( '/couple-dashboard/' ),                  'icon' => 'icon-heart-ring' ],
    [ 'slug' => 'profile',          'label' => __( 'My Profile',        'sandiegoweddingdirectory' ), 'url' => home_url( '/couple-dashboard/profile/' ),          'icon' => 'icon-my-profile' ],
    [ 'slug' => 'vendor-manager',   'label' => __( 'Vendor Manager',    'sandiegoweddingdirectory' ), 'url' => home_url( '/couple-dashboard/vendor-manager/' ),   'icon' => 'icon-vendor-manager' ],
    [ 'slug' => 'checklist',        'label' => __( 'Checklist',         'sandiegoweddingdirectory' ), 'url' => home_url( '/couple-dashboard/checklist/' ),        'icon' => 'icon-checklist' ],
    [ 'slug' => 'budget',           'label' => __( 'Budget Calculator', 'sandiegoweddingdirectory' ), 'url' => home_url( '/couple-dashboard/budget/' ),           'icon' => 'icon-budget' ],
    [ 'slug' => 'seating-chart',    'label' => __( 'Seating Chart',     'sandiegoweddingdirectory' ), 'url' => home_url( '/couple-dashboard/seating-chart/' ),    'icon' => 'icon-four-side-table-2' ],
    [ 'slug' => 'guest-management', 'label' => __( 'Guest Management',  'sandiegoweddingdirectory' ), 'url' => home_url( '/couple-dashboard/guest-management/' ), 'icon' => 'icon-guestlist' ],
    [ 'slug' => 'real-wedding',     'label' => __( 'Real Wedding',      'sandiegoweddingdirectory' ), 'url' => home_url( '/couple-dashboard/real-wedding/' ),     'icon' => 'icon-dove' ],
    [ 'slug' => 'reviews',          'label' => __( 'My Reviews',        'sandiegoweddingdirectory' ), 'url' => home_url( '/couple-dashboard/reviews/' ),          'icon' => 'icon-reviews' ],
    [ 'slug' => 'wedding-website',  'label' => __( 'Wedding Website',   'sandiegoweddingdirectory' ), 'url' => home_url( '/couple-dashboard/website/' ),          'icon' => 'icon-website' ],
    [ 'slug' => 'logout',           'label' => __( 'Logout',            'sandiegoweddingdirectory' ), 'url' => wp_logout_url( home_url( '/' ) ),                  'icon' => 'icon-logout' ],
];
?>

<nav class="cd-nav" aria-label="<?php esc_attr_e( 'Couple dashboard navigation', 'sandiegoweddingdirectory' ); ?>">
    <ul class="cd-nav__list">
        <?php foreach ( $nav_items as $item ) :
            $is_active = ( $item['slug'] === $active );
            $class     = 'cd-nav__link' . ( $is_active ? ' cd-nav__link--active' : '' );
        ?>
            <li class="cd-nav__item">
                <a href="<?php echo esc_url( $item['url'] ); ?>" class="<?php echo esc_attr( $class ); ?>">
                    <span class="cd-nav__icon <?php echo esc_attr( $item['icon'] ); ?>" aria-hidden="true"></span>
                    <span class="cd-nav__label"><?php echo esc_html( $item['label'] ); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
