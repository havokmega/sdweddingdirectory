<?php
/**
 * Couple Dashboard — s6 Wedding Details
 *
 * Glance strip of 5 wedding attributes: Color, Season, Style, Designer (attire),
 * Honeymoon. Each item links to the discovery page that filters real weddings
 * by that attribute (community feature). Edit pencil opens inline editor.
 *
 * $args:
 *   - color, season, style, attire, honeymoon (string)
 */

$color     = $args['color']     ?? '';
$season    = $args['season']    ?? '';
$style     = $args['style']     ?? '';
$attire    = $args['attire']    ?? '';
$honeymoon = $args['honeymoon'] ?? '';

// Resolve color name → hex swatch. Editable later via taxonomy/term meta.
$color_swatches = [
    'gold'    => '#d4b15c',
    'rose'    => '#d18ca8',
    'blush'   => '#f3c9c9',
    'navy'    => '#1f3a68',
    'emerald' => '#0d6b5b',
    'ivory'   => '#f5f0e6',
    'silver'  => '#c0c4c8',
];
$color_key = strtolower( $color );
$swatch    = $color_swatches[ $color_key ] ?? '#cccccc';

$items = [
    [
        'label'     => __( 'Color', 'sandiegoweddingdirectory' ),
        'value'     => $color,
        'icon_html' => '<span class="cd-wd__swatch" style="background:' . esc_attr( $swatch ) . ';"></span>',
        'filter'    => 'color',
    ],
    [
        'label'     => __( 'Season', 'sandiegoweddingdirectory' ),
        'value'     => $season,
        'icon_html' => '<span class="cd-wd__icon icon-fall" aria-hidden="true"></span>',
        'filter'    => 'season',
    ],
    [
        'label'     => __( 'Style', 'sandiegoweddingdirectory' ),
        'value'     => $style,
        'icon_html' => '<span class="cd-wd__icon icon-bohemian-1" aria-hidden="true"></span>',
        'filter'    => 'style',
    ],
    [
        'label'     => __( "Designer's Name", 'sandiegoweddingdirectory' ),
        'value'     => $attire,
        'icon_html' => '<span class="cd-wd__icon icon-bridal-wear" aria-hidden="true"></span>',
        'filter'    => 'attire',
    ],
    [
        'label'     => __( 'Honeymoon', 'sandiegoweddingdirectory' ),
        'value'     => $honeymoon,
        'icon_html' => '<span class="cd-wd__icon icon-plane-1" aria-hidden="true"></span>',
        'filter'    => 'honeymoon',
    ],
];

// Placeholder community count (real number wired in once /real-weddings filter exists).
$community_count = 3;
?>

<section class="cd-card cd-wedding-details">
    <div class="cd-card__head">
        <h2 class="cd-card__title"><?php esc_html_e( 'Wedding details', 'sandiegoweddingdirectory' ); ?></h2>
    </div>

    <div class="cd-wd__grid">
        <?php foreach ( $items as $item ) : ?>
            <div class="cd-wd__item" data-field="<?php echo esc_attr( $item['filter'] ); ?>">
                <div class="cd-wd__icon-wrap"><?php echo $item['icon_html']; // safe — built from static markup above ?></div>
                <p class="cd-wd__label"><?php echo esc_html( $item['label'] ); ?></p>
                <p class="cd-wd__value"><?php echo esc_html( $item['value'] ?: __( '—', 'sandiegoweddingdirectory' ) ); ?></p>
                <?php if ( $item['value'] ) : ?>
                    <a href="<?php echo esc_url( home_url( '/real-weddings/?' . $item['filter'] . '=' . rawurlencode( strtolower( $item['value'] ) ) ) ); ?>" class="cd-wd__community">
                        <?php
                        printf(
                            /* translators: %d: number of matching couples */
                            esc_html( _n( '%d couple', '%d couples', $community_count, 'sandiegoweddingdirectory' ) ),
                            $community_count
                        );
                        ?>
                    </a>
                <?php endif; ?>
                <button type="button" class="cd-wd__edit" aria-label="<?php
                    /* translators: %s: field label */
                    printf( esc_attr__( 'Edit %s', 'sandiegoweddingdirectory' ), esc_attr( $item['label'] ) );
                ?>">&#9998;</button>
            </div>
        <?php endforeach; ?>
    </div>
</section>
