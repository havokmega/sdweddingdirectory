<?php /** * Home Section: Find Your Location */ ?>
<?php global $featured_city_slides; ?>
    <section class="elementor-section elementor-top-section elementor-element elementor-element-82bfe89 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="82bfe89" data-element_type="section">
        <div class="elementor-container elementor-column-gap-default">
            <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-e5d5d48" data-id="e5d5d48" data-element_type="column">
                <div class="elementor-widget-wrap elementor-element-populated">
                    <div class="elementor-element elementor-element-3d2bfc9 elementor-widget elementor-widget-elementorsdweddingdirectory_elementor_section_title" data-id="3d2bfc9" data-element_type="widget" data-widget_type="elementorsdweddingdirectory_elementor_section_title.default">
                        <div class="elementor-widget-container">
                            <div class="section-title col  text-center"><h2>Find Your Location</h2><p>Discover wedding venues and vendors across San Diego County</p></div>
                        </div>
                    </div>

                    <div class="elementor-element elementor-element-940bba6 elementor-widget elementor-widget-html" data-id="940bba6" data-element_type="widget" data-widget_type="html.default">
                        <div class="elementor-widget-container">
                            <div class="sd-city-carousel-wrapper">
                                <div class="owl-carousel sdweddingdirectory-owl-carousel sd-city-carousel" data-dots="false" data-nav="true" data-loop="true" data-margin="16" data-breakpoint='{"0": {"items": 2}, "576": {"items": 3}, "768": {"items": 4}, "992": {"items": 5}, "1200": {"items": 6}}'>
                                    <?php foreach ( $featured_city_slides as $city ) : ?>
                                        <div class="sd-city-slide">
                                            <a href="<?php echo esc_url( home_url( '/venues/?location=' . $city['slug'] ) ); ?>">
                                                <div class="sd-city-img"><img loading="lazy" decoding="async" src="<?php echo esc_url( $city['image'] ); ?>" alt="<?php echo esc_attr( $city['name'] ); ?>" /></div>
                                                <h6 class="sd-city-name"><?php echo esc_html( $city['name'] ); ?></h6>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="elementor-element elementor-element-fd80856 elementor-widget elementor-widget-html" data-id="fd80856" data-element_type="widget" data-widget_type="html.default">
                        <div class="elementor-widget-container">
                            <div class="text-center mt-4"><a class="btn btn-dark btn-rounded" href="<?php echo esc_url( home_url( '/venues/' ) ); ?>">See All Cities</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
