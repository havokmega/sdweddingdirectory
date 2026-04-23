<?php
/**
 * 404 Page Template
 */

get_header();
?>

<div class="container section">
    <div class="error-404">
        <div class="error-404__graphic">
            <img class="error-404__svg" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/404-error-page/404_error.svg' ); ?>" alt="" aria-hidden="true" loading="lazy">
        </div>

        <h1 class="error-404__title"><?php esc_html_e( 'Page Not Found', 'sandiegoweddingdirectory' ); ?></h1>
        <p class="error-404__desc"><?php esc_html_e( 'Sorry, the page you are looking for does not exist or has been moved. Use the links below to find what you need.', 'sandiegoweddingdirectory' ); ?></p>

        <nav class="error-404__nav" aria-label="<?php esc_attr_e( 'Helpful links', 'sandiegoweddingdirectory' ); ?>">
            <a class="btn btn--outline" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'sandiegoweddingdirectory' ); ?></a>
            <a class="btn btn--outline" href="<?php echo esc_url( home_url( '/wedding-planning/' ) ); ?>"><?php esc_html_e( 'Planning Tools', 'sandiegoweddingdirectory' ); ?></a>
            <a class="btn btn--outline" href="<?php echo esc_url( home_url( '/venues/' ) ); ?>"><?php esc_html_e( 'Venues', 'sandiegoweddingdirectory' ); ?></a>
            <a class="btn btn--outline" href="<?php echo esc_url( home_url( '/vendors/' ) ); ?>"><?php esc_html_e( 'Vendors', 'sandiegoweddingdirectory' ); ?></a>
            <a class="btn btn--outline" href="<?php echo esc_url( home_url( '/real-weddings/' ) ); ?>"><?php esc_html_e( 'Real Weddings', 'sandiegoweddingdirectory' ); ?></a>
        </nav>
    </div>
</div>

<?php
get_footer();
