<?php
/**
 * Planning: FAQ Section
 *
 * Uses the reusable faq-section component with planning-specific questions.
 */

get_template_part( 'template-parts/components/faq-section', null, [
    'heading'   => __( 'Frequently Asked Questions', 'sdweddingdirectory-v2' ),
    'desc'      => __( "Have questions about the Checklist tool? We've got you.", 'sdweddingdirectory-v2' ),
    'align'     => 'left',
    'id_prefix' => 'planning-faq',
    'open'      => 1,
    'items'     => [
        [
            'question' => __( 'Is your Wedding Checklist free?', 'sdweddingdirectory-v2' ),
            'answer'   => '<p>' . __( 'Yes. The Wedding Checklist is included with your free SD Wedding Directory account, so you can start organizing tasks as soon as you sign up.', 'sdweddingdirectory-v2' ) . '</p>',
        ],
        [
            'question' => __( 'What do I need to put on my Wedding Checklist?', 'sdweddingdirectory-v2' ),
            'answer'   => '<p>' . __( 'We provide helpful default items to begin with. If you\'re skipping certain traditions, you can easily edit or add tasks to match your personal plans.', 'sdweddingdirectory-v2' ) . '</p>',
        ],
        [
            'question' => __( 'Can I plan my entire wedding with your Wedding Checklist?', 'sdweddingdirectory-v2' ),
            'answer'   => '<p>' . __( 'The Checklist is built to guide your full planning process, and it works even better alongside the budget, guest list, seating chart, and vendor organizer in your dashboard.', 'sdweddingdirectory-v2' ) . '</p>',
        ],
        [
            'question' => __( 'Does your Wedding Checklist include a timeline?', 'sdweddingdirectory-v2' ),
            'answer'   => '<p>' . __( 'Yes. Default checklist items are organized around your wedding date, with tasks scheduled months, weeks, and days before the celebration.', 'sdweddingdirectory-v2' ) . '</p>',
        ],
        [
            'question' => __( 'Can I print my Wedding Checklist?', 'sdweddingdirectory-v2' ),
            'answer'   => '<p>' . __( 'The Checklist is meant to stay editable online, but if you want a paper copy you can use your browser\'s print option whenever you need one.', 'sdweddingdirectory-v2' ) . '</p>',
        ],
    ],
] );
