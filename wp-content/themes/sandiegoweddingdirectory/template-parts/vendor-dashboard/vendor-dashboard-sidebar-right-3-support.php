<?php
/**
 * Vendor Dashboard — Right sidebar 3: Support
 */
?>

<aside class="vd-widget">
    <h3 class="vd-widget__title">
        <span class="icon-envelope" aria-hidden="true"></span>
        <?php esc_html_e( 'Need Help?', 'sandiegoweddingdirectory' ); ?>
    </h3>

    <p class="vd-widget__text">
        <?php esc_html_e( 'Questions about your listing or dashboard? We are here to help.', 'sandiegoweddingdirectory' ); ?>
    </p>

    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="vd-widget__link">
        <?php esc_html_e( 'Contact support', 'sandiegoweddingdirectory' ); ?> &rarr;
    </a>
</aside>
