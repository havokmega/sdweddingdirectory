<?php
/**
 * Cost: Category card panel — 300x290 cards with icon, ALL-CAPS title,
 * lorem sentence, and link. Cards sit flush with 2px gaps.
 */

$lorem = __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'sandiegoweddingdirectory' );

$categories = [
    [ 'label' => __( 'Catering',         'sandiegoweddingdirectory' ), 'slug' => 'wedding-catering-san-diego',          'icon' => 'icon-cutlery' ],
    [ 'label' => __( 'Photographer',     'sandiegoweddingdirectory' ), 'slug' => 'wedding-photographer-san-diego',      'icon' => 'icon-camera' ],
    [ 'label' => __( 'Cake',             'sandiegoweddingdirectory' ), 'slug' => 'wedding-cake-san-diego',              'icon' => 'icon-cake' ],
    [ 'label' => __( 'Flowers',          'sandiegoweddingdirectory' ), 'slug' => 'wedding-flowers-san-diego',           'icon' => 'icon-flowers' ],
    [ 'label' => __( 'Planner',          'sandiegoweddingdirectory' ), 'slug' => 'wedding-planner-san-diego',           'icon' => 'icon-checklist' ],
    [ 'label' => __( 'Hair & Makeup',    'sandiegoweddingdirectory' ), 'slug' => 'wedding-hair-and-makeup-san-diego',   'icon' => 'icon-bridal-wear' ],
    [ 'label' => __( 'DJ',               'sandiegoweddingdirectory' ), 'slug' => 'wedding-dj-san-diego',                'icon' => 'icon-music' ],
    [ 'label' => __( 'Venue',            'sandiegoweddingdirectory' ), 'slug' => 'wedding-venue-san-diego',             'icon' => 'icon-venue' ],
    [ 'label' => __( 'Videographer',     'sandiegoweddingdirectory' ), 'slug' => 'wedding-videographer-san-diego',      'icon' => 'icon-videographer' ],
    [ 'label' => __( 'Band',             'sandiegoweddingdirectory' ), 'slug' => 'wedding-band-san-diego',              'icon' => 'icon-music' ],
    [ 'label' => __( 'Dress',            'sandiegoweddingdirectory' ), 'slug' => 'wedding-dress-san-diego',             'icon' => 'icon-bridal-wear' ],
    [ 'label' => __( 'Tuxedo',           'sandiegoweddingdirectory' ), 'slug' => 'wedding-tuxedo-san-diego',            'icon' => 'icon-bow-tie-1' ],
    [ 'label' => __( 'Officiant',        'sandiegoweddingdirectory' ), 'slug' => 'wedding-officiant-san-diego',         'icon' => 'icon-church' ],
    [ 'label' => __( 'Ceremony Music',   'sandiegoweddingdirectory' ), 'slug' => 'wedding-ceremony-music-san-diego',    'icon' => 'icon-music' ],
    [ 'label' => __( 'Photo Booths',     'sandiegoweddingdirectory' ), 'slug' => 'wedding-photo-booths-san-diego',      'icon' => 'icon-camera-alt' ],
    [ 'label' => __( 'Rentals',          'sandiegoweddingdirectory' ), 'slug' => 'wedding-rentals-san-diego',           'icon' => 'icon-tags' ],
];
// Cards visible by default = first 2 rows on desktop (4-up). Smaller screens
// will naturally show fewer per row but the same first 8 cards.
$initial = 8;
?>
<section class="cost-s3-category-card-panel section" aria-label="<?php esc_attr_e( 'Browse wedding cost categories', 'sandiegoweddingdirectory' ); ?>">
    <div class="container">
        <div class="cost-s3-category-card-panel__header">
            <h2 class="cost-s3-category-card-panel__heading"><?php esc_html_e( 'Browse cost by category', 'sandiegoweddingdirectory' ); ?></h2>
            <p class="cost-s3-category-card-panel__desc"><?php esc_html_e( 'Pick a category to see San Diego averages, ranges, and what shapes the price.', 'sandiegoweddingdirectory' ); ?></p>
        </div>

        <ul class="cost-s3-category-card-panel__grid" data-initial="<?php echo esc_attr( $initial ); ?>">
            <?php foreach ( $categories as $i => $category ) :
                $url    = home_url( '/cost/' . $category['slug'] . '/' );
                $hidden = $i >= $initial;
                ?>
                <li class="cost-s3-category-card-panel__item<?php echo $hidden ? ' is-hidden' : ''; ?>"<?php echo $hidden ? ' aria-hidden="true"' : ''; ?>>
                    <article class="cost-s3-category-card-panel__card">
                        <span class="cost-s3-category-card-panel__icon <?php echo esc_attr( $category['icon'] ); ?>" aria-hidden="true"></span>
                        <h3 class="cost-s3-category-card-panel__label"><?php echo esc_html( $category['label'] ); ?></h3>
                        <p class="cost-s3-category-card-panel__text"><?php echo esc_html( $lorem ); ?></p>
                        <a class="cost-s3-category-card-panel__link" href="<?php echo esc_url( $url ); ?>">
                            <?php
                            printf(
                                /* translators: %s: vendor category label */
                                esc_html__( 'View %s cost', 'sandiegoweddingdirectory' ),
                                esc_html( $category['label'] )
                            );
                            ?>
                        </a>
                    </article>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ( count( $categories ) > $initial ) : ?>
            <div class="cost-s3-category-card-panel__more">
                <button class="btn btn--primary cost-s3-category-card-panel__toggle" type="button" data-expanded="false">
                    <span class="cost-s3-category-card-panel__toggle-label-show"><?php esc_html_e( 'View more', 'sandiegoweddingdirectory' ); ?></span>
                    <span class="cost-s3-category-card-panel__toggle-label-hide"><?php esc_html_e( 'View less', 'sandiegoweddingdirectory' ); ?></span>
                </button>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
(function () {
    var btn = document.querySelector('.cost-s3-category-card-panel__toggle');
    if (!btn) return;
    var grid = document.querySelector('.cost-s3-category-card-panel__grid');
    if (!grid) return;
    var initial = parseInt(grid.getAttribute('data-initial'), 10) || 8;

    btn.addEventListener('click', function () {
        var expanded = btn.getAttribute('data-expanded') === 'true';
        var items = grid.querySelectorAll('.cost-s3-category-card-panel__item');
        items.forEach(function (item, i) {
            if (i < initial) return;
            if (expanded) {
                item.classList.add('is-hidden');
                item.setAttribute('aria-hidden', 'true');
            } else {
                item.classList.remove('is-hidden');
                item.removeAttribute('aria-hidden');
            }
        });
        btn.setAttribute('data-expanded', expanded ? 'false' : 'true');
    });
})();
</script>
