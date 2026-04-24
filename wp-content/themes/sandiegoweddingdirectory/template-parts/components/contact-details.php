<?php
/**
 * Component: Contact Details
 *
 * Hard-coded 3-column contact info: Phone, Address, Inquiry.
 */
?>
<div class="contact-details">
    <div class="contact-details__grid grid grid--3col">
        <div class="contact-details__item">
            <span class="contact-details__icon icon-building" aria-hidden="true"></span>
            <h3 class="contact-details__label"><?php esc_html_e( 'Phone', 'sandiegoweddingdirectory' ); ?></h3>
            <p class="contact-details__value">
                <a href="tel:+16195551212">(619) 555-1212</a>
            </p>
        </div>
        <div class="contact-details__item">
            <span class="contact-details__icon icon-building" aria-hidden="true"></span>
            <h3 class="contact-details__label"><?php esc_html_e( 'Address', 'sandiegoweddingdirectory' ); ?></h3>
            <p class="contact-details__value">
                <?php esc_html_e( 'San Diego, CA 92101', 'sandiegoweddingdirectory' ); ?>
            </p>
        </div>
        <div class="contact-details__item">
            <span class="contact-details__icon icon-envelope-o" aria-hidden="true"></span>
            <h3 class="contact-details__label"><?php esc_html_e( 'Inquiry', 'sandiegoweddingdirectory' ); ?></h3>
            <p class="contact-details__value">
                <a href="mailto:maildesk@sdweddingdirectory.com">maildesk@sdweddingdirectory.com</a>
            </p>
        </div>
    </div>
</div>
