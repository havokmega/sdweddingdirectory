<?php
/**
 * Template Name: Contact
 *
 * Contact page with header, email display, and contact-details component.
 */

get_header();

get_template_part( 'template-parts/components/page-header', null, [
    'title' => __( 'Get In Touch', 'sdweddingdirectory-v2' ),
    'desc'  => __( 'Have a question or want to learn more about SD Wedding Directory? We would love to hear from you.', 'sdweddingdirectory-v2' ),
] );
?>

<div class="container section">
    <div class="contact-page">
        <div class="contact-page__email">
            <p><?php esc_html_e( 'You can reach us at:', 'sdweddingdirectory-v2' ); ?></p>
            <p><a class="contact-page__email-link" href="mailto:maildesk@sdweddingdirectory.com">maildesk@sdweddingdirectory.com</a></p>
        </div>

        <div class="contact-page__content">
            <?php the_content(); ?>
        </div>

        <?php get_template_part( 'template-parts/components/contact-details' ); ?>
    </div>
</div>

<?php
get_footer();
