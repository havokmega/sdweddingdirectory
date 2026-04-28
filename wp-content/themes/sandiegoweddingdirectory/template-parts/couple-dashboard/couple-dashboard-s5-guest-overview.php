<?php
/**
 * Couple Dashboard — s5 Guest List Overview
 *
 * Four event cards (Wedding, Rehearsal Dinner, Shower, Dance Party) showing
 * Attended / Invited / Declined counts pulled from sdwd_guests user meta.
 *
 * $args:
 *   - guests (array of [ name, event, status ])
 */

$guests = $args['guests'] ?? [];
if ( ! is_array( $guests ) ) { $guests = []; }

$events = [
    'wedding'   => [ 'label' => __( 'Wedding',         'sandiegoweddingdirectory' ), 'icon' => 'icon-heart-ring' ],
    'rehearsal' => [ 'label' => __( 'Rehearsal Dinner','sandiegoweddingdirectory' ), 'icon' => 'icon-love-gift' ],
    'shower'    => [ 'label' => __( 'Shower',          'sandiegoweddingdirectory' ), 'icon' => 'icon-ballon-heart' ],
    'dance'     => [ 'label' => __( 'Dance Party',     'sandiegoweddingdirectory' ), 'icon' => 'icon-music' ],
];

$counts = [];
foreach ( array_keys( $events ) as $slug ) {
    $counts[ $slug ] = [ 'attended' => 0, 'invited' => 0, 'declined' => 0 ];
}
foreach ( $guests as $g ) {
    $event  = $g['event']  ?? '';
    $status = $g['status'] ?? '';
    if ( isset( $counts[ $event ] ) && isset( $counts[ $event ][ $status ] ) ) {
        $counts[ $event ][ $status ]++;
    }
}
?>

<section class="cd-card cd-guest-overview">
    <div class="cd-card__head">
        <h2 class="cd-card__title"><?php esc_html_e( 'Guest List Overview', 'sandiegoweddingdirectory' ); ?></h2>
        <a href="<?php echo esc_url( home_url( '/couple-dashboard/guest-management/' ) ); ?>" class="cd-card__link">
            <?php esc_html_e( 'Guest List', 'sandiegoweddingdirectory' ); ?> &rsaquo;
        </a>
    </div>

    <div class="cd-guest-overview__grid">
        <?php foreach ( $events as $slug => $meta ) :
            $c = $counts[ $slug ];
        ?>
            <a href="<?php echo esc_url( home_url( '/couple-dashboard/guest-management/?event=' . $slug ) ); ?>" class="cd-event-card">
                <span class="cd-event-card__icon <?php echo esc_attr( $meta['icon'] ); ?>" aria-hidden="true"></span>
                <div class="cd-event-card__body">
                    <h3 class="cd-event-card__title"><?php echo esc_html( $meta['label'] ); ?></h3>
                    <p class="cd-event-card__counts">
                        <span class="cd-event-card__count"><strong><?php echo (int) $c['attended']; ?></strong> <?php esc_html_e( 'Attended', 'sandiegoweddingdirectory' ); ?></span>
                        <span class="cd-event-card__count"><strong><?php echo (int) $c['invited']; ?></strong> <?php esc_html_e( 'Invited', 'sandiegoweddingdirectory' ); ?></span>
                        <span class="cd-event-card__count"><strong><?php echo (int) $c['declined']; ?></strong> <?php esc_html_e( 'Declined', 'sandiegoweddingdirectory' ); ?></span>
                    </p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>
