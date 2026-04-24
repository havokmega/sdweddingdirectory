<?php
/**
 * Single Vendor Profile
 */

get_header();

$post_id   = get_the_ID();
$company   = get_post_meta( $post_id, 'sdwd_company_name', true ) ?: get_the_title();
$email     = get_post_meta( $post_id, 'sdwd_email', true );
$phone     = get_post_meta( $post_id, 'sdwd_phone', true );
$website   = get_post_meta( $post_id, 'sdwd_company_website', true );
$social    = get_post_meta( $post_id, 'sdwd_social', true );
$hours     = get_post_meta( $post_id, 'sdwd_hours', true );
$pricing   = get_post_meta( $post_id, 'sdwd_pricing', true );

if ( ! is_array( $social ) )  { $social  = []; }
if ( ! is_array( $hours ) )   { $hours   = []; }
if ( ! is_array( $pricing ) ) { $pricing = []; }

$categories = wp_get_post_terms( $post_id, 'vendor-category', [ 'fields' => 'names' ] );
$category   = ( ! is_wp_error( $categories ) && ! empty( $categories ) ) ? $categories[0] : '';

$images    = [];
$thumb_id  = get_post_thumbnail_id( $post_id );
if ( $thumb_id ) {
    $images[] = $thumb_id;
}
$attached = get_posts( [
    'post_parent'    => $post_id,
    'post_type'      => 'attachment',
    'post_mime_type' => 'image',
    'posts_per_page' => 10,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'fields'         => 'ids',
    'exclude'        => $thumb_id ? [ $thumb_id ] : [],
] );
$images = array_merge( $images, $attached );

$starting_price = '';
if ( ! empty( $pricing[0]['price'] ) ) {
    $starting_price = $pricing[0]['price'];
}

$quote_nonce = wp_create_nonce( 'sdwd_quote_nonce' );
$tel_href    = $phone ? 'tel:' . preg_replace( '/[^0-9+]/', '', $phone ) : '';
?>

<div id="content" class="site-content vendor-profile">

    <?php // --- Sticky Top Bar (hidden until scrolled) --- ?>
    <div class="profile-topbar" id="profile-topbar" aria-hidden="true">
        <div class="container profile-topbar__inner">
            <div class="profile-topbar__title"><?php echo esc_html( $company ); ?></div>
            <nav class="profile-topbar__nav">
                <ul class="profile-nav__list">
                    <li><a class="profile-nav__link" href="#about"><?php esc_html_e( 'About', 'sandiegoweddingdirectory' ); ?></a></li>
                    <?php if ( ! empty( $pricing ) ) : ?>
                        <li><a class="profile-nav__link" href="#pricing"><?php esc_html_e( 'Pricing', 'sandiegoweddingdirectory' ); ?></a></li>
                    <?php endif; ?>
                    <?php if ( ! empty( $hours ) ) : ?>
                        <li><a class="profile-nav__link" href="#hours"><?php esc_html_e( 'Hours', 'sandiegoweddingdirectory' ); ?></a></li>
                    <?php endif; ?>
                    <li><a class="profile-nav__link" href="#quote"><?php esc_html_e( 'Contact', 'sandiegoweddingdirectory' ); ?></a></li>
                </ul>
            </nav>
            <a class="btn btn--primary profile-topbar__cta" href="#quote"><?php esc_html_e( 'Request Pricing', 'sandiegoweddingdirectory' ); ?></a>
        </div>
    </div>

    <?php // --- Photo Collage --- ?>
    <?php if ( ! empty( $images ) ) : ?>
        <div class="photo-collage" id="photo-collage">
            <?php foreach ( array_slice( $images, 0, 5 ) as $i => $img_id ) : ?>
                <div class="photo-collage__item">
                    <?php echo wp_get_attachment_image( $img_id, 'large' ); ?>
                    <?php if ( $i === 4 && count( $images ) > 5 ) : ?>
                        <span class="photo-collage__more"><?php esc_html_e( 'See all photos', 'sandiegoweddingdirectory' ); ?></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php // --- Breadcrumbs + Header Band --- ?>
    <div class="vendor-profile-head">
        <div class="container">
            <?php
            $breadcrumb_items = [
                [ 'label' => __( 'Home', 'sandiegoweddingdirectory' ), 'url' => home_url( '/' ) ],
                [ 'label' => __( 'Vendors', 'sandiegoweddingdirectory' ), 'url' => get_post_type_archive_link( 'vendor' ) ],
            ];
            if ( $category ) {
                $cat_terms = wp_get_post_terms( $post_id, 'vendor-category' );
                if ( ! is_wp_error( $cat_terms ) && ! empty( $cat_terms ) ) {
                    $breadcrumb_items[] = [
                        'label' => $cat_terms[0]->name,
                        'url'   => get_term_link( $cat_terms[0] ),
                    ];
                }
            }
            $breadcrumb_items[] = [ 'label' => $company ];

            get_template_part( 'template-parts/components/breadcrumbs', null, [
                'items' => $breadcrumb_items,
            ] );
            ?>

            <div class="vendor-profile-head__header">
                <div>
                    <?php if ( $category ) : ?>
                        <p class="vendor-profile-head__eyebrow"><?php echo esc_html( $category ); ?></p>
                    <?php endif; ?>
                    <h1 class="vendor-profile-head__title"><?php echo esc_html( $company ); ?></h1>
                    <div class="vendor-profile-head__meta">
                        <span class="vendor-profile-head__rating"><?php esc_html_e( 'New on SDWD', 'sandiegoweddingdirectory' ); ?></span>
                        <?php if ( $starting_price ) : ?>
                            <span><?php echo esc_html( $starting_price ); ?></span>
                        <?php endif; ?>
                        <?php if ( $phone ) : ?>
                            <span><?php echo esc_html( $phone ); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php // --- Sticky Section Nav --- ?>
    <nav class="profile-nav" id="profile-nav">
        <div class="container">
            <ul class="profile-nav__list">
                <li><a class="profile-nav__link" href="#about"><?php esc_html_e( 'About', 'sandiegoweddingdirectory' ); ?></a></li>
                <?php if ( ! empty( $pricing ) ) : ?>
                    <li><a class="profile-nav__link" href="#pricing"><?php esc_html_e( 'Pricing', 'sandiegoweddingdirectory' ); ?></a></li>
                <?php endif; ?>
                <?php if ( ! empty( $hours ) ) : ?>
                    <li><a class="profile-nav__link" href="#hours"><?php esc_html_e( 'Hours', 'sandiegoweddingdirectory' ); ?></a></li>
                <?php endif; ?>
                <?php if ( ! empty( $social ) ) : ?>
                    <li><a class="profile-nav__link" href="#social"><?php esc_html_e( 'Social', 'sandiegoweddingdirectory' ); ?></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <?php // --- Profile Layout (main + sidebar) --- ?>
    <div class="container">
        <div class="profile-layout">
            <div class="profile-main">

                <?php // --- About --- ?>
                <section class="profile-section" id="about">
                    <h2 class="profile-section__title"><?php printf( esc_html__( 'About %s', 'sandiegoweddingdirectory' ), esc_html( $company ) ); ?></h2>
                    <div class="vendor-profile-copy">
                        <?php the_content(); ?>
                    </div>

                    <?php if ( $category ) : ?>
                        <div class="vendor-profile-tags">
                            <h3 class="vendor-profile-tags__title"><?php esc_html_e( 'Category', 'sandiegoweddingdirectory' ); ?></h3>
                            <div class="vendor-profile-tags__items">
                                <?php foreach ( $categories as $cat_name ) : ?>
                                    <span class="vendor-profile-tags__item"><?php echo esc_html( $cat_name ); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </section>

                <?php // --- Pricing --- ?>
                <?php if ( ! empty( $pricing ) ) : ?>
                    <section class="profile-section" id="pricing">
                        <div class="profile-section__head">
                            <h2 class="profile-section__title"><?php esc_html_e( 'Pricing', 'sandiegoweddingdirectory' ); ?></h2>
                            <?php if ( $starting_price ) : ?>
                                <p class="profile-section__sub"><?php printf( esc_html__( 'Starting at %s', 'sandiegoweddingdirectory' ), '<strong>' . esc_html( $starting_price ) . '</strong>' ); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php
                        $tier_count    = count( $pricing );
                        $featured_idx  = $tier_count >= 3 ? 1 : -1;
                        ?>
                        <div class="vendor-pricing-grid vendor-pricing-grid--<?php echo (int) $tier_count; ?>">
                            <?php foreach ( $pricing as $idx => $tier ) : ?>
                                <?php $is_featured = ( $idx === $featured_idx ); ?>
                                <div class="vendor-pricing-card<?php echo $is_featured ? ' vendor-pricing-card--featured' : ''; ?>">
                                    <?php if ( $is_featured ) : ?>
                                        <span class="vendor-pricing-card__badge"><?php esc_html_e( 'Most Popular', 'sandiegoweddingdirectory' ); ?></span>
                                    <?php endif; ?>
                                    <?php if ( ! empty( $tier['name'] ) ) : ?>
                                        <h3 class="vendor-pricing-card__title"><?php echo esc_html( $tier['name'] ); ?></h3>
                                    <?php endif; ?>
                                    <?php if ( ! empty( $tier['price'] ) ) : ?>
                                        <div class="vendor-pricing-card__price"><?php echo esc_html( $tier['price'] ); ?></div>
                                    <?php endif; ?>
                                    <?php if ( ! empty( $tier['features'] ) ) : ?>
                                        <ul class="vendor-pricing-card__items">
                                            <?php foreach ( $tier['features'] as $feature ) : ?>
                                                <li>
                                                    <span class="icon-check" aria-hidden="true"></span>
                                                    <span><?php echo esc_html( $feature ); ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                    <a class="btn <?php echo $is_featured ? 'btn--primary' : 'btn--outline'; ?> vendor-pricing-card__cta" href="#quote"><?php esc_html_e( 'Request Pricing', 'sandiegoweddingdirectory' ); ?></a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <?php // --- Hours --- ?>
                <?php if ( ! empty( $hours ) ) : ?>
                    <section class="profile-section" id="hours">
                        <h2 class="profile-section__title"><?php esc_html_e( 'Business Hours', 'sandiegoweddingdirectory' ); ?></h2>
                        <div class="vendor-profile-facts">
                            <?php
                            $day_labels = [
                                'monday'    => __( 'Monday', 'sandiegoweddingdirectory' ),
                                'tuesday'   => __( 'Tuesday', 'sandiegoweddingdirectory' ),
                                'wednesday' => __( 'Wednesday', 'sandiegoweddingdirectory' ),
                                'thursday'  => __( 'Thursday', 'sandiegoweddingdirectory' ),
                                'friday'    => __( 'Friday', 'sandiegoweddingdirectory' ),
                                'saturday'  => __( 'Saturday', 'sandiegoweddingdirectory' ),
                                'sunday'    => __( 'Sunday', 'sandiegoweddingdirectory' ),
                            ];
                            foreach ( $day_labels as $day_key => $day_label ) :
                                $day_data = $hours[ $day_key ] ?? [];
                                $is_closed = ! empty( $day_data['closed'] );
                                $open  = $day_data['open'] ?? '';
                                $close = $day_data['close'] ?? '';
                            ?>
                                <div class="vendor-profile-facts__item">
                                    <span class="vendor-profile-facts__label"><?php echo esc_html( $day_label ); ?></span>
                                    <?php if ( $is_closed ) : ?>
                                        <?php esc_html_e( 'Closed', 'sandiegoweddingdirectory' ); ?>
                                    <?php elseif ( $open && $close ) : ?>
                                        <?php echo esc_html( $open . ' – ' . $close ); ?>
                                    <?php else : ?>
                                        &mdash;
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <?php // --- Social --- ?>
                <?php
                $social_filtered = array_filter( $social, function( $row ) {
                    return ! empty( $row['label'] ) || ! empty( $row['url'] );
                } );
                ?>
                <?php if ( ! empty( $social_filtered ) ) : ?>
                    <section class="profile-section" id="social">
                        <h2 class="profile-section__title"><?php esc_html_e( 'Social Media', 'sandiegoweddingdirectory' ); ?></h2>
                        <div class="vendor-profile-tags__items">
                            <?php foreach ( $social_filtered as $link ) : ?>
                                <?php if ( ! empty( $link['url'] ) ) : ?>
                                    <a class="vendor-profile-tags__item" href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener noreferrer">
                                        <?php echo esc_html( $link['label'] ?: $link['url'] ); ?>
                                    </a>
                                <?php else : ?>
                                    <span class="vendor-profile-tags__item"><?php echo esc_html( $link['label'] ); ?></span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <?php // --- Gallery --- ?>
                <?php if ( count( $images ) > 1 ) : ?>
                    <section class="profile-section" id="gallery">
                        <h2 class="profile-section__title"><?php esc_html_e( 'Photos', 'sandiegoweddingdirectory' ); ?></h2>
                        <div class="gallery-grid">
                            <?php foreach ( $images as $img_id ) : ?>
                                <div class="gallery-grid__item">
                                    <?php echo wp_get_attachment_image( $img_id, 'medium', false, [ 'loading' => 'lazy' ] ); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

            </div>

            <?php // --- Sidebar (two states) --- ?>
            <aside class="profile-sidebar" id="quote">

                <?php // State 1: short card (above-fold) ?>
                <div class="quote-card quote-card--short">
                    <div class="quote-card__head">
                        <div class="quote-card__name"><?php echo esc_html( $company ); ?></div>
                        <?php if ( $category ) : ?>
                            <div class="quote-card__cat"><?php echo esc_html( $category ); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="quote-card__body">
                        <?php if ( $starting_price ) : ?>
                            <div class="quote-card__price"><?php echo esc_html( $starting_price ); ?><span><?php esc_html_e( ' starting', 'sandiegoweddingdirectory' ); ?></span></div>
                        <?php endif; ?>
                        <button type="button" class="btn btn--primary quote-card__cta" data-quote-open><?php esc_html_e( 'Request Pricing', 'sandiegoweddingdirectory' ); ?></button>
                        <?php if ( $phone ) : ?>
                            <a class="quote-card__phone" href="<?php echo esc_attr( $tel_href ); ?>"><span class="icon-phone" aria-hidden="true"></span><?php echo esc_html( $phone ); ?></a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php // State 2: full message form ?>
                <form class="quote-card quote-card--full" id="sdwd-quote-form" novalidate>
                    <h3 class="quote-card__title"><?php esc_html_e( 'Message Supplier', 'sandiegoweddingdirectory' ); ?></h3>
                    <p class="quote-card__lead"><?php printf( esc_html__( 'Send a message to %s', 'sandiegoweddingdirectory' ), esc_html( $company ) ); ?></p>

                    <input type="hidden" name="action" value="sdwd_send_quote">
                    <input type="hidden" name="nonce" value="<?php echo esc_attr( $quote_nonce ); ?>">
                    <input type="hidden" name="post_id" value="<?php echo (int) $post_id; ?>">

                    <div class="quote-field">
                        <label for="sdwd-q-name"><?php esc_html_e( 'Your Name', 'sandiegoweddingdirectory' ); ?></label>
                        <input type="text" id="sdwd-q-name" name="name" required>
                    </div>
                    <div class="quote-field">
                        <label for="sdwd-q-email"><?php esc_html_e( 'Email', 'sandiegoweddingdirectory' ); ?></label>
                        <input type="email" id="sdwd-q-email" name="email" required>
                    </div>
                    <div class="quote-field">
                        <label for="sdwd-q-phone"><?php esc_html_e( 'Phone', 'sandiegoweddingdirectory' ); ?></label>
                        <input type="tel" id="sdwd-q-phone" name="phone">
                    </div>
                    <div class="quote-row">
                        <div class="quote-field">
                            <label for="sdwd-q-date"><?php esc_html_e( 'Wedding Date', 'sandiegoweddingdirectory' ); ?></label>
                            <input type="date" id="sdwd-q-date" name="wedding_date">
                        </div>
                        <div class="quote-field">
                            <label for="sdwd-q-guests"><?php esc_html_e( 'Guests', 'sandiegoweddingdirectory' ); ?></label>
                            <input type="number" id="sdwd-q-guests" name="guests" min="1">
                        </div>
                    </div>
                    <div class="quote-field">
                        <label for="sdwd-q-msg"><?php esc_html_e( 'Message', 'sandiegoweddingdirectory' ); ?></label>
                        <textarea id="sdwd-q-msg" name="message" rows="4" required></textarea>
                    </div>

                    <button type="submit" class="btn btn--primary quote-card__cta"><?php esc_html_e( 'Request Pricing', 'sandiegoweddingdirectory' ); ?></button>
                    <p class="quote-card__status" data-quote-status aria-live="polite"></p>
                </form>

                <?php if ( function_exists( 'sdwd_is_unclaimed' ) && sdwd_is_unclaimed( $post_id ) ) : ?>
                    <div class="quote-card quote-card--claim">
                        <button type="button" class="btn btn--outline contact-card__cta" id="sdwd-claim-btn" data-post-id="<?php echo (int) $post_id; ?>"><?php esc_html_e( 'Claim This Business', 'sandiegoweddingdirectory' ); ?></button>
                        <div id="sdwd-claim-form" hidden>
                            <textarea id="sdwd-claim-msg" rows="3" placeholder="<?php esc_attr_e( 'Tell us how you are connected to this business...', 'sandiegoweddingdirectory' ); ?>"></textarea>
                            <button type="button" class="btn btn--primary contact-card__cta" id="sdwd-claim-submit"><?php esc_html_e( 'Submit Claim', 'sandiegoweddingdirectory' ); ?></button>
                            <p id="sdwd-claim-status"></p>
                        </div>
                    </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>

</div>

<?php get_footer(); ?>
