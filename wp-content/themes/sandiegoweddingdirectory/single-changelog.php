<?php
/**
 * Single Changelog Template
 */

get_header();
?>

<div class="container section">
    <article class="changelog-single">
        <h1 class="changelog-single__title"><?php the_title(); ?></h1>
        <time class="changelog-single__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
            <?php echo esc_html( get_the_date() ); ?>
        </time>
        <div class="changelog-single__content">
            <?php the_content(); ?>
        </div>
    </article>
</div>

<?php
get_footer();
