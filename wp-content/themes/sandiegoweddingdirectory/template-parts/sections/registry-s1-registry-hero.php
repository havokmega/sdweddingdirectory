<?php
/**
 * Registry: Hero — color-block hero with primary + secondary CTA.
 *
 * H1 pulls from the current page title so the same hero works on the
 * landing page (/wedding-registry/) and child pages (/wedding-registry/X/).
 * Description prefers the page excerpt; falls back to a per-template default.
 */

$page_id  = absint( get_queried_object_id() );
$is_child = is_page_template( 'page-registry-child.php' );
$title    = $page_id ? get_the_title( $page_id ) : __( 'Build a registry guests will love', 'sandiegoweddingdirectory' );
$excerpt  = $page_id ? get_post_field( 'post_excerpt', $page_id ) : '';

if ( $excerpt ) {
    $desc = $excerpt;
} elseif ( $is_child ) {
    $desc = __( 'Browse every retailer that pairs with your free SD Wedding Directory registry — one link, every store.', 'sandiegoweddingdirectory' );
} else {
    $desc = __( 'Pick the retailers you love, share one link, and let your guests give you exactly what you need for your new life together.', 'sandiegoweddingdirectory' );
}
?>
<section class="registry-s1-registry-hero" aria-label="<?php echo esc_attr( $title ); ?>">
    <div class="container registry-s1-registry-hero__inner">
        <div class="registry-s1-registry-hero__copy">
            <h1 class="registry-s1-registry-hero__title"><?php echo esc_html( $title ); ?></h1>
            <p class="registry-s1-registry-hero__desc"><?php echo esc_html( $desc ); ?></p>
            <div class="registry-s1-registry-hero__actions">
                <a class="btn btn--primary" href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>"><?php esc_html_e( 'Create your registry', 'sandiegoweddingdirectory' ); ?></a>
                <a class="btn btn--outline-primary" href="<?php echo esc_url( home_url( '/wedding-registry/retail-registries/' ) ); ?>"><?php esc_html_e( 'Find couple registry', 'sandiegoweddingdirectory' ); ?></a>
            </div>
        </div>
        <div class="registry-s1-registry-hero__art" aria-hidden="true"></div>
    </div>
</section>
