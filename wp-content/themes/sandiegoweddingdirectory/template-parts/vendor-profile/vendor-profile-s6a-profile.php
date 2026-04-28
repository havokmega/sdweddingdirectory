<?php
/**
 * Vendor Profile — s6a Profile
 *
 * Account-owner basics. Email is locked (account email = login email).
 *
 * $args:
 *   - user (WP_User)
 *   - post_id (int)
 */

$user    = $args['user']    ?? wp_get_current_user();
$post_id = (int) ( $args['post_id'] ?? 0 );

$first = $user->first_name;
$last  = $user->last_name;
$phone = get_post_meta( $post_id, 'sdwd_phone', true );
?>

<form class="cd-form" data-cd-form="profile">
    <fieldset class="cd-form__group">
        <legend class="cd-form__legend"><?php esc_html_e( 'Account', 'sandiegoweddingdirectory' ); ?></legend>
        <div class="cd-form__row">
            <div class="cd-form__field">
                <label for="first_name"><?php esc_html_e( 'First Name', 'sandiegoweddingdirectory' ); ?></label>
                <input type="text" id="first_name" name="first_name" value="<?php echo esc_attr( $first ); ?>">
            </div>
            <div class="cd-form__field">
                <label for="last_name"><?php esc_html_e( 'Last Name', 'sandiegoweddingdirectory' ); ?></label>
                <input type="text" id="last_name" name="last_name" value="<?php echo esc_attr( $last ); ?>">
            </div>
        </div>
        <div class="cd-form__row">
            <div class="cd-form__field">
                <label for="account_email"><?php esc_html_e( 'Email', 'sandiegoweddingdirectory' ); ?></label>
                <input type="email" id="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" disabled>
                <p class="cd-form__hint"><?php esc_html_e( 'Account email is fixed. Public-facing email lives under Business Profile.', 'sandiegoweddingdirectory' ); ?></p>
            </div>
            <div class="cd-form__field">
                <label for="sdwd_phone"><?php esc_html_e( 'Phone', 'sandiegoweddingdirectory' ); ?></label>
                <input type="tel" id="sdwd_phone" name="sdwd_phone" value="<?php echo esc_attr( $phone ); ?>">
            </div>
        </div>
    </fieldset>

    <div class="cd-form__actions">
        <button type="submit" class="btn btn--primary"><?php esc_html_e( 'Save Changes', 'sandiegoweddingdirectory' ); ?></button>
    </div>
    <div class="cd-form__status" aria-live="polite"></div>
</form>
