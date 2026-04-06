<?php
/**
 *    Template Name: Vendor Category
 *    -------------------------------
 */

global $wp_query, $post, $page;

/**
 *  Current Page
 *  ------------
 */
$paged  =   get_query_var( 'paged' ) 

        ?   absint( get_query_var( 'paged' ) ) 

        :   absint( '1' );

/**
 *  Container Start
 *  ---------------
 */
do_action( 'sdweddingdirectory_main_container' );

/**
 *  Have SDWeddingDirectory - Plugin Activated ?
 *  --------------------------------------------
 */
if( class_exists( 'SDWeddingDirectory_Loader' ) ){

    $vendor_terms = get_terms( [
        'taxonomy'       =>  sanitize_key( 'vendor-category' ),
        'hide_empty'     =>  false,
        'orderby'        =>  esc_attr( 'name' ),
        'order'          =>  esc_attr( 'ASC' ),
        'parent'         =>  absint( '0' ),
    ] );

    $vendor_terms = is_array( $vendor_terms ) ? array_filter( $vendor_terms, function( $term ){
        return ! in_array( $term->slug, [ 'venues', 'venue', 'wedding-venues' ], true );
    } ) : [];

    $vendor_term_placeholder = esc_url( get_theme_file_uri( 'assets/images/categories/venues.png' ) );
    $vendor_post_placeholder = esc_url( get_theme_file_uri( 'assets/images/banner-bg.jpg' ) );

    $vendor_category_image_map = [
        'wedding-planning'   => 'planners.png',
        'wedding-planners'   => 'planners.png',
        'planner'            => 'planners.png',
        'planners'           => 'planners.png',
        'bands'              => 'bands.png',
        'cakes'              => 'cakes.png',
        'catering'           => 'caterers.png',
        'caterers'           => 'caterers.png',
        'ceremony-music'     => 'ceremony-music.png',
        'djs'                => 'djs.png',
        'dress-attire'       => 'attire.png',
        'dress-and-attire'   => 'attire.png',
        'event-rentals'      => 'event-rentals.png',
        'favors-gifts'       => 'favors-and-gifts.png',
        'favors-and-gifts'   => 'favors-and-gifts.png',
        'flowers'            => 'flowers.png',
        'hair-makeup'        => 'hair-and-makeup.png',
        'hair-and-makeup'    => 'hair-and-makeup.png',
        'invitations'        => 'invitations.png',
        'jewelry'            => 'jewelry.png',
        'lighting-decor'     => 'lighting-and-decor.png',
        'lighting-and-decor' => 'lighting-and-decor.png',
        'officiants'         => 'officiants.png',
        'photo-booths'       => 'photo-booths.png',
        'photography'        => 'photography.png',
        'transportation'     => 'transportation.png',
        'travel-agents'      => 'travel-agents.png',
        'videography'        => 'videography.png',
    ];

    $get_vendor_category_theme_image = function( $term = null ) use ( $vendor_category_image_map, $vendor_term_placeholder ){

        if( ! is_object( $term ) || empty( $term->slug ) ){
            return $vendor_term_placeholder;
        }

        $slug = sanitize_title( $term->slug );

        if( ! isset( $vendor_category_image_map[ $slug ] ) ){
            return $vendor_term_placeholder;
        }

        return esc_url( get_theme_file_uri( 'assets/images/categories/' . $vendor_category_image_map[ $slug ] ) );
    };

    $primary_breakpoints = wp_json_encode( [
        '0'    => [ 'items' => 2 ],
        '576'  => [ 'items' => 3 ],
        '768'  => [ 'items' => 4 ],
        '992'  => [ 'items' => 5 ],
        '1200' => [ 'items' => 6 ],
    ] );

    $vendor_breakpoints = wp_json_encode( [
        '0'    => [ 'items' => 1 ],
        '576'  => [ 'items' => 2 ],
        '768'  => [ 'items' => 3 ],
        '1200' => [ 'items' => 4 ],
    ] );

    $find_vendor_term = function( $candidates = [] ) use ( $vendor_terms ){

        if( ! is_array( $candidates ) || ! is_array( $vendor_terms ) ){
            return null;
        }

        $normalize = function( $value ){
            $value = wp_strip_all_tags( (string) $value );
            $value = strtolower( trim( $value ) );
            $value = preg_replace( '/[^a-z0-9]+/', '-', $value );
            return trim( (string) $value, '-' );
        };

        $lookup = [];

        foreach( $vendor_terms as $term ){
            $lookup[ $normalize( $term->slug ) ] = $term;
            $lookup[ $normalize( $term->name ) ] = $term;
        }

        foreach( $candidates as $candidate ){
            $candidate_key = $normalize( $candidate );

            if( isset( $lookup[ $candidate_key ] ) ){
                return $lookup[ $candidate_key ];
            }
        }

        return null;
    };

    $featured_vendor_blocks = [
        [
            'title'       => esc_html__( 'San Diego Hair & Makeup Artists', 'sdweddingdirectory' ),
            'description' => esc_html__( 'Discover beauty teams that understand your timeline, your style, and the pace of your wedding morning. From airbrush coverage to touch-up plans, compare local artists and find a crew that keeps your look polished from first look to final dance.', 'sdweddingdirectory' ),
            'candidates'  => [ 'Hair & Makeup', 'Hair and Makeup', 'hair-makeup', 'hair-and-makeup' ],
        ],
        [
            'title'       => esc_html__( 'San Diego Dress & Attire Specialists', 'sdweddingdirectory' ),
            'description' => esc_html__( 'Browse boutiques and stylists for wedding gowns, tuxes, and tailoring support in one place. Preview vendors, review their work, and short-list teams that can handle fittings, alterations, and day-of prep without stress.', 'sdweddingdirectory' ),
            'candidates'  => [ 'Dress & Attire', 'Dress and Attire', 'dress-attire', 'dress-and-attire' ],
        ],
        [
            'title'       => esc_html__( 'San Diego Wedding Officiants', 'sdweddingdirectory' ),
            'description' => esc_html__( 'Find officiants for faith-based, civil, and custom ceremonies with personalities that match your event. Compare ceremony style, availability, and communication approach so your ceremony feels personal and well-paced.', 'sdweddingdirectory' ),
            'candidates'  => [ 'Officiants', 'Officiant', 'officiants', 'officiant' ],
        ],
        [
            'title'       => esc_html__( 'San Diego Wedding Photography Teams', 'sdweddingdirectory' ),
            'description' => esc_html__( 'Compare photographers by editing style, coverage options, and experience at San Diego venues. Short-list pros who can capture portraits, candid moments, and reception energy while keeping your schedule moving smoothly.', 'sdweddingdirectory' ),
            'candidates'  => [ 'Photography', 'Photographer', 'photography', 'wedding-photography' ],
        ],
        [
            'title'       => esc_html__( 'San Diego Wedding Planners', 'sdweddingdirectory' ),
            'description' => esc_html__( 'Explore full-service planners and coordinators who can organize timelines, vendor communication, and production details. Use profiles and ratings to choose a planning partner that matches your budget and event complexity.', 'sdweddingdirectory' ),
            'candidates'  => [ 'Wedding Planners', 'Wedding Planner', 'Planning', 'wedding-planners', 'wedding-planner', 'planning' ],
        ],
    ];
    ?>
    <div class="sd-vendors-landing">

        <section class="sd-vendors-landing-section py-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 mb-4">
                        <h2 class="mb-0"><?php echo esc_html__( 'San Diego Wedding Vendors by Category', 'sdweddingdirectory' ); ?></h2>
                    </div>
                </div>

                <?php if( ! empty( $vendor_terms ) ){ ?>
                    <div class="owl-carousel sdweddingdirectory-owl-carousel sd-vendors-category-carousel"
                        data-breakpoint="<?php echo esc_attr( $primary_breakpoints ); ?>"
                        data-dots="false"
                        data-nav="true"
                        data-loop="true"
                        data-margin="20"
                        data-auto-play="false"
                        data-auto-play-speed="1000"
                        data-auto-play-timeout="5000">
                        <?php foreach( $vendor_terms as $vendor_term ){

                            $term_link = get_term_link( $vendor_term );

                            if( is_wp_error( $term_link ) ){
                                $term_link = home_url( '/vendors/' );
                            }

                            $term_image = $get_vendor_category_theme_image( $vendor_term );
                            ?>
                            <div class="item">
                                <a class="sd-vendors-category-slide" href="<?php echo esc_url( $term_link ); ?>">
                                    <span class="sd-vendors-category-slide-image">
                                        <img loading="lazy" src="<?php echo esc_url( $term_image ); ?>" alt="<?php echo esc_attr( $vendor_term->name ); ?>" />
                                    </span>
                                    <span class="sd-vendors-category-slide-title"><?php echo esc_html( $vendor_term->name ); ?></span>
                                    <span class="sd-vendors-category-slide-count">
                                        <?php
                                        printf(
                                            esc_html__( '%1$s vendors', 'sdweddingdirectory' ),
                                            esc_html( number_format_i18n( absint( $vendor_term->count ) ) )
                                        );
                                        ?>
                                    </span>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </section>

        <?php foreach( $featured_vendor_blocks as $featured_block ){

            $featured_term = $find_vendor_term( $featured_block['candidates'] );

            $featured_link = home_url( '/vendors/' );

            $featured_image = esc_url( get_template_directory_uri() . '/assets/images/cutoff-banner.png' );

            if( ! empty( $featured_term ) ){
                $term_link = get_term_link( $featured_term );

                if( ! is_wp_error( $term_link ) ){
                    $featured_link = $term_link;
                }

                $featured_image = $get_vendor_category_theme_image( $featured_term );
            }

            $vendor_query_args = [
                'post_type'         =>  esc_attr( 'vendor' ),
                'post_status'       =>  esc_attr( 'publish' ),
                'posts_per_page'    =>  absint( '8' ),
                'orderby'           =>  esc_attr( 'date' ),
                'order'             =>  esc_attr( 'DESC' ),
            ];

            if( ! empty( $featured_term ) ){
                $vendor_query_args['tax_query'] = [
                    [
                        'taxonomy'  =>  sanitize_key( 'vendor-category' ),
                        'field'     =>  esc_attr( 'term_id' ),
                        'terms'     =>  [ absint( $featured_term->term_id ) ],
                    ]
                ];
            }

            $vendor_query = new WP_Query( $vendor_query_args );
            ?>
            <section class="sd-vendors-feature-popout py-5">
                <div class="container">
                    <div class="sd-vendors-feature-frame">
                        <div class="sd-vendors-feature-card">
                            <h3><?php echo esc_html( $featured_block['title'] ); ?></h3>
                            <p class="mb-0"><?php echo esc_html( $featured_block['description'] ); ?></p>
                        </div>
                        <div class="sd-vendors-feature-visual">
                            <a href="<?php echo esc_url( $featured_link ); ?>">
                                <img loading="lazy" src="<?php echo esc_url( $featured_image ); ?>" alt="<?php echo esc_attr( $featured_block['title'] ); ?>" />
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="sd-vendors-feature-carousel py-5">
                <div class="container">
                    <div class="owl-carousel sdweddingdirectory-owl-carousel sd-vendors-feature-vendor-carousel"
                        data-breakpoint="<?php echo esc_attr( $vendor_breakpoints ); ?>"
                        data-dots="false"
                        data-nav="true"
                        data-loop="true"
                        data-margin="20"
                        data-auto-play="false"
                        data-auto-play-speed="1000"
                        data-auto-play-timeout="5000">

                        <?php
                        if( $vendor_query->have_posts() ){

                            while( $vendor_query->have_posts() ){ $vendor_query->the_post();

                                $vendor_post_id = absint( get_the_ID() );
                                $vendor_url = get_the_permalink( $vendor_post_id );

                                $vendor_name = get_post_meta( $vendor_post_id, sanitize_key( 'company_name' ), true );
                                $vendor_name = ! empty( $vendor_name ) ? $vendor_name : get_the_title( $vendor_post_id );

                                $vendor_image = ! empty( $featured_term )
                                                ? $get_vendor_category_theme_image( $featured_term )
                                                : $vendor_post_placeholder;

                                $vendor_image_alt = class_exists( 'SDWeddingDirectory_Vendor' )
                                                    ? SDWeddingDirectory_Vendor:: vendor_post_image_alt( $vendor_post_id )
                                                    : get_the_title( $vendor_post_id );

                                $vendor_rating = apply_filters( 'sdweddingdirectory/rating/average', '', [
                                    'vendor_id'     =>  $vendor_post_id,
                                    'before'        =>  '<span>',
                                    'after'         =>  '</span>',
                                    'icon'          =>  '<i class="fa fa-star me-1"></i>',
                                ] );

                                $vendor_price = class_exists( 'SDWeddingDirectory_Vendor' )
                                                ? SDWeddingDirectory_Vendor:: vendor_result_price( $vendor_post_id )
                                                : '';
                                ?>
                                <div class="item">
                                    <article class="sd-vendors-feature-vendor-card">
                                        <a class="sd-vendors-feature-vendor-image" href="<?php echo esc_url( $vendor_url ); ?>">
                                            <img loading="lazy" src="<?php echo esc_url( $vendor_image ); ?>" alt="<?php echo esc_attr( $vendor_image_alt ); ?>" />
                                        </a>
                                        <div class="sd-vendors-feature-vendor-body">
                                            <h4><a href="<?php echo esc_url( $vendor_url ); ?>"><?php echo esc_html( $vendor_name ); ?></a></h4>
                                            <p class="sd-vendors-feature-vendor-rating mb-2">
                                                <?php
                                                if( ! empty( $vendor_rating ) ){
                                                    echo wp_kses_post( $vendor_rating );
                                                }else{
                                                    echo esc_html__( 'New vendor', 'sdweddingdirectory' );
                                                }
                                                ?>
                                            </p>
                                            <p class="sd-vendors-feature-vendor-price mb-2">
                                                <?php
                                                if( ! empty( $vendor_price ) ){
                                                    printf(
                                                        esc_html__( 'Starting price: %1$s', 'sdweddingdirectory' ),
                                                        esc_html( $vendor_price )
                                                    );
                                                }else{
                                                    echo esc_html__( 'Pricing available on request', 'sdweddingdirectory' );
                                                }
                                                ?>
                                            </p>
                                            <a class="sd-vendors-feature-vendor-link" href="<?php echo esc_url( $vendor_url ); ?>">
                                                <?php echo esc_html__( 'Request Pricing', 'sdweddingdirectory' ); ?>
                                            </a>
                                        </div>
                                    </article>
                                </div>
                            <?php }

                            wp_reset_postdata();
                        }
                        ?>
                    </div>
                </div>
            </section>
        <?php } ?>

        <section class="sd-vendors-seo-block py-5">
            <div class="container">
                <h2><?php echo esc_html__( 'Your Wedding Vendors are the Key to a Beautiful Big Day', 'sdweddingdirectory' ); ?></h2>
                <p class="mb-0">
                    <?php echo esc_html__( 'The right vendors shape everything. From your DJ and photographer to your planner and florist, each one plays a role in how your day looks, feels, and flows. Choose a team that understands your vision, communicates clearly, and shows up prepared. When your vendors are dialed in, everything else falls into place.', 'sdweddingdirectory' ); ?>
                </p>
            </div>
        </section>

        <section class="sd-vendors-three-column py-5">
            <div class="container">
                <div class="row g-4">
                    <div class="col-12 col-lg-4">
                        <h4><?php echo esc_html__( 'When to Start Searching for Wedding Vendors & Services', 'sdweddingdirectory' ); ?></h4>
                        <p><?php echo esc_html__( 'Start now. Even if your vision isn\'t fully locked in, the right vendors help shape it. A strong team will guide your decisions on style, timeline, logistics, and budget, while helping you avoid common mistakes early. In San Diego, top vendors book out 9-18 months in advance, especially for peak dates. Waiting limits your options, increases pricing pressure, and forces you to settle instead of choosing the best fit.', 'sdweddingdirectory' ); ?></p>
                        <h4><?php echo esc_html__( 'Which Wedding Vendors to Book First', 'sdweddingdirectory' ); ?></h4>
                        <p class="mb-0"><?php echo esc_html__( 'Lock in your venue first, since it determines your date, location, and overall structure. If you plan to use a wedding planner or coordinator, book them at the same time or immediately after. From there, prioritize your photographer, DJ or band, and videographer, since these vendors have a major impact on your experience and memories. Once those are secured, move into catering (if not included), florals, rentals, and overall design. Hair, makeup, and smaller vendors can come later.', 'sdweddingdirectory' ); ?></p>
                    </div>
                    <div class="col-12 col-lg-4">
                        <p><?php echo esc_html__( 'The early bookings set the tone for everything else, and in a competitive market like San Diego, the best vendors will not hold dates without contracts. Booking in the right order keeps your planning efficient and prevents conflicts.', 'sdweddingdirectory' ); ?></p>
                        <h4><?php echo esc_html__( 'How to Choose Wedding Vendors & Services', 'sdweddingdirectory' ); ?></h4>
                        <p class="mb-0"><?php echo esc_html__( 'Start by looking at full galleries and real examples of work, not just highlight reels. Read consistent reviews and pay attention to how vendors communicate during the inquiry process. Responsiveness, clarity, and professionalism matter just as much as talent. You want vendors who are organized, experienced, and easy to work with under pressure. Ask direct questions about timelines, backup plans, and experience at your venue. Trust your instincts throughout the process. If communication feels off or you feel pressured to commit, walk away. The right vendors will feel like a solid fit both professionally and personally, and will make the process smoother instead of adding stress.', 'sdweddingdirectory' ); ?></p>
                    </div>
                    <div class="col-12 col-lg-4">
                        <h4><?php echo esc_html__( 'When You Need to Prioritize', 'sdweddingdirectory' ); ?></h4>
                        <p class="mb-0"><?php echo esc_html__( 'There is no universal rule for how to allocate your budget, and most generic guides do not reflect your actual priorities. Decide early what matters most to you and build your budget around that. If music and energy are a top priority, invest more in your DJ or band. If food and guest experience matter most, allocate more toward catering. Photography is often worth prioritizing because it lasts beyond the day itself. At the same time, identify areas where you can scale back without impacting the experience. This could mean simplifying decor, reducing extras, or limiting upgrades that do not add real value. Be realistic about trade-offs and avoid spreading your budget too thin across every category. A focused budget creates a stronger overall result than trying to maximize everything at once.', 'sdweddingdirectory' ); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <section class="sd-vendors-button-row py-5">
            <div class="container">
                <h3 class="mb-3"><?php echo esc_html__( 'Every wedding vendor you need, all in one place', 'sdweddingdirectory' ); ?></h3>
                <?php if( ! empty( $vendor_terms ) ){
                    $vendor_term_lookup = [];
                    foreach( $vendor_terms as $vt ){
                        $vendor_term_lookup[ sanitize_title( $vt->slug ) ] = $vt;
                    }

                    $vendor_btn_order = [
                        'photography',
                        'djs',
                        'hair-and-makeup', 'hair-makeup',
                        'wedding-planners', 'wedding-planning', 'planners',
                        'catering', 'caterers',
                        'flowers',
                        'videography',
                        'officiants',
                        'event-rentals',
                        'photo-booths',
                        'bands',
                        'dress-and-attire', 'dress-attire',
                        'cakes',
                        'transportation',
                        'ceremony-music',
                        'lighting-and-decor', 'lighting-decor',
                        'invitations',
                        'travel-agents',
                        'jewelry',
                        'favors-and-gifts', 'favors-gifts',
                    ];

                    $ordered_terms = [];
                    $used_ids = [];
                    foreach( $vendor_btn_order as $slug ){
                        if( isset( $vendor_term_lookup[ $slug ] ) && ! in_array( $vendor_term_lookup[ $slug ]->term_id, $used_ids, true ) ){
                            $ordered_terms[] = $vendor_term_lookup[ $slug ];
                            $used_ids[] = $vendor_term_lookup[ $slug ]->term_id;
                        }
                    }
                    foreach( $vendor_terms as $vt ){
                        if( ! in_array( $vt->term_id, $used_ids, true ) ){
                            $ordered_terms[] = $vt;
                        }
                    }
                    ?>
                    <div class="d-flex flex-wrap justify-content-start sd-vendor-btn-grid">
                        <?php foreach( $ordered_terms as $vendor_term ){
                            $term_link = get_term_link( $vendor_term );
                            if( is_wp_error( $term_link ) ){
                                $term_link = home_url( '/vendors/' );
                            }
                            $btn_name = $vendor_term->name;
                            $btn_overrides = [
                                'event-rentals'    => 'Wedding Rentals',
                                'photo-booths'     => 'Photo Booths',
                                'dress-and-attire' => 'Wedding Dresses',
                                'dress-attire'     => 'Wedding Dresses',
                                'travel-agents'    => 'Travel Agents',
                                'favors-and-gifts' => 'Wedding Favors',
                                'favors-gifts'     => 'Wedding Favors',
                                'ceremony-music'   => 'Wedding Musicians',
                                'transportation'   => 'Wedding Limos',
                            ];
                            $term_slug = sanitize_title( $vendor_term->slug );
                            if ( isset( $btn_overrides[ $term_slug ] ) ) {
                                $btn_name = $btn_overrides[ $term_slug ];
                            } elseif ( stripos( $btn_name, 'wedding' ) === false ) {
                                $btn_name = 'Wedding ' . $btn_name;
                            }
                            ?>
                            <a class="btn btn-outline-dark btn-sm rounded" href="<?php echo esc_url( $term_link ); ?>">
                                <?php echo esc_html( $btn_name ); ?>
                            </a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </section>

        <section class="sd-vendors-inline-links py-4">
            <div class="container">
                <h3 class="mb-3"><?php echo esc_html__( 'All San Diego Wedding Vendors', 'sdweddingdirectory' ); ?></h3>
                <p class="mb-0">
                    <?php
                    if( ! empty( $vendor_terms ) ){
                        $inline_links = [];

                        foreach( $vendor_terms as $vendor_term ){
                            $term_link = get_term_link( $vendor_term );

                            if( is_wp_error( $term_link ) ){
                                continue;
                            }

                            $inline_links[] = sprintf(
                                '<a href="%1$s">%2$s</a>',
                                esc_url( $term_link ),
                                esc_html( $vendor_term->name )
                            );
                        }

                        echo wp_kses_post( implode( ' <span class="sd-vendors-inline-links-separator">&bull;</span> ', $inline_links ) );
                    }
                    ?>
                </p>
            </div>
        </section>

    </div>
    <?php
}else{

    /**
     *  Article Not Found!
     *  ------------------
     */
    do_action( 'sdweddingdirectory_empty_article' );
}

/**
 *  Container End
 *  -------------
 */
do_action( 'sdweddingdirectory_main_container_end' ); ?>
