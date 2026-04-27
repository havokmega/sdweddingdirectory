<?php
/**
 * Registry: FAQ — uses the reusable faq-section component.
 */

get_template_part( 'template-parts/components/faq-section', null, [
    'heading'   => __( 'Frequently Asked Questions', 'sandiegoweddingdirectory' ),
    'desc'      => __( 'Everything to know about building a wedding registry.', 'sandiegoweddingdirectory' ),
    'align'     => 'left',
    'id_prefix' => 'registry-faq',
    'open'      => 0,
    'items'     => [
        [
            'question' => __( 'When should I create my wedding registry?', 'sandiegoweddingdirectory' ),
            'answer'   => '<p>' . __( 'Most couples set up their registry as soon as they\'re engaged so it\'s ready to share with engagement parties, showers, and save-the-dates.', 'sandiegoweddingdirectory' ) . '</p>',
        ],
        [
            'question' => __( 'How many retailers should I register with?', 'sandiegoweddingdirectory' ),
            'answer'   => '<p>' . __( 'Two or three is plenty. Pick stores that cover different price points and styles so guests have options at every budget.', 'sandiegoweddingdirectory' ) . '</p>',
        ],
        [
            'question' => __( 'Can I add cash, honeymoon, or charity gifts?', 'sandiegoweddingdirectory' ),
            'answer'   => '<p>' . __( 'Yes — alongside traditional gifts, you can add cash funds, honeymoon experiences, and charitable causes to the same registry.', 'sandiegoweddingdirectory' ) . '</p>',
        ],
        [
            'question' => __( 'How do I share my registry with guests?', 'sandiegoweddingdirectory' ),
            'answer'   => '<p>' . __( 'Share one short link in your invitations, on your wedding website, or directly with anyone who asks where you\'re registered.', 'sandiegoweddingdirectory' ) . '</p>',
        ],
        [
            'question' => __( 'Is there a fee to create a registry?', 'sandiegoweddingdirectory' ),
            'answer'   => '<p>' . __( 'Setting up your registry through SD Wedding Directory is free — you only pay if you choose retailers that charge their own optional fees.', 'sandiegoweddingdirectory' ) . '</p>',
        ],
    ],
] );
