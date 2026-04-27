<?php
/**
 * Cost Child: 3-column inline links — sibling cost categories.
 *
 * Renders all 16 cost children for cross-linking SEO; the current page is
 * marked but not removed (reads as a category index footer).
 */

$current_id = absint( get_queried_object_id() );

$links = [
    [ 'label' => __( 'Catering cost',          'sandiegoweddingdirectory' ), 'url' => home_url( '/cost/wedding-catering-san-diego/' ) ],
    [ 'label' => __( 'Photographer cost',      'sandiegoweddingdirectory' ), 'url' => home_url( '/cost/wedding-photographer-san-diego/' ) ],
    [ 'label' => __( 'Cake cost',              'sandiegoweddingdirectory' ), 'url' => home_url( '/cost/wedding-cake-san-diego/' ) ],
    [ 'label' => __( 'Flowers cost',           'sandiegoweddingdirectory' ), 'url' => home_url( '/cost/wedding-flowers-san-diego/' ) ],
    [ 'label' => __( 'Planner cost',           'sandiegoweddingdirectory' ), 'url' => home_url( '/cost/wedding-planner-san-diego/' ) ],
    [ 'label' => __( 'Hair & Makeup cost',     'sandiegoweddingdirectory' ), 'url' => home_url( '/cost/wedding-hair-and-makeup-san-diego/' ) ],
    [ 'label' => __( 'DJ cost',                'sandiegoweddingdirectory' ), 'url' => home_url( '/cost/wedding-dj-san-diego/' ) ],
    [ 'label' => __( 'Venue cost',             'sandiegoweddingdirectory' ), 'url' => home_url( '/cost/wedding-venue-san-diego/' ) ],
    [ 'label' => __( 'Videographer cost',      'sandiegoweddingdirectory' ), 'url' => home_url( '/cost/wedding-videographer-san-diego/' ) ],
    [ 'label' => __( 'Band cost',              'sandiegoweddingdirectory' ), 'url' => home_url( '/cost/wedding-band-san-diego/' ) ],
    [ 'label' => __( 'Dress cost',             'sandiegoweddingdirectory' ), 'url' => home_url( '/cost/wedding-dress-san-diego/' ) ],
    [ 'label' => __( 'Tuxedo cost',            'sandiegoweddingdirectory' ), 'url' => home_url( '/cost/wedding-tuxedo-san-diego/' ) ],
    [ 'label' => __( 'Officiant cost',         'sandiegoweddingdirectory' ), 'url' => home_url( '/cost/wedding-officiant-san-diego/' ) ],
    [ 'label' => __( 'Ceremony Music cost',    'sandiegoweddingdirectory' ), 'url' => home_url( '/cost/wedding-ceremony-music-san-diego/' ) ],
    [ 'label' => __( 'Photo Booth cost',       'sandiegoweddingdirectory' ), 'url' => home_url( '/cost/wedding-photo-booths-san-diego/' ) ],
    [ 'label' => __( 'Rentals cost',           'sandiegoweddingdirectory' ), 'url' => home_url( '/cost/wedding-rentals-san-diego/' ) ],
];
?>
<section class="cost-child-s4-3col-links section" aria-label="<?php esc_attr_e( 'Other cost categories', 'sandiegoweddingdirectory' ); ?>">
    <div class="container">
        <ul class="cost-child-s4-3col-links__list">
            <?php foreach ( $links as $link ) :
                $is_current = ( $current_id && trailingslashit( $link['url'] ) === trailingslashit( get_permalink( $current_id ) ) ); ?>
                <li class="cost-child-s4-3col-links__item<?php echo $is_current ? ' is-current' : ''; ?>">
                    <a class="cost-child-s4-3col-links__link" href="<?php echo esc_url( $link['url'] ); ?>"<?php echo $is_current ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $link['label'] ); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
