<?php
/**
 * Taxonomy: Real Wedding Location
 *
 * Special template with parent/child logic:
 * - If parent term (has children): show child term location cards.
 * - If leaf term (no children): show real-wedding-card grid with pagination.
 */

get_header();

$term    = get_queried_object();
$term_id = $term->term_id;

get_template_part( 'template-parts/components/page-header', null, [
    'title'       => single_term_title( '', false ),
    'desc'        => term_description(),
    'breadcrumbs' => [
        [ 'label' => __( 'Home', 'sdweddingdirectory-v2' ), 'url' => home_url( '/' ) ],
        [ 'label' => __( 'Real Weddings', 'sdweddingdirectory-v2' ), 'url' => get_post_type_archive_link( 'real-wedding' ) ],
        [ 'label' => single_term_title( '', false ), 'url' => '' ],
    ],
] );

// Check if this term has children
$child_terms = get_terms( [
    'taxonomy'   => 'real-wedding-location',
    'parent'     => $term_id,
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
] );

$has_children = ! is_wp_error( $child_terms ) && ! empty( $child_terms );
?>

<section class="section">
    <div class="container">

        <?php if ( $has_children ) : ?>
            <?php // Parent term: show child location cards ?>
            <div class="grid grid--4col">
                <?php foreach ( $child_terms as $child ) : ?>
                    <a class="card" href="<?php echo esc_url( get_term_link( $child ) ); ?>">
                        <?php
                        $child_image = get_term_meta( $child->term_id, 'term_image', true );
                        if ( $child_image ) :
                            ?>
                            <div class="card__media">
                                <img class="card__image" src="<?php echo esc_url( $child_image ); ?>" alt="<?php echo esc_attr( $child->name ); ?>">
                            </div>
                        <?php endif; ?>
                        <div class="card__body">
                            <h3 class="card__title"><?php echo esc_html( $child->name ); ?></h3>
                            <span class="card__meta">
                                <?php
                                /* translators: %d: number of real weddings */
                                printf( esc_html( _n( '%d Wedding', '%d Weddings', $child->count, 'sdweddingdirectory-v2' ) ), $child->count );
                                ?>
                            </span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

        <?php else : ?>
            <?php
            // Leaf term: show real wedding cards
            $paged = max( 1, get_query_var( 'paged', 1 ) );

            $rw_query = new WP_Query( [
                'post_type'      => 'real-wedding',
                'posts_per_page' => 12,
                'paged'          => $paged,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'tax_query'      => [
                    [
                        'taxonomy' => 'real-wedding-location',
                        'field'    => 'term_id',
                        'terms'    => $term_id,
                    ],
                ],
            ] );

            if ( $rw_query->have_posts() ) :
                ?>
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
                <p><?php esc_html_e( 'No real weddings found for this location.', 'sdweddingdirectory-v2' ); ?></p>
            <?php endif; ?>

        <?php endif; ?>

    </div>
</section>

<?php
get_footer();
