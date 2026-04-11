<?php
/**
 * Component: Vendor Popout
 *
 * Wide image on the right with an overlapping text card on the left.
 * Same layout concept as venue-popout but with its own classes for independent styling.
 *
 * @param array $args {
 *     @type string $heading   Required. Heading text.
 *     @type string $desc      Optional. Description paragraph.
 *     @type string $image     Optional. Image URL.
 *     @type string $image_alt Optional. Image alt text.
 *     @type string $cta_text  Optional. CTA button text.
 *     @type string $cta_url   Optional. CTA button URL.
 * }
 */

$heading   = $args['heading'] ?? '';
$desc      = $args['desc'] ?? '';
$image     = $args['image'] ?? '';
$image_alt = $args['image_alt'] ?? $heading;
$cta_text  = $args['cta_text'] ?? '';
$cta_url   = $args['cta_url'] ?? '';

if ( ! $heading ) {
	return;
}
?>
<div class="vendor-popout">
	<div class="vendor-popout__image">
		<?php if ( $image ) : ?>
			<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy">
		<?php endif; ?>
	</div>

	<div class="vendor-popout__content">
		<h2 class="vendor-popout__title"><?php echo esc_html( $heading ); ?></h2>

		<?php if ( $desc ) : ?>
			<p class="vendor-popout__desc"><?php echo wp_kses_post( $desc ); ?></p>
		<?php endif; ?>

		<?php if ( $cta_text && $cta_url ) : ?>
			<a class="btn btn--primary vendor-popout__cta" href="<?php echo esc_url( $cta_url ); ?>">
				<?php echo esc_html( $cta_text ); ?>
			</a>
		<?php endif; ?>
	</div>
</div>
