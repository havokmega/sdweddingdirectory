<?php
/**
 * Blog Archive Template
 */

get_header();
?>

<div class="blog-archive">
    <?php
    get_template_part( 'template-parts/components/page-header', null, [
        'title' => get_the_archive_title(),
        'desc'  => get_the_archive_description(),
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
            <p><?php esc_html_e( 'No posts found.', 'sdweddingdirectory-v2' ); ?></p>
        <?php endif; ?>
    </div>
</div>

<?php
get_footer();
