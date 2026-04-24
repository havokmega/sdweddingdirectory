<?php
/**
 * Archive: Real Weddings
 *
 * Displays real weddings with gallery images in a 4-column card grid.
 * Uses a custom WP_Query with meta_query to ensure gallery existence.
 */

get_header();

get_template_part( 'template-parts/components/page-header', null, [
    'title'       => __( 'Real Weddings', 'sandiegoweddingdirectory' ),
    'desc'        => __( 'Get inspired by real San Diego weddings', 'sandiegoweddingdirectory' ),
    'breadcrumbs' => [
        [ 'label' => __( 'Home', 'sandiegoweddingdirectory' ), 'url' => home_url( '/' ) ],
        [ 'label' => __( 'Real Weddings', 'sandiegoweddingdirectory' ), 'url' => '' ],
    ],
] );

$paged = max( 1, get_query_var( 'paged', 1 ) );

$rw_query = new WP_Query( [
    'post_type'      => 'real-wedding',
    'posts_per_page' => 12,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'meta_query'     => [
        [
            'key'     => 'realwedding_gallery',
            'compare' => 'EXISTS',
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
            <p><?php esc_html_e( 'No real weddings found.', 'sandiegoweddingdirectory' ); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php
get_footer();
