<?php
/**
 * Couple Dashboard — Hero
 *
 * Large photo header with greeting, names, countdown to wedding date, and a
 * service tracker showing checklist completion progress.
 *
 * $args:
 *   - first_name          (string)
 *   - partner_name        (string)
 *   - wedding_date        (string, ISO yyyy-mm-dd)
 *   - hero_photo          (string URL, optional)
 *   - checklist_total     (int)
 *   - checklist_completed (int)
 */

$first_name          = $args['first_name'] ?? '';
$partner_name        = $args['partner_name'] ?? '';
$wedding_date        = $args['wedding_date'] ?? '';
$hero_photo          = $args['hero_photo'] ?? '';
$checklist_total     = (int) ( $args['checklist_total'] ?? 0 );
$checklist_completed = (int) ( $args['checklist_completed'] ?? 0 );

// Fallback photo.
if ( empty( $hero_photo ) ) {
    $hero_photo = get_template_directory_uri() . '/assets/images/placeholders/couple-dashboard/couple-dashboard-banner.jpg';
}

// Countdown math.
$days_remaining = null;
if ( ! empty( $wedding_date ) ) {
    $today   = new DateTime( 'today' );
    $wedding = DateTime::createFromFormat( 'Y-m-d', $wedding_date );
    if ( $wedding && $wedding >= $today ) {
        $diff = $today->diff( $wedding );
        $days_remaining = (int) $diff->days;
    }
}

// Tracker math.
$percent = ( $checklist_total > 0 )
    ? (int) round( ( $checklist_completed / $checklist_total ) * 100 )
    : 0;

$names_display = trim( $first_name );
if ( $partner_name ) {
    $names_display = trim( $first_name . ' & ' . $partner_name );
}
?>

<section class="cd-hero">
    <div class="cd-hero__photo" style="background-image: url('<?php echo esc_url( $hero_photo ); ?>');"></div>
    <div class="cd-hero__overlay"></div>

    <div class="cd-hero__inner">
        <p class="cd-hero__greeting"><?php esc_html_e( 'Welcome back', 'sandiegoweddingdirectory' ); ?></p>
        <h1 class="cd-hero__names">
            <?php echo esc_html( $names_display ?: __( 'Your Dashboard', 'sandiegoweddingdirectory' ) ); ?>
        </h1>

        <?php if ( $days_remaining !== null ) : ?>
            <div class="cd-countdown" aria-label="<?php esc_attr_e( 'Days until your wedding', 'sandiegoweddingdirectory' ); ?>">
                <div class="cd-countdown__unit">
                    <span class="cd-countdown__num"><?php echo esc_html( $days_remaining ); ?></span>
                    <span class="cd-countdown__label"><?php esc_html_e( 'Days to go', 'sandiegoweddingdirectory' ); ?></span>
                </div>
                <?php if ( $wedding_date ) : ?>
                    <div class="cd-countdown__unit">
                        <span class="cd-countdown__num"><?php echo esc_html( date_i18n( 'M j', strtotime( $wedding_date ) ) ); ?></span>
                        <span class="cd-countdown__label"><?php echo esc_html( date_i18n( 'Y', strtotime( $wedding_date ) ) ); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        <?php else : ?>
            <div class="cd-countdown__empty">
                <?php esc_html_e( 'Set your wedding date below to start the countdown.', 'sandiegoweddingdirectory' ); ?>
            </div>
        <?php endif; ?>

        <div class="cd-tracker">
            <div class="cd-tracker__head">
                <span><?php esc_html_e( 'Planning progress', 'sandiegoweddingdirectory' ); ?></span>
                <span><?php echo esc_html( $checklist_completed . ' / ' . $checklist_total ); ?> <?php esc_html_e( 'tasks done', 'sandiegoweddingdirectory' ); ?></span>
            </div>
            <div class="cd-tracker__bar" role="progressbar" aria-valuenow="<?php echo esc_attr( $percent ); ?>" aria-valuemin="0" aria-valuemax="100">
                <div class="cd-tracker__fill" style="width: <?php echo (int) $percent; ?>%;"></div>
            </div>
        </div>
    </div>
</section>
