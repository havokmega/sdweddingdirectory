<?php
/**
 * Template Name: Wedding Cost — Child
 *
 * Orchestrator for cost child pages (e.g. /cost/wedding-photographer-san-diego/).
 * Assign this template to each of the 16 cost child pages in wp-admin.
 */

get_header();
?>

<div class="cost-child">
    <?php
    get_template_part( 'template-parts/sections/cost-child-s1-breadcrumb-page-header' );
    get_template_part( 'template-parts/sections/cost-child-s2-content-sidebar' );
    get_template_part( 'template-parts/sections/cost-child-s3-image-cards-3up-borderless' );
    get_template_part( 'template-parts/sections/cost-child-s4-3col-links' );
    ?>
</div>

<?php
get_footer();
