<?php
/**
 * Planning: Wedding Checklist Feature Block
 */
?>
<section class="planning-checklist planning-feature-section section">
    <div class="container">
        <?php
        get_template_part( 'template-parts/components/feature-block', null, [
            'heading'      => __( 'Wedding Planning Checklist', 'sandiegoweddingdirectory' ),
            'desc'         => __( 'Stay on track with a complete planning timeline so nothing slips through the cracks.', 'sandiegoweddingdirectory' ),
            'intro_text'   => __( 'Learn more', 'sandiegoweddingdirectory' ),
            'intro_url'    => home_url( '/wedding-planning/wedding-checklist/' ),
            'sections'     => [
                [
                    'title' => __( 'Make it your own', 'sandiegoweddingdirectory' ),
                    'desc'  => __( 'Customize your checklist by adding new tasks, editing details, or removing items whenever you need.', 'sandiegoweddingdirectory' ),
                ],
                [
                    'title' => __( 'Monitor your progress', 'sandiegoweddingdirectory' ),
                    'desc'  => __( 'Instantly see what is finished and what still needs attention as your wedding date approaches.', 'sandiegoweddingdirectory' ),
                ],
                [
                    'title' => __( 'Connected to your budget', 'sandiegoweddingdirectory' ),
                    'desc'  => __( 'Your SDWeddingDirectory budget and checklist work together so every task aligns with your spending plan.', 'sandiegoweddingdirectory' ),
                ],
            ],
            'cta_text'     => __( 'Personalize Checklist', 'sandiegoweddingdirectory' ),
            'cta_url'      => home_url( '/wedding-planning/wedding-checklist/' ),
            'image_url'    => get_template_directory_uri() . '/assets/images/planning/wedding-planning-checklist.png',
            'image_alt'    => __( 'Wedding Planning Checklist interface preview', 'sandiegoweddingdirectory' ),
            'image_width'  => 724,
            'image_height' => 632,
            'testimonial'  => [
                'avatar_url' => get_template_directory_uri() . '/assets/images/real-wedding/rw_sarah-michael-1.png',
                'avatar_alt' => __( 'Sarah and Michael', 'sandiegoweddingdirectory' ),
                'author'     => __( 'Sarah and Michael', 'sandiegoweddingdirectory' ),
                'quote'      => __( 'The checklist made it simple to stay focused and keep every deadline moving forward.', 'sandiegoweddingdirectory' ),
            ],
        ] );
        ?>
    </div>
</section>
