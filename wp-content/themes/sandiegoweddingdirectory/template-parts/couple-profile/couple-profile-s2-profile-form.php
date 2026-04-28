<?php
/**
 * Couple Profile — s2 Profile Form
 *
 * Basic info: first name, last name, email, phone.
 *
 * $args:
 *   - user    (WP_User)
 *   - post_id (int)
 */

$user    = $args['user'] ?? wp_get_current_user();
$post_id = (int) ( $args['post_id'] ?? 0 );

$first_name = $user->first_name;
$last_name  = $user->last_name;
$email      = get_post_meta( $post_id, 'sdwd_email', true );
$phone      = get_post_meta( $post_id, 'sdwd_phone', true );

$partner_first_name = get_post_meta( $post_id, 'sdwd_partner_first_name', true );
$partner_last_name  = get_post_meta( $post_id, 'sdwd_partner_last_name', true );
?>

<form class="cd-form" data-cd-form="profile">
    <fieldset class="cd-form__group">
        <legend class="cd-form__legend"><?php esc_html_e( 'About you', 'sandiegoweddingdirectory' ); ?></legend>
        <div class="cd-form__row">
            <div class="cd-form__field">
                <label for="first_name"><?php esc_html_e( 'First Name', 'sandiegoweddingdirectory' ); ?></label>
                <input type="text" id="first_name" name="first_name" value="<?php echo esc_attr( $first_name ); ?>">
            </div>
            <div class="cd-form__field">
                <label for="last_name"><?php esc_html_e( 'Last Name', 'sandiegoweddingdirectory' ); ?></label>
                <input type="text" id="last_name" name="last_name" value="<?php echo esc_attr( $last_name ); ?>">
            </div>
        </div>
        <div class="cd-form__row">
            <div class="cd-form__field">
                <label for="sdwd_email"><?php esc_html_e( 'Email', 'sandiegoweddingdirectory' ); ?></label>
                <input type="email" id="sdwd_email" name="sdwd_email" value="<?php echo esc_attr( $email ); ?>">
            </div>
            <div class="cd-form__field">
                <label for="sdwd_phone"><?php esc_html_e( 'Phone', 'sandiegoweddingdirectory' ); ?></label>
                <input type="tel" id="sdwd_phone" name="sdwd_phone" value="<?php echo esc_attr( $phone ); ?>">
            </div>
        </div>
    </fieldset>

    <fieldset class="cd-form__group">
        <legend class="cd-form__legend"><?php esc_html_e( 'About your partner', 'sandiegoweddingdirectory' ); ?></legend>
        <div class="cd-form__row">
            <div class="cd-form__field">
                <label for="sdwd_partner_first_name"><?php esc_html_e( "Partner's First Name", 'sandiegoweddingdirectory' ); ?></label>
                <input type="text" id="sdwd_partner_first_name" name="sdwd_partner_first_name" value="<?php echo esc_attr( $partner_first_name ); ?>">
            </div>
            <div class="cd-form__field">
                <label for="sdwd_partner_last_name"><?php esc_html_e( "Partner's Last Name", 'sandiegoweddingdirectory' ); ?></label>
                <input type="text" id="sdwd_partner_last_name" name="sdwd_partner_last_name" value="<?php echo esc_attr( $partner_last_name ); ?>">
            </div>
        </div>
    </fieldset>

    <div class="cd-form__actions">
        <button type="submit" class="btn btn--primary"><?php esc_html_e( 'Save Changes', 'sandiegoweddingdirectory' ); ?></button>
    </div>
    <div class="cd-form__status" aria-live="polite"></div>
</form>
