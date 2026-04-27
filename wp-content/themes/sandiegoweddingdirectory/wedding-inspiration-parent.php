<?php
/**
 * Wedding Inspiration — Category Parent View (partial)
 *
 * Rendered by category.php for non-planning categories under /wedding-inspiration/.
 * Renders the category page header (title + desc + filter chips) and the
 * article grid with sticky sidebar + pagination.
 *
 * Not a WP page template — included via category.php (Option B).
 */

if ( ! defined( 'ABSPATH' ) ) {
    return;
}
?>
<div class="blog-parent">
    <?php
    get_template_part( 'template-parts/sections/blog-parent-s1-category-page-header' );
    get_template_part( 'template-parts/sections/blog-parent-s2-article-grid-sidebar' );
    ?>
</div>
