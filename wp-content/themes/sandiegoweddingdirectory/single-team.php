<?php
/**
 * Single Team Member Template
 *
 * 2-column layout with content left, "More Team Members" sidebar right.
 */

get_header();

$role = get_post_meta( get_the_ID(), 'team_role', true );
?>

<div class="container section">
    <div class="team-single grid grid--2col">
        <div class="team-single__content">
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="team-single__media">
                    <?php the_post_thumbnail( 'medium_large', [ 'class' => 'team-single__image' ] ); ?>
                </div>
            <?php endif; ?>

            <h1 class="team-single__name"><?php the_title(); ?></h1>
            <?php if ( $role ) : ?>
                <p class="team-single__role"><?php echo esc_html( $role ); ?></p>
            <?php endif; ?>

            <div class="team-single__bio">
                <?php the_content(); ?>
            </div>
        </div>

        <aside class="team-single__sidebar">
            <h2 class="team-single__sidebar-title"><?php esc_html_e( 'More Team Members', 'sdweddingdirectory-v2' ); ?></h2>
            <?php
            $other_members = new WP_Query( [
                'post_type'      => 'team',
                'posts_per_page' => 6,
                'post__not_in'   => [ get_the_ID() ],
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
            ] );

            if ( $other_members->have_posts() ) :
            ?>
                <ul class="team-single__member-list">
                    <?php
                    while ( $other_members->have_posts() ) :
                        $other_members->the_post();
                        $member_role = get_post_meta( get_the_ID(), 'team_role', true );
                    ?>
                        <li class="team-single__member-item">
                            <a class="team-single__member-link" href="<?php the_permalink(); ?>">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'thumbnail', [ 'class' => 'team-single__member-thumb' ] ); ?>
                                <?php endif; ?>
                                <div class="team-single__member-info">
                                    <span class="team-single__member-name"><?php the_title(); ?></span>
                                    <?php if ( $member_role ) : ?>
                                        <span class="team-single__member-role"><?php echo esc_html( $member_role ); ?></span>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php
                wp_reset_postdata();
            endif;
            ?>
        </aside>
    </div>
</div>

<?php
get_footer();
