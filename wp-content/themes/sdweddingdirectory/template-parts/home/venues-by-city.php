<?php /** * Home Section: Browse Wedding Venues by City */ ?>
<?php global $city_terms; ?>
    <section class="elementor-section elementor-top-section elementor-element elementor-element-d0a3ea4 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="d0a3ea4" data-element_type="section">
        <div class="elementor-container elementor-column-gap-default">
            <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-d0a3fa4" data-id="d0a3fa4" data-element_type="column">
                <div class="elementor-widget-wrap elementor-element-populated">
                    <div class="elementor-element elementor-element-d0a3ab4 elementor-widget elementor-widget-html" data-id="d0a3ab4" data-element_type="widget" data-widget_type="html.default">
                        <div class="elementor-widget-container">
                            <section class="sd-home-section sd-home-section-10">
                                <div class="container">
                                    <h2>Browse Wedding Venues by City</h2>
                                    <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-2">
                                        <?php if ( ! empty( $city_terms ) && ! is_wp_error( $city_terms ) ) : ?>
                                            <?php foreach ( $city_terms as $city ) : ?>
                                                <div class="col">
                                                    <div class="sd-home-city-link">
                                                        <a href="<?php echo esc_url( home_url( '/venues/?location=' . $city->slug ) ); ?>"><?php echo esc_html( $city->name ); ?> Wedding Venues</a>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
