<?php
/**
 * Couple Dashboard — Right sidebar 3: Budget
 *
 * Shows total budget vs. allocated spend, from sdwd_budget_total and
 * sdwd_budget_items user meta.
 *
 * $args:
 *   - budget_total (float)
 *   - budget_items (array, each item has 'amount')
 */

$total = (float) ( $args['budget_total'] ?? 0 );
$items = $args['budget_items'] ?? [];
if ( ! is_array( $items ) ) { $items = []; }

$spent = 0;
foreach ( $items as $item ) {
    if ( isset( $item['amount'] ) ) {
        $spent += (float) $item['amount'];
    }
}

$percent   = ( $total > 0 ) ? min( 150, (int) round( ( $spent / $total ) * 100 ) ) : 0;
$is_over   = ( $total > 0 && $spent > $total );
$remaining = $total - $spent;
?>

<aside class="cd-widget cd-widget--budget">
    <h3 class="cd-widget__title">
        <span class="icon-calculator" aria-hidden="true"></span>
        <?php esc_html_e( 'Budget', 'sandiegoweddingdirectory' ); ?>
    </h3>

    <?php if ( $total <= 0 ) : ?>
        <div class="cd-widget__soon">
            <?php esc_html_e( 'Set your wedding budget to start tracking.', 'sandiegoweddingdirectory' ); ?>
        </div>
    <?php else : ?>
        <div class="cd-budget__totals">
            <span class="cd-budget__label"><?php esc_html_e( 'Spent', 'sandiegoweddingdirectory' ); ?></span>
            <span class="cd-budget__amount">$<?php echo esc_html( number_format( $spent, 0 ) ); ?></span>
        </div>

        <div class="cd-budget__bar">
            <div class="cd-budget__fill <?php echo $is_over ? 'cd-budget__fill--over' : ''; ?>" style="width: <?php echo (int) min( 100, $percent ); ?>%;"></div>
        </div>

        <div class="cd-budget__totals">
            <span class="cd-budget__label">
                <?php echo $is_over ? esc_html__( 'Over budget', 'sandiegoweddingdirectory' ) : esc_html__( 'Remaining', 'sandiegoweddingdirectory' ); ?>
            </span>
            <span class="cd-budget__amount">$<?php echo esc_html( number_format( abs( $remaining ), 0 ) ); ?></span>
        </div>
    <?php endif; ?>

    <a href="<?php echo esc_url( home_url( '/couple-dashboard/budget/' ) ); ?>" class="cd-widget__link">
        <?php esc_html_e( 'Manage budget', 'sandiegoweddingdirectory' ); ?> &rarr;
    </a>
</aside>
