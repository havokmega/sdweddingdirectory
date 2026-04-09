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

// Taxonomy.
$categories = wp_get_post_terms( $post_id, 'vendor-category', [ 'fields' => 'names' ] );
$category   = ( ! is_wp_error( $categories ) && ! empty( $categories ) ) ? $categories[0] : '';

// Gallery images (featured image + any attached images).
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

// Starting price from first pricing tier.
$starting_price = '';
if ( ! empty( $pricing[0]['price'] ) ) {
    $starting_price = $pricing[0]['price'];
}
?>

<div id="content" class="site-content">

    <?php // --- Photo Collage --- ?>
    <?php if ( ! empty( $images ) ) : ?>
        <div class="photo-collage">
            <?php foreach ( array_slice( $images, 0, 5 ) as $img_id ) : ?>
                <div class="photo-collage__item">
                    <?php echo wp_get_attachment_image( $img_id, 'large' ); ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php // --- Profile Header --- ?>
    <div class="vendor-profile-head">
        <div class="container">
            <?php
            $breadcrumb_items = [
                [ 'label' => __( 'Home', 'sdweddingdirectory' ), 'url' => home_url( '/' ) ],
                [ 'label' => __( 'Vendors', 'sdweddingdirectory' ), 'url' => get_post_type_archive_link( 'vendor' ) ],
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
                        <?php if ( $starting_price ) : ?>
                            <span><?php echo esc_html( $starting_price ); ?></span>
                        <?php endif; ?>
                        <?php if ( $phone ) : ?>
                            <span><?php echo esc_html( $phone ); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if ( has_excerpt() ) : ?>
                        <p class="vendor-profile-head__summary"><?php echo esc_html( get_the_excerpt() ); ?></p>
                    <?php endif; ?>
                </div>
                <div class="vendor-profile-head__actions">
                    <?php if ( $email ) : ?>
                        <a class="btn btn--primary" href="mailto:<?php echo esc_attr( $email ); ?>"><?php esc_html_e( 'Request Pricing', 'sdweddingdirectory' ); ?></a>
                    <?php endif; ?>
                    <?php if ( $phone ) : ?>
                        <a class="btn btn--outline" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>">
                            <span class="icon-phone" aria-hidden="true"></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php // --- Sticky Section Nav --- ?>
    <nav class="profile-nav" id="profile-nav">
        <div class="container">
            <ul class="profile-nav__list">
                <li><a class="profile-nav__link" href="#about"><?php esc_html_e( 'About', 'sdweddingdirectory' ); ?></a></li>
                <?php if ( ! empty( $pricing ) ) : ?>
                    <li><a class="profile-nav__link" href="#pricing"><?php esc_html_e( 'Pricing', 'sdweddingdirectory' ); ?></a></li>
                <?php endif; ?>
                <?php if ( ! empty( $hours ) ) : ?>
                    <li><a class="profile-nav__link" href="#hours"><?php esc_html_e( 'Hours', 'sdweddingdirectory' ); ?></a></li>
                <?php endif; ?>
                <?php if ( ! empty( $social ) ) : ?>
                    <li><a class="profile-nav__link" href="#social"><?php esc_html_e( 'Social', 'sdweddingdirectory' ); ?></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <?php // --- Profile Layout (main + sidebar) --- ?>
    <div class="container">
        <div class="profile-layout">
            <div class="profile-main">

                <?php // --- About Section --- ?>
                <section class="profile-section" id="about">
                    <h2 class="profile-section__title"><?php printf( esc_html__( 'About %s', 'sdweddingdirectory' ), esc_html( $company ) ); ?></h2>
                    <div class="vendor-profile-copy">
                        <?php the_content(); ?>
                    </div>

                    <?php if ( $category ) : ?>
                        <div class="vendor-profile-tags">
                            <h3 class="vendor-profile-tags__title"><?php esc_html_e( 'Category', 'sdweddingdirectory' ); ?></h3>
                            <div class="vendor-profile-tags__items">
                                <?php foreach ( $categories as $cat_name ) : ?>
                                    <span class="vendor-profile-tags__item"><?php echo esc_html( $cat_name ); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </section>

                <?php // --- Pricing Section --- ?>
                <?php if ( ! empty( $pricing ) ) : ?>
                    <section class="profile-section" id="pricing">
                        <h2 class="profile-section__title"><?php esc_html_e( 'Pricing', 'sdweddingdirectory' ); ?></h2>
                        <div class="vendor-pricing-grid">
                            <?php foreach ( $pricing as $tier ) : ?>
                                <div class="vendor-pricing-card">
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
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <?php // --- Business Hours Section --- ?>
                <?php if ( ! empty( $hours ) ) : ?>
                    <section class="profile-section" id="hours">
                        <h2 class="profile-section__title"><?php esc_html_e( 'Business Hours', 'sdweddingdirectory' ); ?></h2>
                        <div class="vendor-profile-facts">
                            <?php
                            $day_labels = [
                                'monday'    => __( 'Monday', 'sdweddingdirectory' ),
                                'tuesday'   => __( 'Tuesday', 'sdweddingdirectory' ),
                                'wednesday' => __( 'Wednesday', 'sdweddingdirectory' ),
                                'thursday'  => __( 'Thursday', 'sdweddingdirectory' ),
                                'friday'    => __( 'Friday', 'sdweddingdirectory' ),
                                'saturday'  => __( 'Saturday', 'sdweddingdirectory' ),
                                'sunday'    => __( 'Sunday', 'sdweddingdirectory' ),
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
                                        <?php esc_html_e( 'Closed', 'sdweddingdirectory' ); ?>
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

                <?php // --- Social Media Section --- ?>
                <?php
                $social_filtered = array_filter( $social, function( $row ) {
                    return ! empty( $row['label'] ) || ! empty( $row['url'] );
                } );
                ?>
                <?php if ( ! empty( $social_filtered ) ) : ?>
                    <section class="profile-section" id="social">
                        <h2 class="profile-section__title"><?php esc_html_e( 'Social Media', 'sdweddingdirectory' ); ?></h2>
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

                <?php // --- Gallery Section --- ?>
                <?php if ( count( $images ) > 1 ) : ?>
                    <section class="profile-section" id="gallery">
                        <h2 class="profile-section__title"><?php esc_html_e( 'Photos', 'sdweddingdirectory' ); ?></h2>
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

            <?php // --- Sidebar --- ?>
            <aside class="profile-sidebar">
                <div class="contact-card">
                    <?php if ( $starting_price ) : ?>
                        <div class="contact-card__price"><?php echo esc_html( $starting_price ); ?></div>
                    <?php endif; ?>

                    <?php if ( $email ) : ?>
                        <a class="btn btn--primary contact-card__cta" href="mailto:<?php echo esc_attr( $email ); ?>"><?php esc_html_e( 'Request Pricing', 'sdweddingdirectory' ); ?></a>
                    <?php endif; ?>

                    <ul class="contact-card__details">
                        <?php if ( $phone ) : ?>
                            <li>
                                <span class="icon-phone" aria-hidden="true"></span>
                                <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if ( $email ) : ?>
                            <li>
                                <span class="icon-mail" aria-hidden="true"></span>
                                <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if ( $website ) : ?>
                            <li>
                                <span class="icon-link" aria-hidden="true"></span>
                                <a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Visit Website', 'sdweddingdirectory' ); ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if ( $category ) : ?>
                            <li>
                                <span class="icon-tag" aria-hidden="true"></span>
                                <span><?php echo esc_html( $category ); ?></span>
                            </li>
                        <?php endif; ?>
                    </ul>

                    <?php if ( function_exists( 'sdwd_is_unclaimed' ) && sdwd_is_unclaimed( $post_id ) ) : ?>
                        <hr style="margin: 16px 0;">
                        <button type="button" class="btn btn--outline contact-card__cta" id="sdwd-claim-btn" data-post-id="<?php echo $post_id; ?>"><?php esc_html_e( 'Claim This Business', 'sdweddingdirectory' ); ?></button>
                        <div id="sdwd-claim-form" style="display:none; margin-top:12px;">
                            <textarea id="sdwd-claim-msg" rows="3" placeholder="<?php esc_attr_e( 'Tell us how you are connected to this business...', 'sdweddingdirectory' ); ?>" style="width:100%; margin-bottom:8px;"></textarea>
                            <button type="button" class="btn btn--primary contact-card__cta" id="sdwd-claim-submit"><?php esc_html_e( 'Submit Claim', 'sdweddingdirectory' ); ?></button>
                            <p id="sdwd-claim-status" style="font-size:0.9rem; margin-top:8px;"></p>
                        </div>
                        <script>
                        document.getElementById('sdwd-claim-btn').addEventListener('click', function() {
                            this.style.display = 'none';
                            document.getElementById('sdwd-claim-form').style.display = 'block';
                        });
                        document.getElementById('sdwd-claim-submit').addEventListener('click', function() {
                            var data = new FormData();
                            data.append('action', 'sdwd_submit_claim');
                            data.append('nonce', '<?php echo wp_create_nonce( 'sdwd_claim_nonce' ); ?>');
                            data.append('post_id', '<?php echo $post_id; ?>');
                            data.append('message', document.getElementById('sdwd-claim-msg').value);
                            fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', { method: 'POST', body: data })
                                .then(function(r) { return r.json(); })
                                .then(function(res) {
                                    document.getElementById('sdwd-claim-status').textContent = res.data.message;
                                    if (res.success) {
                                        document.getElementById('sdwd-claim-submit').disabled = true;
                                    }
                                });
                        });
                        </script>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
    </div>

</div>

<?php get_footer(); ?>
