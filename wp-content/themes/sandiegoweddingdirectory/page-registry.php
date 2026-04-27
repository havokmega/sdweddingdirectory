<?php
/**
 * Template Name: Wedding Registry
 *
 * Orchestrator for the wedding registry landing page.
 */

get_header();
?>

<div class="registry">
    <?php
    get_template_part( 'template-parts/sections/registry-s1-registry-hero' );
    get_template_part( 'template-parts/sections/registry-s2-logo-grid' );
    get_template_part( 'template-parts/sections/registry-s3-registry-feature' );
    get_template_part( 'template-parts/sections/registry-s4-registry-feature-reversed' );
    get_template_part( 'template-parts/sections/registry-s5-registry-feature' );
    get_template_part( 'template-parts/sections/registry-s6-faq' );
    get_template_part( 'template-parts/sections/registry-s7-feature-card-grid-33' );
    ?>
</div>

<?php
get_footer();
