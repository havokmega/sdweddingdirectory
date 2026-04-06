<?php
/**
 * Component: Section Title
 *
 * @param array $args {
 *     @type string $heading  Required. Heading text.
 *     @type string $desc     Optional. Subtext paragraph.
 *     @type string $align    Optional. 'center' or 'left'. Default 'left'.
 *     @type string $tag      Optional. HTML tag. Default 'h2'.
 * }
 */

$heading = $args['heading'] ?? '';
$desc    = $args['desc'] ?? '';
$align   = $args['align'] ?? 'left';
$tag     = $args['tag'] ?? 'h2';

if ( ! $heading ) {
    return;
}

$class = 'section-title';
if ( 'center' === $align ) {
    $class .= ' section-title--center';
}
?>
<div class="<?php echo esc_attr( $class ); ?>">
    <<?php echo esc_attr( $tag ); ?> class="section-title__heading"><?php echo esc_html( $heading ); ?></<?php echo esc_attr( $tag ); ?>>
    <?php if ( $desc ) : ?>
        <p class="section-title__desc"><?php echo wp_kses_post( $desc ); ?></p>
    <?php endif; ?>
</div>
