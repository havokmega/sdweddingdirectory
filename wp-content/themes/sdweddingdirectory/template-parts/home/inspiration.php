<?php /** * Home Section: Inspiration */ ?>
    <section class="elementor-section elementor-top-section elementor-element elementor-element-a6714e2 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="a6714e2" data-element_type="section">
        <div class="elementor-container elementor-column-gap-default">
            <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-6db2cd2" data-id="6db2cd2" data-element_type="column">
                <div class="elementor-widget-wrap elementor-element-populated">
                    <div class="elementor-element elementor-element-a8f2665 elementor-widget elementor-widget-elementorsdweddingdirectory_elementor_section_title" data-id="a8f2665" data-element_type="widget" data-widget_type="elementorsdweddingdirectory_elementor_section_title.default">
                        <div class="elementor-widget-container">
                            <div class="section-title col  text-center"><h2>Inspiration</h2><p>Tips, advice, and ideas for your perfect wedding</p></div>
                        </div>
                    </div>

                    <div class="elementor-element elementor-element-1633eb5 elementor-widget elementor-widget-html" data-id="1633eb5" data-element_type="widget" data-widget_type="html.default">
                        <div class="elementor-widget-container">
                            <div class="sd-blog-categories">
                                <div class="row row-cols-xl-6 row-cols-lg-3 row-cols-2 g-4 justify-content-center">
                                    <?php
                                    $blog_categories = [
                                        [ 'slug' => 'wedding-planning-how-to', 'title' => 'Planning How To' ],
                                        [ 'slug' => 'wedding-ceremony', 'title' => 'Ceremony' ],
                                        [ 'slug' => 'wedding-reception', 'title' => 'Reception' ],
                                        [ 'slug' => 'wedding-services', 'title' => 'Services' ],
                                        [ 'slug' => 'wedding-fashion', 'title' => 'Fashion' ],
                                        [ 'slug' => 'hair-makeup', 'title' => 'Hair & Makeup' ],
                                    ];

                                    foreach ( $blog_categories as $cat ) {
                                        ?>
                                        <div class="col text-center">
                                            <a href="<?php echo esc_url( home_url( '/inspiration/' . $cat['slug'] . '/' ) ); ?>" class="sd-blog-cat-link">
                                                <div class="sd-blog-cat-circle">
                                                    <img loading="lazy" decoding="async" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/blog/' . $cat['slug'] . '.jpg' ) ); ?>" alt="<?php echo esc_attr( $cat['title'] ); ?>" />
                                                </div>
                                                <h6 class="mt-2"><?php echo esc_html( $cat['title'] ); ?></h6>
                                            </a>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="elementor-element elementor-element-442ba6b elementor-widget elementor-widget-html" data-id="442ba6b" data-element_type="widget" data-widget_type="html.default">
                        <div class="elementor-widget-container">
                            <div class="row row-cols-xl-4 row-cols-lg-4 row-cols-md-2 row-cols-1 g-4 sd-blog-grid">
                                <?php
                                $featured_articles = [
                                    [
                                        'url'   => '/the-ultimate-honeymoon-advice-guide-for-modern-couples/',
                                        'image' => get_theme_file_uri( 'assets/images/blog/honeymoon-advice.jpg' ),
                                        'alt'   => 'Honeymoon Advice Guide',
                                        'cat'   => 'WEDDING HONEYMOON ADVICE',
                                        'title' => 'The Ultimate Honeymoon Advice Guide for Modern Couples',
                                    ],
                                    [
                                        'url'   => '/the-ultimate-wedding-budget-guide-how-to-plan-and-spend-smart/',
                                        'image' => get_theme_file_uri( 'assets/images/blog/budget.jpg' ),
                                        'alt'   => 'Wedding Budget Guide',
                                        'cat'   => 'WEDDING BUDGET',
                                        'title' => 'The Ultimate Wedding Budget Guide: How to Plan and Spend Smart',
                                    ],
                                    [
                                        'url'   => '/san-diego-wedding-legal-paperwork-guide-what-you-actually-need-to-get-married/',
                                        'image' => get_theme_file_uri( 'assets/images/blog/legal-paperwork.jpg' ),
                                        'alt'   => 'Legal Paperwork Guide',
                                        'cat'   => 'LEGAL PAPERWORK',
                                        'title' => 'San Diego Wedding Legal Paperwork Guide: What You Actually Need to Get Married',
                                    ],
                                    [
                                        'url'   => '/smart-wedding-tips-every-san-diego-couple-should-know/',
                                        'image' => get_theme_file_uri( 'assets/images/blog/trends-and-tips.jpg' ),
                                        'alt'   => 'Smart Wedding Tips',
                                        'cat'   => 'WEDDING TRENDS & TIPS',
                                        'title' => 'Smart Wedding Tips Every San Diego Couple Should Know',
                                    ],
                                ];

                                foreach ( $featured_articles as $article ) {
                                    ?>
                                    <div class="col">
                                        <a href="<?php echo esc_url( home_url( $article['url'] ) ); ?>" class="sd-blog-card">
                                            <div class="sd-blog-card-img">
                                                <img loading="lazy" decoding="async" src="<?php echo esc_url( $article['image'] ); ?>" alt="<?php echo esc_attr( $article['alt'] ); ?>" />
                                            </div>
                                            <div class="sd-blog-card-body">
                                                <span class="sd-blog-card-cat"><?php echo esc_html( $article['cat'] ); ?></span>
                                                <h6 class="sd-blog-card-title"><?php echo esc_html( $article['title'] ); ?></h6>
                                            </div>
                                        </a>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="elementor-element elementor-element-77fdcdb elementor-widget elementor-widget-html" data-id="77fdcdb" data-element_type="widget" data-widget_type="html.default">
                        <div class="elementor-widget-container">
                            <div class="text-center mt-4"><a class="sd-browse-link" href="<?php echo esc_url( home_url( '/inspiration/' ) ); ?>">Browse all articles <span>&rarr;</span></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
