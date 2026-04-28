<?php
/**
 * Couple Dashboard — s2 Hero
 *
 * Two-column hero per spec:
 *   left:  tall photo + live countdown (D/H/M/S) + share
 *   right: couple avatars + names + date + 3 stat blocks
 *
 * $args:
 *   - first_name, partner_first_name (string)
 *   - wedding_date (ISO yyyy-mm-dd)
 *   - couple_photo_id, partner_photo_id, hero_photo_id (int attachment IDs)
 *   - vendors_hired, vendors_total, tasks_completed, tasks_total,
 *     guests_attending, guests_total (int)
 */

$first_name         = $args['first_name'] ?? '';
$partner_first_name = $args['partner_first_name'] ?? '';
$wedding_date       = $args['wedding_date'] ?? '';
$couple_photo_id    = (int) ( $args['couple_photo_id'] ?? 0 );
$partner_photo_id   = (int) ( $args['partner_photo_id'] ?? 0 );
$hero_photo_id      = (int) ( $args['hero_photo_id'] ?? 0 );

$vendors_hired    = (int) ( $args['vendors_hired'] ?? 0 );
$vendors_total    = (int) ( $args['vendors_total'] ?? 0 );
$tasks_completed  = (int) ( $args['tasks_completed'] ?? 0 );
$tasks_total      = (int) ( $args['tasks_total'] ?? 0 );
$guests_attending = (int) ( $args['guests_attending'] ?? 0 );
$guests_total     = (int) ( $args['guests_total'] ?? 0 );

$placeholder_dir = get_template_directory_uri() . '/assets/images/placeholders/couple-dashboard';
$hero_photo_url    = $hero_photo_id    ? wp_get_attachment_image_url( $hero_photo_id,    'large' ) : $placeholder_dir . '/couple-dashboard-banner.jpg';
$couple_photo_url  = $couple_photo_id  ? wp_get_attachment_image_url( $couple_photo_id,  'thumbnail' ) : $placeholder_dir . '/groom.jpg';
$partner_photo_url = $partner_photo_id ? wp_get_attachment_image_url( $partner_photo_id, 'thumbnail' ) : $placeholder_dir . '/bride.jpg';

$names_display = trim( $first_name );
if ( $partner_first_name ) {
    $names_display = trim( $first_name . ' & ' . $partner_first_name );
}

$wedding_iso = '';
$wedding_human = '';
if ( $wedding_date ) {
    $ts = strtotime( $wedding_date . ' 00:00:00' );
    if ( $ts ) {
        $wedding_iso   = gmdate( 'c', $ts );
        $wedding_human = date_i18n( 'F j, Y', $ts );
    }
}
?>

<section class="cd-hero">
    <div class="cd-hero__photo-col" style="background-image: url('<?php echo esc_url( $hero_photo_url ); ?>');">
        <?php if ( $wedding_iso ) : ?>
            <div class="cd-hero__countdown" data-cd-countdown="<?php echo esc_attr( $wedding_iso ); ?>">
                <div class="cd-hero__countdown-unit">
                    <span class="cd-hero__countdown-num" data-unit="days">--</span>
                    <span class="cd-hero__countdown-label"><?php esc_html_e( 'Days', 'sandiegoweddingdirectory' ); ?></span>
                </div>
                <div class="cd-hero__countdown-unit">
                    <span class="cd-hero__countdown-num" data-unit="hours">--</span>
                    <span class="cd-hero__countdown-label"><?php esc_html_e( 'Hours', 'sandiegoweddingdirectory' ); ?></span>
                </div>
                <div class="cd-hero__countdown-unit">
                    <span class="cd-hero__countdown-num" data-unit="minutes">--</span>
                    <span class="cd-hero__countdown-label"><?php esc_html_e( 'Minutes', 'sandiegoweddingdirectory' ); ?></span>
                </div>
                <div class="cd-hero__countdown-unit">
                    <span class="cd-hero__countdown-num" data-unit="seconds">--</span>
                    <span class="cd-hero__countdown-label"><?php esc_html_e( 'Seconds', 'sandiegoweddingdirectory' ); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <button type="button" class="cd-hero__share" aria-label="<?php esc_attr_e( 'Share', 'sandiegoweddingdirectory' ); ?>">
            <span class="icon-share" aria-hidden="true"></span>
            <span><?php esc_html_e( 'Share', 'sandiegoweddingdirectory' ); ?></span>
        </button>
    </div>

    <div class="cd-hero__info">
        <a href="<?php echo esc_url( home_url( '/couple-dashboard/profile/' ) ); ?>" class="cd-hero__edit">
            <span class="icon-edit" aria-hidden="true"></span>
            <?php esc_html_e( 'Edit', 'sandiegoweddingdirectory' ); ?>
        </a>

        <div class="cd-hero__identity">
            <div class="cd-hero__avatars">
                <img src="<?php echo esc_url( $couple_photo_url ); ?>"  alt="" class="cd-hero__avatar" loading="lazy">
                <img src="<?php echo esc_url( $partner_photo_url ); ?>" alt="" class="cd-hero__avatar" loading="lazy">
            </div>
            <div class="cd-hero__id-text">
                <h1 class="cd-hero__names"><?php echo esc_html( $names_display ?: __( 'Your Dashboard', 'sandiegoweddingdirectory' ) ); ?></h1>
                <?php if ( $wedding_human ) : ?>
                    <p class="cd-hero__date">
                        <span class="icon-calendar" aria-hidden="true"></span>
                        <?php echo esc_html( $wedding_human ); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <div class="cd-hero__status">
            <span class="cd-hero__status-label"><?php esc_html_e( 'Status', 'sandiegoweddingdirectory' ); ?></span>
            <span class="cd-hero__status-text"><?php esc_html_e( "Just said yes? Let's get started!", 'sandiegoweddingdirectory' ); ?></span>
        </div>

        <div class="cd-hero__stats">
            <div class="cd-hero__stat">
                <span class="cd-hero__stat-num"><?php echo (int) $vendors_hired; ?></span>
                <span class="cd-hero__stat-suffix"><?php
                    /* translators: %d: total vendor categories */
                    printf( esc_html__( 'Out of %d', 'sandiegoweddingdirectory' ), (int) $vendors_total );
                ?></span>
                <span class="cd-hero__stat-label"><?php esc_html_e( 'Services Hired', 'sandiegoweddingdirectory' ); ?></span>
            </div>
            <div class="cd-hero__stat">
                <span class="cd-hero__stat-num"><?php echo (int) $tasks_completed; ?></span>
                <span class="cd-hero__stat-suffix"><?php
                    printf( esc_html__( 'Out of %d', 'sandiegoweddingdirectory' ), (int) $tasks_total );
                ?></span>
                <span class="cd-hero__stat-label"><?php esc_html_e( 'Tasks completed', 'sandiegoweddingdirectory' ); ?></span>
            </div>
            <div class="cd-hero__stat">
                <span class="cd-hero__stat-num"><?php echo (int) $guests_attending; ?></span>
                <span class="cd-hero__stat-suffix"><?php
                    printf( esc_html__( 'Out of %d', 'sandiegoweddingdirectory' ), (int) $guests_total );
                ?></span>
                <span class="cd-hero__stat-label"><?php esc_html_e( 'Guests Attending', 'sandiegoweddingdirectory' ); ?></span>
            </div>
        </div>
    </div>
</section>
