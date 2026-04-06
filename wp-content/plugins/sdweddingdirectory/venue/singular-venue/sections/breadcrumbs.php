<?php
/**
 * Venue Singular - Section 1: Breadcrumbs + Counter + Next Link
 */

$post_id = isset( $post_id ) ? absint( $post_id ) : absint( get_the_ID() );

if( empty( $post_id ) ){
    return;
}

$business_name = get_the_title( $post_id );

$terms = wp_get_post_terms( $post_id, sanitize_key( 'venue-type' ) );
$term = ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ? $terms[0] : null;

$category_name = $term ? $term->name : esc_html__( 'All Venues', 'sdweddingdirectory' );
$category_url  = $term ? get_term_link( $term ) : home_url( '/venues/' );

$query_args = [
    'post_type'      => esc_attr( 'venue' ),
    'post_status'    => [ 'publish' ],
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'orderby'        => 'title',
    'order'          => 'ASC',
];

if( $term ){
    $query_args['tax_query'] = [
        [
            'taxonomy' => sanitize_key( 'venue-type' ),
            'field'    => 'term_id',
            'terms'    => [ absint( $term->term_id ) ],
        ],
    ];
}

$venues_query = new WP_Query( $query_args );
$venue_ids = is_array( $venues_query->posts ) ? array_values( array_map( 'absint', $venues_query->posts ) ) : [];

wp_reset_postdata();

$total_count = count( $venue_ids );
$current_pos = array_search( $post_id, $venue_ids, true );
$current_pos = $current_pos === false ? 0 : absint( $current_pos );
$display_pos = $total_count > 0 ? ( $current_pos + 1 ) : 1;

$next_id = $total_count > 0 ? $venue_ids[ ( $current_pos + 1 ) % $total_count ] : 0;
$next_url = $next_id > 0 ? get_permalink( $next_id ) : home_url( '/venues/' );
?>
<div class="sd-profile-breadcrumbs border-bottom">
    <div class="container d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/venues/' ) ); ?>"><?php esc_html_e( 'Weddings', 'sdweddingdirectory' ); ?></a></li>
                <li class="breadcrumb-item"><a href="<?php echo esc_url( $category_url ); ?>"><?php echo esc_html( $category_name ); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo esc_html( $business_name ); ?></li>
            </ol>
        </nav>
        <div class="sd-profile-counter">
            <span><?php echo esc_html( sprintf( '%1$s of %2$s', absint( $display_pos ), max( absint( $total_count ), absint( '1' ) ) ) ); ?></span>
            <a href="<?php echo esc_url( $next_url ); ?>" class="ms-2"><?php esc_html_e( 'Next', 'sdweddingdirectory' ); ?> <i class="fa fa-arrow-right"></i></a>
        </div>
    </div>
</div>
