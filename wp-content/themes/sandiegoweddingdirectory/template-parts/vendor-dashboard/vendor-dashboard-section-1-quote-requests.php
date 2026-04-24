<?php
/**
 * Vendor Dashboard — Section 2: Recent Quote Requests
 *
 * $args:
 *   - quotes (array) latest quote request rows (stub supported)
 */

$quotes = isset( $args['quotes'] ) && is_array( $args['quotes'] ) ? $args['quotes'] : [];
?>

<section class="vd-card">
    <div class="vd-card__head">
        <h2 class="vd-card__title"><?php esc_html_e( 'Recent Quote Requests', 'sandiegoweddingdirectory' ); ?></h2>
        <a href="<?php echo esc_url( home_url( '/vendor-dashboard/quote-requests/' ) ); ?>" class="vd-card__link">
            <?php esc_html_e( 'View all', 'sandiegoweddingdirectory' ); ?> &rarr;
        </a>
    </div>

    <?php if ( empty( $quotes ) ) : ?>
        <div class="vd-card__empty">
            <span class="vd-card__empty-title"><?php esc_html_e( 'No quote requests yet', 'sandiegoweddingdirectory' ); ?></span>
            <?php esc_html_e( 'When couples request quotes from you, they will appear here.', 'sandiegoweddingdirectory' ); ?>
        </div>
    <?php else : ?>
        <ul class="vd-quotes">
            <?php foreach ( array_slice( $quotes, 0, 5 ) as $q ) : ?>
                <li class="vd-quote">
                    <span class="vd-quote__name"><?php echo esc_html( $q['name'] ?? '' ); ?></span>
                    <span class="vd-quote__date"><?php echo esc_html( $q['date'] ?? '' ); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
