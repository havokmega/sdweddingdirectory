<?php
/**
 * Template Name: Wedding Inspiration (Blog Index)
 *
 * Orchestrator for the wedding inspiration / blog index page.
 * Assign this template to the WP page that lives at /wedding-inspiration/.
 */

get_header();
?>

<div class="blog-index">
    <?php
    get_template_part( 'template-parts/sections/blog-s1-blog-hero' );
    get_template_part( 'template-parts/sections/blog-s2-circle-category-row-10up' );
    get_template_part( 'template-parts/sections/blog-s3-featured-grid-1plus4' );
    get_template_part( 'template-parts/sections/blog-s4-article-grid-sidebar' );
    ?>
</div>

<?php
get_footer();
