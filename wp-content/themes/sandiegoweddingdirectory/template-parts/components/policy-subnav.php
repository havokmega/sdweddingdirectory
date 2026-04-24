<?php
/**
 * Component: Policy Subnav
 *
 * Horizontal tab nav for Privacy Policy, Terms of Use, CA Privacy, FAQ.
 *
 * @param array $args {
 *     @type string $active Slug of the active item.
 * }
 */

$active = $args['active'] ?? '';

$items = [
    [
        'slug'  => 'privacy-policy',
        'label' => __( 'Privacy Policy', 'sandiegoweddingdirectory' ),
        'url'   => home_url( '/privacy-policy/' ),
    ],
    [
        'slug'  => 'terms-of-use',
        'label' => __( 'Terms of Use', 'sandiegoweddingdirectory' ),
        'url'   => home_url( '/terms-of-use/' ),
    ],
    [
        'slug'  => 'ca-privacy',
        'label' => __( 'CA Privacy', 'sandiegoweddingdirectory' ),
        'url'   => home_url( '/ca-privacy/' ),
    ],
    [
        'slug'  => 'faqs',
        'label' => __( 'FAQ', 'sandiegoweddingdirectory' ),
        'url'   => home_url( '/faqs/' ),
    ],
];
?>
<nav class="policy-subnav" aria-label="<?php esc_attr_e( 'Policy navigation', 'sandiegoweddingdirectory' ); ?>">
    <ul class="policy-subnav__list">
        <?php foreach ( $items as $item ) : ?>
            <li class="policy-subnav__item">
                <?php if ( $item['slug'] === $active ) : ?>
                    <span class="policy-subnav__link policy-subnav__link--active"><?php echo esc_html( $item['label'] ); ?></span>
                <?php else : ?>
                    <a class="policy-subnav__link" href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
