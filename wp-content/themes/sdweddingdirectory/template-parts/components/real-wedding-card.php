<?php
/**
 * Component: Real Wedding Card
 *
 * @param array $args {
 *     @type int    $post_id     Required. Real wedding post ID.
 *     @type string $image_size  Optional. WP image size. Default 'medium'.
 * }
 */

$post_id    = $args['post_id'] ?? 0;
$image_size = $args['image_size'] ?? 'medium';

if ( ! $post_id ) {
    return;
}

$permalink = get_permalink( $post_id );
$thumbnail = get_the_post_thumbnail( $post_id, $image_size, [ 'class' => 'card__image', 'alt' => '' ] );

$bride_first = get_post_meta( $post_id, 'bride_first_name', true );
$groom_first = get_post_meta( $post_id, 'groom_first_name', true );
$title       = trim( $bride_first . ' & ' . $groom_first );
if ( '&' === trim( $title ) ) {
    $title = get_the_title( $post_id );
}

$wedding_date = get_post_meta( $post_id, 'wedding_date', true );

if ( ! $thumbnail ) {
    $placeholder = get_template_directory_uri() . '/assets/images/placeholders/real-wedding/placeholder.jpg';
    $thumbnail   = '<img class="card__image" src="' . esc_url( $placeholder ) . '" alt="' . esc_attr( $title ) . '">';
}
?>
<a class="card" href="<?php echo esc_url( $permalink ); ?>">
    <div class="card__media">
        <?php echo $thumbnail; ?>
    </div>
    <div class="card__body">
        <h3 class="card__title"><?php echo esc_html( $title ); ?></h3>
        <?php if ( $wedding_date ) : ?>
            <span class="card__date"><?php echo esc_html( $wedding_date ); ?></span>
        <?php endif; ?>
    </div>
</a>
