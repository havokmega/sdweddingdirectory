<?php
/**
 * Registry: Feature (reversed) — image left, copy right.
 */

$image = get_template_directory_uri() . '/assets/images/placeholders/registry-feature-2.jpg';
?>
<section class="registry-s4-registry-feature-reversed section">
    <div class="container registry-s4-registry-feature-reversed__inner">
        <div class="registry-s4-registry-feature-reversed__media">
            <img class="registry-s4-registry-feature-reversed__image" src="<?php echo esc_url( $image ); ?>" alt="<?php esc_attr_e( 'Track gifts in one place', 'sandiegoweddingdirectory' ); ?>" loading="lazy">
        </div>
        <div class="registry-s4-registry-feature-reversed__copy">
            <p class="registry-s4-registry-feature-reversed__eyebrow"><?php esc_html_e( 'Stay organized', 'sandiegoweddingdirectory' ); ?></p>
            <h2 class="registry-s4-registry-feature-reversed__heading"><?php esc_html_e( 'Track gifts and thank-yous in one place', 'sandiegoweddingdirectory' ); ?></h2>
            <p class="registry-s4-registry-feature-reversed__desc"><?php esc_html_e( 'See what was purchased, who sent it, and tick off thank-you notes as you go — no spreadsheets required.', 'sandiegoweddingdirectory' ); ?></p>
            <a class="btn btn--primary" href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>"><?php esc_html_e( 'Open the dashboard', 'sandiegoweddingdirectory' ); ?></a>
        </div>
    </div>
</section>
