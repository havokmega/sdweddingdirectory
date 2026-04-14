<?php
/**
 * Couple Dashboard — Right sidebar 1: Wedding Website Link
 *
 * Shows the couple's wedding website URL and an edit link. If the website
 * module is not yet available, renders a "Coming soon" block.
 *
 * $args:
 *   - website_url (string, optional)
 */

$website_url = $args['website_url'] ?? '';
?>

<aside class="cd-widget cd-widget--website">
    <h3 class="cd-widget__title">
        <span class="icon-globe" aria-hidden="true"></span>
        <?php esc_html_e( 'Your Wedding Website', 'sdweddingdirectory' ); ?>
    </h3>

    <?php if ( $website_url ) : ?>
        <a href="<?php echo esc_url( $website_url ); ?>" class="cd-widget__website-url" target="_blank" rel="noopener">
            <?php echo esc_html( $website_url ); ?>
        </a>
        <a href="<?php echo esc_url( home_url( '/couple-dashboard/website/' ) ); ?>" class="cd-widget__link">
            <?php esc_html_e( 'Edit website', 'sdweddingdirectory' ); ?> &rarr;
        </a>
    <?php else : ?>
        <div class="cd-widget__soon">
            <?php esc_html_e( 'Wedding website builder is coming soon.', 'sdweddingdirectory' ); ?>
        </div>
    <?php endif; ?>
</aside>
