<?php
/**
 * SDWD Core — User Roles
 *
 * Three hard-separated roles: couple, vendor, venue.
 * A user is exactly one. No overlap.
 */

defined( 'ABSPATH' ) || exit;

function sdwd_register_roles() {

    $business_caps = [
        'read'         => true,
        'upload_files' => true,
        'edit_posts'   => true,
        'delete_posts' => true,
    ];

    // Couple — browse vendors/venues, leave reviews, plan wedding.
    if ( ! get_role( 'couple' ) ) {
        add_role( 'couple', __( 'Couple', 'sdwd-core' ), [
            'read' => true,
        ] );
    }

    // Vendor — photographer, DJ, caterer, etc.
    if ( ! get_role( 'vendor' ) ) {
        add_role( 'vendor', __( 'Vendor', 'sdwd-core' ), $business_caps );
    }

    // Venue — event location / reception space.
    if ( ! get_role( 'venue' ) ) {
        add_role( 'venue', __( 'Venue', 'sdwd-core' ), $business_caps );
    }
}
