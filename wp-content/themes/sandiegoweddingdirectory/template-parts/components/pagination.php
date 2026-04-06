<?php
/**
 * Component: Pagination
 *
 * @param array $args {
 *     @type WP_Query $query Optional. Custom query. Default global $wp_query.
 * }
 */

$query = $args['query'] ?? null;

if ( $query ) {
    $total   = $query->max_num_pages;
    $current = max( 1, $query->get( 'paged' ) ?: get_query_var( 'paged', 1 ) );
} else {
    global $wp_query;
    $total   = $wp_query->max_num_pages ?? 1;
    $current = max( 1, get_query_var( 'paged', 1 ) );
}

if ( $total <= 1 ) {
    return;
}

$add_args = [];

foreach ( $_GET as $key => $value ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if ( 'paged' === $key ) {
        continue;
    }

    $key = sanitize_key( $key );

    if ( is_array( $value ) ) {
        $add_args[ $key ] = array_map( 'sanitize_text_field', wp_unslash( $value ) );
    } else {
        $add_args[ $key ] = sanitize_text_field( wp_unslash( $value ) );
    }
}

$links = paginate_links( [
    'total'     => $total,
    'current'   => $current,
    'prev_text' => '<span class="icon-chevron-left" aria-hidden="true"></span><span class="screen-reader-text">' . esc_html__( 'Previous', 'sdweddingdirectory-v2' ) . '</span>',
    'next_text' => '<span class="icon-chevron-right" aria-hidden="true"></span><span class="screen-reader-text">' . esc_html__( 'Next', 'sdweddingdirectory-v2' ) . '</span>',
    'type'      => 'array',
    'mid_size'  => 2,
    'add_args'  => $add_args,
] );

if ( ! $links ) {
    return;
}
?>
<nav class="pagination" aria-label="<?php esc_attr_e( 'Page navigation', 'sdweddingdirectory-v2' ); ?>">
    <?php foreach ( $links as $link ) : ?>
        <?php echo $link; ?>
    <?php endforeach; ?>
</nav>
