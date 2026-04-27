<?php
/**
 * Registry: Feature (third) — copy left, image right.
 */

$image = get_template_directory_uri() . '/assets/images/placeholders/registry-feature-3.jpg';
?>
<section class="registry-s5-registry-feature section">
    <div class="container registry-s5-registry-feature__inner">
        <div class="registry-s5-registry-feature__copy">
            <p class="registry-s5-registry-feature__eyebrow"><?php esc_html_e( 'Cash funds, honeymoon, charity', 'sandiegoweddingdirectory' ); ?></p>
            <h2 class="registry-s5-registry-feature__heading"><?php esc_html_e( 'Add the kind of gifts that actually fit your life', 'sandiegoweddingdirectory' ); ?></h2>
            <p class="registry-s5-registry-feature__desc"><?php esc_html_e( 'Mix traditional gifts with cash funds, honeymoon experiences, and charitable causes — all from the same registry page.', 'sandiegoweddingdirectory' ); ?></p>
            <a class="btn btn--primary" href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>"><?php esc_html_e( 'Start your registry', 'sandiegoweddingdirectory' ); ?></a>
        </div>
        <div class="registry-s5-registry-feature__media">
            <img class="registry-s5-registry-feature__image" src="<?php echo esc_url( $image ); ?>" alt="<?php esc_attr_e( 'Mix gift types in one registry', 'sandiegoweddingdirectory' ); ?>" loading="lazy">
        </div>
    </div>
</section>
