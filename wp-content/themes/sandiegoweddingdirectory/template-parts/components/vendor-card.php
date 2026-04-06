<?php
/**
 * Component: Vendor Card
 *
 * @param array $args {
 *     @type int    $post_id     Required. Vendor post ID.
 *     @type string $image_size  Optional. WP image size. Default 'medium'.
 * }
 */

$post_id    = $args['post_id'] ?? 0;
$image_size = $args['image_size'] ?? 'medium';

if ( ! $post_id ) {
    return;
}

$title      = function_exists( 'sdwdv2_get_vendor_company_name' ) ? sdwdv2_get_vendor_company_name( $post_id ) : get_the_title( $post_id );
$permalink  = get_permalink( $post_id );
$thumb_id   = get_post_thumbnail_id( $post_id );
$thumbnail  = ( $thumb_id && file_exists( get_attached_file( $thumb_id ) ) )
    ? get_the_post_thumbnail( $post_id, $image_size, [ 'class' => 'card__image', 'alt' => esc_attr( $title ) ] )
    : '';
$categories = wp_get_post_terms( $post_id, 'vendor-category', [ 'fields' => 'names' ] );
$category   = ! is_wp_error( $categories ) && ! empty( $categories ) ? $categories[0] : '';

// Rating — uses plugin filter.
$rating_value = apply_filters( 'sdweddingdirectory/rating/average', '', [
    'venue_id' => $post_id,
] );
$rating_num = is_numeric( $rating_value ) ? (float) $rating_value : 0;

// Review count.
$reviews_query = function_exists( 'sdwdv2_get_vendor_reviews_query' )
    ? sdwdv2_get_vendor_reviews_query( $post_id, -1 )
    : null;
$review_count = $reviews_query ? $reviews_query->found_posts : 0;
if ( $reviews_query ) {
    wp_reset_postdata();
}

// Price.
$price_label = function_exists( 'sdwdv2_get_vendor_starting_price' )
    ? sdwdv2_get_vendor_starting_price( $post_id )
    : '';

if ( ! $thumbnail ) {
    $banner_id = function_exists( 'sdwdv2_get_vendor_image_id' ) ? sdwdv2_get_vendor_image_id( $post_id ) : 0;
    $banner    = ( $banner_id && file_exists( get_attached_file( $banner_id ) ) )
        ? wp_get_attachment_image( $banner_id, $image_size, false, [ 'class' => 'card__image', 'alt' => esc_attr( $title ) ] )
        : '';

    if ( $banner ) {
        $thumbnail = $banner;
    } else {
        // Use the vendor's category image as the default until a business claims and uploads their own.
        $primary_cat = function_exists( 'sdwdv2_get_vendor_primary_category' ) ? sdwdv2_get_vendor_primary_category( $post_id ) : null;
        $placeholder = $primary_cat && function_exists( 'sdwdv2_get_vendor_category_image_url' )
            ? sdwdv2_get_vendor_category_image_url( $primary_cat )
            : get_template_directory_uri() . '/assets/images/placeholders/vendor-post/vendor-post.jpg';
        $thumbnail   = '<img class="card__image" src="' . esc_url( $placeholder ) . '" alt="' . esc_attr( $title ) . '">';
    }
}
?>
<div class="card card--vendor">
    <a class="card__media" href="<?php echo esc_url( $permalink ); ?>">
        <?php echo $thumbnail; ?>
    </a>
    <div class="card__body">
        <h3 class="card__title">
            <a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
        </h3>

        <div class="card__rating-row">
            <?php if ( $rating_num > 0 ) : ?>
                <span class="card__star icon-star" aria-hidden="true"></span>
                <span class="card__rating-value"><?php echo esc_html( number_format_i18n( $rating_num, 1 ) ); ?></span>
                <?php if ( $review_count > 0 ) : ?>
                    <span class="card__review-count">(<?php echo esc_html( $review_count ); ?>)</span>
                <?php endif; ?>
                <?php if ( $category ) : ?>
                    <span class="card__rating-sep">&middot;</span>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ( $category ) : ?>
                <span class="card__location"><?php echo esc_html( $category ); ?></span>
            <?php endif; ?>
        </div>

        <?php if ( $price_label ) : ?>
            <div class="card__details-row">
                <span class="card__price"><?php echo esc_html( $price_label ); ?></span>
            </div>
        <?php endif; ?>

        <a class="card__request-link" href="<?php echo esc_url( $permalink ); ?>">
            <?php esc_html_e( 'Request pricing', 'sdweddingdirectory' ); ?>
        </a>
    </div>
</div>
