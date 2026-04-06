<?php
/**
 * Template Name: FAQs
 *
 * FAQ page with policy subnav, 4-tab interface, and contact details.
 */

get_header();
?>

<?php
get_template_part( 'template-parts/components/policy-subnav', null, [
    'active' => 'faqs',
] );
?>

<div class="container section">
    <h1 class="faq-page__title"><?php esc_html_e( 'Frequently Asked Questions', 'sdweddingdirectory' ); ?></h1>

    <div class="faq-tabs">
        <div class="faq-tabs__nav" role="tablist">
            <button class="faq-tabs__tab faq-tabs__tab--active" role="tab" aria-selected="true" aria-controls="faq-panel-general" data-tab="general">
                <?php esc_html_e( 'General', 'sdweddingdirectory' ); ?>
            </button>
            <button class="faq-tabs__tab" role="tab" aria-selected="false" aria-controls="faq-panel-vendor" data-tab="vendor">
                <?php esc_html_e( 'Vendor', 'sdweddingdirectory' ); ?>
            </button>
            <button class="faq-tabs__tab" role="tab" aria-selected="false" aria-controls="faq-panel-couples" data-tab="couples">
                <?php esc_html_e( 'Groom & Brides', 'sdweddingdirectory' ); ?>
            </button>
            <button class="faq-tabs__tab" role="tab" aria-selected="false" aria-controls="faq-panel-pricing" data-tab="pricing">
                <?php esc_html_e( 'Pricing', 'sdweddingdirectory' ); ?>
            </button>
        </div>

        <div class="faq-tabs__panels">
            <div class="faq-tabs__panel faq-tabs__panel--active" id="faq-panel-general" role="tabpanel" data-tab="general">
                <?php
                get_template_part( 'template-parts/components/faq-accordion', null, [
                    'items' => [
                        [
                            'question' => __( 'What is SD Wedding Directory?', 'sdweddingdirectory' ),
                            'answer'   => '<p>' . __( 'SD Wedding Directory is San Diego\'s dedicated wedding resource. We connect engaged couples with the best local wedding vendors and venues, and provide free planning tools to help you organize every detail of your celebration.', 'sdweddingdirectory' ) . '</p>',
                        ],
                        [
                            'question' => __( 'Is it free to use?', 'sdweddingdirectory' ),
                            'answer'   => '<p>' . __( 'Yes, SD Wedding Directory is completely free for couples. You can browse vendors and venues, save favorites, use planning tools, and create a wedding website at no cost.', 'sdweddingdirectory' ) . '</p>',
                        ],
                        [
                            'question' => __( 'How do I contact a vendor or venue?', 'sdweddingdirectory' ),
                            'answer'   => '<p>' . __( 'Each vendor and venue listing includes a contact form that allows you to send a direct message or request a quote. Simply visit the listing page and fill out the inquiry form.', 'sdweddingdirectory' ) . '</p>',
                        ],
                    ],
                ] );
                ?>
            </div>

            <div class="faq-tabs__panel" id="faq-panel-vendor" role="tabpanel" data-tab="vendor" hidden>
                <?php
                get_template_part( 'template-parts/components/faq-accordion', null, [
                    'items' => [
                        [
                            'question' => __( 'How do I list my business on SD Wedding Directory?', 'sdweddingdirectory' ),
                            'answer'   => '<p>' . __( 'You can create a vendor account and set up your listing through the vendor dashboard. Add your business details, photos, services, and pricing to start connecting with engaged couples.', 'sdweddingdirectory' ) . '</p>',
                        ],
                        [
                            'question' => __( 'Can I manage my own listing?', 'sdweddingdirectory' ),
                            'answer'   => '<p>' . __( 'Yes, vendors have full control over their listings through the vendor dashboard. You can update your description, photos, pricing, and availability at any time.', 'sdweddingdirectory' ) . '</p>',
                        ],
                        [
                            'question' => __( 'How do I receive leads and inquiries?', 'sdweddingdirectory' ),
                            'answer'   => '<p>' . __( 'When a couple sends you a message or quote request through your listing, you will receive a notification via email and in your vendor dashboard. You can respond directly from the dashboard.', 'sdweddingdirectory' ) . '</p>',
                        ],
                    ],
                ] );
                ?>
            </div>

            <div class="faq-tabs__panel" id="faq-panel-couples" role="tabpanel" data-tab="couples" hidden>
                <?php
                get_template_part( 'template-parts/components/faq-accordion', null, [
                    'items' => [
                        [
                            'question' => __( 'How do I create a couple account?', 'sdweddingdirectory' ),
                            'answer'   => '<p>' . __( 'Click the "Join as a Couple" button in the header to create your free account. Once registered, you will have access to your personal dashboard with planning tools, saved vendors, and your wedding website builder.', 'sdweddingdirectory' ) . '</p>',
                        ],
                        [
                            'question' => __( 'Can I save my favorite vendors?', 'sdweddingdirectory' ),
                            'answer'   => '<p>' . __( 'Yes, you can save any vendor or venue to your favorites list from their listing page. Access your saved favorites from your couple dashboard at any time.', 'sdweddingdirectory' ) . '</p>',
                        ],
                        [
                            'question' => __( 'How do I create a wedding website?', 'sdweddingdirectory' ),
                            'answer'   => '<p>' . __( 'Log in to your couple dashboard and navigate to the Wedding Website section. Choose a template, add your story and event details, and share the link with your guests.', 'sdweddingdirectory' ) . '</p>',
                        ],
                    ],
                ] );
                ?>
            </div>

            <div class="faq-tabs__panel" id="faq-panel-pricing" role="tabpanel" data-tab="pricing" hidden>
                <?php
                get_template_part( 'template-parts/components/faq-accordion', null, [
                    'items' => [
                        [
                            'question' => __( 'Is SD Wedding Directory free for couples?', 'sdweddingdirectory' ),
                            'answer'   => '<p>' . __( 'Yes, SD Wedding Directory is completely free for engaged couples. All planning tools, vendor browsing, and the wedding website builder are included at no cost.', 'sdweddingdirectory' ) . '</p>',
                        ],
                        [
                            'question' => __( 'What does a vendor listing cost?', 'sdweddingdirectory' ),
                            'answer'   => '<p>' . __( 'We offer flexible listing options for vendors and venues. Contact us for current pricing information and available packages.', 'sdweddingdirectory' ) . '</p>',
                        ],
                        [
                            'question' => __( 'Are there any hidden fees?', 'sdweddingdirectory' ),
                            'answer'   => '<p>' . __( 'No. There are no hidden fees, no commissions on bookings, and no transaction charges. What you see in our pricing packages is what you pay.', 'sdweddingdirectory' ) . '</p>',
                        ],
                    ],
                ] );
                ?>
            </div>
        </div>
    </div>

    <?php get_template_part( 'template-parts/components/contact-details' ); ?>
</div>

<?php
get_footer();
