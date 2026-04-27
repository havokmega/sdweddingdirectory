<?php
/**
 * Registry: Feature — copy left, image right.
 */

$image = get_template_directory_uri() . '/assets/images/placeholders/registry-feature-1.jpg';
?>
<section class="registry-s3-registry-feature section">
    <div class="container registry-s3-registry-feature__inner">
        <div class="registry-s3-registry-feature__copy">
            <p class="registry-s3-registry-feature__eyebrow"><?php esc_html_e( 'One link, every store', 'sandiegoweddingdirectory' ); ?></p>
            <h2 class="registry-s3-registry-feature__heading"><?php esc_html_e( 'Combine every retailer into one shareable registry', 'sandiegoweddingdirectory' ); ?></h2>
            <p class="registry-s3-registry-feature__desc"><?php esc_html_e( 'No more juggling tabs. Add gifts from any store and share one simple link with friends and family — wherever they prefer to shop.', 'sandiegoweddingdirectory' ); ?></p>
            <a class="btn btn--primary" href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>"><?php esc_html_e( 'Start your registry', 'sandiegoweddingdirectory' ); ?></a>
        </div>
        <div class="registry-s3-registry-feature__media">
            <img class="registry-s3-registry-feature__image" src="<?php echo esc_url( $image ); ?>" alt="<?php esc_attr_e( 'Registry tools preview', 'sandiegoweddingdirectory' ); ?>" loading="lazy">
        </div>
    </div>
</section>
