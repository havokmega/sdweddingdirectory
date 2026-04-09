<?php
/**
 * SDWD Core — Vendor Meta Boxes
 *
 * Business info, social media, business hours, and pricing tiers.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'add_meta_boxes', 'sdwd_vendor_meta_boxes' );
add_action( 'save_post_vendor', 'sdwd_save_vendor_meta', 10, 2 );

/**
 * Register vendor meta boxes.
 */
function sdwd_vendor_meta_boxes() {
    add_meta_box(
        'sdwd-vendor-business',
        __( 'Business Info', 'sdwd-core' ),
        'sdwd_vendor_business_cb',
        'vendor',
        'normal',
        'high'
    );

    add_meta_box(
        'sdwd-vendor-social',
        __( 'Social Media', 'sdwd-core' ),
        'sdwd_vendor_social_cb',
        'vendor',
        'normal',
        'default'
    );

    add_meta_box(
        'sdwd-vendor-hours',
        __( 'Business Hours', 'sdwd-core' ),
        'sdwd_vendor_hours_cb',
        'vendor',
        'normal',
        'default'
    );

    add_meta_box(
        'sdwd-vendor-pricing',
        __( 'Pricing Tiers', 'sdwd-core' ),
        'sdwd_vendor_pricing_cb',
        'vendor',
        'normal',
        'default'
    );
}

/**
 * Business Info meta box.
 */
function sdwd_vendor_business_cb( $post ) {
    wp_nonce_field( 'sdwd_vendor_meta', 'sdwd_vendor_meta_nonce' );

    $fields = [
        'sdwd_company_name'   => __( 'Company Name', 'sdwd-core' ),
        'sdwd_email'          => __( 'Email / Username', 'sdwd-core' ),
        'sdwd_phone'          => __( 'Phone', 'sdwd-core' ),
        'sdwd_company_website' => __( 'Website', 'sdwd-core' ),
    ];
    ?>
    <table class="form-table sdwd-meta-table">
        <?php foreach ( $fields as $key => $label ) : ?>
            <tr>
                <th><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
                <td><input type="text" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( get_post_meta( $post->ID, $key, true ) ); ?>" class="regular-text"></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th><label for="sdwd_password"><?php esc_html_e( 'New Password', 'sdwd-core' ); ?></label></th>
            <td>
                <input type="text" id="sdwd_password" name="sdwd_password" value="" class="regular-text" autocomplete="off">
                <p class="description"><?php esc_html_e( 'Leave blank to keep current password.', 'sdwd-core' ); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Social Media meta box (repeatable rows).
 */
function sdwd_vendor_social_cb( $post ) {
    $social = get_post_meta( $post->ID, 'sdwd_social', true );
    if ( ! is_array( $social ) || empty( $social ) ) {
        $social = [ [ 'label' => '', 'url' => '' ] ];
    }
    ?>
    <div class="sdwd-repeater" data-sdwd-repeater="social">
        <div class="sdwd-repeater__items">
            <?php foreach ( $social as $i => $row ) : ?>
                <div class="sdwd-repeater__row">
                    <input type="text" name="sdwd_social[<?php echo $i; ?>][label]" value="<?php echo esc_attr( $row['label'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'e.g. Facebook, Instagram', 'sdwd-core' ); ?>" class="regular-text">
                    <input type="url" name="sdwd_social[<?php echo $i; ?>][url]" value="<?php echo esc_attr( $row['url'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'https://', 'sdwd-core' ); ?>" class="regular-text">
                    <button type="button" class="button sdwd-repeater__remove">&times;</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button sdwd-repeater__add" data-sdwd-add="social"><?php esc_html_e( '+ Add Social Link', 'sdwd-core' ); ?></button>
    </div>
    <?php
}

/**
 * Business Hours meta box.
 */
function sdwd_vendor_hours_cb( $post ) {
    $hours = get_post_meta( $post->ID, 'sdwd_hours', true );
    if ( ! is_array( $hours ) ) {
        $hours = [];
    }

    $days = [
        'monday'    => __( 'Monday', 'sdwd-core' ),
        'tuesday'   => __( 'Tuesday', 'sdwd-core' ),
        'wednesday' => __( 'Wednesday', 'sdwd-core' ),
        'thursday'  => __( 'Thursday', 'sdwd-core' ),
        'friday'    => __( 'Friday', 'sdwd-core' ),
        'saturday'  => __( 'Saturday', 'sdwd-core' ),
        'sunday'    => __( 'Sunday', 'sdwd-core' ),
    ];
    ?>
    <table class="form-table sdwd-meta-table">
        <?php foreach ( $days as $key => $label ) : ?>
            <tr>
                <th><?php echo esc_html( $label ); ?></th>
                <td>
                    <input type="time" name="sdwd_hours[<?php echo esc_attr( $key ); ?>][open]" value="<?php echo esc_attr( $hours[ $key ]['open'] ?? '' ); ?>">
                    <span>&ndash;</span>
                    <input type="time" name="sdwd_hours[<?php echo esc_attr( $key ); ?>][close]" value="<?php echo esc_attr( $hours[ $key ]['close'] ?? '' ); ?>">
                    <label style="margin-left:12px;">
                        <input type="checkbox" name="sdwd_hours[<?php echo esc_attr( $key ); ?>][closed]" value="1" <?php checked( ! empty( $hours[ $key ]['closed'] ) ); ?>>
                        <?php esc_html_e( 'Closed', 'sdwd-core' ); ?>
                    </label>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php
}

/**
 * Pricing Tiers meta box.
 */
function sdwd_vendor_pricing_cb( $post ) {
    $pricing = get_post_meta( $post->ID, 'sdwd_pricing', true );
    if ( ! is_array( $pricing ) || empty( $pricing ) ) {
        $pricing = [];
    }

    // Always show at least 1 empty tier, max 3.
    while ( count( $pricing ) < 1 ) {
        $pricing[] = [ 'name' => '', 'price' => '', 'features' => [] ];
    }
    ?>
    <div class="sdwd-pricing-tiers" data-sdwd-pricing>
        <?php foreach ( $pricing as $t => $tier ) : ?>
            <div class="sdwd-pricing-tier" data-sdwd-tier="<?php echo $t; ?>">
                <h4><?php printf( esc_html__( 'Tier %d', 'sdwd-core' ), $t + 1 ); ?>
                    <?php if ( $t > 0 ) : ?>
                        <button type="button" class="button-link sdwd-pricing-tier__remove"><?php esc_html_e( 'Remove', 'sdwd-core' ); ?></button>
                    <?php endif; ?>
                </h4>
                <table class="form-table sdwd-meta-table">
                    <tr>
                        <th><label><?php esc_html_e( 'Package Name', 'sdwd-core' ); ?></label></th>
                        <td><input type="text" name="sdwd_pricing[<?php echo $t; ?>][name]" value="<?php echo esc_attr( $tier['name'] ?? '' ); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label><?php esc_html_e( 'Price', 'sdwd-core' ); ?></label></th>
                        <td><input type="text" name="sdwd_pricing[<?php echo $t; ?>][price]" value="<?php echo esc_attr( $tier['price'] ?? '' ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'e.g. $500, Starting at $1,000', 'sdwd-core' ); ?>"></td>
                    </tr>
                    <tr>
                        <th><label><?php esc_html_e( 'Features', 'sdwd-core' ); ?></label></th>
                        <td>
                            <div class="sdwd-repeater" data-sdwd-repeater="features">
                                <div class="sdwd-repeater__items">
                                    <?php
                                    $features = $tier['features'] ?? [];
                                    if ( empty( $features ) ) {
                                        $features = [ '' ];
                                    }
                                    foreach ( $features as $f => $feature ) : ?>
                                        <div class="sdwd-repeater__row">
                                            <input type="text" name="sdwd_pricing[<?php echo $t; ?>][features][]" value="<?php echo esc_attr( $feature ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'Feature included', 'sdwd-core' ); ?>">
                                            <button type="button" class="button sdwd-repeater__remove">&times;</button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <button type="button" class="button sdwd-repeater__add" data-sdwd-add="features" data-sdwd-tier-index="<?php echo $t; ?>" data-sdwd-max="10"><?php esc_html_e( '+ Add Feature', 'sdwd-core' ); ?></button>
                            </div>
                        </td>
                    </tr>
                </table>
                <hr>
            </div>
        <?php endforeach; ?>
        <?php if ( count( $pricing ) < 3 ) : ?>
            <button type="button" class="button button-primary sdwd-pricing-tier__add"><?php esc_html_e( '+ Add Pricing Tier', 'sdwd-core' ); ?></button>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Save vendor meta.
 */
function sdwd_save_vendor_meta( $post_id, $post ) {
    if ( ! isset( $_POST['sdwd_vendor_meta_nonce'] ) || ! wp_verify_nonce( $_POST['sdwd_vendor_meta_nonce'], 'sdwd_vendor_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Text fields.
    $text_fields = [ 'sdwd_company_name', 'sdwd_email', 'sdwd_phone', 'sdwd_company_website' ];
    foreach ( $text_fields as $key ) {
        if ( isset( $_POST[ $key ] ) ) {
            update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
        }
    }

    // Password (set on linked user).
    if ( ! empty( $_POST['sdwd_password'] ) && $post->post_author ) {
        wp_set_password( $_POST['sdwd_password'], $post->post_author );
    }

    // Social media (repeatable).
    if ( isset( $_POST['sdwd_social'] ) && is_array( $_POST['sdwd_social'] ) ) {
        $social = [];
        foreach ( $_POST['sdwd_social'] as $row ) {
            $label = sanitize_text_field( wp_unslash( $row['label'] ?? '' ) );
            $url   = esc_url_raw( wp_unslash( $row['url'] ?? '' ) );
            if ( ! empty( $label ) || ! empty( $url ) ) {
                $social[] = [ 'label' => $label, 'url' => $url ];
            }
        }
        update_post_meta( $post_id, 'sdwd_social', $social );
    }

    // Business hours.
    if ( isset( $_POST['sdwd_hours'] ) && is_array( $_POST['sdwd_hours'] ) ) {
        $hours = [];
        foreach ( $_POST['sdwd_hours'] as $day => $vals ) {
            $hours[ sanitize_key( $day ) ] = [
                'open'   => sanitize_text_field( $vals['open'] ?? '' ),
                'close'  => sanitize_text_field( $vals['close'] ?? '' ),
                'closed' => ! empty( $vals['closed'] ),
            ];
        }
        update_post_meta( $post_id, 'sdwd_hours', $hours );
    }

    // Pricing tiers.
    if ( isset( $_POST['sdwd_pricing'] ) && is_array( $_POST['sdwd_pricing'] ) ) {
        $pricing = [];
        foreach ( array_slice( $_POST['sdwd_pricing'], 0, 3 ) as $tier ) {
            $name  = sanitize_text_field( wp_unslash( $tier['name'] ?? '' ) );
            $price = sanitize_text_field( wp_unslash( $tier['price'] ?? '' ) );
            $features = [];
            if ( isset( $tier['features'] ) && is_array( $tier['features'] ) ) {
                foreach ( array_slice( $tier['features'], 0, 10 ) as $f ) {
                    $val = sanitize_text_field( wp_unslash( $f ) );
                    if ( ! empty( $val ) ) {
                        $features[] = $val;
                    }
                }
            }
            if ( ! empty( $name ) || ! empty( $price ) || ! empty( $features ) ) {
                $pricing[] = [ 'name' => $name, 'price' => $price, 'features' => $features ];
            }
        }
        update_post_meta( $post_id, 'sdwd_pricing', $pricing );
    }
}
