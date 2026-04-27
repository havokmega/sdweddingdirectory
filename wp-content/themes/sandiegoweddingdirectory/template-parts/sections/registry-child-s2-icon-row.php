<?php
/**
 * Registry Child: 3-up icon row.
 *
 * Reuses the planning-child-icons + planning-child-tool-card styles
 * (planning.css is enqueued for registry pages, so the look is identical
 * to the icon row at the top of /wedding-planning/{slug}/ pages).
 */

$theme_uri = get_template_directory_uri();

$items = [
    [
        'icon'  => 'wedding-website/1_gift.svg',
        'title' => __( 'One link, every store', 'sandiegoweddingdirectory' ),
        'desc'  => __( 'Add gifts from any retailer and share a single registry URL with every guest.', 'sandiegoweddingdirectory' ),
    ],
    [
        'icon'  => 'wedding-website/2_envelope.svg',
        'title' => __( 'Cash + experience funds', 'sandiegoweddingdirectory' ),
        'desc'  => __( 'Pair traditional gifts with honeymoon, home, and charity funds — all on one page.', 'sandiegoweddingdirectory' ),
    ],
    [
        'icon'  => 'checklist/1_clipboard.svg',
        'title' => __( 'Thank-you tracking', 'sandiegoweddingdirectory' ),
        'desc'  => __( 'See who sent what and check off thank-yous as they go out the door.', 'sandiegoweddingdirectory' ),
    ],
];
?>
<section class="planning-child-icons section" aria-label="<?php esc_attr_e( 'Registry highlights', 'sandiegoweddingdirectory' ); ?>">
    <div class="container">
        <div class="registry-s2-logo-grid__header">
            <h2 class="registry-s2-logo-grid__heading"><?php esc_html_e( 'Why egaged couples choose our wedding registry', 'sandiegoweddingdirectory' ); ?></h2>
        </div>

        <div class="planning-child-icons__grid grid grid--3col">
            <?php foreach ( $items as $card ) : ?>
                <div class="planning-child-tool-card">
                    <img class="planning-child-tool-card__icon" src="<?php echo esc_url( $theme_uri . '/assets/images/planning/' . $card['icon'] ); ?>" alt="" loading="lazy">
                    <h3 class="planning-child-tool-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
                    <p class="planning-child-tool-card__desc"><?php echo esc_html( $card['desc'] ); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
