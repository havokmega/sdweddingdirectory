<?php
/**
 * Couple Budget — Shell
 *
 * Spec s13: top summary + category sidebar + table on right.
 *
 * $args:
 *   - budget_total (float)
 *   - budget_items (array of [ category, amount, vendor, paid ])
 */

$total = (float) ( $args['budget_total'] ?? 0 );
$items = $args['budget_items'] ?? [];
if ( ! is_array( $items ) ) { $items = []; }

$spent = 0.0;
foreach ( $items as $i ) { $spent += (float) ( $i['amount'] ?? 0 ); }
$remaining = $total - $spent;
$is_over   = $spent > $total && $total > 0;
$percent   = $total > 0 ? min( 100, (int) round( ( $spent / $total ) * 100 ) ) : 0;

// Category aggregation for the left sidebar.
$by_cat = [];
foreach ( $items as $row ) {
    $cat = $row['category'] ?? __( 'Uncategorized', 'sandiegoweddingdirectory' );
    if ( ! isset( $by_cat[ $cat ] ) ) { $by_cat[ $cat ] = 0; }
    $by_cat[ $cat ] += (float) ( $row['amount'] ?? 0 );
}
?>

<div class="cd-budget">
    <div class="cd-card cd-budget__summary">
        <div class="cd-card__head">
            <h1 class="cd-card__title"><?php esc_html_e( 'Budget Calculator', 'sandiegoweddingdirectory' ); ?></h1>
            <button type="button" class="btn btn--primary" data-cd-edit-budget>
                <?php esc_html_e( 'Edit total budget', 'sandiegoweddingdirectory' ); ?>
            </button>
        </div>
        <div class="cd-budget__stats">
            <div class="cd-budget__stat">
                <span class="cd-budget__stat-label"><?php esc_html_e( 'Total Budget', 'sandiegoweddingdirectory' ); ?></span>
                <span class="cd-budget__stat-num">$<?php echo esc_html( number_format( $total, 0 ) ); ?></span>
            </div>
            <div class="cd-budget__stat">
                <span class="cd-budget__stat-label"><?php esc_html_e( 'Spent', 'sandiegoweddingdirectory' ); ?></span>
                <span class="cd-budget__stat-num">$<?php echo esc_html( number_format( $spent, 0 ) ); ?></span>
            </div>
            <div class="cd-budget__stat">
                <span class="cd-budget__stat-label"><?php echo $is_over ? esc_html__( 'Over Budget', 'sandiegoweddingdirectory' ) : esc_html__( 'Remaining', 'sandiegoweddingdirectory' ); ?></span>
                <span class="cd-budget__stat-num<?php echo $is_over ? ' cd-budget__stat-num--over' : ''; ?>">
                    $<?php echo esc_html( number_format( abs( $remaining ), 0 ) ); ?>
                </span>
            </div>
        </div>
        <div class="cd-budget__bar">
            <div class="cd-budget__fill<?php echo $is_over ? ' cd-budget__fill--over' : ''; ?>" style="width: <?php echo (int) $percent; ?>%;"></div>
        </div>
    </div>

    <div class="cd-budget__layout">
        <aside class="cd-card cd-budget__sidebar">
            <h2 class="cd-budget__sidebar-title"><?php esc_html_e( 'By Category', 'sandiegoweddingdirectory' ); ?></h2>
            <ul class="cd-budget__cats">
                <?php foreach ( $by_cat as $cat => $amt ) : ?>
                    <li class="cd-budget__cat">
                        <span class="cd-budget__cat-name"><?php echo esc_html( $cat ); ?></span>
                        <span class="cd-budget__cat-amt">$<?php echo esc_html( number_format( $amt, 0 ) ); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>

        <div class="cd-card cd-budget__table-wrap">
            <div class="cd-card__head">
                <h2 class="cd-card__title"><?php esc_html_e( 'Items', 'sandiegoweddingdirectory' ); ?></h2>
                <button type="button" class="btn btn--ghost" data-cd-add-budget-item>
                    + <?php esc_html_e( 'Add item', 'sandiegoweddingdirectory' ); ?>
                </button>
            </div>
            <table class="cd-budget__table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Category', 'sandiegoweddingdirectory' ); ?></th>
                        <th><?php esc_html_e( 'Vendor', 'sandiegoweddingdirectory' ); ?></th>
                        <th class="cd-budget__col-amt"><?php esc_html_e( 'Amount', 'sandiegoweddingdirectory' ); ?></th>
                        <th class="cd-budget__col-actions"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( empty( $items ) ) : ?>
                        <tr><td colspan="4" class="cd-budget__empty"><?php esc_html_e( 'No items yet. Add your first.', 'sandiegoweddingdirectory' ); ?></td></tr>
                    <?php else :
                        foreach ( $items as $i => $row ) :
                            $cat    = $row['category'] ?? '';
                            $vendor = $row['vendor']   ?? '';
                            $amount = (float) ( $row['amount'] ?? 0 );
                    ?>
                        <tr data-cd-budget-row="<?php echo (int) $i; ?>">
                            <td><?php echo esc_html( $cat ); ?></td>
                            <td><?php echo esc_html( $vendor ?: '—' ); ?></td>
                            <td class="cd-budget__col-amt">$<?php echo esc_html( number_format( $amount, 0 ) ); ?></td>
                            <td class="cd-budget__col-actions">
                                <button type="button" class="cd-budget__edit" aria-label="<?php esc_attr_e( 'Edit', 'sandiegoweddingdirectory' ); ?>">&#9998;</button>
                                <button type="button" class="cd-budget__delete" aria-label="<?php esc_attr_e( 'Delete', 'sandiegoweddingdirectory' ); ?>">&times;</button>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
