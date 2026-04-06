<?php
/**
 * Taxonomy: Real Wedding Style
 *
 * Standard real wedding taxonomy grid: page-header, real-wedding-card grid, pagination.
 */

get_header();

$term = get_queried_object();

get_template_part( 'template-parts/components/page-header', null, [
    'title'       => single_term_title( '', false ),
    'desc'        => term_description(),
    'breadcrumbs' => [
        [ 'label' => __( 'Home', 'sdweddingdirectory-v2' ), 'url' => home_url( '/' ) ],
        [ 'label' => __( 'Real Weddings', 'sdweddingdirectory-v2' ), 'url' => get_post_type_archive_link( 'real-wedding' ) ],
        [ 'label' => single_term_title( '', false ), 'url' => '' ],
    ],
] );

$paged = max( 1, get_query_var( 'paged', 1 ) );

$rw_query = new WP_Query( [
    'post_type'      => 'real-wedding',
    'posts_per_page' => 12,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'tax_query'      => [
        [
            'taxonomy' => 'real-wedding-style',
            'field'    => 'term_id',
            'terms'    => $term->term_id,
        ],
    ],
] );
?>

<section class="section">
    <div class="container">
        <?php if ( $rw_query->have_posts() ) : ?>
            <div class="grid grid--4col">
                <?php
                while ( $rw_query->have_posts() ) :
                    $rw_query->the_post();
                    get_template_part( 'template-parts/components/real-wedding-card', null, [
                        'post_id'    => get_the_ID(),
                        'image_size' => 'medium',
                    ] );
                endwhile;
                wp_reset_postdata();
                ?>
            </div>

            <?php
            get_template_part( 'template-parts/components/pagination', null, [
                'query' => $rw_query,
            ] );
            ?>
        <?php else : ?>
            <p><?php esc_html_e( 'No real weddings found.', 'sdweddingdirectory-v2' ); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php
get_footer();
