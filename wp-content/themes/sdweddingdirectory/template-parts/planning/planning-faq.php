<?php
/**
 * Wedding planning FAQ section.
 */
$accordion_settings = isset( $args['accordion_settings'] ) && is_array( $args['accordion_settings'] ) ? $args['accordion_settings'] : [];
$accordion_id       = ! empty( $accordion_settings['have_id'] ) ? sanitize_html_class( $accordion_settings['have_id'] ) : 'sdwd-planning-faq';
$accordion_items    = ! empty( $accordion_settings['sdweddingdirectory_group_accordion'] ) && is_array( $accordion_settings['sdweddingdirectory_group_accordion'] )
    ? $accordion_settings['sdweddingdirectory_group_accordion']
    : [];
?>
<section class="sdwd-plan-faq" aria-label="Wedding Planning FAQ">
    <div class="sdwd-plan-wrap sdwd-plan-wrap-narrow">
      <div class="sdwd-plan-faq-head">
        <h2>Frequently Asked Questions</h2>
        <p>Have questions about our planning features? You are in the right place.</p>
      </div>
    </div>
  </section>
</div>
<?php if ( ! empty( $accordion_items ) ) : ?>
    <div class="sdwd-plan-wrap">
        <div id="<?php echo esc_attr( $accordion_id ); ?>" class="accordion theme-accordian">
            <?php foreach ( $accordion_items as $index => $item ) : ?>
                <?php
                $item_heading = ! empty( $item['accordion_heading'] ) ? $item['accordion_heading'] : '';
                $item_content = ! empty( $item['accordion_content'] ) ? $item['accordion_content'] : '';
                $item_key     = ! empty( $item['_id'] ) ? sanitize_html_class( $item['_id'] ) : 'item-' . absint( $index + 1 );
                $heading_id   = 'sdwd-planning-head-' . $item_key;
                $collapse_id  = 'sdwd-planning-collapse-' . $item_key;
                $is_first     = ( $index === 0 );
                ?>
                <div class="card-header" id="<?php echo esc_attr( $heading_id ); ?>">
                    <a href="javascript:" data-bs-toggle="collapse" data-bs-target="#<?php echo esc_attr( $collapse_id ); ?>" aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $collapse_id ); ?>" class="<?php echo $is_first ? '' : 'collapsed'; ?>">
                        <?php echo esc_html( $item_heading ); ?>
                    </a>
                </div>

                <div id="<?php echo esc_attr( $collapse_id ); ?>" class="collapse <?php echo $is_first ? 'show' : ''; ?>" aria-labelledby="<?php echo esc_attr( $heading_id ); ?>" data-bs-parent="#<?php echo esc_attr( $accordion_id ); ?>">
                    <div class="card-body">
                        <?php echo wp_kses_post( $item_content ); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
