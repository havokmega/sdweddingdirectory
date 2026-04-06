<?php
/**
 * Component: Venue Card
 *
 * @param array $args {
 *     @type int    $post_id     Required. Venue post ID.
 *     @type string $image_size  Optional. WP image size. Default 'medium'.
 * }
 */

$post_id    = $args['post_id'] ?? 0;
$image_size = $args['image_size'] ?? 'medium';

if ( ! $post_id ) {
    return;
}

$title     = get_the_title( $post_id );
$permalink = get_permalink( $post_id );
$thumb_id  = get_post_thumbnail_id( $post_id );
$thumbnail = ( $thumb_id && file_exists( get_attached_file( $thumb_id ) ) )
    ? get_the_post_thumbnail( $post_id, $image_size, [ 'class' => 'card__image', 'alt' => esc_attr( $title ) ] )
    : '';

$types     = wp_get_post_terms( $post_id, 'venue-type', [ 'fields' => 'names' ] );
$type      = ! is_wp_error( $types ) && ! empty( $types ) ? $types[0] : '';

$locations = wp_get_post_terms( $post_id, 'venue-location', [ 'fields' => 'names' ] );
$location  = ! is_wp_error( $locations ) && ! empty( $locations ) ? $locations[0] : '';

// Rating — uses plugin filter; returns formatted number or 0.
$rating_value = apply_filters( 'sdweddingdirectory/rating/average', '', [
    'venue_id' => $post_id,
] );
$rating_num = is_numeric( $rating_value ) ? (float) $rating_value : 0;

// Review count.
$reviews_query = function_exists( 'sdwdv2_get_venue_reviews_query' )
    ? sdwdv2_get_venue_reviews_query( $post_id, -1 )
    : null;
$review_count = $reviews_query ? $reviews_query->found_posts : 0;
if ( $reviews_query ) {
    wp_reset_postdata();
}

// Price.
$price_label = function_exists( 'sdwdv2_get_venue_starting_price' )
    ? sdwdv2_get_venue_starting_price( $post_id )
    : '';

// Capacity.
$capacity = get_post_meta( $post_id, 'venue_seat_capacity', true );

if ( ! $thumbnail ) {
    $location_terms = wp_get_post_terms( $post_id, 'venue-location', [ 'fields' => 'slugs' ] );
    $location_slug  = ( ! is_wp_error( $location_terms ) && ! empty( $location_terms ) ) ? $location_terms[0] : '';
    $placeholder    = ( $location_slug && function_exists( 'sdwdv2_get_venue_location_image_url' ) )
        ? sdwdv2_get_venue_location_image_url( $location_slug )
        : get_template_directory_uri() . '/assets/images/placeholders/venue-post/placeholder.jpg';
    $thumbnail = '<img class="card__image" src="' . esc_url( $placeholder ) . '" alt="' . esc_attr( $title ) . '">';
}
?>
<div class="card card--venue">
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
                <?php if ( $location ) : ?>
                    <span class="card__rating-sep">&middot;</span>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ( $location ) : ?>
                <span class="card__location"><?php echo esc_html( $location ); ?></span>
            <?php endif; ?>
        </div>

        <?php if ( $price_label || $capacity ) : ?>
            <div class="card__details-row">
                <?php if ( $price_label ) : ?>
                    <span class="card__price"><?php echo esc_html( $price_label ); ?></span>
                <?php endif; ?>
                <?php if ( $capacity ) : ?>
                    <span class="card__capacity">
                        <span class="icon-seating-chart" aria-hidden="true"></span>
                        <?php echo esc_html( $capacity ); ?>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <a class="card__request-link" href="<?php echo esc_url( $permalink ); ?>">
            <?php esc_html_e( 'Request pricing', 'sdweddingdirectory' ); ?>
        </a>
    </div>
</div>
