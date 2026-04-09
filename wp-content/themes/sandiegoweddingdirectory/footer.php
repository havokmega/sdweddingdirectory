<?php
/**
 * SDWeddingDirectory v2 Footer
 */
?></main><!-- #content -->

<footer id="colophon" class="site-footer">
    <div class="footer__main">
        <div class="container footer__grid">
            <div class="footer__brand">
                <a class="footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo/sdweddingdirectorylogo.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                </a>
                <p><?php esc_html_e( "San Diego's wedding directory for venues, vendors, and inspiration.", 'sdweddingdirectory-v2' ); ?></p>
                <p class="mb-1"><a href="mailto:maildesk@sdweddingdirectory.com">maildesk@sdweddingdirectory.com</a></p>
                <p class="mb-0"><a href="tel:+16195551212">(619) 555-1212</a></p>
            </div>

            <div class="footer__widget">
                <h3 class="widget-title"><?php esc_html_e( 'Venue Types', 'sdweddingdirectory-v2' ); ?></h3>
                <ul class="footer__list">
                    <?php
                    foreach (
                        [
                            'outdoor-weddings'     => __( 'Outdoor Weddings', 'sdweddingdirectory-v2' ),
                            'beach-weddings'       => __( 'Beach Weddings', 'sdweddingdirectory-v2' ),
                            'garden-weddings'      => __( 'Garden Weddings', 'sdweddingdirectory-v2' ),
                            'barns-farms-weddings' => __( 'Barns & Farms', 'sdweddingdirectory-v2' ),
                        ] as $slug => $label
                    ) {
                        $term    = get_term_by( 'slug', $slug, 'venue-type' );
                        $term_id = ( $term && ! is_wp_error( $term ) ) ? absint( $term->term_id ) : 0;
                        $link    = $term_id ? add_query_arg( 'cat_id', $term_id, home_url( '/venues/' ) ) : home_url( '/venues/' );
                        printf(
                            '<li><a href="%1$s">%2$s</a></li>',
                            esc_url( $link ),
                            esc_html( $label )
                        );
                    }
                    ?>
                    <li><a href="<?php echo esc_url( home_url( '/venues/' ) ); ?>"><?php esc_html_e( 'See All Venue Types', 'sdweddingdirectory-v2' ); ?></a></li>
                </ul>
            </div>

            <div class="footer__widget">
                <h3 class="widget-title"><?php esc_html_e( 'Vendor Categories', 'sdweddingdirectory-v2' ); ?></h3>
                <ul class="footer__list">
                    <?php
                    foreach (
                        [
                            'photography'      => __( 'Photography', 'sdweddingdirectory-v2' ),
                            'wedding-planners' => __( 'Wedding Planning', 'sdweddingdirectory-v2' ),
                            'djs'              => __( 'DJs', 'sdweddingdirectory-v2' ),
                            'catering'         => __( 'Catering', 'sdweddingdirectory-v2' ),
                        ] as $slug => $label
                    ) {
                        $term = get_term_by( 'slug', $slug, 'vendor-category' );
                        $url  = $term && ! is_wp_error( $term ) ? get_term_link( $term, 'vendor-category' ) : home_url( '/vendors/' );
                        printf(
                            '<li><a href="%1$s">%2$s</a></li>',
                            esc_url( $url ),
                            esc_html( $label )
                        );
                    }
                    ?>
                    <li><a href="<?php echo esc_url( home_url( '/vendors/' ) ); ?>"><?php esc_html_e( 'See All Vendor Categories', 'sdweddingdirectory-v2' ); ?></a></li>
                </ul>
            </div>

            <div class="footer__widget">
                <h3 class="widget-title"><?php esc_html_e( 'Locations', 'sdweddingdirectory-v2' ); ?></h3>
                <ul class="footer__list">
                    <?php
                    foreach (
                        [
                            'san-diego' => __( 'San Diego', 'sdweddingdirectory-v2' ),
                            'carlsbad'  => __( 'Carlsbad', 'sdweddingdirectory-v2' ),
                            'la-mesa'   => __( 'La Mesa', 'sdweddingdirectory-v2' ),
                            'oceanside' => __( 'Oceanside', 'sdweddingdirectory-v2' ),
                        ] as $slug => $label
                    ) {
                        printf(
                            '<li><a href="%1$s">%2$s</a></li>',
                            esc_url( add_query_arg( 'location', sanitize_title( $slug ), home_url( '/venues/' ) ) ),
                            esc_html( $label )
                        );
                    }
                    ?>
                    <li><a href="<?php echo esc_url( home_url( '/venues/' ) ); ?>"><?php esc_html_e( 'See All Cities', 'sdweddingdirectory-v2' ); ?></a></li>
                </ul>
            </div>

            <div class="footer__widget">
                <h3 class="widget-title"><?php esc_html_e( 'Newsletter', 'sdweddingdirectory-v2' ); ?></h3>
                <p><?php esc_html_e( 'Subscribe to our newsletter to receive exclusive offers and wedding tips.', 'sdweddingdirectory-v2' ); ?></p>
                <form class="sdwdv2-footer-newsletter" action="javascript:;" method="post" onsubmit="return false;">
                    <label class="screen-reader-text" for="sdwdv2-footer-email"><?php esc_html_e( 'Enter Email Address', 'sdweddingdirectory-v2' ); ?></label>
                    <input id="sdwdv2-footer-email" type="email" placeholder="<?php esc_attr_e( 'Enter Email Address', 'sdweddingdirectory-v2' ); ?>" />
                </form>
            </div>
        </div>
    </div>

    <div class="footer__tiny">
        <div class="container footer__tiny-row">
            <div class="footer__copyright"><?php esc_html_e( 'SD Wedding Directory', 'sdweddingdirectory-v2' ); ?></div>
            <?php
            wp_nav_menu(
                [
                    'theme_location' => 'tiny-footer-menu',
                    'container'      => false,
                    'menu_class'     => 'footer__tiny-menu',
                    'fallback_cb'    => '__return_false',
                ]
            );
            ?>
            <?php if ( ! has_nav_menu( 'tiny-footer-menu' ) ) : ?>
                <ul class="footer__tiny-menu">
                    <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About', 'sdweddingdirectory-v2' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'sdweddingdirectory-v2' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/policy/' ) ); ?>"><?php esc_html_e( 'CA Privacy', 'sdweddingdirectory-v2' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'sdweddingdirectory-v2' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/policy/' ) ); ?>"><?php esc_html_e( 'Terms of Use', 'sdweddingdirectory-v2' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/faqs/' ) ); ?>"><?php esc_html_e( 'FAQs', 'sdweddingdirectory-v2' ); ?></a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <a class="back-to-top" href="#page" aria-label="Back to top">
        <span class="icon-arrow-up" aria-hidden="true"></span>
    </a>
</footer>

<?php if ( ! is_user_logged_in() ) : ?>
    <?php get_template_part( 'template-parts/modals/couple-login' ); ?>
    <?php get_template_part( 'template-parts/modals/couple-registration' ); ?>
    <?php get_template_part( 'template-parts/modals/vendor-login' ); ?>
    <?php get_template_part( 'template-parts/modals/vendor-registration' ); ?>
    <?php get_template_part( 'template-parts/modals/forgot-password' ); ?>
<?php endif; ?>

<?php wp_footer(); ?>
</div><!-- #page -->
</body>
</html>
