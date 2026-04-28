<?php
/**
 * Vendor Dashboard — Hero
 *
 * Photo banner with company name and profile completion tracker.
 *
 * $args:
 *   - company_name      (string)
 *   - hero_photo        (string URL, optional)
 *   - profile_complete  (int 0-100)
 *   - profile_url       (string) public profile permalink
 */

$company_name     = $args['company_name']     ?? '';
$hero_photo       = $args['hero_photo']       ?? '';
$profile_complete = (int) ( $args['profile_complete'] ?? 0 );
$profile_url      = $args['profile_url']      ?? '';

if ( empty( $hero_photo ) ) {
    $hero_photo = get_template_directory_uri() . '/assets/images/placeholders/couple-dashboard/couple-dashboard-banner.jpg';
}
?>

<section class="vd-hero">
    <div class="vd-hero__photo" style="background-image: url('<?php echo esc_url( $hero_photo ); ?>');"></div>
    <div class="vd-hero__overlay"></div>

    <div class="vd-hero__inner">
        <p class="vd-hero__greeting"><?php esc_html_e( 'Welcome back', 'sandiegoweddingdirectory' ); ?></p>
        <h1 class="vd-hero__name">
            <?php echo esc_html( $company_name ?: __( 'Your Business', 'sandiegoweddingdirectory' ) ); ?>
        </h1>

        <div class="vd-tracker">
            <div class="vd-tracker__head">
                <span><?php esc_html_e( 'Profile completion', 'sandiegoweddingdirectory' ); ?></span>
                <span><?php echo esc_html( $profile_complete . '%' ); ?></span>
            </div>
            <div class="vd-tracker__bar" role="progressbar" aria-valuenow="<?php echo esc_attr( $profile_complete ); ?>" aria-valuemin="0" aria-valuemax="100">
                <div class="vd-tracker__fill" style="width: <?php echo (int) $profile_complete; ?>%;"></div>
            </div>
        </div>

        <?php if ( $profile_url ) : ?>
            <a href="<?php echo esc_url( $profile_url ); ?>" class="vd-hero__cta">
                <?php esc_html_e( 'View public profile', 'sandiegoweddingdirectory' ); ?> &rarr;
            </a>
        <?php endif; ?>
    </div>
</section>
