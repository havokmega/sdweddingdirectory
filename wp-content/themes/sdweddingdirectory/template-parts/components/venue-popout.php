<?php
/**
 * Component: Venue Popout
 *
 * Wide image on the right with an overlapping text card on the left.
 *
 * @param array $args {
 *     @type string $heading   Required. Heading text.
 *     @type string $desc      Optional. Description paragraph.
 *     @type string $image     Optional. Image URL.
 *     @type string $image_alt Optional. Image alt text.
 * }
 */

$heading   = $args['heading'] ?? '';
$desc      = $args['desc'] ?? '';
$image     = $args['image'] ?? '';
$image_alt = $args['image_alt'] ?? $heading;

if ( ! $heading ) {
	return;
}
?>
<div class="venue-popout">
	<div class="venue-popout__image">
		<?php if ( $image ) : ?>
			<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy">
		<?php endif; ?>
	</div>

	<div class="venue-popout__content">
		<h2 class="venue-popout__title"><?php echo esc_html( $heading ); ?></h2>

		<?php if ( $desc ) : ?>
			<p class="venue-popout__desc"><?php echo wp_kses_post( $desc ); ?></p>
		<?php endif; ?>
	</div>
</div>
