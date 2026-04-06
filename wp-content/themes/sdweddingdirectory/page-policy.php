<?php
/**
 *  Template Name: Policy Page
 *  --------------------------
 *  Shared template for Privacy Policy, CA Privacy, Terms of Use.
 *  Renders the policy sub-nav then the_content().
 */
get_header();
?>

<?php get_template_part( 'template-parts/policy-subnav' ); ?>

<main id="content" class="site-content">
    <div class="main-content content wide-tb-90">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                        <div class="sd-policy-page-content">
                            <?php the_content(); ?>
                        </div>
                    <?php endwhile; endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
