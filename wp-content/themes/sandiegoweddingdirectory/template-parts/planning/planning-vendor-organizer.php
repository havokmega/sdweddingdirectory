<?php
/**
 * Planning: Vendor Organizer Feature Block
 */
?>
<section class="planning-vendor-organizer planning-feature-section section">
    <div class="container">
        <?php
        get_template_part( 'template-parts/components/feature-block', null, [
            'heading'      => __( 'Vendor Organizer', 'sandiegoweddingdirectory' ),
            'desc'         => __( 'Search, organize, and communicate with vendors all in one place.', 'sandiegoweddingdirectory' ),
            'intro_text'   => __( 'Learn more', 'sandiegoweddingdirectory' ),
            'intro_url'    => home_url( '/wedding-planning/vendor-manager/' ),
            'sections'     => [
                [
                    'title' => __( 'Reach out with ease', 'sandiegoweddingdirectory' ),
                    'desc'  => __( 'Browse professionals and send messages directly through your SDWeddingDirectory account.', 'sandiegoweddingdirectory' ),
                ],
                [
                    'title' => __( 'Keep detailed notes', 'sandiegoweddingdirectory' ),
                    'desc'  => __( 'Store important information and reminders for each vendor so nothing gets forgotten.', 'sandiegoweddingdirectory' ),
                ],
                [
                    'title' => __( 'Save and compare', 'sandiegoweddingdirectory' ),
                    'desc'  => __( 'Bookmark top choices and review pricing and feedback side by side to make confident decisions.', 'sandiegoweddingdirectory' ),
                ],
            ],
            'cta_text'     => __( 'Browse Vendors', 'sandiegoweddingdirectory' ),
            'cta_url'      => home_url( '/wedding-planning/vendor-manager/' ),
            'image_url'    => get_template_directory_uri() . '/assets/images/planning/wedding-planning-vendor-manager.png',
            'image_alt'    => __( 'Vendor organizer interface preview', 'sandiegoweddingdirectory' ),
            'image_width'  => 938,
            'image_height' => 749,
            'testimonial'  => [
                'avatar_url' => get_template_directory_uri() . '/assets/images/real-wedding/rw_jessica-david-1.png',
                'avatar_alt' => __( 'Jessica and David', 'sandiegoweddingdirectory' ),
                'author'     => __( 'Jessica and David', 'sandiegoweddingdirectory' ),
                'quote'      => __( 'Having notes and messages in one place made vendor decisions so much faster.', 'sandiegoweddingdirectory' ),
            ],
        ] );
        ?>
    </div>
</section>
