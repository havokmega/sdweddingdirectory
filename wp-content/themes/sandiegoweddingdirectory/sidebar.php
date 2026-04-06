<?php
/**
 * Sidebar Template
 *
 * Renders the blog sidebar widget area.
 */

if ( ! is_active_sidebar( 'sdwdv2-blog-sidebar' ) ) {
    return;
}
?>
<aside class="sidebar" role="complementary" aria-label="<?php esc_attr_e( 'Sidebar', 'sdweddingdirectory-v2' ); ?>">
    <?php dynamic_sidebar( 'sdwdv2-blog-sidebar' ); ?>
</aside>
