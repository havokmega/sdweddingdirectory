<?php
/**
 * One-time migration of OptionTree data to native wp_options.
 * Safe to run multiple times - skips if already migrated.
 */
function sdwd_migrate_optiontree_data() {
    if ( get_option( 'sdwd_optiontree_migrated' ) ) {
        return;
    }

    // OptionTree stores all values in a single serialized option.
    $ot_data = get_option( 'option_tree', [] );
    if ( ! is_array( $ot_data ) || empty( $ot_data ) ) {
        $ot_data = get_option( 'option_tree_settings', [] );
    }

    if ( is_array( $ot_data ) && ! empty( $ot_data ) ) {
        foreach ( $ot_data as $key => $value ) {
            update_option( 'sdwd_' . $key, $value, false );
        }
    }

    update_option( 'sdwd_optiontree_migrated', true );
}
add_action( 'admin_init', 'sdwd_migrate_optiontree_data' );
