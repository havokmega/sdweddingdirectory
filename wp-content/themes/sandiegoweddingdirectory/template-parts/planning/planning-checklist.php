<?php
/**
 * Planning: Wedding Checklist Feature Block
 */
?>
<section class="planning-checklist planning-feature-section section">
    <div class="container">
        <?php
        get_template_part( 'template-parts/components/feature-block', null, [
            'heading'      => __( 'Wedding Planning Checklist', 'sdweddingdirectory' ),
            'desc'         => __( 'Stay on track with a complete planning timeline so nothing slips through the cracks.', 'sdweddingdirectory' ),
            'intro_text'   => __( 'Learn more', 'sdweddingdirectory' ),
            'intro_url'    => home_url( '/wedding-planning/wedding-checklist/' ),
            'sections'     => [
                [
                    'title' => __( 'Make it your own', 'sdweddingdirectory' ),
                    'desc'  => __( 'Customize your checklist by adding new tasks, editing details, or removing items whenever you need.', 'sdweddingdirectory' ),
                ],
                [
                    'title' => __( 'Monitor your progress', 'sdweddingdirectory' ),
                    'desc'  => __( 'Instantly see what is finished and what still needs attention as your wedding date approaches.', 'sdweddingdirectory' ),
                ],
                [
                    'title' => __( 'Connected to your budget', 'sdweddingdirectory' ),
                    'desc'  => __( 'Your SDWeddingDirectory budget and checklist work together so every task aligns with your spending plan.', 'sdweddingdirectory' ),
                ],
            ],
            'cta_text'     => __( 'Personalize Checklist', 'sdweddingdirectory' ),
            'cta_url'      => home_url( '/wedding-planning/wedding-checklist/' ),
            'image_url'    => get_template_directory_uri() . '/assets/images/planning/wedding-planning-checklist.png',
            'image_alt'    => __( 'Wedding Planning Checklist interface preview', 'sdweddingdirectory' ),
            'image_width'  => 724,
            'image_height' => 632,
            'testimonial'  => [
                'initials' => __( 'AL', 'sdweddingdirectory' ),
                'author'   => __( 'Alex and Lauren', 'sdweddingdirectory' ),
                'quote'    => __( 'The checklist made it simple to stay focused and keep every deadline moving forward.', 'sdweddingdirectory' ),
            ],
        ] );
        ?>
    </div>
</section>
