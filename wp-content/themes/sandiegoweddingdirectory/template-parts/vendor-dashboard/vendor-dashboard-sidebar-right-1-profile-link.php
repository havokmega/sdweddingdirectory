<?php
/**
 * Vendor Dashboard — Right sidebar 1: Profile link
 *
 * $args:
 *   - profile_url (string)
 *   - profile_complete (int 0-100)
 */

$profile_url      = $args['profile_url']      ?? '';
$profile_complete = (int) ( $args['profile_complete'] ?? 0 );
?>

<aside class="vd-widget">
    <h3 class="vd-widget__title">
        <span class="icon-my-profile" aria-hidden="true"></span>
        <?php esc_html_e( 'Your Public Profile', 'sandiegoweddingdirectory' ); ?>
    </h3>

    <p class="vd-widget__text">
        <?php
        if ( $profile_complete >= 90 ) {
            esc_html_e( 'Your profile looks great. Couples can find you easily.', 'sandiegoweddingdirectory' );
        } elseif ( $profile_complete >= 50 ) {
            esc_html_e( 'You are on your way. Fill in the rest of your profile to rank higher.', 'sandiegoweddingdirectory' );
        } else {
            esc_html_e( 'Complete your profile so couples can discover your business.', 'sandiegoweddingdirectory' );
        }
        ?>
    </p>

    <a href="<?php echo esc_url( home_url( '/vendor-dashboard/profile/' ) ); ?>" class="vd-widget__link">
        <?php esc_html_e( 'Edit profile', 'sandiegoweddingdirectory' ); ?> &rarr;
    </a>

    <?php if ( $profile_url ) : ?>
        <a href="<?php echo esc_url( $profile_url ); ?>" class="vd-widget__link" target="_blank" rel="noopener">
            <?php esc_html_e( 'View public page', 'sandiegoweddingdirectory' ); ?> &rarr;
        </a>
    <?php endif; ?>
</aside>
