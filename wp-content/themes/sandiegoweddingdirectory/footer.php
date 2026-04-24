<?php
/**
 * SDWeddingDirectory v2 Footer
 */
?></main><!-- #content -->

<footer id="colophon" class="site-footer">
    <div class="footer__main">
        <div class="container footer__grid">
            <div class="footer__widget">
                <h3 class="widget-title"><?php esc_html_e( 'San Diego Wedding Directory', 'sandiegoweddingdirectory' ); ?></h3>
                <ul class="footer__list">
                    <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About', 'sandiegoweddingdirectory' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'sandiegoweddingdirectory' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/policy/' ) ); ?>"><?php esc_html_e( 'CA Privacy', 'sandiegoweddingdirectory' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'sandiegoweddingdirectory' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/policy/' ) ); ?>"><?php esc_html_e( 'Terms of Use', 'sandiegoweddingdirectory' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/faqs/' ) ); ?>"><?php esc_html_e( 'FAQs', 'sandiegoweddingdirectory' ); ?></a></li>
                </ul>
            </div>

            <div class="footer__widget">
                <h3 class="widget-title"><?php esc_html_e( 'Planning Tools', 'sandiegoweddingdirectory' ); ?></h3>
                <ul class="footer__list">
                    <li><a href="<?php echo esc_url( home_url( '/wedding-planning/wedding-checklist/' ) ); ?>"><?php esc_html_e( 'Wedding Checklist', 'sandiegoweddingdirectory' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/wedding-planning/wedding-seating-chart/' ) ); ?>"><?php esc_html_e( 'Seating Chart', 'sandiegoweddingdirectory' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/wedding-planning/vendor-manager/' ) ); ?>"><?php esc_html_e( 'Vendor Manager', 'sandiegoweddingdirectory' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/wedding-planning/wedding-guest-list/' ) ); ?>"><?php esc_html_e( 'Guest List', 'sandiegoweddingdirectory' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/wedding-planning/wedding-budget/' ) ); ?>"><?php esc_html_e( 'Budget', 'sandiegoweddingdirectory' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/wedding-planning/wedding-website/' ) ); ?>"><?php esc_html_e( 'Wedding Website', 'sandiegoweddingdirectory' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/cost/' ) ); ?>"><?php esc_html_e( 'Wedding Cost Guides', 'sandiegoweddingdirectory' ); ?></a></li>
                </ul>
            </div>

            <div class="footer__widget">
                <h3 class="widget-title"><?php esc_html_e( 'Vendor Categories', 'sandiegoweddingdirectory' ); ?></h3>
                <ul class="footer__list">
                    <?php
                    foreach (
                        [
                            'photography'      => __( 'Photography', 'sandiegoweddingdirectory' ),
                            'wedding-planners' => __( 'Wedding Planning', 'sandiegoweddingdirectory' ),
                            'djs'              => __( 'DJs', 'sandiegoweddingdirectory' ),
                            'catering'         => __( 'Catering', 'sandiegoweddingdirectory' ),
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
                    <li><a href="<?php echo esc_url( home_url( '/vendors/' ) ); ?>"><?php esc_html_e( 'See All Vendor Categories', 'sandiegoweddingdirectory' ); ?></a></li>
                </ul>
            </div>

            <div class="footer__widget">
                <h3 class="widget-title"><?php esc_html_e( 'Locations', 'sandiegoweddingdirectory' ); ?></h3>
                <ul class="footer__list">
                    <?php
                    foreach (
                        [
                            'san-diego' => __( 'San Diego', 'sandiegoweddingdirectory' ),
                            'carlsbad'  => __( 'Carlsbad', 'sandiegoweddingdirectory' ),
                            'la-mesa'   => __( 'La Mesa', 'sandiegoweddingdirectory' ),
                            'oceanside' => __( 'Oceanside', 'sandiegoweddingdirectory' ),
                        ] as $slug => $label
                    ) {
                        printf(
                            '<li><a href="%1$s">%2$s</a></li>',
                            esc_url( add_query_arg( 'location', sanitize_title( $slug ), home_url( '/venues/' ) ) ),
                            esc_html( $label )
                        );
                    }
                    ?>
                    <li><a href="<?php echo esc_url( home_url( '/venues/' ) ); ?>"><?php esc_html_e( 'See All Cities', 'sandiegoweddingdirectory' ); ?></a></li>
                </ul>
            </div>

        </div>
    </div>

    <div class="container"><hr class="footer__divider"></div>

    <div class="footer__tiny">
        <div class="container footer__tiny-row">
            <a class="footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo/sdweddingdirectorylogo.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
            </a>
            <p class="footer__copyright"><?php esc_html_e( '© 2014 - 2026 San Diego Wedding Directory', 'sandiegoweddingdirectory' ); ?></p>
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
