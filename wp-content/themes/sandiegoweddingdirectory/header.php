<?php
/**
 * SDWeddingDirectory v2 Header
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'sandiegoweddingdirectory' ); ?></a>

<header id="masthead" class="site-header header-version-two">
    <div class="header-version-two__bar">
        <div class="container header__row">
            <div class="header__brand">
                <a class="header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo/sdweddingdirectorylogo.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                </a>
            </div>

            <button class="header__toggle" type="button" aria-expanded="false" aria-controls="site-navigation" data-nav-toggle>
                <span class="screen-reader-text"><?php esc_html_e( 'Toggle navigation', 'sandiegoweddingdirectory' ); ?></span>
                <span class="header__toggle-bar"></span>
                <span class="header__toggle-bar"></span>
                <span class="header__toggle-bar"></span>
            </button>

            <nav id="site-navigation" class="header__nav header-nav" aria-label="<?php esc_attr_e( 'Primary Menu', 'sandiegoweddingdirectory' ); ?>" data-nav-panel>
                <?php
                wp_nav_menu(
                    [
                        'theme_location' => 'primary-menu',
                        'menu_id'        => 'primary-menu',
                        'menu_class'     => 'nav header__menu header-nav__list',
                        'container'      => false,
                        'fallback_cb'    => [ 'SDSDWeddingDirectoryectory_Navwalker', 'fallback' ],
                        'walker'         => new SDSDWeddingDirectoryectory_Navwalker(),
                    ]
                );
                ?>
            </nav>

            <div class="header__actions">
                <?php
                $user      = is_user_logged_in() ? wp_get_current_user() : null;
                $dashboard = '';
                if ( $user ) {
                    if ( in_array( 'vendor', $user->roles, true ) ) {
                        $dashboard = home_url( '/vendor-dashboard/' );
                    } elseif ( in_array( 'venue', $user->roles, true ) ) {
                        $dashboard = home_url( '/venue-dashboard/' );
                    } elseif ( in_array( 'couple', $user->roles, true ) ) {
                        $dashboard = home_url( '/couple-dashboard/' );
                    }
                }
                ?>
                <?php if ( $dashboard ) : ?>
                    <a class="btn btn--dashboard sd-header-btn-full" href="<?php echo esc_url( $dashboard ); ?>">
                        <span class="header__btn-icon icon-dashboard" aria-hidden="true"></span>
                        <?php esc_html_e( 'My Dashboard', 'sandiegoweddingdirectory' ); ?>
                    </a>
                <?php else : ?>
                    <a class="btn btn--primary sd-header-btn-full" href="javascript:" data-sdwd-modal-open="couple-register">
                        <span class="header__btn-icon icon-user-o" aria-hidden="true"></span>
                        <?php esc_html_e( 'Join as a Couple', 'sandiegoweddingdirectory' ); ?>
                    </a>
                    <a class="btn btn--vendor sd-header-btn-full" href="javascript:" data-sdwd-modal-open="vendor-register">
                        <span class="header__btn-icon icon-plus" aria-hidden="true"></span>
                        <?php esc_html_e( 'Join as a Vendor', 'sandiegoweddingdirectory' ); ?>
                    </a>
                    <a class="sd-header-btn-icon" href="javascript:" data-sdwd-modal-open="couple-login" aria-label="<?php esc_attr_e( 'Log in', 'sandiegoweddingdirectory' ); ?>">
                        <span class="icon-user-o" aria-hidden="true"></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<main id="content" class="site-content">
