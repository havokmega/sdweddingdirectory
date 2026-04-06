<?php
/**
 * Component: Feature Block
 *
 * Planning feature block with text content, screenshot, and testimonial.
 *
 * @param array $args {
 *     @type string $heading      Required. Heading text.
 *     @type string $desc         Optional. Introductory paragraph.
 *     @type string $intro_text   Optional. Small accent link text.
 *     @type string $intro_url    Optional. Accent link URL.
 *     @type array  $sections     Optional. Array of ['title'=>'', 'desc'=>''].
 *     @type string $cta_text     Optional. CTA button text.
 *     @type string $cta_url      Optional. CTA button URL.
 *     @type string $image_url    Optional. Image source URL.
 *     @type string $image_alt    Optional. Image alt text.
 *     @type int    $image_width  Optional. Image width attribute.
 *     @type int    $image_height Optional. Image height attribute.
 *     @type bool   $reversed     Optional. Swap image/content sides. Default false.
 *     @type array  $testimonial  Optional. ['initials'=>'', 'author'=>'', 'quote'=>''].
 * }
 */

$heading      = $args['heading'] ?? '';
$desc         = $args['desc'] ?? '';
$intro_text   = $args['intro_text'] ?? '';
$intro_url    = $args['intro_url'] ?? '#';
$sections     = $args['sections'] ?? [];
$cta_text     = $args['cta_text'] ?? '';
$cta_url      = $args['cta_url'] ?? '#';
$image_url    = $args['image_url'] ?? '';
$image_alt    = $args['image_alt'] ?? $heading;
$image_width  = $args['image_width'] ?? '';
$image_height = $args['image_height'] ?? '';
$reversed     = $args['reversed'] ?? false;
$testimonial  = $args['testimonial'] ?? [];

if ( ! $heading ) {
    return;
}

$block_class = 'feature-block feature-block--planning';
if ( $reversed ) {
    $block_class .= ' feature-block--reversed';
}
?>
<div class="<?php echo esc_attr( $block_class ); ?>">
    <div class="feature-block__content">
        <div class="feature-block__intro">
            <h2 class="feature-block__heading"><?php echo esc_html( $heading ); ?></h2>

            <?php if ( $desc ) : ?>
                <p class="feature-block__desc"><?php echo wp_kses_post( $desc ); ?></p>
            <?php endif; ?>

            <?php if ( $intro_text ) : ?>
                <a class="feature-block__eyebrow" href="<?php echo esc_url( $intro_url ); ?>"><?php echo esc_html( $intro_text ); ?></a>
            <?php endif; ?>
        </div>

        <?php if ( ! empty( $sections ) ) : ?>
            <div class="feature-block__stack">
                <?php foreach ( $sections as $section ) : ?>
                    <?php
                    $section_title = $section['title'] ?? '';
                    $section_desc  = $section['desc'] ?? '';

                    if ( ! $section_title && ! $section_desc ) {
                        continue;
                    }
                    ?>
                    <div class="feature-block__item">
                        <?php if ( $section_title ) : ?>
                            <h3 class="feature-block__item-heading"><?php echo esc_html( $section_title ); ?></h3>
                        <?php endif; ?>

                        <?php if ( $section_desc ) : ?>
                            <p class="feature-block__item-desc"><?php echo wp_kses_post( $section_desc ); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ( $cta_text ) : ?>
            <a class="btn btn--cta feature-block__cta" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $cta_text ); ?></a>
        <?php endif; ?>
    </div>

    <?php if ( $image_url || ! empty( $testimonial['quote'] ) ) : ?>
        <div class="feature-block__media">
            <?php if ( $image_url ) : ?>
                <div class="feature-block__image-wrap">
                    <img class="feature-block__image"
                        src="<?php echo esc_url( $image_url ); ?>"
                        alt="<?php echo esc_attr( $image_alt ); ?>"
                        <?php if ( $image_width ) : ?>width="<?php echo esc_attr( $image_width ); ?>"<?php endif; ?>
                        <?php if ( $image_height ) : ?>height="<?php echo esc_attr( $image_height ); ?>"<?php endif; ?>
                        loading="lazy">
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $testimonial['quote'] ) ) : ?>
                <blockquote class="feature-block__testimonial">
                    <?php if ( ! empty( $testimonial['initials'] ) ) : ?>
                        <span class="feature-block__testimonial-avatar"><?php echo esc_html( $testimonial['initials'] ); ?></span>
                    <?php endif; ?>

                    <div class="feature-block__testimonial-body">
                        <?php if ( ! empty( $testimonial['author'] ) ) : ?>
                            <cite class="feature-block__testimonial-author"><?php echo esc_html( $testimonial['author'] ); ?></cite>
                        <?php endif; ?>
                        <p class="feature-block__testimonial-quote">"<?php echo esc_html( $testimonial['quote'] ); ?>"</p>
                    </div>
                </blockquote>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
