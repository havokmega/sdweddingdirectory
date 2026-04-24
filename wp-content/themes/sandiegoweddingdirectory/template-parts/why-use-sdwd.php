<?php
/**
 * Template Part: Why Use SD Wedding Directory
 *
 * Auto-detects post type and shows relevant copy for vendor vs venue.
 */

$post_type = get_post_type();

if ( 'venue' === $post_type ) {
    $heading     = __( 'Why List Your Venue on SD Wedding Directory?', 'sandiegoweddingdirectory' );
    $description = __( 'SD Wedding Directory connects your venue with engaged couples actively searching for wedding locations in San Diego County.', 'sandiegoweddingdirectory' );
    $bullets     = [
        __( 'Receive direct messages and inquiries from interested couples', 'sandiegoweddingdirectory' ),
        __( 'Get quote requests sent straight to your inbox', 'sandiegoweddingdirectory' ),
        __( 'Showcase your venue with photos, pricing, and availability', 'sandiegoweddingdirectory' ),
        __( 'Reach couples who are ready to book their wedding venue', 'sandiegoweddingdirectory' ),
    ];
} else {
    $heading     = __( 'Why List Your Business on SD Wedding Directory?', 'sandiegoweddingdirectory' );
    $description = __( 'SD Wedding Directory connects your business with engaged couples actively searching for wedding vendors in San Diego County.', 'sandiegoweddingdirectory' );
    $bullets     = [
        __( 'Receive direct messages from couples planning their wedding', 'sandiegoweddingdirectory' ),
        __( 'Get quote requests delivered straight to your dashboard', 'sandiegoweddingdirectory' ),
        __( 'Showcase your services with photos, reviews, and pricing', 'sandiegoweddingdirectory' ),
        __( 'Reach couples who are actively booking wedding professionals', 'sandiegoweddingdirectory' ),
    ];
}
?>
<section class="why-sdwd section">
    <div class="container">
        <h2 class="why-sdwd__heading"><?php echo esc_html( $heading ); ?></h2>
        <p class="why-sdwd__desc"><?php echo esc_html( $description ); ?></p>
        <ul class="why-sdwd__list">
            <?php foreach ( $bullets as $bullet ) : ?>
                <li class="why-sdwd__item">
                    <span class="why-sdwd__icon" aria-hidden="true">&#xe9a1;</span>
                    <?php echo esc_html( $bullet ); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
