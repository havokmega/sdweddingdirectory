<?php
/**
 * Template Name: Wedding Planning
 *
 * Orchestrator for the wedding planning page.
 */

get_header();
?>

<div class="planning">
    <?php
    get_template_part( 'template-parts/planning/planning-hero' );
    get_template_part( 'template-parts/planning/planning-intro' );
    get_template_part( 'template-parts/planning/planning-checklist' );
    get_template_part( 'template-parts/planning/planning-vendor-organizer' );
    get_template_part( 'template-parts/planning/planning-wedding-website' );
    get_template_part( 'template-parts/planning/planning-secondary-intro' );
    get_template_part( 'template-parts/planning/planning-tool-cards' );
    get_template_part( 'template-parts/planning/planning-detailed-copy' );
    get_template_part( 'template-parts/planning/planning-faq' );
    ?>
</div>

<?php
get_footer();
