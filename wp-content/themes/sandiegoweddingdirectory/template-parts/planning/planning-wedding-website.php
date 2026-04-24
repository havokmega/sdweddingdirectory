<?php
/**
 * Planning: Wedding Website Builder Feature Block
 */
?>
<section class="planning-wedding-website planning-feature-section section">
    <div class="container">
        <?php
        get_template_part( 'template-parts/components/feature-block', null, [
            'heading'      => __( 'Custom Wedding Website', 'sandiegoweddingdirectory' ),
            'desc'         => __( 'Build a personalized site to share details with your guests.', 'sandiegoweddingdirectory' ),
            'intro_text'   => __( 'Learn more', 'sandiegoweddingdirectory' ),
            'intro_url'    => home_url( '/wedding-planning/wedding-website/' ),
            'sections'     => [
                [
                    'title' => __( 'Launch quickly', 'sandiegoweddingdirectory' ),
                    'desc'  => __( 'Select a layout, enter your event information, and publish your site in just a few steps.', 'sandiegoweddingdirectory' ),
                ],
                [
                    'title' => __( 'Link your registry', 'sandiegoweddingdirectory' ),
                    'desc'  => __( 'Connect your registry so guests can easily find everything in one place.', 'sandiegoweddingdirectory' ),
                ],
                [
                    'title' => __( 'Manage RSVPs', 'sandiegoweddingdirectory' ),
                    'desc'  => __( 'Guest responses automatically update your Guest List, keeping everything organized.', 'sandiegoweddingdirectory' ),
                ],
            ],
            'cta_text'     => __( 'Start Your Website', 'sandiegoweddingdirectory' ),
            'cta_url'      => home_url( '/wedding-planning/wedding-website/' ),
            'image_url'    => get_template_directory_uri() . '/assets/images/planning/wedding-planning-wedding-website.png',
            'image_alt'    => __( 'Wedding website interface preview', 'sandiegoweddingdirectory' ),
            'image_width'  => 1002,
            'image_height' => 824,
            'testimonial'  => [
                'avatar_url' => get_template_directory_uri() . '/assets/images/real-wedding/rw_emily-james-1.png',
                'avatar_alt' => __( 'Emily and James', 'sandiegoweddingdirectory' ),
                'author'     => __( 'Emily and James', 'sandiegoweddingdirectory' ),
                'quote'      => __( 'Our website, RSVPs, and guest info all stayed synced, which saved us hours every week.', 'sandiegoweddingdirectory' ),
            ],
        ] );
        ?>
    </div>
</section>
