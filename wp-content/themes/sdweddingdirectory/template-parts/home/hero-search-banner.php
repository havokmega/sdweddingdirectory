<?php /** * Home Section: Hero Search Banner */ ?>
    <section class="elementor-section elementor-top-section elementor-element elementor-element-e3a5d74 elementor-section-full_width elementor-section-height-default elementor-section-height-default" data-id="e3a5d74" data-element_type="section">
        <div class="elementor-container elementor-column-gap-no">
            <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-48f9690" data-id="48f9690" data-element_type="column">
                <div class="elementor-widget-wrap elementor-element-populated">
                    <div class="elementor-element elementor-element-4f7ca83 elementor-widget elementor-widget-elementorsdweddingdirectory_elementor_home_page_slider_one" data-id="4f7ca83" data-element_type="widget" data-widget_type="elementorsdweddingdirectory_elementor_home_page_slider_one.default">
                        <div class="elementor-widget-container">
                            <?php
                            if ( class_exists( 'SDWeddingDirectory_Shortcode_Slider_Version' ) ) {
                                echo SDWeddingDirectory_Shortcode_Slider_Version::page_builder( [
                                    'layout'               => 2,
                                    'background_image'     => esc_url( sdweddingdirectory_random_banner( 'home-hero-random', 5, 'jpg' ) ),
                                    'form_heading'         => 'Find the Perfect Wedding Vendor',
                                    'form_description'     => 'Search top San Diego wedding vendors with reviews, pricing, and availability.',
                                    'submit_button_text'   => 'Search Now',
                                    'venue_category_desc'=> 'Browse popular venue types',
                                ] );
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
