<?php
/**
 * Component: Inline Link Grid
 *
 * @param array $args {
 *     @type string $heading  Optional. Section heading above the grid.
 *     @type array  $rows     Required. Array of rows, each row is array of ['label'=>'', 'url'=>''].
 *     @type int    $columns  Optional. Number of columns. Default 4.
 * }
 */

$heading = $args['heading'] ?? '';
$rows    = $args['rows'] ?? [];
$columns = $args['columns'] ?? 4;

if ( empty( $rows ) ) {
    return;
}
?>
<div class="link-grid">
    <?php if ( $heading ) : ?>
        <h3 class="link-grid__heading"><?php echo esc_html( $heading ); ?></h3>
    <?php endif; ?>

    <?php foreach ( $rows as $row ) : ?>
        <div class="link-grid__row">
            <?php foreach ( $row as $i => $link ) : ?>
                <?php
                $link_href = $link['path'] ?? $link['url'] ?? '#';
                $link_href = ( is_string( $link_href ) && preg_match( '#^https?://#i', $link_href ) )
                    ? $link_href
                    : home_url( $link_href );
                ?>
                <?php if ( $i > 0 ) : ?>
                    <span class="link-grid__sep" aria-hidden="true">&bull;</span>
                <?php endif; ?>
                <a class="link-grid__item" href="<?php echo esc_url( $link_href ); ?>">
                    <?php echo esc_html( $link['label'] ); ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>
