<?php
/**
 * Component: Icon Card Row
 *
 * Grid of cards with icon, heading, and subtext.
 *
 * @param array $args {
 *     @type string $heading  Optional. Section heading.
 *     @type string $desc     Optional. Section description.
 *     @type array  $cards    Required. Array of ['icon_url'=>'', 'title'=>'', 'desc'=>'', 'cta_label'=>'', 'cta_url'=>''].
 *     @type int    $columns  Optional. Number of columns. Default 3.
 * }
 */

$heading = $args['heading'] ?? '';
$desc    = $args['desc'] ?? '';
$cards   = $args['cards'] ?? [];
$columns = max( 1, intval( $args['columns'] ?? 3 ) );

if ( empty( $cards ) ) {
    return;
}

$grid_class = 'tool-card-row tool-card-row--' . $columns . 'col';
?>
<div class="<?php echo esc_attr( $grid_class ); ?>">
    <?php if ( $heading ) : ?>
        <div class="tool-card-row__header">
            <h2 class="tool-card-row__heading"><?php echo esc_html( $heading ); ?></h2>
            <?php if ( $desc ) : ?>
                <p class="tool-card-row__desc"><?php echo wp_kses_post( $desc ); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="tool-card-row__grid grid grid--<?php echo esc_attr( $columns ); ?>col">
        <?php foreach ( $cards as $card ) : ?>
            <?php
            $title     = $card['title'] ?? '';
            $cta_label = $card['cta_label'] ?? '';
            $cta_url   = $card['cta_url'] ?? '';

            if ( '' === $title ) {
                continue;
            }
            ?>
            <article class="tool-card">
                <?php if ( ! empty( $card['icon_url'] ) ) : ?>
                    <div class="tool-card__icon">
                        <img src="<?php echo esc_url( $card['icon_url'] ); ?>" alt="" loading="lazy">
                    </div>
                <?php endif; ?>
                <h3 class="tool-card__title"><?php echo esc_html( $title ); ?></h3>
                <?php if ( ! empty( $card['desc'] ) ) : ?>
                    <p class="tool-card__desc"><?php echo esc_html( $card['desc'] ); ?></p>
                <?php endif; ?>
                <?php if ( $cta_label && $cta_url ) : ?>
                    <a class="tool-card__cta" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $cta_label ); ?></a>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</div>
