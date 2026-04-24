<?php
/**
 * Couple Dashboard — Section 2: Guest List Overview
 *
 * Placeholder until the guest list module is rebuilt in SDWD Couple plugin.
 */
?>

<section class="cd-card cd-guest-list">
    <div class="cd-card__head">
        <h2 class="cd-card__title"><?php esc_html_e( 'Guest List Overview', 'sandiegoweddingdirectory' ); ?></h2>
        <a href="<?php echo esc_url( home_url( '/couple-dashboard/guest-management/' ) ); ?>" class="cd-card__link">
            <?php esc_html_e( 'Open manager', 'sandiegoweddingdirectory' ); ?> &rarr;
        </a>
    </div>

    <div class="cd-section__soon">
        <?php esc_html_e( 'Guest management is coming soon. You\'ll be able to build your list, track RSVPs, and plan meals here.', 'sandiegoweddingdirectory' ); ?>
    </div>
</section>
