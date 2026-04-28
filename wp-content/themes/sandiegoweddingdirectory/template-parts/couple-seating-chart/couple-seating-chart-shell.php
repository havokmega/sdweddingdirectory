<?php
/**
 * Couple Seating Chart — Shell
 *
 * Spec s18: top controls + table list left + draggable seating canvas right.
 * MVP: structural shell with controls + table list. Drag/drop interactions
 * land in a follow-up pass (vendored interact.js or native pointer events).
 *
 * $args:
 *   - tables (array of [ id, name, seats, shape, guests ])
 *   - guests (array of [ name, event, status ])
 */

$tables = $args['tables'] ?? [];
$guests = $args['guests'] ?? [];
if ( ! is_array( $tables ) ) { $tables = []; }
if ( ! is_array( $guests ) ) { $guests = []; }

$wedding_guests = array_values( array_filter( $guests, fn( $g ) => ( $g['event'] ?? '' ) === 'wedding' ) );

// Build a list of seated guest names for the unassigned pool.
$seated = [];
foreach ( $tables as $t ) {
    foreach ( ( $t['guests'] ?? [] ) as $name ) { $seated[ $name ] = true; }
}
$unassigned = array_values( array_filter( $wedding_guests, fn( $g ) => empty( $seated[ $g['name'] ?? '' ] ) ) );
?>

<div class="cd-card cd-seating">
    <div class="cd-card__head">
        <h1 class="cd-card__title"><?php esc_html_e( 'Seating Chart', 'sandiegoweddingdirectory' ); ?></h1>
        <div class="cd-seating__head-actions">
            <button type="button" class="btn btn--ghost" data-cd-seating-add-table>+ <?php esc_html_e( 'Add Table', 'sandiegoweddingdirectory' ); ?></button>
            <button type="button" class="btn btn--ghost" data-cd-seating-add-guest>+ <?php esc_html_e( 'Add Guest', 'sandiegoweddingdirectory' ); ?></button>
            <button type="button" class="btn btn--ghost" data-cd-seating-auto><?php esc_html_e( 'Auto Assign', 'sandiegoweddingdirectory' ); ?></button>
            <button type="button" class="btn btn--primary" data-cd-seating-save><?php esc_html_e( 'Save Layout', 'sandiegoweddingdirectory' ); ?></button>
        </div>
    </div>

    <div class="cd-seating__layout">
        <aside class="cd-seating__sidebar">
            <h2 class="cd-seating__sidebar-title"><?php esc_html_e( 'Tables', 'sandiegoweddingdirectory' ); ?></h2>
            <?php if ( empty( $tables ) ) : ?>
                <p class="cd-section__soon"><?php esc_html_e( 'No tables yet. Add your first table to start.', 'sandiegoweddingdirectory' ); ?></p>
            <?php else : ?>
                <ul class="cd-seating__tables">
                    <?php foreach ( $tables as $t ) :
                        $name  = $t['name']  ?? __( 'Table', 'sandiegoweddingdirectory' );
                        $seats = (int) ( $t['seats'] ?? 8 );
                        $count = count( $t['guests'] ?? [] );
                    ?>
                        <li class="cd-seating__table">
                            <span class="cd-seating__table-name"><?php echo esc_html( $name ); ?></span>
                            <span class="cd-seating__table-count"><?php echo (int) $count; ?> / <?php echo (int) $seats; ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <h2 class="cd-seating__sidebar-title"><?php esc_html_e( 'Unassigned guests', 'sandiegoweddingdirectory' ); ?></h2>
            <ul class="cd-seating__pool">
                <?php foreach ( $unassigned as $g ) : ?>
                    <li class="cd-seating__chip" draggable="true"><?php echo esc_html( $g['name'] ?? '' ); ?></li>
                <?php endforeach; ?>
                <?php if ( empty( $unassigned ) ) : ?>
                    <li class="cd-seating__chip cd-seating__chip--empty"><?php esc_html_e( 'All guests assigned!', 'sandiegoweddingdirectory' ); ?></li>
                <?php endif; ?>
            </ul>
        </aside>

        <div class="cd-seating__canvas" data-cd-seating-canvas>
            <?php if ( empty( $tables ) ) : ?>
                <p class="cd-seating__canvas-empty"><?php esc_html_e( 'Tables you add will appear here. Drag guests from the left to assign seats.', 'sandiegoweddingdirectory' ); ?></p>
            <?php else :
                foreach ( $tables as $t ) :
                    $name  = $t['name']  ?? '';
                    $seats = (int) ( $t['seats'] ?? 8 );
                    $shape = $t['shape'] ?? 'round';
            ?>
                <div class="cd-seating__table-vis cd-seating__table-vis--<?php echo esc_attr( $shape ); ?>">
                    <span class="cd-seating__table-vis-name"><?php echo esc_html( $name ); ?></span>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</div>
