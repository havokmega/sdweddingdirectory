<?php
/**
 * Component: FAQ Accordion
 *
 * @param array $args {
 *     @type array $items          Required. Array of ['question'=>'', 'answer'=>''].
 *     @type bool  $allow_multiple Optional. Allow multiple panels open. Default false.
 * }
 */

$items          = $args['items'] ?? [];
$allow_multiple = $args['allow_multiple'] ?? false;

if ( empty( $items ) ) {
    return;
}
?>
<div class="faq-accordion" data-allow-multiple="<?php echo $allow_multiple ? 'true' : 'false'; ?>">
    <?php foreach ( $items as $index => $item ) :
        $question = $item['question'] ?? '';
        $answer   = $item['answer'] ?? '';

        if ( ! $question || ! $answer ) {
            continue;
        }

        $id = 'faq-' . sanitize_title( $question ) . '-' . $index;
    ?>
        <div class="faq-accordion__item">
            <button class="faq-accordion__question" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $id ); ?>">
                <span class="faq-accordion__question-text"><?php echo esc_html( $question ); ?></span>
                <span class="faq-accordion__icon" aria-hidden="true"></span>
            </button>
            <div class="faq-accordion__answer" id="<?php echo esc_attr( $id ); ?>" data-visible="false">
                <?php echo wp_kses_post( $answer ); ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
