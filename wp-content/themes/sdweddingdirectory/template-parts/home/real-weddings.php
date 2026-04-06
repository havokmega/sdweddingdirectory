<?php /** * Home Section: Real Weddings */ ?>
    <section class="elementor-section elementor-top-section elementor-element elementor-element-8e85667 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="8e85667" data-element_type="section">
        <div class="elementor-container elementor-column-gap-default">
            <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-033ad05" data-id="033ad05" data-element_type="column">
                <div class="elementor-widget-wrap elementor-element-populated">
                    <div class="elementor-element elementor-element-c205b5d elementor-widget elementor-widget-elementorsdweddingdirectory_elementor_section_title" data-id="c205b5d" data-element_type="widget" data-widget_type="elementorsdweddingdirectory_elementor_section_title.default">
                        <div class="elementor-widget-container">
                            <div class="section-title col  text-center"><h2>Real Weddings</h2><p>Find inspiration from real San Diego couples</p></div>
                        </div>
                    </div>

                    <div class="elementor-element elementor-element-e760de1 elementor-widget elementor-widget-elementorsdweddingdirectory_elementor_realwedding_post" data-id="e760de1" data-element_type="widget" data-widget_type="elementorsdweddingdirectory_elementor_realwedding_post.default">
                        <div class="elementor-widget-container">
                            <div  class="row row-cols-xxl-4 row-cols-xl-4 row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-1 ">
                                <?php
                                if ( class_exists( 'SDWeddingDirectory_Shortcode_RealWedding_Post' ) ) {
                                    echo SDWeddingDirectory_Shortcode_RealWedding_Post::page_builder( [
                                        'layout'         => 1,
                                        'posts_per_page' => 4,
                                        'pagination'     => 'false',
                                        'style'          => '1',
                                    ] );
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="elementor-element elementor-element-0b8744c elementor-widget elementor-widget-html" data-id="0b8744c" data-element_type="widget" data-widget_type="html.default">
                        <div class="elementor-widget-container">
                            <div class="text-center mt-4"><a class="btn btn-dark btn-rounded" href="<?php echo esc_url( home_url( '/real-weddings/' ) ); ?>">See All Real Weddings</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
