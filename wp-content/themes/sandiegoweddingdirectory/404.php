<?php
/**
 * 404 Page Template
 */

get_header();
?>

<div class="container section">
    <div class="error-404">
        <div class="error-404__graphic">
            <svg class="error-404__svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 160" width="200" height="160" aria-hidden="true">
                <circle cx="100" cy="80" r="70" fill="none" stroke="currentColor" stroke-width="2" opacity="0.2"/>
                <text x="100" y="95" text-anchor="middle" font-size="48" font-weight="700" fill="currentColor" opacity="0.3">404</text>
            </svg>
        </div>

        <h1 class="error-404__title"><?php esc_html_e( 'Page Not Found', 'sdweddingdirectory-v2' ); ?></h1>
        <p class="error-404__desc"><?php esc_html_e( 'Sorry, the page you are looking for does not exist or has been moved. Use the links below to find what you need.', 'sdweddingdirectory-v2' ); ?></p>

        <nav class="error-404__nav" aria-label="<?php esc_attr_e( 'Helpful links', 'sdweddingdirectory-v2' ); ?>">
            <a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'sdweddingdirectory-v2' ); ?></a>
            <a class="btn btn--outline" href="<?php echo esc_url( home_url( '/venues/' ) ); ?>"><?php esc_html_e( 'Venues', 'sdweddingdirectory-v2' ); ?></a>
            <a class="btn btn--outline" href="<?php echo esc_url( home_url( '/vendors/' ) ); ?>"><?php esc_html_e( 'Vendors', 'sdweddingdirectory-v2' ); ?></a>
            <a class="btn btn--outline" href="<?php echo esc_url( home_url( '/real-weddings/' ) ); ?>"><?php esc_html_e( 'Real Weddings', 'sdweddingdirectory-v2' ); ?></a>
        </nav>
    </div>
</div>

<?php
get_footer();
