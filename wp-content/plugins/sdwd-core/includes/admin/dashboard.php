<?php
/**
 * SDWD Core — Custom WP Admin Dashboard
 *
 * Replaces the default WordPress dashboard widgets with a single full-width
 * SD Wedding Directory site overview (couples / venues / vendors / analytics).
 *
 * Visuals are intentionally 1:1 with the legacy `sdweddingdirectory` plugin
 * dashboard. Data sources have been rewired to the current sdwd-core /
 * sdwd-couple data model:
 *
 *   - claims:   `sdwd_claim` post meta on vendor/venue (status = pending|approved)
 *   - reviews:  `sdwd_review` CPT (sdwd-couple)
 *   - claimed:  post_author is NOT in administrator role
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'sdwd_render_admin_dashboard_stats' ) ) {

    /**
     * Keep the SDWD stats widget visible if White Label CMS strips dashboard widgets.
     */
    add_filter( 'wlcms_exclude_dashboard_metaboxes', function ( $widgets ) {
        $widgets   = is_array( $widgets ) ? $widgets : [];
        $widgets[] = 'sdwd_site_stats';
        return array_values( array_unique( $widgets ) );
    } );

    /**
     * Ensure the stats metabox is never hidden via user screen options.
     */
    add_filter( 'get_user_option_metaboxhidden_dashboard', function ( $hidden ) {
        $hidden = is_array( $hidden ) ? $hidden : [];
        return array_values( array_diff( $hidden, [ 'sdwd_site_stats' ] ) );
    } );

    /**
     * Strip default WP dashboard widgets and register the SDWD overview.
     */
    add_action( 'wp_dashboard_setup', function () {
        remove_meta_box( 'dashboard_primary',         'dashboard', 'side' );
        remove_meta_box( 'dashboard_secondary',       'dashboard', 'side' );
        remove_meta_box( 'dashboard_quick_press',     'dashboard', 'side' );
        remove_meta_box( 'dashboard_recent_drafts',   'dashboard', 'side' );
        remove_meta_box( 'dashboard_incoming_links',  'dashboard', 'normal' );
        remove_meta_box( 'dashboard_plugins',         'dashboard', 'normal' );
        remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_right_now',       'dashboard', 'normal' );
        remove_meta_box( 'dashboard_activity',        'dashboard', 'normal' );
        remove_meta_box( 'dashboard_site_health',     'dashboard', 'normal' );
        remove_action( 'welcome_panel', 'wp_welcome_panel' );

        wp_add_dashboard_widget(
            'sdwd_site_stats',
            esc_html__( 'SD Wedding Directory - Site Overview', 'sdwd-core' ),
            'sdwd_render_admin_dashboard_stats'
        );
    }, 99 );

    /**
     * Count vendor/venue posts whose author is NOT an administrator.
     * "Claimed" in the current model = a non-admin user owns the listing.
     */
    function sdwd_dashboard_count_claimed( $post_type ) {
        global $wpdb;

        $cap_key = $wpdb->prefix . 'capabilities';

        return absint( $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->posts} p
             INNER JOIN {$wpdb->users} u ON p.post_author = u.ID
             INNER JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
             WHERE p.post_type = %s
             AND p.post_status = 'publish'
             AND um.meta_key = %s
             AND um.meta_value NOT LIKE %s",
            $post_type,
            $cap_key,
            '%administrator%'
        ) ) );
    }

    /**
     * Count posts of a given type that have a pending `sdwd_claim` meta.
     */
    function sdwd_dashboard_count_pending_claims() {
        global $wpdb;

        // Serialized status='pending' substring match is reliable here:
        // the claim payload always serializes the status field.
        return absint( $wpdb->get_var(
            "SELECT COUNT(DISTINCT pm.post_id)
             FROM {$wpdb->postmeta} pm
             INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
             WHERE pm.meta_key = 'sdwd_claim'
             AND pm.meta_value LIKE '%\"status\";s:7:\"pending\"%'
             AND p.post_type IN ('vendor','venue')
             AND p.post_status = 'publish'"
        ) );
    }

    /**
     * Count couple users registered since a given MySQL datetime string.
     */
    function sdwd_dashboard_count_users_by_role_since( $role, $since_datetime ) {
        global $wpdb;

        $cap_key = $wpdb->prefix . 'capabilities';

        return absint( $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->users} u
             INNER JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
             WHERE um.meta_key = %s
             AND um.meta_value LIKE %s
             AND u.user_registered >= %s",
            $cap_key,
            '%' . $wpdb->esc_like( '"' . $role . '"' ) . '%',
            $since_datetime
        ) ) );
    }

    /**
     * Count active users (have a session token) with a given role.
     */
    function sdwd_dashboard_count_active_users( $role ) {
        global $wpdb;

        $cap_key = $wpdb->prefix . 'capabilities';

        return absint( $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(DISTINCT u.ID) FROM {$wpdb->users} u
             INNER JOIN {$wpdb->usermeta} um  ON u.ID = um.user_id
             INNER JOIN {$wpdb->usermeta} um2 ON u.ID = um2.user_id
             WHERE um.meta_key = %s
             AND um.meta_value LIKE %s
             AND um2.meta_key = 'session_tokens'
             AND um2.meta_value != ''",
            $cap_key,
            '%' . $wpdb->esc_like( '"' . $role . '"' ) . '%'
        ) ) );
    }

    /**
     * Render the dashboard widget body.
     */
    function sdwd_render_admin_dashboard_stats() {

        // ---------- Counts ----------
        $venue_totals  = wp_count_posts( 'venue' );
        $venue_count   = isset( $venue_totals->publish ) ? absint( $venue_totals->publish ) : 0;
        $venue_pending = isset( $venue_totals->pending ) ? absint( $venue_totals->pending ) : 0;

        $vendor_totals  = wp_count_posts( 'vendor' );
        $vendor_count   = isset( $vendor_totals->publish ) ? absint( $vendor_totals->publish ) : 0;
        $vendor_pending = isset( $vendor_totals->pending ) ? absint( $vendor_totals->pending ) : 0;

        $user_roles        = count_users();
        $roles             = isset( $user_roles['avail_roles'] ) && is_array( $user_roles['avail_roles'] ) ? $user_roles['avail_roles'] : [];
        $couple_count      = isset( $roles['couple'] ) ? absint( $roles['couple'] ) : 0;
        $vendor_user_count = isset( $roles['vendor'] ) ? absint( $roles['vendor'] ) : 0;
        $venue_user_count  = isset( $roles['venue'] ) ? absint( $roles['venue'] ) : 0;
        $business_users    = $vendor_user_count + $venue_user_count;

        $venues_claimed   = sdwd_dashboard_count_claimed( 'venue' );
        $venues_unclaimed = max( 0, $venue_count - $venues_claimed );

        $vendors_claimed   = sdwd_dashboard_count_claimed( 'vendor' );
        $vendors_unclaimed = max( 0, $vendor_count - $vendors_claimed );

        $vendor_categories = get_terms( [
            'taxonomy'   => 'vendor-category',
            'hide_empty' => false,
            'parent'     => 0,
        ] );

        $first_of_month = gmdate( 'Y-m-01 00:00:00' );
        $start_of_week  = gmdate( 'Y-m-d 00:00:00', strtotime( 'monday this week' ) );
        $start_of_today = gmdate( 'Y-m-d 00:00:00' );

        $new_couples_today = sdwd_dashboard_count_users_by_role_since( 'couple', $start_of_today );
        $new_couples_week  = sdwd_dashboard_count_users_by_role_since( 'couple', $start_of_week );
        $new_couples_month = sdwd_dashboard_count_users_by_role_since( 'couple', $first_of_month );

        $new_vendors_week  = sdwd_dashboard_count_users_by_role_since( 'vendor', $start_of_week );
        $new_vendors_month = sdwd_dashboard_count_users_by_role_since( 'vendor', $first_of_month );

        $active_couples = sdwd_dashboard_count_active_users( 'couple' );

        $pending_claims_count = sdwd_dashboard_count_pending_claims();

        $reviews_totals    = wp_count_posts( 'sdwd_review' );
        $review_count      = isset( $reviews_totals->publish ) ? absint( $reviews_totals->publish ) : 0;

        $venue_type_count = wp_count_terms( [ 'taxonomy' => 'venue-type', 'hide_empty' => false ] );
        $venue_type_count = is_wp_error( $venue_type_count ) ? 0 : absint( $venue_type_count );

        $blog_posts = wp_count_posts( 'post' );
        $blog_count = isset( $blog_posts->publish ) ? absint( $blog_posts->publish ) : 0;

        $real_weddings = wp_count_posts( 'real-wedding' );
        $wedding_count = isset( $real_weddings->publish ) ? absint( $real_weddings->publish ) : 0;

        $venue_claimed_pct  = $venue_count  > 0 ? absint( round( ( $venues_claimed  / $venue_count  ) * 100 ) ) : 0;
        $vendor_claimed_pct = $vendor_count > 0 ? absint( round( ( $vendors_claimed / $vendor_count ) * 100 ) ) : 0;

        $vendor_category_rows = [];
        $exclude_slugs        = [ 'venues', 'venue', 'wedding-venues' ];
        if ( is_array( $vendor_categories ) ) {
            foreach ( $vendor_categories as $vendor_category ) {
                if ( ! is_object( $vendor_category ) || empty( $vendor_category->slug ) ) {
                    continue;
                }
                if ( in_array( $vendor_category->slug, $exclude_slugs, true ) ) {
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
                        <div class="sdwd-panel-label"><?php esc_html_e( 'Couples', 'sdwd-core' ); ?></div>
                        <section class="sdwd-panel">
                            <div class="sdwd-metric-grid sdwd-metric-grid-4">
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $couple_count ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Total Couples', 'sdwd-core' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $new_couples_week ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'New This Week', 'sdwd-core' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $new_couples_month ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'New This Month', 'sdwd-core' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $new_couples_today ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'New Today', 'sdwd-core' ); ?></div>
                                </div>
                            </div>

                            <div class="sdwd-section-subtitle"><?php esc_html_e( 'Active Couples', 'sdwd-core' ); ?></div>
                            <div class="sdwd-cat-table-wrap">
                                <table class="sdwd-cat-table">
                                    <tr>
                                        <td><?php esc_html_e( 'Logged In (Last 30 Days)', 'sdwd-core' ); ?></td>
                                        <td><?php echo esc_html( number_format_i18n( $active_couples ) ); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </section>
                    </div>

                    <div class="sdwd-panel-wrap">
                        <div class="sdwd-panel-label"><?php esc_html_e( 'Venues', 'sdwd-core' ); ?></div>
                        <section class="sdwd-panel">
                            <div class="sdwd-metric-grid sdwd-metric-grid-3">
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $venue_count ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Total Venues', 'sdwd-core' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $venues_claimed ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Claimed', 'sdwd-core' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $venues_unclaimed ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Unclaimed', 'sdwd-core' ); ?></div>
                                </div>
                            </div>
                            <?php if ( $venue_count > 0 ) : ?>
                                <div class="sdwd-claimed-bar">
                                    <div class="claimed" style="width: <?php echo esc_attr( $venue_claimed_pct ); ?>%;"></div>
                                    <div class="unclaimed" style="width: <?php echo esc_attr( 100 - $venue_claimed_pct ); ?>%;"></div>
                                </div>
                                <div class="sdwd-claimed-legend">
                                    <span class="leg-claimed"><?php echo esc_html( sprintf( __( 'Claimed: %s', 'sdwd-core' ), number_format_i18n( $venues_claimed ) ) ); ?></span>
                                    <span class="leg-unclaimed"><?php echo esc_html( sprintf( __( 'Unclaimed: %s', 'sdwd-core' ), number_format_i18n( $venues_unclaimed ) ) ); ?></span>
                                </div>
                            <?php else : ?>
                                <p class="sdwd-empty"><?php esc_html_e( 'No venues yet.', 'sdwd-core' ); ?></p>
                            <?php endif; ?>

                            <div class="sdwd-metric-grid sdwd-metric-grid-3">
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $pending_claims_count ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Pending Claims', 'sdwd-core' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $review_count ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Total Reviews', 'sdwd-core' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $venue_type_count ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Venue Types', 'sdwd-core' ); ?></div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="sdwd-panel-wrap">
                        <div class="sdwd-panel-label"><?php esc_html_e( 'Vendors', 'sdwd-core' ); ?></div>
                        <section class="sdwd-panel">
                            <div class="sdwd-metric-grid sdwd-metric-grid-4">
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $vendor_count ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Total Vendors', 'sdwd-core' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $business_users ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Vendor Accounts', 'sdwd-core' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $vendors_claimed ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Claimed', 'sdwd-core' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $vendors_unclaimed ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Unclaimed', 'sdwd-core' ); ?></div>
                                </div>
                            </div>
                            <?php if ( $vendor_count > 0 ) : ?>
                                <div class="sdwd-claimed-bar">
                                    <div class="claimed" style="width: <?php echo esc_attr( $vendor_claimed_pct ); ?>%;"></div>
                                    <div class="unclaimed" style="width: <?php echo esc_attr( 100 - $vendor_claimed_pct ); ?>%;"></div>
                                </div>
                                <div class="sdwd-claimed-legend">
                                    <span class="leg-claimed"><?php echo esc_html( sprintf( __( 'Claimed: %s', 'sdwd-core' ), number_format_i18n( $vendors_claimed ) ) ); ?></span>
                                    <span class="leg-unclaimed"><?php echo esc_html( sprintf( __( 'Unclaimed: %s', 'sdwd-core' ), number_format_i18n( $vendors_unclaimed ) ) ); ?></span>
                                </div>
                            <?php else : ?>
                                <p class="sdwd-empty"><?php esc_html_e( 'No vendors yet.', 'sdwd-core' ); ?></p>
                            <?php endif; ?>

                            <div class="sdwd-metric-grid sdwd-metric-grid-3">
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $new_vendors_week ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'New This Week', 'sdwd-core' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $new_vendors_month ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'New This Month', 'sdwd-core' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $vendor_pending ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Pending Vendors', 'sdwd-core' ); ?></div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

                <div class="sdwd-dashboard-side">
                    <div class="sdwd-panel-wrap">
                        <div class="sdwd-panel-label"><?php esc_html_e( 'Site Analytics', 'sdwd-core' ); ?></div>
                        <section class="sdwd-panel sdwd-panel-analytics">
                            <div class="sdwd-metric-grid sdwd-metric-grid-2">
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $venue_count + $vendor_count ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Total Listings', 'sdwd-core' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $couple_count + $business_users ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Total Accounts', 'sdwd-core' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( sprintf( '%s%%', number_format_i18n( $venue_claimed_pct ) ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Venue Claim Rate', 'sdwd-core' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( sprintf( '%s%%', number_format_i18n( $vendor_claimed_pct ) ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Vendor Claim Rate', 'sdwd-core' ); ?></div>
                                </div>
                            </div>
                            <div class="sdwd-metric-grid sdwd-metric-grid-2">
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $blog_count ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Total Blog Posts', 'sdwd-core' ); ?></div>
                                </div>
                                <div class="sdwd-metric">
                                    <div class="sdwd-metric-number"><?php echo esc_html( number_format_i18n( $wedding_count ) ); ?></div>
                                    <div class="sdwd-metric-label"><?php esc_html_e( 'Real Weddings', 'sdwd-core' ); ?></div>
                                </div>
                            </div>

                            <div class="sdwd-section-subtitle"><?php esc_html_e( 'Vendors by Category', 'sdwd-core' ); ?></div>
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
                                <p class="sdwd-empty"><?php esc_html_e( 'No vendor categories found.', 'sdwd-core' ); ?></p>
                            <?php endif; ?>

                            <div class="sdwd-section-subtitle"><?php esc_html_e( 'Quick Actions', 'sdwd-core' ); ?></div>
                            <div class="sdwd-cat-table-wrap">
                                <table class="sdwd-cat-table">
                                    <tr>
                                        <td><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=vendor' ) ); ?>"><?php esc_html_e( 'Pending Claims', 'sdwd-core' ); ?></a></td>
                                        <td><?php echo esc_html( number_format_i18n( $pending_claims_count ) ); ?></td>
                                    </tr>
                                    <tr>
                                        <td><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sdwd_review' ) ); ?>"><?php esc_html_e( 'All Reviews', 'sdwd-core' ); ?></a></td>
                                        <td><?php echo esc_html( number_format_i18n( $review_count ) ); ?></td>
                                    </tr>
                                    <tr>
                                        <td><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=vendor&post_status=pending' ) ); ?>"><?php esc_html_e( 'Pending Vendors', 'sdwd-core' ); ?></a></td>
                                        <td><?php echo esc_html( number_format_i18n( $vendor_pending ) ); ?></td>
                                    </tr>
                                    <tr>
                                        <td><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=venue&post_status=pending' ) ); ?>"><?php esc_html_e( 'Pending Venues', 'sdwd-core' ); ?></a></td>
                                        <td><?php echo esc_html( number_format_i18n( $venue_pending ) ); ?></td>
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
