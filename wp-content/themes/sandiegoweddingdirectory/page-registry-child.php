<?php
/**
 * Template Name: Wedding Registry — Retail Registries
 *
 * Orchestrator for child registry pages (e.g. /wedding-registry/retail-registries/).
 * No hero — child page goes straight into the extended retailer grid and icon row.
 */

get_header();
?>

<div class="registry-child">
    <?php
    get_template_part( 'template-parts/sections/registry-child-s1-logo-grid-extended' );
    get_template_part( 'template-parts/sections/registry-child-s2-icon-row' );
    ?>
</div>

<?php
get_footer();
