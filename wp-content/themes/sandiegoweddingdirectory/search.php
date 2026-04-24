<?php
/**
 * Search Results Template
 */

get_header();

get_template_part( 'template-parts/components/page-header', null, [
    'title' => sprintf(
        /* translators: %s: search query */
        __( 'Search Results for: %s', 'sandiegoweddingdirectory' ),
        get_search_query()
    ),
] );
?>

<div class="container section">
    <?php if ( have_posts() ) : ?>
        <div class="grid grid--4col">
            <?php
            while ( have_posts() ) :
                the_post();
                get_template_part( 'template-parts/components/post-card', null, [
                    'post_id' => get_the_ID(),
                ] );
            endwhile;
            ?>
        </div>

        <?php get_template_part( 'template-parts/components/pagination' ); ?>
    <?php else : ?>
        <div class="search-no-results">
            <p><?php esc_html_e( 'No results found. Try a different search term.', 'sandiegoweddingdirectory' ); ?></p>
            <?php get_search_form(); ?>
        </div>
    <?php endif; ?>
</div>

<?php
get_footer();
