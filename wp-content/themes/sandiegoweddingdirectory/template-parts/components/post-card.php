<?php
/**
 * Component: Post Card (Blog/Article)
 *
 * @param array $args {
 *     @type int    $post_id       Required. Post ID.
 *     @type string $image_size    Optional. WP image size. Default 'medium'.
 *     @type bool   $show_category Optional. Show category badge. Default true.
 * }
 */

$post_id       = $args['post_id'] ?? 0;
$image_size    = $args['image_size'] ?? 'medium';
$show_category = $args['show_category'] ?? true;

if ( ! $post_id ) {
    return;
}

$title     = get_the_title( $post_id );
$permalink = get_permalink( $post_id );
$thumbnail = get_the_post_thumbnail( $post_id, $image_size, [ 'class' => 'post-card__image', 'alt' => esc_attr( $title ) ] );

$categories = get_the_category( $post_id );
$category   = ! empty( $categories ) ? $categories[0] : null;
?>
<a class="post-card" href="<?php echo esc_url( $permalink ); ?>">
    <?php if ( $thumbnail ) : ?>
        <div class="post-card__media">
            <?php echo $thumbnail; ?>
        </div>
    <?php endif; ?>
    <div class="post-card__body">
        <?php if ( $show_category && $category ) : ?>
            <span class="post-card__category"><?php echo esc_html( $category->name ); ?></span>
        <?php endif; ?>
        <h3 class="post-card__title"><?php echo esc_html( $title ); ?></h3>
    </div>
</a>
