<?php
/**
 * Component: FAQ Section
 *
 * Reusable FAQ accordion section with heading and description.
 *
 * @param array $args {
 *     @type string $heading   Required. Section heading.
 *     @type string $desc      Optional. Section description.
 *     @type string $align     Optional. Heading alignment ('center'|'left'). Default 'center'.
 *     @type string $id_prefix Optional. Prefix for answer IDs. Default 'faq'.
 *     @type int    $open      Optional. 0-based index of default open item. Default 1.
 *     @type array  $items     Required. Array of ['question'=>'', 'answer'=>''].
 * }
 */

$heading   = $args['heading'] ?? '';
$desc      = $args['desc'] ?? '';
$align     = $args['align'] ?? 'center';
$id_prefix = $args['id_prefix'] ?? 'faq';
$open      = $args['open'] ?? 1;
$items     = $args['items'] ?? [];

if ( empty( $items ) || ! $heading ) {
    return;
}
?>
<section class="planning-faq section" aria-label="<?php echo esc_attr( $heading ); ?>">
    <div class="container">
        <div class="planning-faq__header">
            <p class="planning-faq__eyebrow"><?php echo esc_html( $heading ); ?></p>
            <?php if ( $desc ) : ?>
                <h2 class="planning-faq__heading"><?php echo wp_kses_post( $desc ); ?></h2>
            <?php endif; ?>
        </div>

        <div class="faq-accordion planning-faq__accordion" data-allow-multiple="false">
            <?php foreach ( $items as $index => $item ) :
                $answer_id = esc_attr( $id_prefix ) . '-answer-' . ( $index + 1 );
                $is_open   = ( $open === $index );
                ?>
                <div class="faq-accordion__item planning-faq__item">
                    <button
                        class="faq-accordion__question planning-faq__question"
                        type="button"
                        aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>"
                        aria-controls="<?php echo esc_attr( $answer_id ); ?>"
                    >
                        <span class="faq-accordion__question-text planning-faq__question-text"><?php echo esc_html( $item['question'] ); ?></span>
                        <span class="planning-faq__toggle" aria-hidden="true"></span>
                    </button>
                    <div
                        class="faq-accordion__answer planning-faq__answer"
                        id="<?php echo esc_attr( $answer_id ); ?>"
                        data-visible="<?php echo $is_open ? 'true' : 'false'; ?>"
                    >
                        <?php echo wp_kses_post( $item['answer'] ); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
