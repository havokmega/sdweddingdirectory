<?php

global $post, $wp_query;

/**
 *  Container Start
 *  ---------------
 */
do_action( 'sdweddingdirectory_main_container' );

/**
 *  Make sure core plugin activated
 *  -------------------------------
 */
if( class_exists( 'SDWeddingDirectory_Loader' ) ){

    /**
     *  Extract
     *  -------
     */
    extract( (array) get_queried_object() );

    /**
     *  Defaults
     *  --------
     */
    $paged          =   get_query_var( 'paged' )  ?  absint( get_query_var( 'paged' ) ) :  absint( '1' );

    $posts_per_page =   apply_filters( 'sdweddingdirectory/vendor/post-per-page', absint( '12' ) );

    $term_post_id   =   esc_attr( $taxonomy ) . '_' . absint( $term_id );

    /**
     *  Normalize Vendor Options
     *  ------------------------
     */
    $normalize_vendor_options = function( $list = [], $mode = '' ){

        if( SDWeddingDirectory_Loader:: _is_array( $list ) ){

            $options = [];

            foreach( $list as $row ){

                if( $mode === esc_attr( 'pricing' ) ){

                    if( isset( $row['min'], $row['max'] ) ){

                        $key = sprintf( '%1$s-%2$s', $row['min'], $row['max'] );

                        $label = ! empty( $row['label'] ) ? $row['label'] : $key;

                        $options[ $key ] = $label;
                    }
                }elseif( ! empty( $row['value'] ) ){

                    $options[ $row['value'] ] = ! empty( $row['label'] ) ? $row['label'] : $row['value'];
                }
            }

            return $options;
        }

        return [];
    };

    /**
     *  Query Values
     *  ------------
     */
    $get_query_values = function( $key = '' ){

        if( empty( $key ) || ! isset( $_GET[ $key ] ) || $_GET[ $key ] === '' ){

            return [];
        }

        $raw = wp_unslash( $_GET[ $key ] );

        if( is_array( $raw ) ){

            return array_values( array_filter( array_map( 'sanitize_text_field', $raw ) ) );
        }

        $raw = sanitize_text_field( $raw );

        return array_values( array_filter( preg_split( "/\,/", $raw ) ) );
    };

    /**
     *  Selected Filters
     *  ---------------
     */
    $selected_pricing      =   $get_query_values( 'vendor_pricing' );
    $selected_services     =   $get_query_values( 'vendor_services' );
    $selected_style        =   $get_query_values( 'vendor_style' );
    $selected_specialties  =   $get_query_values( 'vendor_specialties' );

    /**
     *  Meta Query
     *  ----------
     */
    $meta_query = [];

    /**
     *  Require Profile Banner
     *  ----------------------
     */
    $meta_query[] = array(

                        'key'       =>  esc_attr( 'profile_banner' ),

                        'compare'   =>  '!=',

                        'value'     =>  ''
                    );

    /**
     *  Pricing (OR)
     *  ------------
     */
    if( SDWeddingDirectory_Loader:: _is_array( $selected_pricing ) ){

        $pricing_clause = [ 'relation' => 'OR' ];

        foreach( $selected_pricing as $value ){

            $pricing_clause[] = [

                'key'       =>  esc_attr( 'vendor_pricing' ),

                'compare'   =>  esc_attr( 'LIKE' ),

                'value'     =>  '"' . esc_attr( $value ) . '"'
            ];
        }

        $meta_query[] = $pricing_clause;
    }

    /**
     *  Services, Style, Specialties (AND)
     *  ----------------------------------
     */
    if( SDWeddingDirectory_Loader:: _is_array( $selected_services ) ){

        foreach( $selected_services as $value ){

            $meta_query[] = [

                'key'       =>  esc_attr( 'vendor_services' ),

                'compare'   =>  esc_attr( 'LIKE' ),

                'value'     =>  '"' . esc_attr( $value ) . '"'
            ];
        }
    }

    if( SDWeddingDirectory_Loader:: _is_array( $selected_style ) ){

        foreach( $selected_style as $value ){

            $meta_query[] = [

                'key'       =>  esc_attr( 'vendor_style' ),

                'compare'   =>  esc_attr( 'LIKE' ),

                'value'     =>  '"' . esc_attr( $value ) . '"'
            ];
        }
    }

    if( SDWeddingDirectory_Loader:: _is_array( $selected_specialties ) ){

        foreach( $selected_specialties as $value ){

            $meta_query[] = [

                'key'       =>  esc_attr( 'vendor_specialties' ),

                'compare'   =>  esc_attr( 'LIKE' ),

                'value'     =>  '"' . esc_attr( $value ) . '"'
            ];
        }
    }

    /**
     *  Vendor Query
     *  ------------
     */
    $query_args = [

        'post_type'         =>  esc_attr( 'vendor' ),

        'post_status'       =>  esc_attr( 'publish' ),

        'posts_per_page'    =>  absint( $posts_per_page ),

        'paged'             =>  absint( $paged ),

        'tax_query'         =>  [

                                    [
                                        'taxonomy'  =>  $taxonomy,
                                        'terms'     =>  $term_id,
                                    ]
                                ],
    ];

    if( SDWeddingDirectory_Loader:: _is_array( $meta_query ) ){

        $query_args['meta_query'] = array_merge( [ 'relation' => 'AND' ], $meta_query );
    }

    $query = new WP_Query( $query_args );

    /**
     *  Pagination Args
     *  ---------------
     */
    $pagination_args = array_filter( (array) $_GET, 'strlen' );

    unset( $pagination_args['paged'] );

    /**
     *  Filter UI + Results
     *  -------------------
     */
    ?>
    <div class="row">

        <div class="col-12 col-lg-3 sdweddingdirectory-vendor-sidebar">

            <form class="sdweddingdirectory-vendor-filters" method="get" data-term-id="<?php echo absint( $term_id ); ?>" data-taxonomy="<?php echo esc_attr( $taxonomy ); ?>">

                <?php
                /**
                 *  Pricing
                 *  -------
                 */
                $pricing_available = sdwd_get_term_field( 'vendor_pricing_available', $term_id );

                $pricing_options   = sdwd_get_term_repeater( 'vendor_pricing_options', $term_id, [ 'label', 'min', 'max' ] );

                $pricing_options   = $normalize_vendor_options( $pricing_options, 'pricing' );

                if( $pricing_available && SDWeddingDirectory_Loader:: _is_array( $pricing_options ) ){
                    ?>
                    <div class="find-venue-widget mt-2">
                        <div class="checkbox-section venue-filter-section">
                            <div class="head">
                                <strong><?php echo esc_html__( 'Pricing', 'sdweddingdirectory' ); ?></strong>
                                <a class="toggle" href="#vendor_pricing" role="button" aria-expanded="true" aria-controls="vendor_pricing" data-bs-toggle="collapse" data-bs-target="#vendor_pricing">
                                    <i class="fa fa-angle-down"></i>
                                </a>
                            </div>
                            <div class="collapse show" id="vendor_pricing">
                                <div class="checkbox-data venue-filter-data">
                                    <div class="row row-cols-1">
                                        <?php foreach( $pricing_options as $value => $label ){ ?>
                                            <div class="col">
                                                <div class="mb-3">
                                                    <input autocomplete="off" type="checkbox" class="form-check-input" name="vendor_pricing[]" value="<?php echo esc_attr( $value ); ?>" id="vendor_pricing_<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $selected_pricing, true ) ); ?> />
                                                    <label class="form-check-label" for="vendor_pricing_<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></label>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }

                /**
                 *  Services
                 *  --------
                 */
                $services_available = sdwd_get_term_field( 'vendor_services_available', $term_id );

                $services_options   = sdwd_get_term_repeater( 'vendor_services_options', $term_id );

                $services_options   = $normalize_vendor_options( $services_options, 'label_value' );

                if( $services_available && SDWeddingDirectory_Loader:: _is_array( $services_options ) ){
                    ?>
                    <div class="find-venue-widget mt-2">
                        <div class="checkbox-section venue-filter-section">
                            <div class="head">
                                <strong><?php echo esc_html__( 'Services', 'sdweddingdirectory' ); ?></strong>
                                <a class="toggle" href="#vendor_services" role="button" aria-expanded="true" aria-controls="vendor_services" data-bs-toggle="collapse" data-bs-target="#vendor_services">
                                    <i class="fa fa-angle-down"></i>
                                </a>
                            </div>
                            <div class="collapse show" id="vendor_services">
                                <div class="checkbox-data venue-filter-data">
                                    <div class="row row-cols-1">
                                        <?php foreach( $services_options as $value => $label ){ ?>
                                            <div class="col">
                                                <div class="mb-3">
                                                    <input autocomplete="off" type="checkbox" class="form-check-input" name="vendor_services[]" value="<?php echo esc_attr( $value ); ?>" id="vendor_services_<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $selected_services, true ) ); ?> />
                                                    <label class="form-check-label" for="vendor_services_<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></label>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }

                /**
                 *  Style
                 *  -----
                 */
                $style_available = sdwd_get_term_field( 'vendor_style_available', $term_id );

                $style_options   = sdwd_get_term_repeater( 'vendor_style_options', $term_id );

                $style_options   = $normalize_vendor_options( $style_options, 'label_value' );

                if( $style_available && SDWeddingDirectory_Loader:: _is_array( $style_options ) ){
                    ?>
                    <div class="find-venue-widget mt-2">
                        <div class="checkbox-section venue-filter-section">
                            <div class="head">
                                <strong><?php echo esc_html__( 'Style', 'sdweddingdirectory' ); ?></strong>
                                <a class="toggle" href="#vendor_style" role="button" aria-expanded="true" aria-controls="vendor_style" data-bs-toggle="collapse" data-bs-target="#vendor_style">
                                    <i class="fa fa-angle-down"></i>
                                </a>
                            </div>
                            <div class="collapse show" id="vendor_style">
                                <div class="checkbox-data venue-filter-data">
                                    <div class="row row-cols-1">
                                        <?php foreach( $style_options as $value => $label ){ ?>
                                            <div class="col">
                                                <div class="mb-3">
                                                    <input autocomplete="off" type="checkbox" class="form-check-input" name="vendor_style[]" value="<?php echo esc_attr( $value ); ?>" id="vendor_style_<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $selected_style, true ) ); ?> />
                                                    <label class="form-check-label" for="vendor_style_<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></label>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }

                /**
                 *  Specialties
                 *  -----------
                 */
                $specialties_available = sdwd_get_term_field( 'vendor_specialties_available', $term_id );

                $specialties_options   = sdwd_get_term_repeater( 'vendor_specialties_options', $term_id );

                $specialties_options   = $normalize_vendor_options( $specialties_options, 'label_value' );

                if( $specialties_available && SDWeddingDirectory_Loader:: _is_array( $specialties_options ) ){
                    ?>
                    <div class="find-venue-widget mt-2">
                        <div class="checkbox-section venue-filter-section">
                            <div class="head">
                                <strong><?php echo esc_html__( 'Specialties', 'sdweddingdirectory' ); ?></strong>
                                <a class="toggle" href="#vendor_specialties" role="button" aria-expanded="true" aria-controls="vendor_specialties" data-bs-toggle="collapse" data-bs-target="#vendor_specialties">
                                    <i class="fa fa-angle-down"></i>
                                </a>
                            </div>
                            <div class="collapse show" id="vendor_specialties">
                                <div class="checkbox-data venue-filter-data">
                                    <div class="row row-cols-1">
                                        <?php foreach( $specialties_options as $value => $label ){ ?>
                                            <div class="col">
                                                <div class="mb-3">
                                                    <input autocomplete="off" type="checkbox" class="form-check-input" name="vendor_specialties[]" value="<?php echo esc_attr( $value ); ?>" id="vendor_specialties_<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $selected_specialties, true ) ); ?> />
                                                    <label class="form-check-label" for="vendor_specialties_<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></label>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>

                <div class="d-grid gap-2 mt-3">
                    <a class="btn btn-link btn-sm sdweddingdirectory-clear-filters" href="<?php echo esc_url( get_term_link( absint( $term_id ), $taxonomy ) ); ?>"><?php echo esc_html__( 'Clear Filters', 'sdweddingdirectory' ); ?></a>
                </div>

            </form>

            <?php
            /**
             *  Featured DJ CTA — shown on high-intent vendor categories
             *  (bands, ceremony-music, djs, event-rentals, wedding-planners)
             *  to support lead generation for the founder's DJ business.
             */
            $dj_cta_categories = [ 'bands', 'ceremony-music', 'djs', 'event-rentals', 'wedding-planners', 'photo-booths' ];

            if( in_array( $category_slug, $dj_cta_categories, true ) ){

                $dj_link = esc_url( home_url( '/vendors/djs/' ) );

                ?>
                <div class="sdwd-featured-vendor-cta mt-4">
                    <a href="<?php echo $dj_link; ?>" class="sdwd-featured-vendor-cta__link">
                        <img loading="lazy" decoding="async" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/categories/djs.png' ) ); ?>" alt="<?php esc_attr_e( 'San Diego Wedding DJs', 'sdweddingdirectory' ); ?>" class="sdwd-featured-vendor-cta__img" />
                        <span class="sdwd-featured-vendor-cta__badge"><?php esc_html_e( 'Featured', 'sdweddingdirectory' ); ?></span>
                        <span class="sdwd-featured-vendor-cta__title"><?php esc_html_e( 'San Diego Wedding DJs', 'sdweddingdirectory' ); ?></span>
                        <span class="sdwd-featured-vendor-cta__desc"><?php esc_html_e( 'Keep your dance floor packed all night. Find top-rated DJs who handle your timeline, music, and announcements.', 'sdweddingdirectory' ); ?></span>
                    </a>
                </div>
                <?php
            }
            ?>
        </div>

        <div class="col-12 col-lg-9">
            <?php
            $vendor_cards = '';
            $vendor_pagination = '';
            $vendor_found = isset( $query ) ? absint( $query->found_posts ) : absint( '0' );

            if ( $query->have_posts() && class_exists( 'SDWeddingDirectory_Vendor' ) ){

                while ( $query->have_posts() ) {   $query->the_post();

                    $vendor_cards .= sprintf( '<div class="col-12">%1$s</div>',

                        apply_filters( 'sdweddingdirectory/vendor/post', [

                            'post_id'   =>  absint( get_the_ID() ),

                            'layout'    =>  absint( '3' )
                        ] )
                    );
                }

                if( absint( $query->max_num_pages ) >= absint( '2' ) ){

                    $vendor_pagination = apply_filters( 'sdweddingdirectory/pagination', [

                        'numpages'      =>  absint( $query->max_num_pages ),

                        'paged'         =>  absint( $paged ),

                        'add_args'      =>  $pagination_args
                    ] );
                }

                if( isset( $query ) ){

                    wp_reset_postdata();
                }

            }else{

                ob_start();

                do_action( 'sdweddingdirectory_empty_article' );

                $vendor_cards = ob_get_clean();
            }
            ?>
            <div class="d-lg-none mb-3">
                <button class="btn btn-primary w-100 sdweddingdirectory-filter-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#vendor-filter-offcanvas" aria-controls="vendor-filter-offcanvas">
                    <i class="fa fa-sliders me-1" aria-hidden="true"></i>
                    <span><?php echo esc_html__( 'Filters', 'sdweddingdirectory' ); ?></span>
                    <span class="sdweddingdirectory-filter-count badge bg-light text-dark ms-2 d-none" id="vendor-filter-count"></span>
                </button>
            </div>

            <div class="sdweddingdirectory-vendor-count mb-3">
                <p class="mb-0 text-uppercase fw-bold">
                    <span id="vendor_result_count"><?php echo esc_html( $vendor_found ); ?></span> <?php echo esc_html__( 'Results', 'sdweddingdirectory' ); ?>
                </p>
            </div>

            <div id="vendor_search_result" class="row row-cols-1">
                <?php echo $vendor_cards; ?>
            </div>

            <div id="vendor_have_pagination" class="row text-center">
                <?php echo $vendor_pagination; ?>
            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="vendor-filter-offcanvas" aria-labelledby="vendor-filter-offcanvas-label">
        <div class="offcanvas-header">
            <h3 class="offcanvas-title" id="vendor-filter-offcanvas-label"><?php echo esc_html__( 'Filters', 'sdweddingdirectory' ); ?></h3>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body"></div>
        <div class="offcanvas-footer border-top">
            <div class="offcanvas-body">
                <div class="d-grid gap-2 text-center">
                    <button type="button" class="btn btn-primary btn-lg fw-bold" id="vendor-filter-apply" data-bs-dismiss="offcanvas"><?php echo esc_html__( 'View Results', 'sdweddingdirectory' ); ?></button>
                    <button type="button" class="btn btn-link sdweddingdirectory-clear-filters"><?php echo esc_html__( 'Clear All Filters', 'sdweddingdirectory' ); ?></button>
                </div>
            </div>
        </div>
    </div>

    <?php
    $queried_term = get_queried_object();
    $category_name = ( ! empty( $queried_term ) && ! is_wp_error( $queried_term ) && ! empty( $queried_term->name ) )
                    ? $queried_term->name
                    : single_term_title( '', false );
    $category_slug = ( ! empty( $queried_term ) && ! is_wp_error( $queried_term ) && ! empty( $queried_term->slug ) )
                    ? sanitize_title( $queried_term->slug )
                    : '';

    $seo_intro_by_category = [
        'bands'             => 'Live wedding bands bring energy, personality, and a custom soundtrack to your celebration. In San Diego, couples can choose from jazz ensembles, party bands, and specialty groups that match the exact tone of the day.',
        'cakes'             => 'Your cake should look beautiful and taste even better. San Diego cake artists offer everything from clean modern designs to ornate multi-tier centerpieces with custom flavors and dietary options.',
        'catering'          => 'Great catering shapes the full guest experience, from cocktail hour through late-night bites. San Diego caterers can design menus around your style, service format, and budget priorities.',
        'ceremony-music'    => 'Ceremony music sets the emotional tone before the first guest is seated. San Diego ceremony musicians can perform classical, acoustic, or modern selections tailored to each moment.',
        'djs'               => 'The right wedding DJ does more than play songs. San Diego DJs help run your timeline, handle announcements, and keep your dance floor active from first dance to last call.',
        'dress-attire'      => 'Wedding attire should feel like you and photograph beautifully in every setting. San Diego bridal shops and formalwear providers offer options for classic, modern, and destination-style weddings.',
        'event-rentals'     => 'Event rentals define comfort, layout, and overall style. From lounge furniture to specialty tableware, San Diego rental teams can help you build a polished, functional event space.',
        'favors-gifts'      => 'Thoughtful favors and gifts add a personal finish to your celebration. San Diego vendors can help with custom packaging, locally inspired items, and practical guest takeaways.',
        'flowers'           => 'Florals bring color, texture, and seasonal character to your wedding design. San Diego florists can create bouquets, ceremony installs, and reception pieces that align with your vision and budget.',
        'hair-makeup'       => 'Professional hair and makeup help you look camera-ready all day and night. San Diego beauty teams provide trial sessions, timeline planning, and on-site services for you and your wedding party.',
        'invitations'       => 'Your invitation suite gives guests their first look at your wedding style. San Diego invitation designers offer print and digital options with custom wording, paper upgrades, and coordinated day-of stationery.',
        'jewelry'           => 'Wedding jewelry should feel timeless while reflecting your personal style. San Diego jewelers can assist with engagement upgrades, wedding bands, and custom heirloom-inspired pieces.',
        'lighting-decor'    => 'Lighting and decor transform a venue from basic to unforgettable. San Diego design teams can layer uplighting, statement decor, and textured elements to create an immersive atmosphere.',
        'officiants'        => 'A skilled officiant keeps your ceremony personal, clear, and meaningful. San Diego officiants can guide you through legal details and craft a ceremony script that feels authentic to your story.',
        'photo-booths'      => 'Photo booths create instant entertainment and shareable keepsakes for guests. San Diego providers offer modern booth styles, custom backdrops, and branded print or digital galleries.',
        'photography'       => 'Wedding photography is about preserving real moments with intention and consistency. San Diego photographers cover engagement sessions, wedding-day storytelling, and curated gallery delivery.',
        'transportation'    => 'Reliable transportation keeps your timeline running smoothly across multiple locations. San Diego transportation providers handle guest shuttles, wedding party travel, and late-night return logistics.',
        'travel-agents'     => 'Travel planning is easier with expert guidance, especially for honeymoons and destination events. San Diego travel specialists can manage bookings, upgrades, and itinerary details from start to finish.',
        'venues'            => 'Your venue sets the tone for every design and planning decision that follows. San Diego offers beachfront spaces, garden estates, ballrooms, and modern venues for nearly every guest count.',
        'videography'       => 'Wedding videography captures movement, audio, and emotional moments that photos alone cannot. San Diego videographers can deliver cinematic edits, documentary coverage, and social-ready highlight reels.',
        'wedding-planners'  => 'Wedding planners provide structure, clarity, and expert decision support through every phase. San Diego planning professionals can manage design direction, vendor coordination, and day-of execution.'
    ];

    $seo_intro = isset( $seo_intro_by_category[ $category_slug ] )
        ? $seo_intro_by_category[ $category_slug ]
        : sprintf(
            'San Diego %1$s vendors offer a wide range of styles, service levels, and pricing options. With the right shortlist, you can find professionals who fit your vision and planning priorities.',
            strtolower( wp_strip_all_tags( $category_name ) )
        );

    $seo_para_two = sprintf(
        'As you compare %1$s, focus on communication style, package details, and real wedding examples that match your priorities. Ask direct questions about availability, pricing structure, and how each team handles timeline changes.',
        wp_strip_all_tags( $category_name )
    );

    $seo_para_three = sprintf(
        'Use SDWeddingDirectory filters to narrow %1$s by services, specialties, style, and budget range. When you find strong matches, reach out early to confirm dates and build a vendor team that works seamlessly together.',
        strtolower( wp_strip_all_tags( $category_name ) )
    );

    $current_term_link = get_term_link( absint( $term_id ), $taxonomy );
    $current_term_link = is_wp_error( $current_term_link ) ? home_url( '/vendors/' ) : $current_term_link;

    $location_terms = get_terms( [
        'taxonomy'       =>  sanitize_key( 'venue-location' ),
        'hide_empty'     =>  false,
        'orderby'        =>  esc_attr( 'name' ),
        'order'          =>  esc_attr( 'ASC' ),
    ] );

    $location_terms = is_array( $location_terms ) ? $location_terms : [];

    $city_terms = array_filter( $location_terms, function( $location_term ){
        return isset( $location_term->parent ) && absint( $location_term->parent ) > absint( '0' );
    } );

    $vendor_category_terms = get_terms( [
        'taxonomy'       =>  sanitize_key( 'vendor-category' ),
        'hide_empty'     =>  false,
        'orderby'        =>  esc_attr( 'name' ),
        'order'          =>  esc_attr( 'ASC' ),
        'parent'         =>  absint( '0' ),
    ] );

    $vendor_category_terms = is_array( $vendor_category_terms ) ? $vendor_category_terms : [];
    ?>

    <section class="sd-vendor-seo-block mt-5">
        <div class="sd-vendor-seo-guide mb-5">
            <h2 class="mb-3">
                <?php
                printf(
                    esc_html__( 'Find the Best %1$s in San Diego', 'sdweddingdirectory' ),
                    esc_html( $category_name )
                );
                ?>
            </h2>
            <p><?php echo esc_html( $seo_intro ); ?></p>
            <p><?php echo esc_html( $seo_para_two ); ?></p>
            <p class="mb-0"><?php echo esc_html( $seo_para_three ); ?></p>
        </div>

        <div class="sd-local-vendors mb-5">
            <h3 class="mb-3">
                <?php
                printf(
                    esc_html__( 'Local %1$s', 'sdweddingdirectory' ),
                    esc_html( $category_name )
                );
                ?>
            </h3>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-2">
                <?php foreach( $city_terms as $city_term ){
                    $city_link = add_query_arg(
                        [
                            'location'  =>  sanitize_title( $city_term->slug ),
                        ],
                        $current_term_link
                    );
                    ?>
                    <div class="col">
                        <a href="<?php echo esc_url( $city_link ); ?>">
                            <?php echo esc_html( $city_term->name ); ?>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="sd-vendor-all-links">
            <h3 class="mb-3"><?php echo esc_html__( 'All San Diego Wedding Vendors', 'sdweddingdirectory' ); ?></h3>
            <p class="mb-0">
                <?php
                $all_vendor_links = [];

                foreach( $vendor_category_terms as $vendor_term ){

                    $vendor_term_link = get_term_link( $vendor_term );

                    if( is_wp_error( $vendor_term_link ) ){
                        continue;
                    }

                    $all_vendor_links[] = sprintf(
                        '<a href="%1$s">%2$s</a>',
                        esc_url( $vendor_term_link ),
                        esc_html( $vendor_term->name )
                    );
                }

                echo wp_kses_post( implode( ' <span class="sd-vendor-link-separator">&bull;</span> ', $all_vendor_links ) );
                ?>
            </p>
        </div>
    </section>
    <?php
}

/**
 *  Empty Article
 *  -------------
 */
else{

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
do_action( 'sdweddingdirectory_main_container_end' );
