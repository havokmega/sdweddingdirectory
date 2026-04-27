<?php
/**
 * Template Name: Wedding Cost — Landing
 *
 * Orchestrator for the cost landing page (/cost/).
 */

get_header();
?>

<div class="cost">
    <?php
    get_template_part( 'template-parts/sections/cost-s1-dark-hero' );
    get_template_part( 'template-parts/sections/cost-s2-small-icon-row' );
    get_template_part( 'template-parts/sections/cost-s3-category-card-panel' );
    get_template_part( 'template-parts/sections/cost-s4-image-cards-3up-borderless' );
    get_template_part( 'template-parts/sections/cost-s5-promo-banner' );
    get_template_part( 'template-parts/sections/cost-s6-3col-links' );
    ?>
</div>

<?php
get_footer();
