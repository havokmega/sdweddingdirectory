<?php
/**
 * Couple Vendor Manager — Shell
 *
 * Spec s11: top horizontal tabs (favorites / overview / hired) +
 * category sidebar + content.
 *
 * $args:
 *   - active_mode (string)
 *   - user (WP_User)
 */

$active_mode = $args['active_mode'] ?? 'overview';
$user        = $args['user'] ?? wp_get_current_user();

$modes = [
    'overview'  => __( 'Overview',  'sandiegoweddingdirectory' ),
    'favorites' => __( 'Favorites', 'sandiegoweddingdirectory' ),
    'hired'     => __( 'Hired',     'sandiegoweddingdirectory' ),
];

$base_url = home_url( '/couple-dashboard/vendor-manager/' );

$vendor_team = get_user_meta( $user->ID, 'sdwd_vendor_team', true );
if ( ! is_array( $vendor_team ) ) { $vendor_team = []; }

$category_meta = [
    'cake'           => [ 'name' => 'Cake',           'icon' => 'icon-cake' ],
    'dress'          => [ 'name' => 'Dress',          'icon' => 'icon-bridal-wear' ],
    'florist'        => [ 'name' => 'Florist',        'icon' => 'icon-flowers' ],
    'jeweller'       => [ 'name' => 'Jeweller',       'icon' => 'icon-ring-double' ],
    'music'          => [ 'name' => 'Music',          'icon' => 'icon-music' ],
    'photographer'   => [ 'name' => 'Photographer',   'icon' => 'icon-camera1' ],
    'transportation' => [ 'name' => 'Transportation', 'icon' => 'icon-bus' ],
    'venue'          => [ 'name' => 'Venue',          'icon' => 'icon-building' ],
    'videographer'   => [ 'name' => 'Videographer',   'icon' => 'icon-videographer' ],
];

$selected_cat = isset( $_GET['cat'] ) ? sanitize_key( $_GET['cat'] ) : '';
?>

<div class="cd-card cd-vendor-mgr">
    <div class="cd-card__head">
        <h1 class="cd-card__title"><?php esc_html_e( 'Vendor Manager', 'sandiegoweddingdirectory' ); ?></h1>
    </div>

    <nav class="cd-tabs cd-tabs--horizontal" aria-label="<?php esc_attr_e( 'Vendor view modes', 'sandiegoweddingdirectory' ); ?>">
        <?php foreach ( $modes as $slug => $label ) :
            $url       = add_query_arg( 'mode', $slug, $base_url );
            $is_active = ( $slug === $active_mode );
        ?>
            <a href="<?php echo esc_url( $url ); ?>" class="cd-tabs__tab<?php echo $is_active ? ' cd-tabs__tab--active' : ''; ?>">
                <?php echo esc_html( $label ); ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="cd-vendor-mgr__layout">
        <aside class="cd-vendor-mgr__sidebar">
            <h2 class="cd-vendor-mgr__sidebar-title"><?php esc_html_e( 'Categories', 'sandiegoweddingdirectory' ); ?></h2>
            <ul class="cd-vendor-mgr__cats">
                <li class="cd-vendor-mgr__cat<?php echo empty( $selected_cat ) ? ' cd-vendor-mgr__cat--active' : ''; ?>">
                    <a href="<?php echo esc_url( add_query_arg( 'mode', $active_mode, $base_url ) ); ?>"><?php esc_html_e( 'All categories', 'sandiegoweddingdirectory' ); ?></a>
                </li>
                <?php foreach ( $vendor_team as $row ) :
                    $slug   = $row['category'] ?? '';
                    $meta   = $category_meta[ $slug ] ?? [ 'name' => ucfirst( $slug ), 'icon' => '' ];
                    $hearts = (int) ( $row['hearts'] ?? 0 );
                    $hired  = ! empty( $row['hired'] );
                    $url    = add_query_arg( [ 'mode' => $active_mode, 'cat' => $slug ], $base_url );
                ?>
                    <li class="cd-vendor-mgr__cat<?php echo $selected_cat === $slug ? ' cd-vendor-mgr__cat--active' : ''; ?>">
                        <a href="<?php echo esc_url( $url ); ?>">
                            <?php if ( $meta['icon'] ) : ?>
                                <span class="<?php echo esc_attr( $meta['icon'] ); ?>" aria-hidden="true"></span>
                            <?php endif; ?>
                            <span class="cd-vendor-mgr__cat-name"><?php echo esc_html( $meta['name'] ); ?></span>
                            <?php if ( $hired ) : ?>
                                <span class="cd-vendor-mgr__cat-badge"><?php esc_html_e( 'Hired', 'sandiegoweddingdirectory' ); ?></span>
                            <?php elseif ( $hearts ) : ?>
                                <span class="cd-vendor-mgr__cat-count"><?php echo (int) $hearts; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>

        <div class="cd-vendor-mgr__content">
            <?php if ( $active_mode === 'favorites' ) : ?>
                <p class="cd-section__soon"><?php esc_html_e( 'Your saved favorites — coming soon.', 'sandiegoweddingdirectory' ); ?></p>
            <?php elseif ( $active_mode === 'hired' ) :
                $hired = array_filter( $vendor_team, fn( $r ) => ! empty( $r['hired'] ) );
            ?>
                <?php if ( empty( $hired ) ) : ?>
                    <p class="cd-section__soon"><?php esc_html_e( 'No hired vendors yet.', 'sandiegoweddingdirectory' ); ?></p>
                <?php else : ?>
                    <ul class="cd-vendor-mgr__list">
                        <?php foreach ( $hired as $row ) :
                            $slug = $row['category'] ?? '';
                            $meta = $category_meta[ $slug ] ?? [ 'name' => ucfirst( $slug ) ];
                        ?>
                            <li class="cd-vendor-mgr__row">
                                <span class="cd-vendor-mgr__row-name"><?php echo esc_html( $meta['name'] ); ?></span>
                                <span class="cd-vendor-mgr__row-status"><?php esc_html_e( 'Hired', 'sandiegoweddingdirectory' ); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            <?php else : /* overview */ ?>
                <ul class="cd-vendor-mgr__list">
                    <?php foreach ( $vendor_team as $row ) :
                        $slug   = $row['category'] ?? '';
                        $meta   = $category_meta[ $slug ] ?? [ 'name' => ucfirst( $slug ) ];
                        $hearts = (int) ( $row['hearts'] ?? 0 );
                        $hired  = ! empty( $row['hired'] );
                    ?>
                        <li class="cd-vendor-mgr__row">
                            <span class="cd-vendor-mgr__row-name"><?php echo esc_html( $meta['name'] ); ?></span>
                            <span class="cd-vendor-mgr__row-meta"><?php echo (int) $hearts; ?> <?php esc_html_e( 'saved', 'sandiegoweddingdirectory' ); ?></span>
                            <?php if ( $hired ) : ?>
                                <span class="cd-vendor-mgr__row-status"><?php esc_html_e( 'Hired', 'sandiegoweddingdirectory' ); ?></span>
                            <?php else : ?>
                                <a href="<?php echo esc_url( home_url( '/vendors/' . $slug . '/' ) ); ?>" class="cd-vendor-mgr__row-link"><?php esc_html_e( 'Browse', 'sandiegoweddingdirectory' ); ?></a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
