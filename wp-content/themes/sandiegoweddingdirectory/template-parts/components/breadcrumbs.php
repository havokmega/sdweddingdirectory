<?php
/**
 * Component: Breadcrumbs
 *
 * @param array $args {
 *     @type array $items Required. Array of breadcrumb items.
 *           Each item: ['label' => '', 'url' => '']
 *           Last item should have no URL (current page).
 * }
 */

$items = $args['items'] ?? [];

if ( empty( $items ) ) {
    return;
}
?>
<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'sdweddingdirectory-v2' ); ?>">
    <?php foreach ( $items as $index => $item ) : ?>
        <?php $is_last = ( $index === count( $items ) - 1 ); ?>

        <?php if ( $index > 0 ) : ?>
            <span class="breadcrumb__separator" aria-hidden="true">/</span>
        <?php endif; ?>

        <?php if ( ! $is_last && ! empty( $item['url'] ) ) : ?>
            <a class="breadcrumb__link" href="<?php echo esc_url( $item['url'] ); ?>">
                <?php echo esc_html( $item['label'] ); ?>
            </a>
        <?php else : ?>
            <span class="breadcrumb__item" aria-current="page">
                <?php echo esc_html( $item['label'] ); ?>
            </span>
        <?php endif; ?>
    <?php endforeach; ?>
</nav>
