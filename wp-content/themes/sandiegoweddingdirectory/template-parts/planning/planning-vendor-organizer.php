<?php
/**
 * Planning: Vendor Organizer Feature Block
 */
?>
<section class="planning-vendor-organizer planning-feature-section section">
    <div class="container">
        <?php
        get_template_part( 'template-parts/components/feature-block', null, [
            'heading'      => __( 'Vendor Organizer', 'sdweddingdirectory' ),
            'desc'         => __( 'Search, organize, and communicate with vendors all in one place.', 'sdweddingdirectory' ),
            'intro_text'   => __( 'Learn more', 'sdweddingdirectory' ),
            'intro_url'    => home_url( '/wedding-planning/vendor-manager/' ),
            'sections'     => [
                [
                    'title' => __( 'Reach out with ease', 'sdweddingdirectory' ),
                    'desc'  => __( 'Browse professionals and send messages directly through your SDWeddingDirectory account.', 'sdweddingdirectory' ),
                ],
                [
                    'title' => __( 'Keep detailed notes', 'sdweddingdirectory' ),
                    'desc'  => __( 'Store important information and reminders for each vendor so nothing gets forgotten.', 'sdweddingdirectory' ),
                ],
                [
                    'title' => __( 'Save and compare', 'sdweddingdirectory' ),
                    'desc'  => __( 'Bookmark top choices and review pricing and feedback side by side to make confident decisions.', 'sdweddingdirectory' ),
                ],
            ],
            'cta_text'     => __( 'Browse Vendors', 'sdweddingdirectory' ),
            'cta_url'      => home_url( '/wedding-planning/vendor-manager/' ),
            'image_url'    => get_template_directory_uri() . '/assets/images/planning/wedding-planning-vendor-manager.png',
            'image_alt'    => __( 'Vendor organizer interface preview', 'sdweddingdirectory' ),
            'image_width'  => 938,
            'image_height' => 749,
            'testimonial'  => [
                'initials' => __( 'JM', 'sdweddingdirectory' ),
                'author'   => __( 'Jordan and Mia', 'sdweddingdirectory' ),
                'quote'    => __( 'Having notes and messages in one place made vendor decisions so much faster.', 'sdweddingdirectory' ),
            ],
        ] );
        ?>
    </div>
</section>
