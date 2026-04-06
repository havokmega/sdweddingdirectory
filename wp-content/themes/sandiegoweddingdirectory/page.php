<?php
/**
 * Default Page Template
 */

get_header();

$is_planning_child = absint( wp_get_post_parent_id( get_the_ID() ) ) === 4180;

if ( $is_planning_child ) :

    get_template_part( 'template-parts/planning/planning-child-page' );

else : ?>

    <div class="container section">
        <?php
        while ( have_posts() ) :
            the_post();
            the_content();
        endwhile;
        ?>
    </div>

<?php endif; ?>

<?php
get_footer();
