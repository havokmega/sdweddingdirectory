<?php
/**
 * Couple Dashboard — Right sidebar 4: RSVP
 *
 * Placeholder until the RSVP module is rebuilt in SDWD Couple plugin.
 */
?>

<aside class="cd-widget cd-widget--rsvp">
    <h3 class="cd-widget__title">
        <span class="icon-envelope" aria-hidden="true"></span>
        <?php esc_html_e( 'RSVP Tracker', 'sdweddingdirectory' ); ?>
    </h3>

    <div class="cd-widget__soon">
        <?php esc_html_e( 'RSVP tracking opens when guest management is ready.', 'sdweddingdirectory' ); ?>
    </div>

    <a href="<?php echo esc_url( home_url( '/couple-dashboard/guest-management/' ) ); ?>" class="cd-widget__link">
        <?php esc_html_e( 'Go to guest management', 'sdweddingdirectory' ); ?> &rarr;
    </a>
</aside>
