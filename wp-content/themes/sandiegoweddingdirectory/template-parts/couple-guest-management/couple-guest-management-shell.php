<?php
/**
 * Couple Guest Management — Shell
 *
 * Spec s15: overview state (event tabs + multi-event table) and per-event state
 * (event summary card + guest table with toolbar + inline fields).
 *
 * $args:
 *   - active_event (string, 'overview' or event slug)
 *   - guests (array of [ name, event, status, email, phone, meal ])
 */

$active_event = $args['active_event'] ?? 'overview';
$guests       = $args['guests'] ?? [];
if ( ! is_array( $guests ) ) { $guests = []; }

$event_meta = [
    'wedding'   => __( 'Wedding',          'sandiegoweddingdirectory' ),
    'rehearsal' => __( 'Rehearsal Dinner', 'sandiegoweddingdirectory' ),
    'shower'    => __( 'Shower',           'sandiegoweddingdirectory' ),
    'dance'     => __( 'Dance Party',      'sandiegoweddingdirectory' ),
];

$base_url = home_url( '/couple-dashboard/guest-management/' );

// Filter guests for active event view.
$filtered = $guests;
if ( $active_event !== 'overview' && isset( $event_meta[ $active_event ] ) ) {
    $filtered = array_values( array_filter( $guests, fn( $g ) => ( $g['event'] ?? '' ) === $active_event ) );
}

$counts = [];
foreach ( $event_meta as $slug => $_ ) {
    $event_guests = array_filter( $guests, fn( $g ) => ( $g['event'] ?? '' ) === $slug );
    $counts[ $slug ] = [
        'total'    => count( $event_guests ),
        'attended' => count( array_filter( $event_guests, fn( $g ) => ( $g['status'] ?? '' ) === 'attended' ) ),
        'invited'  => count( array_filter( $event_guests, fn( $g ) => ( $g['status'] ?? '' ) === 'invited' ) ),
        'declined' => count( array_filter( $event_guests, fn( $g ) => ( $g['status'] ?? '' ) === 'declined' ) ),
    ];
}
?>

<div class="cd-card cd-guest-mgr">
    <div class="cd-card__head">
        <h1 class="cd-card__title"><?php esc_html_e( 'Guest Management', 'sandiegoweddingdirectory' ); ?></h1>
        <button type="button" class="btn btn--primary" data-cd-add-guest>+ <?php esc_html_e( 'Add Guest', 'sandiegoweddingdirectory' ); ?></button>
    </div>

    <nav class="cd-tabs cd-tabs--horizontal">
        <a href="<?php echo esc_url( $base_url ); ?>" class="cd-tabs__tab<?php echo $active_event === 'overview' ? ' cd-tabs__tab--active' : ''; ?>"><?php esc_html_e( 'Overview', 'sandiegoweddingdirectory' ); ?></a>
        <?php foreach ( $event_meta as $slug => $label ) :
            $url       = add_query_arg( 'event', $slug, $base_url );
            $is_active = ( $slug === $active_event );
        ?>
            <a href="<?php echo esc_url( $url ); ?>" class="cd-tabs__tab<?php echo $is_active ? ' cd-tabs__tab--active' : ''; ?>">
                <?php echo esc_html( $label ); ?>
                <span class="cd-tabs__count"><?php echo (int) $counts[ $slug ]['total']; ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <?php if ( $active_event !== 'overview' && isset( $event_meta[ $active_event ] ) ) :
        $c = $counts[ $active_event ];
    ?>
        <div class="cd-event-summary">
            <h2 class="cd-event-summary__title"><?php echo esc_html( $event_meta[ $active_event ] ); ?></h2>
            <div class="cd-event-summary__stats">
                <div><strong><?php echo (int) $c['total']; ?></strong> <?php esc_html_e( 'Total', 'sandiegoweddingdirectory' ); ?></div>
                <div><strong><?php echo (int) $c['attended']; ?></strong> <?php esc_html_e( 'Attending', 'sandiegoweddingdirectory' ); ?></div>
                <div><strong><?php echo (int) $c['invited']; ?></strong> <?php esc_html_e( 'Pending', 'sandiegoweddingdirectory' ); ?></div>
                <div><strong><?php echo (int) $c['declined']; ?></strong> <?php esc_html_e( 'Declined', 'sandiegoweddingdirectory' ); ?></div>
            </div>
        </div>
    <?php endif; ?>

    <div class="cd-guest-toolbar">
        <input type="search" class="cd-guest-toolbar__search" placeholder="<?php esc_attr_e( 'Search guests…', 'sandiegoweddingdirectory' ); ?>">
    </div>

    <div class="cd-guest-table-wrap">
        <table class="cd-guest-table">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Name', 'sandiegoweddingdirectory' ); ?></th>
                    <?php if ( $active_event === 'overview' ) : ?>
                        <th><?php esc_html_e( 'Event', 'sandiegoweddingdirectory' ); ?></th>
                    <?php endif; ?>
                    <th><?php esc_html_e( 'Status', 'sandiegoweddingdirectory' ); ?></th>
                    <th class="cd-guest-table__actions"></th>
                </tr>
            </thead>
            <tbody>
                <?php if ( empty( $filtered ) ) : ?>
                    <tr><td colspan="4" class="cd-guest-table__empty"><?php esc_html_e( 'No guests yet.', 'sandiegoweddingdirectory' ); ?></td></tr>
                <?php else :
                    foreach ( $filtered as $g ) :
                        $name  = $g['name']   ?? '';
                        $ev    = $g['event']  ?? '';
                        $status= $g['status'] ?? '';
                        $ev_label = $event_meta[ $ev ] ?? ucfirst( $ev );
                ?>
                    <tr class="cd-guest-row" data-cd-guest-status="<?php echo esc_attr( $status ); ?>">
                        <td><?php echo esc_html( $name ); ?></td>
                        <?php if ( $active_event === 'overview' ) : ?>
                            <td><?php echo esc_html( $ev_label ); ?></td>
                        <?php endif; ?>
                        <td><span class="cd-status-badge cd-status-badge--<?php echo esc_attr( $status ?: 'pending' ); ?>"><?php echo esc_html( ucfirst( $status ) ?: __( 'Pending', 'sandiegoweddingdirectory' ) ); ?></span></td>
                        <td class="cd-guest-table__actions">
                            <button type="button" class="cd-guest-row__edit" aria-label="<?php esc_attr_e( 'Edit', 'sandiegoweddingdirectory' ); ?>">&#9998;</button>
                            <button type="button" class="cd-guest-row__delete" aria-label="<?php esc_attr_e( 'Delete', 'sandiegoweddingdirectory' ); ?>">&times;</button>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
