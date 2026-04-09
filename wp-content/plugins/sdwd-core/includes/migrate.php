<?php
/**
 * SDWD Core — Migration
 *
 * One-time data migration from old plugin meta keys to new sdwd_ keys.
 * Run via wp-admin: Tools → SDWD Migration.
 */

defined( 'ABSPATH' ) || exit;

// Admin menu.
add_action( 'admin_menu', function () {
    add_management_page(
        __( 'SDWD Migration', 'sdwd-core' ),
        __( 'SDWD Migration', 'sdwd-core' ),
        'manage_options',
        'sdwd-migration',
        'sdwd_migration_page'
    );
} );

function sdwd_migration_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $ran = false;
    $log = [];

    if ( isset( $_POST['sdwd_run_migration'] ) && check_admin_referer( 'sdwd_migration' ) ) {
        $log = sdwd_run_migration();
        $ran = true;
    }

    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'SDWD Data Migration', 'sdwd-core' ); ?></h1>
        <p><?php esc_html_e( 'This migrates data from the old plugin format to the new sdwd-core format. Safe to run multiple times — it skips fields that already have new data.', 'sdwd-core' ); ?></p>

        <?php if ( $ran ) : ?>
            <div class="notice notice-success">
                <p><strong><?php esc_html_e( 'Migration complete.', 'sdwd-core' ); ?></strong></p>
            </div>
            <h3><?php esc_html_e( 'Log', 'sdwd-core' ); ?></h3>
            <pre style="background:#f0f0f0; padding:16px; max-height:500px; overflow:auto;"><?php echo esc_html( implode( "\n", $log ) ); ?></pre>
        <?php endif; ?>

        <form method="post">
            <?php wp_nonce_field( 'sdwd_migration' ); ?>
            <p>
                <button type="submit" name="sdwd_run_migration" class="button button-primary">
                    <?php esc_html_e( 'Run Migration', 'sdwd-core' ); ?>
                </button>
            </p>
        </form>
    </div>
    <?php
}

function sdwd_run_migration() {
    $log = [];

    // ── 1. Migrate venue address data ──
    $venues = get_posts( [
        'post_type'   => 'venue',
        'post_status' => 'any',
        'numberposts' => -1,
        'fields'      => 'ids',
    ] );

    $venue_count = 0;
    foreach ( $venues as $venue_id ) {
        $changed = false;

        // venue_address → sdwd_street_address.
        $old_addr = get_post_meta( $venue_id, 'venue_address', true );
        if ( $old_addr && ! get_post_meta( $venue_id, 'sdwd_street_address', true ) ) {
            update_post_meta( $venue_id, 'sdwd_street_address', $old_addr );
            $changed = true;
        }

        // venue_pincode → sdwd_zip_code.
        $old_zip = get_post_meta( $venue_id, 'venue_pincode', true );
        if ( $old_zip && ! get_post_meta( $venue_id, 'sdwd_zip_code', true ) ) {
            update_post_meta( $venue_id, 'sdwd_zip_code', $old_zip );
            $changed = true;
        }

        // venue_min_price → sdwd_pricing (basic tier).
        $min_price = get_post_meta( $venue_id, 'venue_min_price', true );
        $max_price = get_post_meta( $venue_id, 'venue_max_price', true );
        if ( ( $min_price || $max_price ) && ! get_post_meta( $venue_id, 'sdwd_pricing', true ) ) {
            $price_label = '';
            if ( $min_price && $max_price ) {
                $price_label = '$' . number_format( (float) $min_price ) . ' – $' . number_format( (float) $max_price );
            } elseif ( $min_price ) {
                $price_label = 'Starting at $' . number_format( (float) $min_price );
            }
            if ( $price_label ) {
                update_post_meta( $venue_id, 'sdwd_pricing', [
                    [ 'name' => '', 'price' => $price_label, 'features' => [] ],
                ] );
                $changed = true;
            }
        }

        // Derive city from venue-location taxonomy if not set.
        if ( ! get_post_meta( $venue_id, 'sdwd_city', true ) ) {
            $locations = wp_get_post_terms( $venue_id, 'venue-location', [ 'fields' => 'names' ] );
            if ( ! is_wp_error( $locations ) && ! empty( $locations ) ) {
                update_post_meta( $venue_id, 'sdwd_city', $locations[0] );
                $changed = true;
            }
        }

        // Use post title as company name if not set.
        if ( ! get_post_meta( $venue_id, 'sdwd_company_name', true ) ) {
            update_post_meta( $venue_id, 'sdwd_company_name', get_the_title( $venue_id ) );
            $changed = true;
        }

        if ( $changed ) {
            $venue_count++;
        }
    }
    $log[] = "Venues migrated: {$venue_count} / " . count( $venues );

    // ── 2. Migrate vendor data ──
    $vendors = get_posts( [
        'post_type'   => 'vendor',
        'post_status' => 'any',
        'numberposts' => -1,
        'fields'      => 'ids',
    ] );

    $vendor_count = 0;
    foreach ( $vendors as $vendor_id ) {
        $changed = false;

        // Use post title as company name if not set.
        if ( ! get_post_meta( $vendor_id, 'sdwd_company_name', true ) ) {
            update_post_meta( $vendor_id, 'sdwd_company_name', get_the_title( $vendor_id ) );
            $changed = true;
        }

        if ( $changed ) {
            $vendor_count++;
        }
    }
    $log[] = "Vendors migrated: {$vendor_count} / " . count( $vendors );

    // ── 3. Link users to posts ──
    $users = get_users( [
        'role__in' => [ 'vendor', 'venue', 'couple' ],
    ] );

    $user_count = 0;
    foreach ( $users as $user ) {
        $existing = get_user_meta( $user->ID, 'sdwd_post_id', true );
        if ( $existing ) {
            continue;
        }

        // Find a post authored by this user.
        $role = '';
        if ( in_array( 'vendor', $user->roles, true ) ) {
            $role = 'vendor';
        } elseif ( in_array( 'venue', $user->roles, true ) ) {
            $role = 'venue';
        } elseif ( in_array( 'couple', $user->roles, true ) ) {
            $role = 'couple';
        }

        if ( ! $role ) {
            continue;
        }

        $posts = get_posts( [
            'post_type'   => $role,
            'author'      => $user->ID,
            'post_status' => 'any',
            'numberposts' => 1,
            'fields'      => 'ids',
        ] );

        if ( ! empty( $posts ) ) {
            update_user_meta( $user->ID, 'sdwd_post_id', $posts[0] );
            $user_count++;
            $log[] = "  Linked user {$user->user_login} (ID {$user->ID}) → post {$posts[0]}";
        }
    }
    $log[] = "Users linked: {$user_count} / " . count( $users );

    // ── 4. Copy user meta to post meta where missing ──
    foreach ( $users as $user ) {
        $post_id = get_user_meta( $user->ID, 'sdwd_post_id', true );
        if ( ! $post_id ) {
            continue;
        }

        $map = [
            'sdwd_company_name'    => 'sdwd_vendor_company_name',
            'sdwd_company_website' => 'sdwd_vendor_company_website',
            'sdwd_phone'           => 'sdwd_vendor_company_contact',
            'sdwd_email'           => '',  // user email
        ];

        foreach ( $map as $post_key => $user_key ) {
            if ( get_post_meta( $post_id, $post_key, true ) ) {
                continue;
            }

            if ( $post_key === 'sdwd_email' ) {
                update_post_meta( $post_id, $post_key, $user->user_email );
            } elseif ( $user_key ) {
                $val = get_user_meta( $user->ID, $user_key, true );
                if ( $val ) {
                    update_post_meta( $post_id, $post_key, $val );
                }
            }
        }
    }
    $log[] = "User meta → post meta sync complete.";

    return $log;
}
