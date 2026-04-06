<?php
/**
 * Component: Page Header
 *
 * Archive/taxonomy page header banner with title and optional description.
 *
 * @param array $args {
 *     @type string $title       Required. Page heading text.
 *     @type string $desc        Optional. Description text below the heading.
 *     @type array  $breadcrumbs Optional. Array of ['label'=>'', 'url'=>''] items for breadcrumbs.
 * }
 */

$title       = $args['title'] ?? '';
$desc        = $args['desc'] ?? '';
$breadcrumbs = $args['breadcrumbs'] ?? [];

if ( ! $title ) {
    return;
}
?>
<div class="page-header">
    <div class="container">
        <?php if ( ! empty( $breadcrumbs ) ) : ?>
            <?php
            get_template_part( 'template-parts/components/breadcrumbs', null, [
                'items' => $breadcrumbs,
            ] );
            ?>
        <?php endif; ?>
        <h1 class="page-header__title"><?php echo esc_html( $title ); ?></h1>
        <?php if ( $desc ) : ?>
            <p class="page-header__desc"><?php echo wp_kses_post( $desc ); ?></p>
        <?php endif; ?>
    </div>
</div>
