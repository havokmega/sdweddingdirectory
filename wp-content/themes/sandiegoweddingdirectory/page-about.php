<?php
/**
 * Template Name: About
 *
 * About Us page with section title and team member grid.
 */

get_header();
?>

<div class="container section">
    <?php
    get_template_part( 'template-parts/components/section-title', null, [
        'heading' => __( 'About Us', 'sandiegoweddingdirectory' ),
        'desc'    => __( 'SD Wedding Directory is San Diego\'s dedicated wedding resource, connecting engaged couples with the best local wedding vendors and venues.', 'sandiegoweddingdirectory' ),
        'align'   => 'center',
        'tag'     => 'h1',
    ] );
    ?>

    <div class="about__content">
        <?php the_content(); ?>
    </div>

    <?php
    $team_query = new WP_Query( [
        'post_type'      => 'team',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ] );

    if ( $team_query->have_posts() ) :
    ?>
        <div class="about__team">
            <?php
            get_template_part( 'template-parts/components/section-title', null, [
                'heading' => __( 'Our Team', 'sandiegoweddingdirectory' ),
                'align'   => 'center',
            ] );
            ?>

            <div class="grid grid--4col">
                <?php
                while ( $team_query->have_posts() ) :
                    $team_query->the_post();
                    $role = get_post_meta( get_the_ID(), 'team_role', true );
                ?>
                    <a class="team-card" href="<?php the_permalink(); ?>">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="team-card__media">
                                <?php the_post_thumbnail( 'medium', [ 'class' => 'team-card__image' ] ); ?>
                            </div>
                        <?php endif; ?>
                        <div class="team-card__body">
                            <h3 class="team-card__name"><?php the_title(); ?></h3>
                            <?php if ( $role ) : ?>
                                <p class="team-card__role"><?php echo esc_html( $role ); ?></p>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
    <?php
        wp_reset_postdata();
    endif;
    ?>
</div>

<?php
get_footer();
