<?php /** * Home Section: Search by Category */ ?>
<?php global $venue_rows, $vendor_rows; ?>
    <section class="elementor-section elementor-top-section elementor-element elementor-element-a7d0b71 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="a7d0b71" data-element_type="section">
        <div class="elementor-container elementor-column-gap-default">
            <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-a7d0c71" data-id="a7d0c71" data-element_type="column">
                <div class="elementor-widget-wrap elementor-element-populated">
                    <div class="elementor-element elementor-element-a7d0d71 elementor-widget elementor-widget-html" data-id="a7d0d71" data-element_type="widget" data-widget_type="html.default">
                        <div class="elementor-widget-container">
                            <section class="sd-home-section sd-home-section-7">
                                <div class="container">
                                    <h2>Search by category to find the perfect wedding team</h2>
                                    <h3>San Diego Wedding Venues</h3>
                                    <div class="sd-home-link-group">
                                        <?php foreach ( $venue_rows as $row ) : ?>
                                            <div class="sd-home-link-row">
                                                <?php foreach ( $row as $idx => $item ) : ?>
                                                    <a href="<?php echo esc_url( home_url( $item['path'] ) ); ?>"><?php echo esc_html( $item['label'] ); ?></a><?php if ( $idx < count( $row ) - 1 ) : ?><span class="sd-home-link-sep">•</span><?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <h3>San Diego Wedding Vendors</h3>
                                    <div class="sd-home-link-group">
                                        <?php foreach ( $vendor_rows as $row ) : ?>
                                            <div class="sd-home-link-row">
                                                <?php foreach ( $row as $idx => $item ) : ?>
                                                    <a href="<?php echo esc_url( home_url( $item['path'] ) ); ?>"><?php echo esc_html( $item['label'] ); ?></a><?php if ( $idx < count( $row ) - 1 ) : ?><span class="sd-home-link-sep">•</span><?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
