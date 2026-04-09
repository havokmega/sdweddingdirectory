<?php
/**
 * Plugin Name: SDWD Couple
 * Description: Couple features for San Diego Wedding Directory — reviews, quotes, wishlist, checklist, budget.
 * Version:     1.0.0
 * Author:      SD Wedding Directory
 * Text Domain: sdwd-couple
 * Requires Plugins: sdwd-core
 */

defined( 'ABSPATH' ) || exit;

define( 'SDWD_COUPLE_VERSION', '1.0.0' );
define( 'SDWD_COUPLE_PATH', plugin_dir_path( __FILE__ ) );

add_action( 'plugins_loaded', function () {
    // Only load if sdwd-core is active.
    if ( ! defined( 'SDWD_CORE_VERSION' ) ) {
        return;
    }

    require_once SDWD_COUPLE_PATH . 'modules/reviews.php';
    require_once SDWD_COUPLE_PATH . 'modules/request-quote.php';
    require_once SDWD_COUPLE_PATH . 'modules/wishlist.php';
    require_once SDWD_COUPLE_PATH . 'modules/checklist.php';
    require_once SDWD_COUPLE_PATH . 'modules/budget.php';
}, 10 );
