<?php
/**
 * Template Name: Policy
 *
 * Policy page with subnav and page content.
 */

get_header();

$page_slug = get_post_field( 'post_name', get_the_ID() );
?>

<?php
get_template_part( 'template-parts/components/policy-subnav', null, [
    'active' => $page_slug,
] );
?>

<div class="container section">
    <div class="policy-page__content">
        <?php the_content(); ?>
    </div>
</div>

<?php
get_footer();
