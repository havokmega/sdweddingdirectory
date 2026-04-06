<?php
/**
 * Custom Admin Dashboard
 * ----------------------
 * Replaces the default WordPress dashboard with site statistics.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'sdwd_render_dashboard_stats' ) ) {

    /**
     * Keep SD stats widget visible when White Label CMS trims dashboard widgets.
     */
    add_filter( 'wlcms_exclude_dashboard_metaboxes', function( $widgets ) {
        $widgets   = is_array( $widgets ) ? $widgets : [];
        $widgets[] = 'sdwd_site_stats';
        return array_values( array_unique( $widgets ) );
    } );

    /**
     * Ensure the stats metabox is not hidden via user screen options.
     */
    add_filter( 'get_user_option_metaboxhidden_dashboard', function( $hidden ) {
        $hidden = is_array( $hidden ) ? $hidden : [];
        return array_values( array_diff( $hidden, [ 'sdwd_site_stats' ] ) );
    } );

    /**
     * Remove default dashboard widgets and add SDWeddingDirectory stats widget.
     */
    add_action( 'wp_dashboard_setup', function() {

        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
        remove_action( 'welcome_panel', 'wp_welcome_panel' );

        wp_add_dashboard_widget(
            'sdwd_site_stats',
            esc_html__( 'SD Wedding Directory - Site Overview', 'sdweddingdirectory' ),
            'sdwd_render_dashboard_stats'
        );
    }, 99 );

    /**
     * Render dashboard statistics.
     */
    function sdwd_render_dashboard_stats() {

        global $wpdb;

        $total_venues = wp_count_posts( 'venue' );
        $venue_count  = isset( $total_venues->publish ) ? absint( $total_venues->publish ) : 0;

        $total_vendors = wp_count_posts( 'vendor' );
        $vendor_count  = isset( $total_vendors->publish ) ? absint( $total_vendors->publish ) : 0;

        $user_roles = count_users();
        $roles      = isset( $user_roles['avail_roles'] ) && is_array( $user_roles['avail_roles'] ) ? $user_roles['avail_roles'] : [];

        $couple_count      = isset( $roles['couple'] ) ? absint( $roles['couple'] ) : 0;
        $vendor_user_count = isset( $roles['vendor'] ) ? absint( $roles['vendor'] ) : 0;

        $venues_claimed = absint( $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->posts}
             WHERE post_type = 'venue' AND post_status = 'publish'
             AND post_author > 1"
        ) );
        $venues_unclaimed = max( 0, $venue_count - $venues_claimed );

        $vendors_claimed = absint( $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->posts}
             WHERE post_type = 'vendor' AND post_status = 'publish'
             AND post_author > 1"
        ) );
        $vendors_unclaimed = max( 0, $vendor_count - $vendors_claimed );

        $vendor_categories = get_terms( [
            'taxonomy'   => 'vendor-category',
            'hide_empty' => false,
            'parent'     => 0,
        ] );

        $first_of_month = gmdate( 'Y-m-01 00:00:00' );
        $new_couples_month = absint(
            $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM {$wpdb->users}
                     INNER JOIN {$wpdb->usermeta} ON {$wpdb->users}.ID = {$wpdb->usermeta}.user_id
                     WHERE {$wpdb->usermeta}.meta_key = %s
                     AND {$wpdb->usermeta}.meta_value LIKE %s
                     AND {$wpdb->users}.user_registered >= %s",
                    $wpdb->prefix . 'capabilities',
                    '%couple%',
                    $first_of_month
                )
            )
        );

        $start_of_week = gmdate( 'Y-m-d 00:00:00', strtotime( 'monday this week' ) );
        $new_couples_week = absint(
            $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM {$wpdb->users}
                     INNER JOIN {$wpdb->usermeta} ON {$wpdb->users}.ID = {$wpdb->usermeta}.user_id
                     WHERE {$wpdb->usermeta}.meta_key = %s
                     AND {$wpdb->usermeta}.meta_value LIKE %s
                     AND {$wpdb->users}.user_registered >= %s",
                    $wpdb->prefix . 'capabilities',
                    '%couple%',
                    $start_of_week
                )
            )
        );

        $start_of_today = gmdate( 'Y-m-d 00:00:00' );
        $new_couples_today = absint(
            $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM {$wpdb->users}
                     INNER JOIN {$wpdb->usermeta} ON {$wpdb->users}.ID = {$wpdb->usermeta}.user_id
                     WHERE {$wpdb->usermeta}.meta_key = %s
                     AND {$wpdb->usermeta}.meta_value LIKE %s
                     AND {$wpdb->users}.user_registered >= %s",
                    $wpdb->prefix . 'capabilities',
                    '%couple%',
                    $start_of_today
                )
            )
        );

        $active_couples = absint(
            $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(DISTINCT u.ID) FROM {$wpdb->users} u
                     INNER JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
                     INNER JOIN {$wpdb->usermeta} um2 ON u.ID = um2.user_id
                     WHERE um.meta_key = %s AND um.meta_value LIKE %s
                     AND um2.meta_key = 'session_tokens' AND um2.meta_value != ''
                     AND u.user_registered <= NOW()",
                    $wpdb->prefix . 'capabilities',
                    '%couple%'
                )
            )
        );

        $pending_venue_claims = wp_count_posts( 'claim-venue' );
        $pending_claims_count = isset( $pending_venue_claims->pending ) ? absint( $pending_venue_claims->pending ) : 0;

        $venue_reviews     = wp_count_posts( 'venue-review' );
        $venue_review_count = isset( $venue_reviews->publish ) ? absint( $venue_reviews->publish ) : 0;

        $venue_type_count = wp_count_terms( array( 'taxonomy' => 'venue-type', 'hide_empty' => false ) );
        $venue_type_count = is_wp_error( $venue_type_count ) ? 0 : absint( $venue_type_count );

        $new_vendors_week = absint(
            $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM {$wpdb->users}
                     INNER JOIN {$wpdb->usermeta} ON {$wpdb->users}.ID = {$wpdb->usermeta}.user_id
                     WHERE {$wpdb->usermeta}.meta_key = %s
                     AND {$wpdb->usermeta}.meta_value LIKE %s
                     AND {$wpdb->users}.user_registered >= %s",
                    $wpdb->prefix . 'capabilities',
                    '%vendor%',
                    $start_of_week
                )
            )
        );

        $new_vendors_month = absint(
            $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM {$wpdb->users}
                     INNER JOIN {$wpdb->usermeta} ON {$wpdb->users}.ID = {$wpdb->usermeta}.user_id
                     WHERE {$wpdb->usermeta}.meta_key = %s
                     AND {$wpdb->usermeta}.meta_value LIKE %s
                     AND {$wpdb->users}.user_registered >= %s",
                    $wpdb->prefix . 'capabilities',
                    '%vendor%',
                    $first_of_month
                )
            )
        );

        $pending_vendors     = wp_count_posts( 'vendor' );
        $pending_vendor_count = isset( $pending_vendors->pending ) ? absint( $pending_vendors->pending ) : 0;

        $blog_posts = wp_count_posts( 'post' );
        $blog_count = isset( $blog_posts->publish ) ? absint( $blog_posts->publish ) : 0;

        $real_weddings = wp_count_posts( 'real-wedding' );
        $wedding_count = isset( $real_weddings->publish ) ? absint( $real_weddings->publish ) : 0;

        $venue_claimed_pct   = $venue_count > 0 ? absint( round( ( $venues_claimed / $venue_count ) * 100 ) ) : 0;
        $vendor_claimed_pct  = $vendor_count > 0 ? absint( round( ( $vendors_claimed / $vendor_count ) * 100 ) ) : 0;
        $vendor_category_rows = [];
        $venue_vendor_slugs   = [ 'venues', 'venue', 'wedding-venues' ];

        if ( is_array( $vendor_categories ) ) {
            foreach ( $vendor_categories as $vendor_category ) {
                if ( ! is_object( $vendor_category ) || empty( $vendor_category->slug ) ) {
                    continue;
                }
                if ( in_array( $vendor_category->slug, $venue_vendor_slugs, true ) ) {
                    continue;
                }
                $vendor_category_rows[] = $vendor_category;
            }
        }
        ?>
        <style>
            #sdwd_site_stats.postbox {
                border: 0;
                background: transparent;
                box-shadow: none;
            }
            #sdwd_site_stats .postbox-header {
                display: none;
            }
            #sdwd_site_stats .inside {
                margin: 0;
                padding: 0;
            }
            #dashboard-widgets .postbox-container {
                width: 100% !important;
                float: none !important;
            }
            #dashboard-widgets #postbox-container-1,
            #dashboard-widgets.columns-2 #postbox-container-1,
            #dashboard-widgets.columns-3 #postbox-container-1,
            #dashboard-widgets.columns-4 #postbox-container-1 {
                width: 100% !important;
                margin-right: 0 !important;
            }
            #postbox-container-2,
            #postbox-container-3,
            #postbox-container-4 {
                display: none;
            }
            .sdwd-dashboard-shell {
                border-radius: 18px;
                background: #dbdbdb;
                padding: 32px;
            }
            .sdwd-dashboard-layout {
                display: grid;
                grid-template-columns: minmax(0, 1.45fr) minmax(0, 1fr);
                gap: 48px;
            }
            .sdwd-dashboard-main {
                display: grid;
                gap: 40px;
            }
            .sdwd-panel-wrap {
                position: relative;
                padding-top: 28px;
            }
            .sdwd-panel-label {
                position: absolute;
                top: -4px;
                left: 30px;
                display: inline-flex;
                align-items: center;
                min-height: 30px;
                min-width: 180px;
                border: 2px solid #1f1f1f;
                border-bottom: 0;
                border-radius: 24px 24px 0 0;
                padding: 6px 22px 4px;
                background: #dbdbdb;
                font-size: 15px;
                font-weight: 600;
                line-height: 1.2;
                letter-spacing: 0.11em;
                text-transform: uppercase;
                color: #2b2b2b;
                z-index: 1;
            }
            .sdwd-panel {
                position: relative;
                z-index: 2;
                border: 2px solid #1f1f1f;
                border-radius: 28px;
                background: #f2f2f2;
                padding: 22px;
            }
            .sdwd-panel.sdwd-panel-analytics {
                min-height: 100%;
            }
            .sdwd-metric-grid {
                display: grid;
                gap: 12px;
            }
            .sdwd-metric-grid.sdwd-metric-grid-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
            .sdwd-metric-grid.sdwd-metric-grid-4 {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
            .sdwd-metric-grid.sdwd-metric-grid-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .sdwd-metric {
                border: 1px solid #d4d4d4;
                border-radius: 14px;
                background: #ffffff;
                padding: 16px 12px;
                text-align: center;
            }
            .sdwd-metric-number {
                font-size: 30px;
                font-weight: 700;
                line-height: 1.15;
                color: #111111;
            }
            .sdwd-metric-label {
                margin-top: 5px;
                font-size: 11px;
                font-weight: 600;
                letter-spacing: 0.07em;
                text-transform: uppercase;
                color: #5a5a5a;
            }
            .sdwd-claimed-bar {
                display: flex;
                height: 16px;
                border: 1px solid #c5c5c5;
                border-radius: 999px;
                overflow: hidden;
                margin-top: 16px;
                background: #f4f4f4;
            }
            .sdwd-claimed-bar .claimed {
                background: #4f8f6f;
            }
            .sdwd-claimed-bar .unclaimed {
                background: #cfd2d4;
            }
            .sdwd-claimed-legend {
                display: flex;
                flex-wrap: wrap;
                gap: 8px 16px;
                margin-top: 10px;
                font-size: 12px;
                color: #4c4c4c;
            }
            .sdwd-claimed-legend span::before {
                content: '';
                display: inline-block;
                width: 9px;
                height: 9px;
                border-radius: 2px;
                margin-right: 6px;
                vertical-align: middle;
            }
            .sdwd-claimed-legend .leg-claimed::before {
                background: #4f8f6f;
            }
            .sdwd-claimed-legend .leg-unclaimed::before {
                background: #cfd2d4;
            }
            .sdwd-section-subtitle {
                margin: 22px 0 10px;
                font-size: 11px;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: #535353;
            }
            .sdwd-cat-table-wrap {
                border: 1px solid #d4d4d4;
                border-radius: 14px;
                overflow: hidden;
                background: #ffffff;
            }
            .sdwd-cat-table {
                width: 100%;
                border-collapse: collapse;
            }
            .sdwd-cat-table td {
                padding: 9px 12px;
                border-bottom: 1px solid #efefef;
                font-size: 12px;
                color: #303030;
            }
            .sdwd-cat-table tr:last-child td {
                border-bottom: 0;
            }
            .sdwd-cat-table td:last-child {
                text-align: right;
                font-weight: 700;
            }
            .sdwd-empty {
                margin: 14px 0 0;
                font-size: 12px;
                color: #666666;
            }
            @media (min-width: 1201px) {
                .sdwd-dashboard-main {
                    display: flex;
                    flex-direction: column;
                    gap: 25px;
                    height: 100%;
                    min-height: 0;
                }
                .sdwd-dashboard-main > .sdwd-panel-wrap {
                    flex: 1 1 0;
                    display: flex;
                    flex-direction: column;
                    min-height: 0;
                }
                .sdwd-dashboard-main > .sdwd-panel-wrap .sdwd-panel {
                    flex: 1 1 auto;
                }
            }
            @media (max-width: 1400px) {
                .sdwd-metric-grid.sdwd-metric-grid-4 {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }
            @media (max-width: 1200px) {
                .sdwd-dashboard-layout {
                    grid-template-columns: 1fr;
                    gap: 28px;
                }
                .sdwd-metric-grid.sdwd-metric-grid-3 {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }
            @media (max-width: 680px) {
                .sdwd-dashboard-shell {
                    padding: 18px;
                }
                .sdwd-panel {
                    padding: 16px;
                }
                .sdwd-panel-label {
                    min-width: 150px;
                }
                .sdwd-metric-grid.sdwd-metric-grid-3,
                .sdwd-metric-grid.sdwd-metric-grid-2,
                .sdwd-metric-grid.sdwd-metric-grid-4 {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <div class="sdwd-dashboard-shell">
            <div class="sdwd-dashboard-layout">
                <div class="sdwd-dashboard-main">
                    <div class="sdwd-panel-wrap">
                        <div class="sdwd-panel-label"><?php esc_html_e( 'Couples', 'sdweddingdirectory' ); ?></div>
                        <section class="sdwd-panel">
                            <div class="sdwd-metric-grid sdwd-metric-grid-4">
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $couple_count ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Total Couples', 'sdweddingdirectory' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $new_couples_week ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'New This Week', 'sdweddingdirectory' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $new_couples_month ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'New This Month', 'sdweddingdirectory' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $new_couples_today ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'New Today', 'sdweddingdirectory' ); ?></div>
                                </div>
                            </div>

                            <div class="sdwd-section-subtitle"><?php esc_html_e( 'Active Couples', 'sdweddingdirectory' ); ?></div>
                            <div class="sdwd-cat-table-wrap">
                                <table class="sdwd-cat-table">
                                    <tr>
                                        <td><?php esc_html_e( 'Logged In (Last 30 Days)', 'sdweddingdirectory' ); ?></td>
                                        <td><?php echo esc_html( number_format_i18n( $active_couples ) ); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </section>
                    </div>

                    <div class="sdwd-panel-wrap">
                        <div class="sdwd-panel-label"><?php esc_html_e( 'Venues', 'sdweddingdirectory' ); ?></div>
                        <section class="sdwd-panel">
                            <div class="sdwd-metric-grid sdwd-metric-grid-3">
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $venue_count ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Total Venues', 'sdweddingdirectory' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $venues_claimed ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Claimed', 'sdweddingdirectory' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $venues_unclaimed ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Unclaimed', 'sdweddingdirectory' ); ?></div>
                                </div>
                            </div>
                            <?php if ( $venue_count > 0 ) : ?>
                                <div class="sdwd-claimed-bar">
                                    <div class="claimed" style="width: <?php echo esc_attr( $venue_claimed_pct ); ?>%;"></div>
                                    <div class="unclaimed" style="width: <?php echo esc_attr( 100 - $venue_claimed_pct ); ?>%;"></div>
                                </div>
                                <div class="sdwd-claimed-legend">
                                    <span class="leg-claimed"><?php echo esc_html( sprintf( __( 'Claimed: %s', 'sdweddingdirectory' ), number_format_i18n( $venues_claimed ) ) ); ?></span>
                                    <span class="leg-unclaimed"><?php echo esc_html( sprintf( __( 'Unclaimed: %s', 'sdweddingdirectory' ), number_format_i18n( $venues_unclaimed ) ) ); ?></span>
                                </div>
                            <?php else : ?>
                                <p class="sdwd-empty"><?php esc_html_e( 'No venues yet.', 'sdweddingdirectory' ); ?></p>
                            <?php endif; ?>

                            <div class="sdwd-metric-grid sdwd-metric-grid-3">
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $pending_claims_count ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Pending Claims', 'sdweddingdirectory' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $venue_review_count ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Total Reviews', 'sdweddingdirectory' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $venue_type_count ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Venue Types', 'sdweddingdirectory' ); ?></div>
                                </div>
                            </div>

                        </section>
                    </div>

                    <div class="sdwd-panel-wrap">
                        <div class="sdwd-panel-label"><?php esc_html_e( 'Vendors', 'sdweddingdirectory' ); ?></div>
                        <section class="sdwd-panel">
                            <div class="sdwd-metric-grid sdwd-metric-grid-4">
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $vendor_count ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Total Vendors', 'sdweddingdirectory' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $vendor_user_count ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Vendor Accounts', 'sdweddingdirectory' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $vendors_claimed ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Claimed', 'sdweddingdirectory' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $vendors_unclaimed ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Unclaimed', 'sdweddingdirectory' ); ?></div>
                                </div>
                            </div>
                            <?php if ( $vendor_count > 0 ) : ?>
                                <div class="sdwd-claimed-bar">
                                    <div class="claimed" style="width: <?php echo esc_attr( $vendor_claimed_pct ); ?>%;"></div>
                                    <div class="unclaimed" style="width: <?php echo esc_attr( 100 - $vendor_claimed_pct ); ?>%;"></div>
                                </div>
                                <div class="sdwd-claimed-legend">
                                    <span class="leg-claimed"><?php echo esc_html( sprintf( __( 'Claimed: %s', 'sdweddingdirectory' ), number_format_i18n( $vendors_claimed ) ) ); ?></span>
                                    <span class="leg-unclaimed"><?php echo esc_html( sprintf( __( 'Unclaimed: %s', 'sdweddingdirectory' ), number_format_i18n( $vendors_unclaimed ) ) ); ?></span>
                                </div>
                            <?php else : ?>
                                <p class="sdwd-empty"><?php esc_html_e( 'No vendors yet.', 'sdweddingdirectory' ); ?></p>
                            <?php endif; ?>

                            <div class="sdwd-metric-grid sdwd-metric-grid-3">
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $new_vendors_week ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'New This Week', 'sdweddingdirectory' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $new_vendors_month ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'New This Month', 'sdweddingdirectory' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $pending_vendor_count ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Pending Vendors', 'sdweddingdirectory' ); ?></div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

                <div class="sdwd-dashboard-side">
                    <div class="sdwd-panel-wrap">
                        <div class="sdwd-panel-label"><?php esc_html_e( 'Site Analytics', 'sdweddingdirectory' ); ?></div>
                        <section class="sdwd-panel sdwd-panel-analytics">
                            <div class="sdwd-metric-grid sdwd-metric-grid-2">
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $venue_count + $vendor_count ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Total Venues', 'sdweddingdirectory' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $couple_count + $vendor_user_count ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Total Accounts', 'sdweddingdirectory' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( sprintf( '%s%%', number_format_i18n( $venue_claimed_pct ) ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Venue Claim Rate', 'sdweddingdirectory' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( sprintf( '%s%%', number_format_i18n( $vendor_claimed_pct ) ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Vendor Claim Rate', 'sdweddingdirectory' ); ?></div>
                                </div>
                            </div>
                            <div class="sdwd-metric-grid sdwd-metric-grid-2">
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $blog_count ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Total Blog Posts', 'sdweddingdirectory' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $wedding_count ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Real Weddings', 'sdweddingdirectory' ); ?></div>
                                </div>
                            </div>

                            <div class="sdwd-section-subtitle"><?php esc_html_e( 'Vendors by Category', 'sdweddingdirectory' ); ?></div>
                            <?php if ( ! empty( $vendor_category_rows ) ) : ?>
                                <div class="sdwd-cat-table-wrap">
                                    <table class="sdwd-cat-table">
                                        <?php foreach ( $vendor_category_rows as $cat ) : ?>
                                            <tr>
                                                <td><?php echo esc_html( $cat->name ); ?></td>
                                                <td><?php echo esc_html( number_format_i18n( absint( $cat->count ) ) ); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </div>
                            <?php else : ?>
                                <p class="sdwd-empty"><?php esc_html_e( 'No vendor categories found.', 'sdweddingdirectory' ); ?></p>
                            <?php endif; ?>

                            <div class="sdwd-section-subtitle"><?php esc_html_e( 'Quick Actions', 'sdweddingdirectory' ); ?></div>
                            <div class="sdwd-cat-table-wrap">
                                <table class="sdwd-cat-table">
                                    <tr>
                                        <td><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=claim-venue&post_status=pending' ) ); ?>"><?php esc_html_e( 'Pending Claims', 'sdweddingdirectory' ); ?></a></td>
                                        <td><?php echo esc_html( number_format_i18n( $pending_claims_count ) ); ?></td>
                                    </tr>
                                    <tr>
                                        <td><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=venue-review' ) ); ?>"><?php esc_html_e( 'All Reviews', 'sdweddingdirectory' ); ?></a></td>
                                        <td><?php echo esc_html( number_format_i18n( $venue_review_count ) ); ?></td>
                                    </tr>
                                    <tr>
                                        <td><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=vendor&post_status=pending' ) ); ?>"><?php esc_html_e( 'Pending Vendors', 'sdweddingdirectory' ); ?></a></td>
                                        <td><?php echo esc_html( number_format_i18n( $pending_vendor_count ) ); ?></td>
                                    </tr>
                                    <tr>
                                        <td><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=venue&post_status=pending' ) ); ?>"><?php esc_html_e( 'Pending Venues', 'sdweddingdirectory' ); ?></a></td>
                                        <td><?php echo esc_html( number_format_i18n( isset( $total_venues->pending ) ? absint( $total_venues->pending ) : 0 ) ); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
