<?php
/**
 * Planning: Wedding Website Builder Feature Block
 */
?>
<section class="planning-wedding-website planning-feature-section section">
    <div class="container">
        <?php
        get_template_part( 'template-parts/components/feature-block', null, [
            'heading'      => __( 'Custom Wedding Website', 'sdweddingdirectory' ),
            'desc'         => __( 'Build a personalized site to share details with your guests.', 'sdweddingdirectory' ),
            'intro_text'   => __( 'Learn more', 'sdweddingdirectory' ),
            'intro_url'    => home_url( '/wedding-planning/wedding-website/' ),
            'sections'     => [
                [
                    'title' => __( 'Launch quickly', 'sdweddingdirectory' ),
                    'desc'  => __( 'Select a layout, enter your event information, and publish your site in just a few steps.', 'sdweddingdirectory' ),
                ],
                [
                    'title' => __( 'Link your registry', 'sdweddingdirectory' ),
                    'desc'  => __( 'Connect your registry so guests can easily find everything in one place.', 'sdweddingdirectory' ),
                ],
                [
                    'title' => __( 'Manage RSVPs', 'sdweddingdirectory' ),
                    'desc'  => __( 'Guest responses automatically update your Guest List, keeping everything organized.', 'sdweddingdirectory' ),
                ],
            ],
            'cta_text'     => __( 'Start Your Website', 'sdweddingdirectory' ),
            'cta_url'      => home_url( '/wedding-planning/wedding-website/' ),
            'image_url'    => get_template_directory_uri() . '/assets/images/planning/wedding-planning-wedding-website.png',
            'image_alt'    => __( 'Wedding website interface preview', 'sdweddingdirectory' ),
            'image_width'  => 1002,
            'image_height' => 824,
            'testimonial'  => [
                'initials' => __( 'CK', 'sdweddingdirectory' ),
                'author'   => __( 'Casey and Kai', 'sdweddingdirectory' ),
                'quote'    => __( 'Our website, RSVPs, and guest info all stayed synced, which saved us hours every week.', 'sdweddingdirectory' ),
            ],
        ] );
        ?>
    </div>
</section>
