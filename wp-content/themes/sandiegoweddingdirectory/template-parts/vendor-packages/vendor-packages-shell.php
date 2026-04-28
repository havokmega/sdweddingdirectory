<?php
/**
 * Vendor Pricing Packages — Shell
 *
 * Spec s2: vendor creates packages shown on profile.
 *
 * $args:
 *   - packages (array of [ name, price, billing, features, cta ])
 *   - post_id  (int)
 */

$packages = $args['packages'] ?? [];
if ( ! is_array( $packages ) ) { $packages = []; }
?>

<div class="cd-card vd-packages">
    <div class="cd-card__head">
        <h1 class="cd-card__title"><?php esc_html_e( 'Pricing Packages', 'sandiegoweddingdirectory' ); ?></h1>
        <button type="button" class="btn btn--primary" data-vd-add-package>+ <?php esc_html_e( 'Add Package', 'sandiegoweddingdirectory' ); ?></button>
    </div>

    <p class="vd-packages__hint">
        <?php esc_html_e( 'These packages appear on your public profile and inside couple dashboards. Couples reference them when requesting a quote.', 'sandiegoweddingdirectory' ); ?>
    </p>

    <?php if ( empty( $packages ) ) : ?>
        <div class="cd-card__empty">
            <span class="cd-card__empty-title"><?php esc_html_e( 'No packages yet', 'sandiegoweddingdirectory' ); ?></span>
            <?php esc_html_e( 'Add your first package — couples can compare options and contact you faster.', 'sandiegoweddingdirectory' ); ?>
        </div>
    <?php else : ?>
        <div class="vd-packages__grid">
            <?php foreach ( $packages as $i => $p ) :
                $features = $p['features'] ?? [];
                if ( ! is_array( $features ) ) { $features = []; }
                $billing  = $p['billing']  ?? 'one-time';
                $billing_label = [
                    'one-time'    => __( 'one-time',    'sandiegoweddingdirectory' ),
                    'hourly'      => __( '/ hour',      'sandiegoweddingdirectory' ),
                    'starting-at' => __( 'starting at', 'sandiegoweddingdirectory' ),
                ][ $billing ] ?? '';
            ?>
                <article class="c-pricing-card" data-vd-package-row="<?php echo (int) $i; ?>">
                    <header class="c-pricing-card__head">
                        <h2 class="c-pricing-card__name"><?php echo esc_html( $p['name'] ?? '' ); ?></h2>
                        <p class="c-pricing-card__price">
                            <?php if ( $billing === 'starting-at' ) : ?>
                                <span class="c-pricing-card__from"><?php esc_html_e( 'from', 'sandiegoweddingdirectory' ); ?></span>
                            <?php endif; ?>
                            <span class="c-pricing-card__amount">$<?php echo esc_html( number_format( (float) ( $p['price'] ?? 0 ), 0 ) ); ?></span>
                            <span class="c-pricing-card__billing"><?php echo esc_html( $billing_label ); ?></span>
                        </p>
                    </header>
                    <ul class="c-pricing-card__features">
                        <?php foreach ( $features as $f ) : ?>
                            <li class="c-pricing-card__feature"><?php echo esc_html( $f ); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <footer class="c-pricing-card__foot">
                        <span class="c-pricing-card__cta-preview"><?php echo esc_html( $p['cta'] ?? __( 'Contact', 'sandiegoweddingdirectory' ) ); ?></span>
                        <div class="c-pricing-card__actions">
                            <button type="button" class="vd-package__edit" aria-label="<?php esc_attr_e( 'Edit', 'sandiegoweddingdirectory' ); ?>">&#9998;</button>
                            <button type="button" class="vd-package__delete" aria-label="<?php esc_attr_e( 'Delete', 'sandiegoweddingdirectory' ); ?>">&times;</button>
                        </div>
                    </footer>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
