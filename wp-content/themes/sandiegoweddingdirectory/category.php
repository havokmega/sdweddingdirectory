<?php
/**
 * Category Archive Template
 *
 * Standard grid layout. If category is a child of "wedding-planning-how-to",
 * shows a 2-column layout with sidebar (category browser + planning links).
 */

get_header();

$current_cat = get_queried_object();
$parent_cat  = null;
$is_planning = false;

if ( $current_cat && $current_cat->parent ) {
    $parent_cat = get_category( $current_cat->parent );
}

// Check if this category or its parent is "wedding-planning-how-to"
if (
    ( $current_cat && 'wedding-planning-how-to' === $current_cat->slug ) ||
    ( $parent_cat && 'wedding-planning-how-to' === $parent_cat->slug )
) {
    $is_planning = true;
}

get_template_part( 'template-parts/components/page-header', null, [
    'title' => single_cat_title( '', false ),
    'desc'  => category_description(),
] );
?>

<div class="container section">
    <?php if ( $is_planning ) : ?>
        <div class="category-archive category-archive--with-sidebar grid grid--2col">
            <div class="category-archive__main">
                <?php if ( have_posts() ) : ?>
                    <div class="category-archive__posts">
                        <?php
                        while ( have_posts() ) :
                            the_post();
                            get_template_part( 'template-parts/components/post-card', null, [
                                'post_id' => get_the_ID(),
                            ] );
                        endwhile;
                        ?>
                    </div>
                    <?php get_template_part( 'template-parts/components/pagination' ); ?>
                <?php else : ?>
                    <p><?php esc_html_e( 'No posts found in this category.', 'sdweddingdirectory-v2' ); ?></p>
                <?php endif; ?>
            </div>

            <aside class="category-archive__sidebar">
                <div class="category-archive__widget">
                    <h3 class="category-archive__widget-title"><?php esc_html_e( 'Browse Categories', 'sdweddingdirectory-v2' ); ?></h3>
                    <?php
                    $planning_parent = get_category_by_slug( 'wedding-planning-how-to' );
                    if ( $planning_parent ) :
                        $child_cats = get_categories( [
                            'parent'     => $planning_parent->term_id,
                            'hide_empty' => false,
                            'orderby'    => 'name',
                            'order'      => 'ASC',
                        ] );
                    ?>
                        <ul class="category-archive__cat-list">
                            <li>
                                <a href="<?php echo esc_url( get_category_link( $planning_parent->term_id ) ); ?>"
                                   <?php if ( $current_cat->term_id === $planning_parent->term_id ) : ?>class="category-archive__cat-link--active"<?php endif; ?>>
                                    <?php echo esc_html( $planning_parent->name ); ?>
                                </a>
                            </li>
                            <?php foreach ( $child_cats as $child ) : ?>
                                <li>
                                    <a href="<?php echo esc_url( get_category_link( $child->term_id ) ); ?>"
                                       <?php if ( $current_cat->term_id === $child->term_id ) : ?>class="category-archive__cat-link--active"<?php endif; ?>>
                                        <?php echo esc_html( $child->name ); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>

                <div class="category-archive__widget">
                    <h3 class="category-archive__widget-title"><?php esc_html_e( 'Planning Tools', 'sdweddingdirectory-v2' ); ?></h3>
                    <ul class="category-archive__link-list">
                        <li><a href="<?php echo esc_url( home_url( '/wedding-planning/' ) ); ?>"><?php esc_html_e( 'Wedding Planning', 'sdweddingdirectory-v2' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/dashboard/checklist/' ) ); ?>"><?php esc_html_e( 'Wedding Checklist', 'sdweddingdirectory-v2' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/dashboard/budget/' ) ); ?>"><?php esc_html_e( 'Budget Calculator', 'sdweddingdirectory-v2' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/dashboard/guest-list/' ) ); ?>"><?php esc_html_e( 'Guest List Manager', 'sdweddingdirectory-v2' ); ?></a></li>
                    </ul>
                </div>
            </aside>
        </div>
    <?php else : ?>
        <?php if ( have_posts() ) : ?>
            <div class="grid grid--4col">
                <?php
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/components/post-card', null, [
                        'post_id' => get_the_ID(),
                    ] );
                endwhile;
                ?>
            </div>
            <?php get_template_part( 'template-parts/components/pagination' ); ?>
        <?php else : ?>
            <p><?php esc_html_e( 'No posts found in this category.', 'sdweddingdirectory-v2' ); ?></p>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php
get_footer();
