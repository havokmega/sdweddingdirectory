<?php
/**
 * Cost: Promo banner — icon + text + action buttons.
 */
?>
<section class="cost-s5-promo-banner section" aria-label="<?php esc_attr_e( 'Plan your wedding budget', 'sandiegoweddingdirectory' ); ?>">
    <div class="container">
        <div class="cost-s5-promo-banner__inner">
            <span class="cost-s5-promo-banner__icon icon-calculator" aria-hidden="true"></span>
            <div class="cost-s5-promo-banner__copy">
                <h2 class="cost-s5-promo-banner__title"><?php esc_html_e( 'Build your wedding budget for free', 'sandiegoweddingdirectory' ); ?></h2>
                <p class="cost-s5-promo-banner__desc"><?php esc_html_e( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore.', 'sandiegoweddingdirectory' ); ?></p>
            </div>
            <div class="cost-s5-promo-banner__actions">
                <a class="btn btn--primary" href="<?php echo esc_url( home_url( '/dashboard/budget/' ) ); ?>"><?php esc_html_e( 'Open budget calculator', 'sandiegoweddingdirectory' ); ?></a>
                <a class="btn btn--secondary" href="<?php echo esc_url( home_url( '/wedding-planning/' ) ); ?>"><?php esc_html_e( 'See planning tools', 'sandiegoweddingdirectory' ); ?></a>
            </div>
        </div>
    </div>
</section>
