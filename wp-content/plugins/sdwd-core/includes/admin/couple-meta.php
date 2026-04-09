<?php
/**
 * SDWD Core — Couple Meta Boxes
 *
 * Contact info, wedding date, and social media links.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'add_meta_boxes', 'sdwd_couple_meta_boxes' );
add_action( 'save_post_couple', 'sdwd_save_couple_meta', 10, 2 );

/**
 * Register couple meta boxes.
 */
function sdwd_couple_meta_boxes() {
    add_meta_box(
        'sdwd-couple-contact',
        __( 'Contact Info', 'sdwd-core' ),
        'sdwd_couple_contact_cb',
        'couple',
        'normal',
        'high'
    );

    add_meta_box(
        'sdwd-couple-wedding',
        __( 'Wedding Details', 'sdwd-core' ),
        'sdwd_couple_wedding_cb',
        'couple',
        'normal',
        'high'
    );

    add_meta_box(
        'sdwd-couple-social',
        __( 'Social Media', 'sdwd-core' ),
        'sdwd_couple_social_cb',
        'couple',
        'normal',
        'default'
    );
}

/**
 * Contact Info meta box.
 */
function sdwd_couple_contact_cb( $post ) {
    wp_nonce_field( 'sdwd_couple_meta', 'sdwd_couple_meta_nonce' );

    $email = get_post_meta( $post->ID, 'sdwd_email', true );
    $phone = get_post_meta( $post->ID, 'sdwd_phone', true );
    ?>
    <table class="form-table sdwd-meta-table">
        <tr>
            <th><label for="sdwd_email"><?php esc_html_e( 'Email / Username', 'sdwd-core' ); ?></label></th>
            <td><input type="email" id="sdwd_email" name="sdwd_email" value="<?php echo esc_attr( $email ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="sdwd_phone"><?php esc_html_e( 'Phone', 'sdwd-core' ); ?></label></th>
            <td><input type="tel" id="sdwd_phone" name="sdwd_phone" value="<?php echo esc_attr( $phone ); ?>" class="regular-text"></td>
        </tr>
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
 * Wedding Details meta box.
 */
function sdwd_couple_wedding_cb( $post ) {
    $wedding_date = get_post_meta( $post->ID, 'sdwd_wedding_date', true );
    ?>
    <table class="form-table sdwd-meta-table">
        <tr>
            <th><label for="sdwd_wedding_date"><?php esc_html_e( 'Wedding Date', 'sdwd-core' ); ?></label></th>
            <td><input type="date" id="sdwd_wedding_date" name="sdwd_wedding_date" value="<?php echo esc_attr( $wedding_date ); ?>"></td>
        </tr>
    </table>
    <?php
}

/**
 * Social Media meta box (repeatable rows).
 */
function sdwd_couple_social_cb( $post ) {
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
 * Save couple meta.
 */
function sdwd_save_couple_meta( $post_id, $post ) {
    if ( ! isset( $_POST['sdwd_couple_meta_nonce'] ) || ! wp_verify_nonce( $_POST['sdwd_couple_meta_nonce'], 'sdwd_couple_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Contact info.
    $text_fields = [ 'sdwd_email', 'sdwd_phone', 'sdwd_wedding_date' ];
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
}
