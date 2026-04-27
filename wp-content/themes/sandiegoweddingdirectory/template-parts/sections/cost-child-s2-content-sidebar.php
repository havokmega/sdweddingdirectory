<?php
/**
 * Cost Child: Long-form content + sticky sidebar.
 *
 * Renders the_content() for the page in the main column and a sticky
 * sidebar with quick-jump links and a related-categories block.
 */

$page_id   = absint( get_queried_object_id() );
$has_query = isset( $GLOBALS['wp_query'] );
?>
<section class="cost-child-s2-content-sidebar section">
    <div class="container cost-child-s2-content-sidebar__inner">
        <article class="cost-child-s2-content-sidebar__main">
            <?php
            if ( $has_query ) {
                if ( have_posts() ) {
                    while ( have_posts() ) {
                        the_post();
                        the_content();
                    }
                    wp_reset_postdata();
                } elseif ( $page_id ) {
                    echo apply_filters( 'the_content', get_post_field( 'post_content', $page_id ) );
                }
            }
            ?>

            <?php if ( ! $page_id || ! get_post_field( 'post_content', $page_id ) ) : ?>
                <p><?php esc_html_e( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'sandiegoweddingdirectory' ); ?></p>
                <h2><?php esc_html_e( 'What you can expect to pay', 'sandiegoweddingdirectory' ); ?></h2>
                <p><?php esc_html_e( 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'sandiegoweddingdirectory' ); ?></p>
                <h2><?php esc_html_e( 'What drives the cost up', 'sandiegoweddingdirectory' ); ?></h2>
                <p><?php esc_html_e( 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', 'sandiegoweddingdirectory' ); ?></p>
                <h2><?php esc_html_e( 'How to save', 'sandiegoweddingdirectory' ); ?></h2>
                <p><?php esc_html_e( 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'sandiegoweddingdirectory' ); ?></p>
            <?php endif; ?>
        </article>

        <aside class="cost-child-s2-content-sidebar__sidebar">
            <div class="cost-child-s2-content-sidebar__widget">
                <h3 class="cost-child-s2-content-sidebar__widget-title"><?php esc_html_e( 'On this page', 'sandiegoweddingdirectory' ); ?></h3>
                <ul class="cost-child-s2-content-sidebar__jump">
                    <li><a href="#what-you-can-expect-to-pay"><?php esc_html_e( 'What to expect', 'sandiegoweddingdirectory' ); ?></a></li>
                    <li><a href="#what-drives-the-cost-up"><?php esc_html_e( 'What drives cost', 'sandiegoweddingdirectory' ); ?></a></li>
                    <li><a href="#how-to-save"><?php esc_html_e( 'How to save', 'sandiegoweddingdirectory' ); ?></a></li>
                </ul>
            </div>

            <div class="cost-child-s2-content-sidebar__widget cost-child-s2-content-sidebar__widget--cta">
                <h3 class="cost-child-s2-content-sidebar__widget-title"><?php esc_html_e( 'Plan your wedding budget', 'sandiegoweddingdirectory' ); ?></h3>
                <p><?php esc_html_e( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'sandiegoweddingdirectory' ); ?></p>
                <a class="btn btn--primary" href="<?php echo esc_url( home_url( '/dashboard/budget/' ) ); ?>"><?php esc_html_e( 'Open budget tool', 'sandiegoweddingdirectory' ); ?></a>
            </div>
        </aside>
    </div>
</section>
